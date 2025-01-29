// kood koostamise aeg: 22.10.2024
// Allan Lond TARpe23
// Ülesanne 3


// 1)
console.log("Sõidu aeg ja kaugus");

function arvutaAeg() {
    const distance = parseFloat(document.getElementById("distance").value);
    const speed = parseFloat(document.getElementById("speed").value);
    
    if (!isNaN(distance) && !isNaN(speed) && speed > 0) {
        const time = distance / speed;
        console.log(`${distance} km läbimiseks kiirusel ${speed} km/h kulub ${time.toFixed(2)} tundi.`);
        document.getElementById("output").textContent = 
        `${distance} km läbimiseks kiirusel ${speed} km/h kulub ${time.toFixed(2)} tundi.`;
    } else {
        document.getElementById("output").textContent = "Palun sisesta korrektne kaugus ja kiirus.";
    }
}



// 2)

function arvutaLehekülgi() {
    const totalPosts = parseInt(document.getElementById("totalPosts").value);
    const postsPerPage = parseInt(document.getElementById("postsPerPage").value);

    if (!isNaN(totalPosts) && !isNaN(postsPerPage) && postsPerPage > 0) {
        const totalPages = Math.ceil(totalPosts / postsPerPage);
        const postsOnLastPage = totalPosts % postsPerPage;

        console.log("Kokku on vaja " + totalPages + " lehekülge.");
        console.log("Viimasel lehel on " + postsOnLastPage + " postitust.");

        document.getElementById("output").textContent = 
        "Kokku on vaja " + totalPages + " lehekülge. Viimasel lehel on " + postsOnLastPage + " postitust.";
    } else {
        document.getElementById("output").textContent = "Palun sisesta korrektne postituste arv ja lehe suurus.";
    }
}



// 3)

function arvutaTöökulu() {
    const võimsus = parseFloat(document.getElementById("võimsus").value);
    const elektriHind = parseFloat(document.getElementById("elektriHind").value);

    if (!isNaN(võimsus) && !isNaN(elektriHind) && võimsus > 0 && elektriHind > 0) {
        const voolutarbimine = võimsus / 1000;
        const tööKulu = voolutarbimine * elektriHind;

        console.log("Serveri töökulu ühe tunni jooksul on: " + tööKulu.toFixed(4) + " eurot");

        document.getElementById("output").textContent = 
        "Serveri töökulu ühe tunni jooksul on: " + tööKulu.toFixed(4) + " eurot";
    } else {
        document.getElementById("output").textContent = "Palun sisesta korrektne võimsus ja elektri hind.";
    }
}

