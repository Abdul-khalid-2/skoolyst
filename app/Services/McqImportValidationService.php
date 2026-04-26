<?php

namespace App\Services;

use App\Models\Subject;
use App\Models\TestType;
use App\Models\Topic;
use Illuminate\Support\Str;

class McqImportValidationService
{
    /**
     * Validate the parsed rows and build payloads ready for insertion.
     * Returns a structure with per-row status used for both preview and
     * the actual import.
     */
    public function validateImportRows(array $rows): array
    {
        $statusMap = [
            'active' => 'published',
            'inactive' => 'archived',
            'draft' => 'draft',
            'published' => 'published',
            'archived' => 'archived',
        ];

        $testTypeMap = [
            'mcq' => 'single',
            'true_false' => 'single',
            'truefalse' => 'single',
            'true-false' => 'single',
            'multi_select' => 'multiple',
            'multiselect' => 'multiple',
            'multi-select' => 'multiple',
            'multiple' => 'multiple',
            'single' => 'single',
        ];

        $optionLetterMap = [
            'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4,
            '1' => 1, '2' => 2, '3' => 3, '4' => 4,
        ];

        $subjectsByName = Subject::active()
            ->get(['id', 'name'])
            ->keyBy(fn ($s) => Str::lower(trim($s->name)));

        $topicsByName = Topic::active()
            ->get(['id', 'subject_id', 'title'])
            ->groupBy('subject_id');

        $testTypesByName = TestType::query()
            ->get(['id', 'name'])
            ->keyBy(fn ($t) => Str::lower(trim($t->name)));

        $userId = auth()->id();
        $errors = [];
        $processed = [];
        $validCount = 0;
        $invalidCount = 0;

        foreach ($rows as $idx => $row) {
            $rowNumber = $idx + 2; // header is row 1, data starts at row 2
            $rowErrors = [];

            $subjectName = trim((string) ($row['subject'] ?? ''));
            $topicName = trim((string) ($row['topic'] ?? ''));
            $question = (string) ($row['question'] ?? '');
            $difficulty = strtolower(trim((string) ($row['difficulty'] ?? '')));

            $explicitQuestionType = strtolower(trim((string) ($row['question_type'] ?? '')));
            $testTypesRaw = trim((string) ($row['test_types'] ?? ''));
            $legacyTestTypeCol = trim((string) ($row['test_type'] ?? ''));

            if ($explicitQuestionType !== '') {
                $questionTypeRaw = $explicitQuestionType;
            } elseif ($legacyTestTypeCol !== '') {
                $legacyKey = strtolower($legacyTestTypeCol);
                if (isset($testTypeMap[$legacyKey])) {
                    $questionTypeRaw = $legacyKey;
                } else {
                    $questionTypeRaw = 'mcq';
                    if ($testTypesRaw === '') {
                        $testTypesRaw = $legacyTestTypeCol;
                    }
                }
            } else {
                $questionTypeRaw = '';
            }

            $statusRaw = strtolower(trim((string) ($row['status'] ?? '')));
            $correctRaw = strtolower(trim((string) ($row['correct_option'] ?? '')));

            $subject = $subjectsByName->get(Str::lower($subjectName));
            if (! $subject) {
                $rowErrors[] = ['field' => 'subject', 'message' => "Subject '{$subjectName}' not found."];
            }

            $topic = null;
            if ($subject) {
                $topicCollection = $topicsByName->get($subject->id, collect());
                $topic = $topicCollection->first(function ($t) use ($topicName) {
                    return Str::lower(trim($t->title)) === Str::lower(trim($topicName));
                });
                if (! $topic) {
                    $rowErrors[] = ['field' => 'topic', 'message' => "Topic '{$topicName}' not found in subject '{$subject->name}'."];
                }
            } elseif ($topicName === '') {
                $rowErrors[] = ['field' => 'topic', 'message' => 'Topic is required.'];
            }

            if (trim(strip_tags($question)) === '') {
                $rowErrors[] = ['field' => 'question', 'message' => 'Question is required.'];
            }

            if (! in_array($difficulty, ['easy', 'medium', 'hard'], true)) {
                $rowErrors[] = ['field' => 'difficulty', 'message' => "Difficulty must be one of: easy, medium, hard."];
            }

            $questionType = $testTypeMap[$questionTypeRaw] ?? null;
            if ($questionType === null) {
                $rowErrors[] = ['field' => 'question_type', 'message' => "question_type must be one of: mcq, true_false, multi_select."];
            }

            $testTypeIds = [];
            if ($testTypesRaw !== '') {
                $names = array_values(array_filter(array_map('trim', explode(',', $testTypesRaw)), fn ($n) => $n !== ''));
                $missing = [];
                foreach ($names as $name) {
                    $key = Str::lower($name);
                    $tt = $testTypesByName->get($key);
                    if (! $tt) {
                        $missing[] = $name;

                        continue;
                    }
                    $testTypeIds[] = $tt->id;
                }
                $testTypeIds = array_values(array_unique($testTypeIds));
                if (! empty($missing)) {
                    $rowErrors[] = [
                        'field' => 'test_types',
                        'message' => 'Unknown test type(s): ' . implode(', ', $missing) . '.',
                    ];
                }
            }

            $optionsByLetter = [
                'a' => trim((string) ($row['option_a'] ?? '')),
                'b' => trim((string) ($row['option_b'] ?? '')),
                'c' => trim((string) ($row['option_c'] ?? '')),
                'd' => trim((string) ($row['option_d'] ?? '')),
            ];

            if ($optionsByLetter['a'] === '') {
                $rowErrors[] = ['field' => 'option_a', 'message' => 'option_a is required.'];
            }
            if ($optionsByLetter['b'] === '') {
                $rowErrors[] = ['field' => 'option_b', 'message' => 'option_b is required.'];
            }

            $formattedOptions = [];
            $key = 1;
            $letterToKey = [];
            foreach (['a', 'b', 'c', 'd'] as $letter) {
                if ($optionsByLetter[$letter] !== '') {
                    $formattedOptions[$key] = $optionsByLetter[$letter];
                    $letterToKey[$letter] = $key;
                    $key++;
                }
            }

            $correctAnswers = [];
            if ($correctRaw === '') {
                $rowErrors[] = ['field' => 'correct_option', 'message' => 'correct_option is required.'];
            } else {
                $tokens = array_filter(array_map('trim', explode(',', $correctRaw)));
                foreach ($tokens as $token) {
                    if (! isset($optionLetterMap[$token])) {
                        $rowErrors[] = ['field' => 'correct_option', 'message' => "Invalid correct_option value '{$token}'. Use a, b, c, or d."];
                        continue;
                    }
                    $letter = is_numeric($token) ? chr(96 + (int) $token) : $token;
                    if (! isset($letterToKey[$letter])) {
                        $rowErrors[] = ['field' => 'correct_option', 'message' => "correct_option '{$token}' refers to an empty option."];
                        continue;
                    }
                    $correctAnswers[] = (string) $letterToKey[$letter];
                }
                $correctAnswers = array_values(array_unique($correctAnswers));
            }

            if ($questionType === 'single' && count($correctAnswers) > 1) {
                $rowErrors[] = ['field' => 'correct_option', 'message' => 'Single-choice (mcq / true_false) questions can have only one correct option.'];
            }
            if ($questionType === 'multiple' && count($correctAnswers) < 2) {
                $rowErrors[] = ['field' => 'correct_option', 'message' => 'multi_select questions must have at least two correct options (comma-separated).'];
            }

            $marks = trim((string) ($row['marks'] ?? ''));
            if ($marks === '' || ! is_numeric($marks) || (float) $marks <= 0) {
                $rowErrors[] = ['field' => 'marks', 'message' => 'marks must be a positive number.'];
            }
            $marksInt = (int) round((float) $marks);

            $negativeMarksRaw = trim((string) ($row['negative_marks'] ?? ''));
            if ($negativeMarksRaw === '') {
                $negativeMarksInt = 0;
            } elseif (! is_numeric($negativeMarksRaw) || (float) $negativeMarksRaw < 0) {
                $rowErrors[] = ['field' => 'negative_marks', 'message' => 'negative_marks must be greater than or equal to 0.'];
                $negativeMarksInt = 0;
            } else {
                $negativeMarksInt = (int) round((float) $negativeMarksRaw);
            }

            $status = $statusMap[$statusRaw] ?? null;
            if ($status === null) {
                $rowErrors[] = ['field' => 'status', 'message' => 'status must be one of: active, inactive, draft.'];
            }

            $isPremiumRaw = strtolower(trim((string) ($row['is_premium'] ?? '')));
            $premiumTrue = ['1', 'true', 'yes', 'y'];
            $premiumFalse = ['', '0', 'false', 'no', 'n'];
            if (in_array($isPremiumRaw, $premiumTrue, true)) {
                $isPremium = true;
            } elseif (in_array($isPremiumRaw, $premiumFalse, true)) {
                $isPremium = false;
            } else {
                $rowErrors[] = ['field' => 'is_premium', 'message' => 'is_premium must be true or false.'];
                $isPremium = false;
            }

            $tagsRaw = trim((string) ($row['tags'] ?? ''));
            $tagsArray = $tagsRaw === ''
                ? null
                : array_values(array_filter(array_map('trim', explode(',', $tagsRaw)), fn ($t) => $t !== ''));

            $explanation = trim((string) ($row['explanation'] ?? ''));
            $hint = trim((string) ($row['hint'] ?? ''));
            $referenceBook = trim((string) ($row['reference_book'] ?? ''));
            $referencePage = trim((string) ($row['reference_page'] ?? ''));

            $isValid = empty($rowErrors);

            $payload = null;
            if ($isValid) {
                $payload = [
                    'uuid' => Str::uuid(),
                    'question' => $question,
                    'question_type' => $questionType,
                    'subject_id' => $subject->id,
                    'topic_id' => $topic->id,
                    'options' => json_encode($formattedOptions),
                    'correct_answers' => json_encode($correctAnswers),
                    'explanation' => $explanation !== '' ? $explanation : null,
                    'hint' => $hint !== '' ? $hint : null,
                    'difficulty_level' => $difficulty,
                    'time_limit_seconds' => null,
                    'marks' => $marksInt,
                    'negative_marks' => $negativeMarksInt,
                    'tags' => $tagsArray ? json_encode($tagsArray) : null,
                    'reference_book' => $referenceBook !== '' ? $referenceBook : null,
                    'reference_page' => $referencePage !== '' ? $referencePage : null,
                    'is_premium' => $isPremium,
                    'is_verified' => false,
                    'status' => $status ?? 'draft',
                    'created_by' => $userId,
                ];
                $validCount++;
            } else {
                $invalidCount++;
                foreach ($rowErrors as $err) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'field' => $err['field'],
                        'message' => $err['message'],
                    ];
                }
            }

            $processed[] = [
                'row_number' => $rowNumber,
                'valid' => $isValid,
                'errors' => $rowErrors,
                'preview' => [
                    'subject' => $subjectName,
                    'topic' => $topicName,
                    'question' => Str::limit(strip_tags($question), 120),
                    'difficulty' => $difficulty,
                    'test_type' => $questionTypeRaw,
                    'test_types' => $testTypesRaw,
                    'correct_option' => $correctRaw,
                    'marks' => $marks,
                    'status' => $statusRaw,
                ],
                'payload' => $payload,
                'test_type_ids' => $testTypeIds,
            ];
        }

        return [
            'rows' => $processed,
            'valid_count' => $validCount,
            'invalid_count' => $invalidCount,
            'errors' => $errors,
        ];
    }
}
