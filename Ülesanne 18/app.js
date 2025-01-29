document.addEventListener("DOMContentLoaded", () => {
    const firstNameInput = document.getElementById("firstName");
    const lastNameInput = document.getElementById("lastName");
    const emailInput = document.getElementById("email");

    const firstNameVal = document.getElementById("firstNameVal");
    const lastNameVal = document.getElementById("lastNameVal");
    const emailVal = document.getElementById("emailVal");

    firstNameInput.addEventListener("input", () => {
        const value = firstNameInput.value.trim();
        if (/^[a-zA-ZäöüõÄÖÜÕ]{2,}$/.test(value)) {
            firstNameInput.classList.remove("is-invalid");
            firstNameInput.classList.add("is-valid");
            firstNameVal.textContent = "Eesnimi sobib.";
            firstNameVal.style.color = "green";
        } else {
            firstNameInput.classList.remove("is-valid");
            firstNameInput.classList.add("is-invalid");
            firstNameVal.textContent = "Eesnimi peab olema vähemalt 2 tähemärki ja sisaldama ainult tähti.";
            firstNameVal.style.color = "red";
        }
    });

    lastNameInput.addEventListener("input", () => {
        const value = lastNameInput.value.trim();
        if (/^[a-zA-ZäöüõÄÖÜÕ]{2,}$/.test(value)) {
            lastNameInput.classList.remove("is-invalid");
            lastNameInput.classList.add("is-valid");
            lastNameVal.textContent = "Perenimi sobib.";
            lastNameVal.style.color = "green";
        } else {
            lastNameInput.classList.remove("is-valid");
            lastNameInput.classList.add("is-invalid");
            lastNameVal.textContent = "Perenimi peab olema vähemalt 2 tähemärki ja sisaldama ainult tähti.";
            lastNameVal.style.color = "red";
        }
    });

    emailInput.addEventListener("input", () => {
        const value = emailInput.value.trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailPattern.test(value)) {
            emailInput.classList.remove("is-invalid");
            emailInput.classList.add("is-valid");
            emailVal.textContent = "Email sobib.";
            emailVal.style.color = "green";
        } else {
            emailInput.classList.remove("is-valid");
            emailInput.classList.add("is-invalid");
            emailVal.textContent = "Sisestage korrektne e-posti aadress.";
            emailVal.style.color = "red";
        }
    });
});
