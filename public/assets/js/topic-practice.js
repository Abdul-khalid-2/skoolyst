/**
 * Topic practice: answers in localStorage; timer start in sessionStorage so paginated Next/Previous does not reset the clock.
 */
(function () {
    'use strict';

    var cfg = window.SKOOLYST_TOPIC_PRACTICE;
    if (!cfg || !cfg.topicId) {
        return;
    }

    var storageKey = 'mcq_answers_' + cfg.topicId;
    var timerKey = 'mcq_topic_test_start_' + cfg.topicId;
    var totalQuestions = parseInt(cfg.totalQuestions, 10) || 0;
    var answeredQuestions = new Set();
    var startTime;
    var timerInterval;

    function getStartTime() {
        var raw = sessionStorage.getItem(timerKey);
        if (raw) {
            var t = parseInt(raw, 10);
            if (!isNaN(t) && t > 0) {
                return t;
            }
        }
        var now = Date.now();
        sessionStorage.setItem(timerKey, String(now));
        return now;
    }

    startTime = getStartTime();

    function setTimeTakenInput() {
        var input = document.getElementById('timeTaken');
        if (input) {
            input.value = String(Math.max(0, Math.floor((Date.now() - startTime) / 1000)));
        }
    }

    function startTimer() {
        if (timerInterval) {
            clearInterval(timerInterval);
        }
        var tick = function () {
            var elapsed = Math.floor((Date.now() - startTime) / 1000);
            var minutes = Math.floor(elapsed / 60);
            var seconds = elapsed % 60;
            var el = document.getElementById('timer');
            if (el) {
                el.textContent =
                    minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
            }
            setTimeTakenInput();
        };
        tick();
        timerInterval = setInterval(tick, 1000);
    }

    function loadSavedAnswers() {
        var saved;
        try {
            saved = JSON.parse(localStorage.getItem(storageKey) || '{}');
        } catch (e) {
            saved = {};
        }
        Object.keys(saved).forEach(function (mcqId) {
            var answers = saved[mcqId];
            var card = document.getElementById('question-' + mcqId);
            if (card) {
                if (Array.isArray(answers)) {
                    answers.forEach(function (answerKey) {
                        var cb = document.getElementById('option-' + mcqId + '-' + answerKey);
                        if (cb) {
                            cb.checked = true;
                            if (cb.closest) {
                                cb.closest('.option-item')?.classList.add('selected');
                            }
                        }
                    });
                } else {
                    var radio = document.getElementById('option-' + mcqId + '-' + answers);
                    if (radio) {
                        radio.checked = true;
                        radio.closest('.option-item')?.classList.add('selected');
                    }
                }
                answeredQuestions.add(parseInt(mcqId, 10));
                var p = document.getElementById('palette-' + mcqId);
                if (p) {
                    p.classList.add('answered');
                }
            }
        });
    }

    function saveAnswer(mcqId, answers) {
        var o = JSON.parse(localStorage.getItem(storageKey) || '{}');
        o[mcqId] = answers;
        localStorage.setItem(storageKey, JSON.stringify(o));
    }

    function countAnsweredFromStorage() {
        try {
            return Object.keys(JSON.parse(localStorage.getItem(storageKey) || '{}')).length;
        } catch (e) {
            return 0;
        }
    }

    function updateProgress() {
        var answeredCount = countAnsweredFromStorage();
        var percentage = totalQuestions > 0 ? (answeredCount / totalQuestions) * 100 : 0;
        var bar = document.getElementById('progressBar');
        if (bar) {
            bar.style.width = percentage + '%';
        }
        var a = document.getElementById('answeredCount');
        if (a) {
            a.textContent = String(answeredCount);
        }
        var s = document.getElementById('answeredStats');
        if (s) {
            s.textContent = answeredCount + '/' + totalQuestions;
        }
    }

    function appendHiddenAnswersFromLocalStorage(form) {
        var saved;
        try {
            saved = JSON.parse(localStorage.getItem(storageKey) || '{}');
        } catch (e) {
            saved = {};
        }
        Object.keys(saved).forEach(function (mcqId) {
            if (document.getElementById('question-' + mcqId)) {
                return;
            }
            var answers = saved[mcqId];
            if (Array.isArray(answers)) {
                answers.forEach(function (ans) {
                    var inp = document.createElement('input');
                    inp.type = 'hidden';
                    inp.name = 'answers[' + mcqId + '][]';
                    inp.value = ans;
                    form.appendChild(inp);
                });
            } else {
                var h = document.createElement('input');
                h.type = 'hidden';
                h.name = 'answers[' + mcqId + ']';
                h.value = answers;
                form.appendChild(h);
            }
        });
    }

    function selectOption(element, mcqId, optionKey, isMultiple) {
        var optionItem = element;
        var questionCard = document.getElementById('question-' + mcqId);
        if (!questionCard) {
            return;
        }
        var inputs = questionCard.querySelectorAll('input');
        if (isMultiple) {
            var cb = document.getElementById('option-' + mcqId + '-' + optionKey);
            if (cb) {
                cb.checked = !cb.checked;
                if (cb.checked) {
                    optionItem.classList.add('selected');
                } else {
                    optionItem.classList.remove('selected');
                }
            }
        } else {
            inputs.forEach(function (i) {
                if (i.type === 'radio') {
                    i.checked = false;
                }
            });
            var radio = document.getElementById('option-' + mcqId + '-' + optionKey);
            if (radio) {
                radio.checked = true;
            }
            questionCard.querySelectorAll('.option-item').forEach(function (item) {
                item.classList.remove('selected');
            });
            optionItem.classList.add('selected');
        }
        var isAnswered = false;
        var selected = [];
        inputs.forEach(function (input) {
            if (input.checked) {
                isAnswered = true;
                selected.push(input.value);
            }
        });
        if (isAnswered) {
            answeredQuestions.add(parseInt(mcqId, 10));
            var pi = document.getElementById('palette-' + mcqId);
            if (pi) {
                pi.classList.add('answered');
            }
            if (isMultiple) {
                saveAnswer(mcqId, selected);
            } else {
                saveAnswer(mcqId, selected[0]);
            }
        } else {
            answeredQuestions.delete(parseInt(mcqId, 10));
            var p2 = document.getElementById('palette-' + mcqId);
            if (p2) {
                p2.classList.remove('answered');
            }
            var o = JSON.parse(localStorage.getItem(storageKey) || '{}');
            delete o[mcqId];
            localStorage.setItem(storageKey, JSON.stringify(o));
        }
        updateProgress();
    }

    function toggleDescription() {
        var preview = document.getElementById('descriptionPreview');
        var full = document.getElementById('descriptionFull');
        var btn = document.getElementById('toggleDescriptionBtn');
        if (!full || !preview) {
            return;
        }
        if (full.classList.contains('show')) {
            full.classList.remove('show');
            preview.style.display = 'block';
            if (btn) {
                btn.innerHTML = 'Read More <i class="fas fa-chevron-down ms-1"></i>';
            }
        } else {
            full.classList.add('show');
            preview.style.display = 'none';
            if (btn) {
                btn.innerHTML = 'Show Less <i class="fas fa-chevron-up ms-1"></i>';
            }
        }
    }

    function toggleHint(mcqId) {
        var h = document.getElementById('hint-' + mcqId);
        if (h) {
            h.classList.toggle('show');
        }
    }

    function scrollToQuestion(e, mcqId) {
        e.preventDefault();
        var el = document.getElementById('question-' + mcqId);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        document.querySelectorAll('.palette-item').forEach(function (item) {
            item.classList.remove('current');
        });
        var p = document.getElementById('palette-' + mcqId);
        if (p) {
            p.classList.add('current');
        }
    }

    function clearTest() {
        if (!confirm('Are you sure you want to clear all answers?')) {
            return;
        }
        document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(function (i) {
            i.checked = false;
        });
        document.querySelectorAll('.option-item').forEach(function (i) {
            i.classList.remove('selected');
        });
        answeredQuestions.clear();
        document.querySelectorAll('.palette-item').forEach(function (i) {
            i.classList.remove('answered');
        });
        localStorage.removeItem(storageKey);
        sessionStorage.removeItem(timerKey);
        startTime = Date.now();
        sessionStorage.setItem(timerKey, String(startTime));
        if (timerInterval) {
            clearInterval(timerInterval);
        }
        startTimer();
        updateProgress();
    }

    function submitTest() {
        var form = document.getElementById('testForm');
        if (!form) {
            return;
        }
        var n = countAnsweredFromStorage();
        if (n === 0) {
            alert('Please answer at least one question before submitting.');
            return;
        }
        setTimeTakenInput();
        if (!confirm('You have answered ' + n + ' out of ' + totalQuestions + ' questions. Are you sure you want to submit?')) {
            return;
        }
        appendHiddenAnswersFromLocalStorage(form);
        localStorage.removeItem(storageKey);
        sessionStorage.removeItem(timerKey);
        form.submit();
    }

    function submitTestEarly(confirmTextOrEl) {
        var msg;
        if (typeof confirmTextOrEl === 'string') {
            msg = confirmTextOrEl;
        } else if (confirmTextOrEl && confirmTextOrEl.getAttribute) {
            msg = confirmTextOrEl.getAttribute('data-confirm') || '';
        }
        if (msg && !window.confirm(msg)) {
            return;
        }
        var form = document.getElementById('testForm');
        if (!form) {
            return;
        }
        setTimeTakenInput();
        appendHiddenAnswersFromLocalStorage(form);
        localStorage.removeItem(storageKey);
        sessionStorage.removeItem(timerKey);
        form.submit();
    }

    document.addEventListener('DOMContentLoaded', function () {
        loadSavedAnswers();
        updateProgress();
        startTimer();

        var form = document.getElementById('testForm');
        if (form) {
            form.addEventListener('submit', function () {
                setTimeTakenInput();
                appendHiddenAnswersFromLocalStorage(this);
                localStorage.removeItem(storageKey);
                sessionStorage.removeItem(timerKey);
            });
        }

        window.addEventListener('scroll', function () {
            var questionCards = document.querySelectorAll('.question-card');
            var scrollPos = window.scrollY + 100;
            questionCards.forEach(function (card) {
                var rect = card.getBoundingClientRect();
                var absTop = window.scrollY + rect.top;
                var absBottom = absTop + rect.height;
                if (scrollPos >= absTop && scrollPos <= absBottom) {
                    var id = card.dataset.questionId;
                    document.querySelectorAll('.palette-item').forEach(function (i) {
                        i.classList.remove('current');
                    });
                    var pal = document.getElementById('palette-' + id);
                    if (pal) {
                        pal.classList.add('current');
                    }
                }
            });
        });
    });

    window.selectOption = selectOption;
    window.toggleDescription = toggleDescription;
    window.toggleHint = toggleHint;
    window.scrollToQuestion = scrollToQuestion;
    window.clearTest = clearTest;
    window.submitTest = submitTest;
    window.submitTopicTestEarly = submitTestEarly;
})();
