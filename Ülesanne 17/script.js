//Allan Lond TARpe23
//Ülesanne 17
//Loomise kuupäev 21.11.2024

const form = document.querySelector('form');
const responseBox = document.querySelector('.vastusekast');

form.addEventListener('submit', function (event) {
    event.preventDefault();

    const firstName = document.getElementById('firstName').value;
    const lastName = document.getElementById('lastName').value;
    const email = document.getElementById('email').value;
    const city = document.getElementById('city').value;
    const country = document.getElementById('country').value;
    const password = document.getElementById('password').value;

    const gender = document.querySelector('input[name="gender"]:checked');
    const genderValue = gender ? gender.value : "Pole valitud";

    responseBox.textContent = `
        Eesnimi: ${firstName}
        Perekonnanimi: ${lastName}
        E-post: ${email}
        Sugu: ${genderValue}
        Linn: ${city}
        Riik: ${country}
        Parool: ${password ? "Sisestatud" : "Puudub"}
    `;
});
