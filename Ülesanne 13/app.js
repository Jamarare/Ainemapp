const eemaldaID = document.getElementById('eemaldaID');
eemaldaID.removeAttribute('id');
eemaldaID.setAttribute('attr', 'Teie Nimi');
console.log(eemaldaID.getAttribute('attr'));

const cardElements = document.querySelectorAll('.card');

cardElements.forEach(card => {
    const title = card.querySelector('img').getAttribute('data-title');
    const description = card.querySelector('img').getAttribute('data-description');
    
    card.querySelector('.card-title').textContent = title;
    card.querySelector('.card-text').textContent = description;
});
