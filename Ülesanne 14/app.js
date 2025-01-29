const listItems = document.querySelectorAll('.list-group-item');

listItems.forEach(item => {
    const tekst = item.textContent.toLowerCase();

    if (tekst.includes("ootel")) {
        item.classList.add('list-group-item-warning');
    } else if (tekst.includes("tehtud")) {
        item.classList.add('list-group-item-success');
    } else if (tekst.includes("viga")) {
        item.classList.add('list-group-item-danger');
    }
});
