document.addEventListener('DOMContentLoaded', function () {
    // Registration Type Toggle
    const typeButtons = document.querySelectorAll('.type-btn');
    const userRegistration = document.getElementById('userRegistration');
    const schoolRegistration = document.getElementById('schoolRegistration');

    typeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const type = this.getAttribute('data-type');

            // Update active button
            typeButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Show appropriate form
            if (type === 'user') {
                userRegistration.style.display = 'block';
                schoolRegistration.classList.remove('active');
            } else {
                userRegistration.style.display = 'none';
                schoolRegistration.classList.add('active');
                resetSchoolForm();
            }
        });
    });

    // School Registration Multi-step Form
    const formSteps = document.querySelectorAll('.form-step');
    const stepIndicators = document.querySelectorAll('.step');
    const nextButtons = document.querySelectorAll('.btn-next');
    const prevButtons = document.querySelectorAll('.btn-prev');

    // Next Button Click
    nextButtons.forEach(button => {
        button.addEventListener('click', function () {
            const currentStep = document.querySelector('.form-step.active');
            const nextStepNumber = this.getAttribute('data-next');

            if (nextStepNumber) {
                // Validate current step before proceeding
                const result = validateStep(currentStep);
                if (result.isValid) {
                    hideErrorSummary();
                    // Update step indicators
                    stepIndicators.forEach(step => {
                        if (parseInt(step.getAttribute('data-step')) === parseInt(nextStepNumber)) {
                            step.classList.add('active');
                        } else if (parseInt(step.getAttribute('data-step')) < parseInt(nextStepNumber)) {
                            step.classList.add('completed');
                            step.classList.remove('active');
                        } else {
                            step.classList.remove('active', 'completed');
                        }
                    });

                    // Show next step
                    currentStep.classList.remove('active');
                    document.querySelector(`.form-step[data-step="${nextStepNumber}"]`).classList.add('active');
                } else {
                    showErrorSummary(result.errors);
                }
            }
        });
    });

    // Previous Button Click
    prevButtons.forEach(button => {
        button.addEventListener('click', function () {
            const currentStep = document.querySelector('.form-step.active');
            const prevStepNumber = this.getAttribute('data-prev');

            if (prevStepNumber) {
                // Update step indicators
                stepIndicators.forEach(step => {
                    if (parseInt(step.getAttribute('data-step')) === parseInt(prevStepNumber)) {
                        step.classList.add('active');
                        step.classList.remove('completed');
                    } else if (parseInt(step.getAttribute('data-step')) < parseInt(prevStepNumber)) {
                        step.classList.add('completed');
                        step.classList.remove('active');
                    } else {
                        step.classList.remove('active', 'completed');
                    }
                });

                // Show previous step
                currentStep.classList.remove('active');
                document.querySelector(`.form-step[data-step="${prevStepNumber}"]`).classList.add('active');
            }
        });
    });

    // Collected errors for the summary box — reset at the start of each validateStep run
    let validationErrors = [];

    function getFieldLabel(field) {
        const parent = field.closest('.form-group') || field.parentNode;
        const lbl = parent.querySelector('.form-label, label.form-check-label');
        return lbl ? lbl.textContent.replace(/\*/g, '').trim() : (field.name || 'Field');
    }

    function getFieldStep(field) {
        const step = field.closest('.form-step');
        return step ? parseInt(step.getAttribute('data-step'), 10) : null;
    }

    // Inline error helpers — reuse the same `.input-error` div the server-side renders
    function setFieldError(field, message) {
        field.classList.add('is-invalid');
        field.style.borderColor = '#e53e3e';

        const parent = field.closest('.form-group') || field.parentNode;
        let err = parent.querySelector('.input-error.js-error');
        if (!err) {
            err = document.createElement('div');
            err.className = 'input-error js-error';
            parent.appendChild(err);
        }
        err.textContent = message;

        validationErrors.push({
            step: getFieldStep(field),
            label: getFieldLabel(field),
            message,
            fieldName: field.name
        });
    }

    function clearFieldError(field) {
        field.classList.remove('is-invalid');
        field.style.borderColor = '';
        const parent = field.closest('.form-group') || field.parentNode;
        const err = parent.querySelector('.input-error.js-error');
        if (err) err.remove();
    }

    const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const PHONE_RE = /^[0-9+\-\s()]{7,20}$/;

    function validateField(field) {
        const val = (field.value || '').trim();
        const name = field.name;
        const type = field.type;

        if (field.hasAttribute('required') && !val) {
            setFieldError(field, 'This field is required.');
            return false;
        }
        if (!val) { clearFieldError(field); return true; } // optional & empty = ok

        if (type === 'email' && !EMAIL_RE.test(val)) {
            setFieldError(field, 'Please enter a valid email address.');
            return false;
        }
        if (name === 'school_contact' && !PHONE_RE.test(val)) {
            setFieldError(field, 'Please enter a valid phone number.');
            return false;
        }
        if (name === 'school_name' && (val.length < 3 || val.length > 100)) {
            setFieldError(field, 'School name must be 3–100 characters.');
            return false;
        }
        if (name === 'school_website') {
            try { new URL(val); } catch (_) {
                setFieldError(field, 'Please enter a valid URL (https://…).');
                return false;
            }
        }
        if (name === 'admin_password' && val.length < 8) {
            setFieldError(field, 'Password must be at least 8 characters.');
            return false;
        }
        if (name === 'admin_password_confirmation') {
            const pw = document.querySelector('input[name="admin_password"]');
            if (pw && val !== pw.value) {
                setFieldError(field, 'Passwords do not match.');
                return false;
            }
        }

        clearFieldError(field);
        return true;
    }

    function validateStep(step) {
        validationErrors = [];
        let isValid = true;

        const fields = step.querySelectorAll('input, select, textarea');
        fields.forEach(f => {
            if (f.type === 'hidden' || f.disabled) return;
            if (!validateField(f)) isValid = false;
        });

        // Step 3 — fee structure specifics (only when visible)
        if (step.getAttribute('data-step') === '3') {
            const feeType = document.querySelector('input[name="fee_structure_type"]:checked');
            if (!feeType) {
                isValid = false;
            } else if (feeType.value === 'class_wise') {
                const rows = document.querySelectorAll('#fees-container .fees-row');
                if (rows.length === 0) isValid = false;
                rows.forEach(row => {
                    const range  = row.querySelector('.class-range');
                    const amount = row.querySelector('.fees-amount');
                    if (range && !range.value.trim()) {
                        setFieldError(range, 'Class range is required.'); isValid = false;
                    } else if (range) { clearFieldError(range); }
                    if (amount) {
                        const v = amount.value.trim();
                        if (!v) { setFieldError(amount, 'Fee amount is required.'); isValid = false; }
                        else if (isNaN(v) || parseFloat(v) < 0) {
                            setFieldError(amount, 'Fee amount must be a non-negative number.'); isValid = false;
                        } else { clearFieldError(amount); }
                    }
                });
            }

            const terms = document.getElementById('school_terms');
            if (terms && !terms.checked) {
                setFieldError(terms, 'You must accept the confirmation to continue.');
                isValid = false;
            } else if (terms) { clearFieldError(terms); }
        }

        if (!isValid) {
            const firstErr = step.querySelector('.is-invalid');
            if (firstErr) {
                firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                try { firstErr.focus({ preventScroll: true }); } catch (_) {}
            }
        }

        return { isValid, errors: validationErrors.slice() };
    }

    // Validate every step at once, accumulating errors from all of them
    function validateAllSteps() {
        const allErrors = [];
        let allValid = true;
        document.querySelectorAll('#schoolRegistration .form-step').forEach(step => {
            const r = validateStep(step);
            if (!r.isValid) allValid = false;
            allErrors.push(...r.errors);
        });
        return { isValid: allValid, errors: allErrors };
    }

    // Jump to a given step (used by clickable error items in the summary)
    function goToStep(stepNumber) {
        const target = document.querySelector(`.form-step[data-step="${stepNumber}"]`);
        if (!target) return;
        formSteps.forEach(s => s.classList.remove('active'));
        target.classList.add('active');
        stepIndicators.forEach(ind => {
            const n = parseInt(ind.getAttribute('data-step'));
            ind.classList.toggle('active', n === stepNumber);
            ind.classList.toggle('completed', n < stepNumber);
        });
    }

    // ── Sticky error summary box ──
    function showErrorSummary(errors) {
        const summary = document.getElementById('schoolErrorSummary');
        const list    = document.getElementById('schoolErrorList');
        if (!summary || !list || !errors || errors.length === 0) return;

        list.innerHTML = errors.map(e => {
            const stepLink = e.step
                ? `<a href="#" data-goto-step="${e.step}" class="error-goto-step">Step ${e.step}:</a> `
                : '';
            const lbl = e.label ? `<strong>${escapeHtml(e.label)}</strong> — ` : '';
            return `<li>${stepLink}${lbl}${escapeHtml(e.message)}</li>`;
        }).join('');

        list.querySelectorAll('.error-goto-step').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const n = parseInt(this.dataset.gotoStep);
                if (n) goToStep(n);
                summary.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        // restart the shake animation
        summary.style.display = 'block';
        summary.style.animation = 'none';
        void summary.offsetWidth;
        summary.style.animation = '';
        summary.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function hideErrorSummary() {
        const summary = document.getElementById('schoolErrorSummary');
        const list    = document.getElementById('schoolErrorList');
        if (summary) summary.style.display = 'none';
        if (list)    list.innerHTML = '';
    }

    function escapeHtml(s) {
        return String(s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    // Expose for inline blade script (backend-error path)
    window.showSchoolErrorSummary = showErrorSummary;
    window.hideSchoolErrorSummary = hideErrorSummary;

    // Close button
    document.getElementById('closeErrorSummary')?.addEventListener('click', hideErrorSummary);

    // Clear error as soon as the user starts correcting the field;
    // auto-hide the summary box once no invalid fields remain.
    function onFieldCorrected(field) {
        clearFieldError(field);
        if (!document.querySelector('#schoolRegistration .is-invalid')) {
            hideErrorSummary();
        }
    }
    document.querySelectorAll('#schoolRegistration input, #schoolRegistration select, #schoolRegistration textarea')
        .forEach(field => {
            field.addEventListener('input',  () => onFieldCorrected(field));
            field.addEventListener('change', () => onFieldCorrected(field));
        });

    // Persist last visited step so it can be restored after a backend error round-trip
    function rememberCurrentStep() {
        const current = document.querySelector('.form-step.active');
        if (current) sessionStorage.setItem('schoolRegLastStep', current.getAttribute('data-step'));
    }
    nextButtons.forEach(b => b.addEventListener('click', rememberCurrentStep));
    prevButtons.forEach(b => b.addEventListener('click', rememberCurrentStep));

    // Final-submit guard — validate ALL steps before letting the form POST,
    // and surface every error at once in the summary box.
    const schoolForm = document.querySelector('#schoolRegistration form');
    if (schoolForm) {
        schoolForm.addEventListener('submit', function (e) {
            const result = validateAllSteps();
            if (!result.isValid) {
                e.preventDefault();
                showErrorSummary(result.errors);
                const firstStep = result.errors.find(err => err.step)?.step;
                if (firstStep) goToStep(firstStep);
                return false;
            }
            hideErrorSummary();
            sessionStorage.removeItem('schoolRegLastStep');
        });
    }

    // Reset School Form
    function resetSchoolForm() {
        formSteps.forEach(step => step.classList.remove('active'));
        document.querySelector('.form-step[data-step="1"]').classList.add('active');

        stepIndicators.forEach(step => {
            if (parseInt(step.getAttribute('data-step')) === 1) {
                step.classList.add('active');
            } else {
                step.classList.remove('active', 'completed');
            }
        });
    }
});