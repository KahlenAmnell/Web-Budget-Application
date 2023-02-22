const limitBox = document.querySelector("#limitContainer");
const selected = document.querySelector("#category");

let categoryId = 0;
let categoryLimit = 0;

//Get category limit from database
const getLimit = async (id) => {
    try {
        const res = await axios.get(`/api/expenseLimit/${id}`);
        categoryLimit = res.data;
    } catch (e) {
        return "problem";
    }
}

//Set limit in the field
const setLimit = (limit) => {
    limit = parseFloat(limit);
    document.querySelector("#categoryLimit").textContent = limit.toFixed(2);
}

//Show limit box
const showLimit = async (limit, categoryId) => {
    if (limitBox.classList.contains('d-none')) {
        limitBox.classList.replace('d-none', 'd-flex');
    }
    setLimit(limit);
    

}

selected.addEventListener('input', async () => {
    categoryId = selected.value;
    await getLimit(categoryId);
    categoryLimit = parseFloat(categoryLimit).toFixed(2);
    if (categoryLimit != 0) {
        await showLimit(categoryLimit, categoryId);
    } else {
        limitBox.classList.replace('d-flex', 'd-none');
    }
})