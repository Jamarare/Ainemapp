//Allan Lond TARpe23
//Ülesanne 16
//Loomise kuupäev 21.11.2024

const mobList = document.getElementById("mob");

mobList.addEventListener("click", function(event) {
  const clickedElement = event.target;

  if (clickedElement.tagName === "LI" || clickedElement.tagName === "SPAN") {
    const hiddenNumber = clickedElement.querySelector(".peida");
    
    if (hiddenNumber) {
      hiddenNumber.classList.remove("peida");
    }
  }
});

