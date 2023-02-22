const limitBox = document.querySelector("#limitContainer");
const selected = document.querySelector("#category");
const amountInput = document.querySelector("#amount");
const dateInput = document.querySelector("#datefield");
let categoryId = 0;
let categoryLimit = 0;
let amount = 0;
let date = 0;

//Get category limit from database
const getLimit = async () => {
    try {
        const res = await axios.get(`/api/expenseLimit/${categoryId}`);
        categoryLimit = res.data;
    } catch (e) {
        return "problem";
    }
}

//Get expenses for the month of the selected date
const getCategoryExpenses = async () => {
    try {
        const res = await axios.get(`/api/categoryExpenses/${categoryId}/${date}`);
        return res.data;
    } catch (e) {
        return "problem";
    }
}

//Set limit in the field
const setLimit = (limit) => {
    limit = parseFloat(limit);
    document.querySelector("#categoryLimit").textContent = limit.toFixed(2);
}

//Set already expense in the field
const setExpenses = async () => {
    const alreadyExpense = document.querySelector("#alreadyExpense");

    if (dateInput.value != "") {
        date = dateInput.value;
    }

    let categoryExpenses = await getCategoryExpenses();
    if (categoryExpenses == null) {
        alreadyExpense.textContent = "0.00";
    } else {
        categoryExpenses = parseFloat(categoryExpenses);
        alreadyExpense.textContent = categoryExpenses;
    }
    return categoryExpenses;
}

//Change limit box color
const changeBoxColor = (newBalance) => {
    if (newBalance < 0) {
        limitBox.classList.replace('border-success', 'border-danger')
        limitBox.classList.replace('bg-success', 'bg-danger')
    } else {
        limitBox.classList.replace('border-danger', 'border-success')
        limitBox.classList.replace('bg-danger', 'bg-success')
    }
}

//Show limit box
const showLimit = async (limit) => {
    if (limitBox.classList.contains('d-none')) {
        limitBox.classList.replace('d-none', 'd-flex');
    }
    setLimit(limit);
    let categoryExpenses = await setExpenses();

    const balance = limit - categoryExpenses;
    document.querySelector("#limitBalance").textContent = balance.toFixed(2);

    const newBalance = limit - categoryExpenses - amount;
    document.querySelector("#newLimitBalance").textContent = Number(newBalance).toFixed(2);

    changeBoxColor(newBalance);
}

amountInput.addEventListener('input', () => {
    amount = amountInput.value;
    if (categoryId != 0 && categoryLimit != 0) {
        showLimit(categoryLimit);
    }
})

dateInput.addEventListener('input', () => {
    date = dateInput.value;
    if (categoryId != 0 && categoryLimit != 0) {
        showLimit(categoryLimit);
    }
})

selected.addEventListener('input', async () => {
    categoryId = selected.value;
    await getLimit();
    categoryLimit = parseFloat(categoryLimit).toFixed(2);
    if (categoryLimit != 0) {
        await showLimit(categoryLimit);
    } else {
        limitBox.classList.replace('d-flex', 'd-none');
    }
})