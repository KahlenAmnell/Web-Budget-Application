const limitBox = document.querySelector("#limitContainer");
const selected = document.querySelector("#category");

let categoryId = 0;
let categoryLimit = 0;

//pobiera dane kategorii z bazy
const getLimit = async (id) => {
    try {
        const res = await axios.get(`/api/expenseLimit/${id}`);
        categoryLimit = res.data;
    } catch (e) {
        return "problem";
    }
}
selected.addEventListener('input', async () => {
    categoryId = selected.value;
    await getLimit(categoryId);
    categoryLimit = parseFloat(categoryLimit).toFixed(2);
    if (categoryLimit != 0) {
        limitBox.classList.replace('d-none', 'd-flex');
    } else {
        limitBox.classList.replace('d-flex', 'd-none');
    }
})