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
    document.getElementById("incomeUpdateDatefield").setAttribute("max", today);
    document.getElementById("expenseUpdateDatefield").setAttribute("max", today);


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

var setIncomeModal = () => {
    $("button[name='updateIncomeBtn']").click(function () {
        $('#incomeAmount').val($(this).attr("data-amount"));
        $('#incomeUpdateDatefield').val($(this).attr("data-date"));
        $('#incomeComment').val($(this).attr("data-comment"));

        var incomeId = $(this).attr("data-id");
        $('#incomeId').val(parseInt(incomeId));

        var selectedCategory = $(this).attr("data-name");
        $(`#incomeCategory option[name='${selectedCategory}']`).attr("selected", "selected");
    })
}

var setExpenseModal = () => {
    $("button[name='updateExpenseBtn']").click(function () {
        $('#expenseAmount').val($(this).attr("data-amount"));
        $('#expenseUpdateDatefield').val($(this).attr("data-date"));
        $('#expenseComment').val($(this).attr("data-comment"));
        
        var expenseId = $(this).attr("data-id");
        $('#expenseId').val(parseInt(expenseId));

        var selectedCategory = $(this).attr("data-name");
        $(`#expenseCategory option[name='${selectedCategory}']`).attr("selected", "selected");

        var selectedPayment = $(this).attr("data-payment");
        $(`#paymentMethod option[name='${selectedPayment}']`).attr("selected", "selected");
    })
}