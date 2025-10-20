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
                if (validateStep(currentStep)) {
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

    // Form Validation
    function validateStep(step) {
        const inputs = step.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.style.borderColor = '#e53e3e';
            } else {
                input.style.borderColor = '#e0e0e0';
            }
        });

        return isValid;
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