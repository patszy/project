class FormValidate {
    constructor(form, options) {
        const defaultOptions = {
            classError: `error`
        }

        this.form = form;
        this.options = Object.assign({}, defaultOptions, options);

        this.form.setAttribute(`novalidate`, true);
        this.prepareElements();
        this.handleSubmit();
    }

    getFields = () => {
        const inputs = [...this.form.querySelectorAll(`input:not(:disabled), select:not(:disabled), textarea:not(:disabled)`)];
        const fields = [];

        for (const element of inputs) if (element.willValidate && element.type != `submit` && element.name != `guardian`) fields.push(element);

        return fields;
    }

    prepareElements = () => {
        const elements = this.getFields();

        for(const element of elements){
            const type = element.type.toLowerCase();
            let eventName = `input`;

            if(type == `checkbox` || type == `radio` || type == `select`) eventName = `change`;
            if(type == `number`) {
                const currentDate = new Date();
                const maxYear = currentDate.getFullYear();
                element.setAttribute(`max`, `${maxYear}`);
            }

            element.addEventListener(eventName, event => this.testInput(event.target));
        }
    }

    testInput = input => {
        let valid = input.checkValidity();
        this.toggleErrorField(input, !valid);

        return valid;
    }

    getErrorText = (element) => {
        const validity = element.validity;
        let text = `Wartość nieprawidłowa.`;

        if (!validity.valid) {
            if (validity.valueMissing) text = `Uzupełnij pole.`;
            if (validity.typeMismatch) {
                if (element.type === `email`) text = `Wpisz właściwy email.`;
                if (element.type === `url`) text = `Wpisz właściwy URL.`;
            }
            if (validity.tooShort) text = `Wartość jest za krótka.`;
            if (validity.tooLong) text = `Wartość jest zbyt długa.`;
            if (validity.badInput) text = `Wpisz liczbę.`;
            if (validity.stepMismatch) text = `Wybierz właściwą wartość.`;
            if (validity.rangeOverflow) text = `Wybierz mniejszą wartość.`;
            if (validity.rangeUnderflow) text = `Wybierz większą wartość.`;
            if (validity.patternMismatch) {
                text = `Niepoprawny format wartości.`;
                if(element.name == `email`) text = `Niepoprawny email.`;
            }
        }

        return text;
    };

    createErrorField = (name, className, text) => {
        let field = document.createElement(name);
        field.innerText = text;

        if(className) field.classList.add(className);

        return field;
    }

    toggleErrorField = (field, show) => {
        const formRow = field.closest(`.form__row`);

        let text = this.getErrorText(field);

        if(show) {
            formRow.classList.add(`--error`);
            if(!formRow.firstElementChild.classList.contains(`error__field`)) formRow.prepend(this.createErrorField(`div`, `error__field`, text));
            else formRow.firstElementChild.innerText = text;
        } else {
            formRow.classList.remove(`--error`);
            if(formRow.firstElementChild.classList.contains(`error__field`)) formRow.firstElementChild.remove();
        }
    }

    handleSubmit = () => {
        this.form.addEventListener(`submit`, event => {
            event.preventDefault();
            const elements = this.getFields();
            let formErrors = false;

            for (const element of elements) {
                this.toggleErrorField(element, !element.checkValidity());
                if(!element.checkValidity()) formErrors = true;
            }

            if(!formErrors) {
                const submit = this.form.querySelector(`.form__submit`);
                submit.disabled = true;
                submit.classList.add(`loading`);

                const url = this.form.action;
                const method = this.form.method;
                const formData = new FormData(this.form);

                fetch(url, {
                    method: method.toUpperCase(),
                    body: formData
                })
                .then(response => response.json())
                .then(response => {
                    if (response.status == `error`) toggleAlert(`${response.error}` , `error`, true);
                    else {
                        if (response.status == `warning`) {
                            toggleAlert(`${response.warning}` , `warning`, true);
                        } else if (response.status == `success`) {
                            if (response.success) {
                                if(response.updateUser || response.deleteUser) {
                                    fetch('./php/modules/logout.php', {method: method.toUpperCase()});
                                    logOut();
                                    setTimeout(() => window.location.reload(true), 1500);
                                }
                                if(response.deletePost) submit.closest(`.post__container`).remove();
                                if(response.post) {
                                    createPosts(response.post, true);
                                }

                                toggleAlert(`${response.success}` ,`success`, true);
                            }
                            else if (response.info) toggleAlert(`${response.info}` ,`info`, true);

                            if(response.session) logIn(response.session);
                        }
                    }
                }).finally(() => {
                    submit.disabled = false;
                    submit.classList.remove(`loading`);
                });
            }
        });
    }
}