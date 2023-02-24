/**
 * Add jQuery Validation plugin method for a valid password
 * 
 * Valid passwords contain at least one letter and one number.
 */
$.validator.addMethod('validPassword',
    function (value, element, param) {
        if (value != '') {
            if (value.match(/.*[a-z]+.*/i) == null) {
                return false;
            }
            if (value.match(/.*\d+.*/) == null) {
                return false;
            }
        }
        return true;
    },
    'Hasło musi zawierać conajmniej 1 literę i 1 cyfrę.'
);

const appearLimitInput = () => {
    const categoryGroup = document.querySelectorAll('input[name="categoryGroup"]');
    const limit = document.querySelector("#limitValue");
    categoryGroup.forEach(group => group.addEventListener('change', () => {
        if (document.querySelector("#expenseRadio").checked == true) {
            limit.classList.remove("d-none");
        } else {
            limit.classList.add("d-none");
        }
    }));
}

const ableLimitAmountInput = () => {
    const limitCheckbox = document.querySelector('#limitCheckbox');
    const limitInput = document.querySelector("#limitAmount");
    limitCheckbox.addEventListener('click', () => {
        if(limitCheckbox.checked == true) {
            limitInput.removeAttribute('disabled');
        } else {
            limitInput.setAttribute('disabled', "");
        }
    })
}