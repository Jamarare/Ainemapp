function showAllParts() {
    document.querySelectorAll('.character > div').forEach(part => {
        part.style.display = 'block';
    });
}

function hideAllParts() {
    document.querySelectorAll('.character > div').forEach(part => {
        part.style.display = 'none';
    });
}
