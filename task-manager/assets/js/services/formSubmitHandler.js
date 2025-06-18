export function disableSubmitButtonOnFormSubmit(formSelector, buttonSelector) {
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector(formSelector);
        const button = document.querySelector(buttonSelector);

        if (form && button) {
            form.addEventListener('submit', function () {
                console.log('Form submitted, disabling button');
                button.disabled = true;
            });
        } else {
            if (!form) console.warn(`Form not found with selector: ${formSelector}`);
            if (!button) console.warn(`Button not found with selector: ${buttonSelector}`);
        }
    });
}