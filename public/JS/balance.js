var todayDate = () => {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }
    today = yyyy + '-' + mm + '-' + dd;
    document.getElementById("beginingDate").setAttribute("max", today);
    document.getElementById("endingDate").setAttribute("max", today);
}

var displayCustomDateInput = () => {
    const customDate = document.querySelector("#customDateInput");
    const periodSelected = document.querySelector("#period");

    periodSelected.addEventListener('change', function () {
        if (periodSelected.selectedIndex === 3) {
            customDate.style.display = "block";
        } else {
            customDate.style.display = "none";
        }
    })
}

var validateUpdateIncome = () => {
    $(document).ready(function () {
        $("#incomeUpdateForm").validate({
            errorClass: 'errors',
            rules: {
                amount: {
                    required: true,
                    min: 0,
                    step: 0.01,
                    max: 2147483646,
                    number: true
                },
                date: {
                    required: true,
                    min: '2000-01-01'
                },
                category: 'required',
                comment: {
                    maxlength: 250
                }
            },
            messages: {
                amount: {
                    required: 'Podaj kwotę.',
                    min: 'Kwota musi być większa niż 0.',
                    step: 'Podaj kwotę do dwóch miejsc po przecinku.',
                    max: 'Kwota nie może być większa od 2147483646.',
                    number: 'Wprowadź liczbę.'
                },
                date: {
                    required: 'Podaj datę.',
                    min: 'Data nie może być wcześniejsza niż 01-01-2000.',
                    max: 'Data nie może być przyszła.'
                },
                category: 'Wybierz kategorię przychodu.',
                comment: {
                    maxlength: 'Komentarz nie może mieć więcej niż 250 znaków.'
                }

            }
        });
    });
}