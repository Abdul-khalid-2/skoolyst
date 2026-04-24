<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let answeredQuestions = new Set();
    let startTime = Date.now();
    let timerInterval;
    let currentPage = {{ $mcqs->currentPage() }};
    let isLoading = false;

    const topicId = '{{ request('topic') ? request('topic') : 'subject_' . ($subject->id ?? 'general') }}';
    const storageKey = `mcq_answers_${topicId}`;
    const totalQuestions = {{ $mcqs->total() ?? 0 }};

    document.addEventListener('DOMContentLoaded', function() {
        loadSavedAnswers();
        updateProgress();
        startTimer();
        setupScrollSpy();
        setupPagination();
        setupMcqFilterSubjectTopicSearch();
    });

    function setupMcqFilterSubjectTopicSearch() {
        var input = document.getElementById('mcqTopicSearch');
        var list = document.getElementById('mcqTopicFilterList');
        if (!input || !list) {
            return;
        }
        input.addEventListener('input', function() {
            var q = (input.value || '').toLowerCase().trim();
            list.querySelectorAll('a').forEach(function(a) {
                var title = a.getAttribute('data-topic-title') || '';
                var text = (a.textContent || '').toLowerCase();
                var show = !q || text.indexOf(q) !== -1 || title.indexOf(q) !== -1;
                a.classList.toggle('d-none', !show);
            });
        });
    }

    function startTimer() {
        if (timerInterval) clearInterval(timerInterval);
        timerInterval = setInterval(function() {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;
            const timer = document.getElementById('timer');
            if (timer) {
                timer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
            const timeTaken = document.getElementById('timeTaken');
            if (timeTaken) {
                timeTaken.value = elapsed;
            }
        }, 1000);
    }

    function setupPagination() {
        $(document).on('click', '.prev-page, .next-page', function(e) {
            e.preventDefault();
            if ($(this).prop('disabled') || isLoading) return;
            const page = $(this).data('page');
            loadPage(page);
        });
    }

    function loadPage(page) {
        if (isLoading) return;
        isLoading = true;
        showLoading(true);
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('page', page);
        $.ajax({
            url: window.location.pathname + '?' + urlParams.toString(),
            type: 'GET',
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(response) {
                if (response.success) {
                    $('#mcqsContent').html(response.html);
                    updatePaginationButtons(response.mcqs);
                    updateQuestionPalette(response.mcqs);
                    $('#totalQuestions').text(response.mcqs.total);
                    loadSavedAnswers();
                    updateProgress();
                    currentPage = page;
                    urlParams.set('page', page);
                    const newUrl = window.location.pathname + '?' + urlParams.toString();
                    window.history.pushState({ path: newUrl }, '', newUrl);
                    $('html, body').animate({
                        scrollTop: $('#mcqsContent').offset().top - 100
                    }, 500);
                }
            },
            error: function(xhr) {
                console.error('Error loading page:', xhr);
                alert('Error loading questions. Please try again.');
            },
            complete: function() {
                isLoading = false;
                showLoading(false);
            }
        });
    }

    function updatePaginationButtons(mcqs) {
        $('.prev-page').prop('disabled', mcqs.current_page === 1).data('page', mcqs.current_page - 1);
        $('.next-page').prop('disabled', mcqs.current_page === mcqs.last_page).data('page', mcqs.current_page + 1);
    }

    function updateQuestionPalette(mcqs) {
        let paletteHtml = '';
        if (mcqs.data && mcqs.data.length > 0) {
            mcqs.data.forEach((mcq, index) => {
                const questionNumber = index + 1 + ((mcqs.current_page - 1) * mcqs.per_page);
                paletteHtml += `<a href="#question-${mcq.id}" class="palette-item" data-question-id="${mcq.id}" id="palette-${mcq.id}" onclick="scrollToQuestion(event, ${mcq.id})">${questionNumber}</a>`;
            });
        }
        $('#paletteGrid').html(paletteHtml);
        answeredQuestions.forEach(id => { updatePaletteItem(id, true); });
    }

    function showLoading(show) {
        if (show) {
            $('#mcqsContent').hide();
            $('#loadingSpinner').show();
        } else {
            $('#mcqsContent').show();
            $('#loadingSpinner').hide();
        }
    }

    function loadSavedAnswers() {
        try {
            const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
            const visibleQuestions = new Set();
            $('.question-card').each(function() {
                const mcqId = $(this).data('question-id');
                visibleQuestions.add(mcqId.toString());
            });
            Object.keys(savedAnswers).forEach(mcqId => {
                if (visibleQuestions.has(mcqId)) {
                    const answers = savedAnswers[mcqId];
                    const questionCard = document.getElementById(`question-${mcqId}`);
                    if (questionCard) {
                        if (Array.isArray(answers)) {
                            answers.forEach(answerKey => {
                                const checkbox = document.getElementById(`option-${mcqId}-${answerKey}`);
                                if (checkbox) {
                                    checkbox.checked = true;
                                    $(checkbox).closest('.option-item').addClass('selected');
                                }
                            });
                        } else {
                            const radio = document.getElementById(`option-${mcqId}-${answers}`);
                            if (radio) {
                                radio.checked = true;
                                $(radio).closest('.option-item').addClass('selected');
                            }
                        }
                        answeredQuestions.add(parseInt(mcqId));
                        updatePaletteItem(mcqId, true);
                    }
                } else {
                    answeredQuestions.add(parseInt(mcqId));
                }
            });
        } catch (e) {
            console.error('Error loading saved answers:', e);
        }
    }

    function saveAnswer(mcqId, answers) {
        try {
            const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
            savedAnswers[mcqId] = answers;
            localStorage.setItem(storageKey, JSON.stringify(savedAnswers));
        } catch (e) {
            console.error('Error saving answer:', e);
        }
    }

    function toggleHint(mcqId) {
        const hintSection = document.getElementById(`hint-${mcqId}`);
        if (hintSection) hintSection.classList.toggle('show');
    }

    function selectOption(element, mcqId, optionKey, isMultiple = false) {
        const questionCard = document.getElementById(`question-${mcqId}`);
        if (!questionCard) return;
        const inputs = questionCard.querySelectorAll('input');
        if (isMultiple) {
            const checkbox = document.getElementById(`option-${mcqId}-${optionKey}`);
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                element.classList.toggle('selected', checkbox.checked);
            }
        } else {
            questionCard.querySelectorAll('.option-item').forEach(item => item.classList.remove('selected'));
            inputs.forEach(input => { if (input.type === 'radio') input.checked = false; });
            const radio = document.getElementById(`option-${mcqId}-${optionKey}`);
            if (radio) {
                radio.checked = true;
                element.classList.add('selected');
            }
        }
        let isAnswered = false;
        const selectedAnswers = [];
        inputs.forEach(input => {
            if (input.checked) {
                isAnswered = true;
                selectedAnswers.push(input.value);
            }
        });
        if (isAnswered) {
            answeredQuestions.add(parseInt(mcqId));
            updatePaletteItem(mcqId, true);
            saveAnswer(mcqId, isMultiple ? selectedAnswers : selectedAnswers[0]);
        } else {
            answeredQuestions.delete(parseInt(mcqId));
            updatePaletteItem(mcqId, false);
            const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
            delete savedAnswers[mcqId];
            localStorage.setItem(storageKey, JSON.stringify(savedAnswers));
        }
        updateProgress();
    }

    function updatePaletteItem(mcqId, isAnswered) {
        const paletteItem = document.getElementById(`palette-${mcqId}`);
        if (paletteItem) paletteItem.classList.toggle('answered', isAnswered);
    }

    function updateProgress() {
        const answeredCount = answeredQuestions.size;
        const percentage = totalQuestions > 0 ? (answeredCount / totalQuestions) * 100 : 0;
        const progressBar = document.getElementById('progressBar');
        const answeredCountEl = document.getElementById('answeredCount');
        const answeredStats = document.getElementById('answeredStats');
        if (progressBar) progressBar.style.width = `${percentage}%`;
        if (answeredCountEl) answeredCountEl.textContent = answeredCount;
        if (answeredStats) answeredStats.textContent = `${answeredCount}/${totalQuestions}`;
    }

    function submitTest() {
        const answeredCount = answeredQuestions.size;
        if (answeredCount === 0) {
            alert('Please answer at least one question before submitting.');
            return;
        }
        if (confirm(`You have answered ${answeredCount} out of ${totalQuestions} questions. Are you sure you want to submit?`)) {
            clearInterval(timerInterval);
            const form = document.getElementById('testForm');
            const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
            Object.keys(savedAnswers).forEach(mcqId => {
                const answers = savedAnswers[mcqId];
                if (Array.isArray(answers)) {
                    answers.forEach(answer => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `answers[${mcqId}][]`;
                        input.value = answer;
                        form.appendChild(input);
                    });
                } else {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `answers[${mcqId}]`;
                    input.value = answers;
                    form.appendChild(input);
                }
            });
            form.submit();
        }
    }

    function clearTest() {
        if (confirm('Are you sure you want to clear all answers?')) {
            document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => { input.checked = false; });
            document.querySelectorAll('.option-item').forEach(item => item.classList.remove('selected'));
            answeredQuestions.clear();
            document.querySelectorAll('.palette-item').forEach(item => item.classList.remove('answered'));
            localStorage.removeItem(storageKey);
            updateProgress();
        }
    }

    function scrollToQuestion(event, mcqId) {
        event.preventDefault();
        const element = document.getElementById(`question-${mcqId}`);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            document.querySelectorAll('.palette-item').forEach(item => item.classList.remove('current'));
            const paletteItem = document.getElementById(`palette-${mcqId}`);
            if (paletteItem) paletteItem.classList.add('current');
        }
    }

    function setupScrollSpy() {
        window.addEventListener('scroll', function() {
            const questionCards = document.querySelectorAll('.question-card');
            const scrollPosition = window.scrollY + 200;
            questionCards.forEach(card => {
                const rect = card.getBoundingClientRect();
                const absoluteTop = window.scrollY + rect.top;
                const absoluteBottom = absoluteTop + rect.height;
                if (scrollPosition >= absoluteTop && scrollPosition <= absoluteBottom) {
                    const mcqId = card.dataset.questionId;
                    document.querySelectorAll('.palette-item').forEach(item => item.classList.remove('current'));
                    const paletteItem = document.getElementById(`palette-${mcqId}`);
                    if (paletteItem) paletteItem.classList.add('current');
                }
            });
        });
    }

    window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get('page') || 1;
        if (parseInt(page) !== currentPage) loadPage(page);
    });

    window.addEventListener('beforeunload', function() {
        clearInterval(timerInterval);
    });
</script>
