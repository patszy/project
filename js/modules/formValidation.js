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

    getFields() {
        const inputs = [...this.form.querySelectorAll(`input:not(:disabled), select:not(:disabled), textarea:not(:disabled)`)];
        const fields = [];

        for (const element of inputs) if (element.willValidate && element.type != `submit` && element.name != `guardian`) fields.push(element);

        return fields;
    }

    prepareElements() {
        const elements = this.getFields();

        for(const element of elements){
            const type = element.type.toLowerCase();
            let eventName = `input`;

            if(type == `checkbox` || type == `radio` || type == `select`) eventName = `change`;
            if(type == `date`) {
                const currentDate = new Date();
                const maxYear = currentDate.getFullYear()-18;
                element.setAttribute(`max`, `${maxYear}-01-01`);
            }

            element.addEventListener(eventName, event => this.testInput(event.target));
        }
    }

    testInput(input) {
        let valid = input.checkValidity();
        this.toggleErrorField(input, !valid);
        return valid;
    }

    getErrorText = (element) => {
        const validity = element.validity;
        let text = `Wrong value.`;

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
            if (validity.rangeOverflow) {
                text = `Wybierz mniejszą wartość.`;
                if(element.name == `bdate`) text = `Musisz skończyć 18 lat.`;
            }
            if (validity.rangeUnderflow) text = `Wybierz większą wartość.`;
            if (validity.patternMismatch) {
                text = `Podaj wartość w wymaganym formacie.`;

                if(element.name == `name` || element.name == `sname`) {
                    text = `Wartość musi zaczynać się z dużej litery i mieć więcej niż 2 litery.`
                }
                if(element.name == `password`) {
                    text = `Hasło musi składać się z min. 8 znaków.`;
                }
            }
        }

        return text;
    };

    toggleErrorField(field, show){
        const formRow = field.closest(`.form__row`);

        let text = this.getErrorText(field);

        if(show) {
            formRow.classList.add(`--error`);
            formRow.setAttribute(`title`, text);
        } else {
            formRow.classList.remove(`--error`);
            formRow.removeAttribute(`title`);
        }
    }

    handleSubmit() {
        this.form.addEventListener(`submit`, event => {
            event.preventDefault();
            const elements = this.getFields();
            let formErrors = false;

            for (const element of elements) {
                this.toggleErrorField(element, !element.checkValidity());
                if(!element.checkValidity()) formErrors = true;
            }

            if(!formErrors) {
                event.target.submit();
            }
        });
    }
}

document.addEventListener(`DOMContentLoaded`, () =>{
    const formLogin = document.getElementsByClassName(`form__login`)[0];
    const formRegister = document.getElementsByClassName(`form__register`)[0];

    const formLoginValidation = new FormValidate(formLogin, {});
    const formRegisterValidation = new FormValidate(formRegister, {});
});