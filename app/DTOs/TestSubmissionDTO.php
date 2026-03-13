<?php

namespace App\DTOs;

class TestSubmissionDTO
{
    public function __construct(
        public readonly ?string $topic_id,
        public readonly string $subject_id,
        public readonly ?string $test_type_id,
        public readonly ?string $time_taken,
        public readonly array $answers,
        public readonly string $token
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            topic_id: $data['topic_id'] ?? null,
            subject_id: $data['subject_id'],
            test_type_id: $data['test_type_id'] ?? null,
            time_taken: $data['time_taken'] ?? null,
            answers: $data['answers'],
            token: $data['_token']
        );
    }

    public function hasTopic(): bool
    {
        return !is_null($this->topic_id) && $this->topic_id !== 'null';
    }
    public function hasTestType(): bool
    {
        return !is_null($this->test_type_id) && $this->test_type_id !== 'null';
    }

    public function getTopicId(): ?int
    {
        return $this->hasTopic() ? (int)$this->topic_id : null;
    }

    public function getSubjectId(): int
    {
        return (int)$this->subject_id;
    }
    public function getTestTypeId(): ?int
    {
        return $this->hasTestType() ? (int)$this->test_type_id : null;
    }
}