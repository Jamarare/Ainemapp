//Allan Lond TARpe23
//Ülesanne 6
//Loomise kuupäev 05.11.2024


function kontrolliNumberit() {
    const number = parseFloat(document.getElementById("numberInput").value);
    
    if (isNaN(number)) {
        document.getElementById("result").textContent = "Palun sisestage korrektne number.";
        return;
    }
    
    switch (true) {
        case (number > 0):
            document.getElementById("result").textContent = "Number on positiivne.";
            break;
        case (number < 0):
            document.getElementById("result").textContent = "Number on negatiivne.";
            break;
        case (number === 0):
            document.getElementById("result").textContent = "Number on null.";
            break;
        default:
            document.getElementById("result").textContent = "Palun sisestage korrektne number.";
            break;
    }
}
document.getElementById("checkButton").addEventListener("click", kontrolliNumberit);


function kontrolliLauda() {
    const broneering = parseInt(document.getElementById("bookingInput").value);
    
    if (isNaN(broneering) || broneering < 1) {
        document.getElementById("tableResult").textContent = "Palun sisestage korrektne broneeringu arv.";
        return;
    }

    switch (true) {
        case (broneering === 1 || broneering === 2):
            document.getElementById("tableResult").textContent = "Valige laud kahele inimesele.";
            break;
        case (broneering === 3 || broneering === 4):
            document.getElementById("tableResult").textContent = "Valige laud neljale inimesele.";
            break;
        case (broneering === 5 || broneering === 6):
            document.getElementById("tableResult").textContent = "Valige laud kuuele inimesele.";
            break;
        case (broneering > 6):
            document.getElementById("tableResult").textContent = "Valige suur laud.";
            break;
        default:
            document.getElementById("tableResult").textContent = "Palun sisestage korrektne broneeringu arv.";
            break;
    }
}
document.getElementById("checkTableButton").addEventListener("click", kontrolliLauda);