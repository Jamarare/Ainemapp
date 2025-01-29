document.addEventListener("DOMContentLoaded", () => {
    const button = document.getElementById("theme");
    const body = document.body;

    button.addEventListener("click", () => {
        console.log("pihtas");
        
        if (button.classList.contains("btn-dark")) {
            button.classList.remove("btn-dark");
            button.classList.add("btn-light");
            button.textContent = "Light Theme";
        } else {
            button.classList.remove("btn-light");
            button.classList.add("btn-dark");
            button.textContent = "Dark Theme";
        }

        body.classList.toggle("dark");
    });
});
