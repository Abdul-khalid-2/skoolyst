document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("testimonialForm");
    if (!form) {
        return;
    }

    const submitBtn = form.querySelector(".btn-submit");
    const submitText = form.querySelector(".submit-text");
    const spinner = form.querySelector(".spinner-border");
    const formMessage = document.getElementById("formMessage");
    const submitUrl = form.dataset.submitUrl || "/testimonials";

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        submitBtn.disabled = true;
        submitText.textContent = "Submitting...";
        spinner.classList.remove("d-none");

        formMessage.classList.add("d-none");
        formMessage.textContent = "";

        try {
            const response = await fetch(submitUrl, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Accept": "application/json"
                },
                body: new FormData(form)
            });

            const data = await response.json();

            if (response.ok) {
                formMessage.classList.remove("d-none", "alert-danger");
                formMessage.classList.add("alert-success");
                formMessage.textContent = data.message;

                form.reset();

                const starInputs = form.querySelectorAll('input[name="rating"]');
                starInputs.forEach(function (input) {
                    input.checked = false;
                });

                formMessage.scrollIntoView({ behavior: "smooth" });

                setTimeout(function () {
                    formMessage.classList.add("d-none");
                }, 5000);
            } else {
                formMessage.classList.remove("d-none", "alert-success");
                formMessage.classList.add("alert-danger");

                if (data.errors) {
                    let errorHtml = '<ul class="mb-0">';
                    for (const field in data.errors) {
                        errorHtml += "<li>" + data.errors[field][0] + "</li>";
                    }
                    errorHtml += "</ul>";
                    formMessage.innerHTML = errorHtml;
                } else {
                    formMessage.textContent = data.message || "An error occurred. Please try again.";
                }
            }
        } catch (error) {
            console.error("Error:", error);
            formMessage.classList.remove("d-none", "alert-success");
            formMessage.classList.add("alert-danger");
            formMessage.textContent = "An error occurred. Please try again.";
        } finally {
            submitBtn.disabled = false;
            submitText.textContent = "Submit Feedback";
            spinner.classList.add("d-none");
        }
    });
});
