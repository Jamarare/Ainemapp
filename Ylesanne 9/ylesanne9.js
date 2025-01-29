//Allan Lond TARpe23
//Ülesanne 9
//Koostamise kuupäev 07.11.2024

function Nimi() {
    return "Allan";
}

console.log(Nimi());

const NooleFunktsioon = () => "Allan";

console.log(NooleFunktsioon());


function kuupäevEesti() {
    const kuupäev = document.getElementById("kuupaevInput").value;

    const kuud = [
        "jaanuar", "veebruar", "märts", "aprill", "mai", "juuni",
        "juuli", "august", "september", "oktoober", "november", "detsember"
    ];
    const osad = kuupäev.split(".");
    const päev = osad[0];
    const kuu = parseInt(osad[1], 10) - 1;

    const kuupäevEestiKujul = `${päev}. ${kuud[kuu]} ${aasta}`;

    document.getElementById("tulemus").innerText = kuupäevEestiKujul;
}


function arvutaSummaJaKeskmine(...arvud) {
    if (arvud.length === 0) {
        return { summa: 0, keskmine: 0 };
    }

    const summa = arvud.reduce((kokku, arv) => kokku + arv, 0);
    const keskmine = summa / arvud.length;

    return { summa, keskmine };
}

function arvutaJaKuva() {
    const sisend = document.getElementById("numbridInput").value;
    const arvud = sisend.split(",").map(arv => parseInt(arv.trim(), 10)).filter(arv => !isNaN(arv));
    const tulemused = arvutaSummaJaKeskmine(...arvud);
    document.getElementById("tulemus").innerText = `Summa: ${tulemused.summa}, Keskmine: ${tulemused.keskmine.toFixed(2)}`;
}


const salajaneSõnum = sõnum => {
    return sõnum.replace(/[aeiouäöüõ]/g, '*');
};

function kuvaSalajaneSõnum() {
    const sõnum = document.getElementById("sõnumInput").value;
    const salajane = salajaneSõnum(sõnum);
    document.getElementById("tulemus").innerText = `Salajane sõnum: ${salajane}`;
}


const leiaUnikaalsedNimed = nimed => {
    const unikaalsed = [];
    nimed.forEach(nimi => {
        if (!unikaalsed.includes(nimi)) {
            unikaalsed.push(nimi);
        }
    });
    return unikaalsed;
};

function kuvaUnikaalsedNimed() {
    const sisend = document.getElementById("nimedInput").value;
    const nimed = sisend.split(",").map(nimi => nimi.trim());
    const unikaalsedNimed = leiaUnikaalsedNimed(nimed);

    document.getElementById("tulemus").innerText = `Unikaalsed nimed: ${unikaalsedNimed.join(", ")}`;
}
