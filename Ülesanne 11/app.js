const nimed = [
    "mari maasikas", "jaan jõesaar", "kristiina kukk", "margus mustikas", 
    "jaak järve", "kadi kask", "Toomas Tamm", "Kadi Meri", "Leena Laas", 
    "Madis Mets", "Hannes Hõbe", "Anu Allikas", "Kristjan Käär", "Eva Esimene", 
    "Jüri Jõgi", "Liis Lepik", "Kalle Kask", "Tiina Teder", "Kaidi Koppel", "tiina Toom"
];

const vormindatudNimed = nimed.map(nimi => {
    const [eesnimi, perenimi] = nimi.split(' ');
    const email = `${perenimi.toLowerCase()}@metshein.com`;
    return {
        nimi: eesnimi.charAt(0).toUpperCase() + eesnimi.slice(1) + ' ' + perenimi.charAt(0).toUpperCase() + perenimi.slice(1),
        email: email
    };
});

document.getElementById('nimed').innerHTML = vormindatudNimed.map(person => 
    `<p>${person.nimi} - ${person.email}</p>`
).join('');

function otsiNimi(nimi, nimed) {
    const leidub = nimed.find(person => person.nimi.toLowerCase() === nimi.toLowerCase());
    return leidub ? `Täisnimi: ${leidub.nimi}, E-mail: ${leidub.email}` : "Nimi ei leitud.";
}

function otsiNimiSisestatud() {
    const otsitavNimi = document.getElementById("nimiOtsing").value;
    const tulemus = otsiNimi(otsitavNimi, vormindatudNimed);
    document.getElementById("otsinguTulemus").innerText = tulemus;
}

const inimesteAndmed = [
    { nimi: "Mari Maasikas", isikukood: "38705123568" },
    { nimi: "Jaan Jõesaar", isikukood: "49811234567" },
    { nimi: "Kristiina Kukk", isikukood: "39203029876" },
    { nimi: "Margus Mustikas", isikukood: "49807010346" },
    { nimi: "Jaak Järve", isikukood: "39504234985" },
    { nimi: "Kadi Kask", isikukood: "39811136789" },
];

function leiaSyndiaegJaVanus(isikukood) {
    const sünnikuupäev = isikukood.slice(5, 7) + '.' + isikukood.slice(3, 5) + '.' + (isikukood.slice(0, 1) === '3' || isikukood.slice(0, 1) === '4' ? '19' : '20') + isikukood.slice(1, 3);
    const sünniaasta = (isikukood.slice(0, 1) === '3' || isikukood.slice(0, 1) === '4') ? '19' + isikukood.slice(1, 3) : '20' + isikukood.slice(1, 3);
    const tänaneKuupäev = new Date();
    const vanus = tänaneKuupäev.getFullYear() - parseInt(sünniaasta);
    return { sünnikuupäev, vanus };
}

document.getElementById('inimesteAndmed').innerHTML = inimesteAndmed.map(inimene => {
    const andmed = leiaSyndiaegJaVanus(inimene.isikukood);
    return `<p>${inimene.nimi} sünnikuupäev: ${andmed.sünnikuupäev}, vanus: ${andmed.vanus} aastat.</p>`;
}).join('');

const opilased = [
    { nimi: "Anna", tulemused: [4.5, 4.8, 4.6] },
    { nimi: "Mart", tulemused: [5.2, 5.1, 5.4] },
    { nimi: "Kati", tulemused: [4.9, 5.0, 4.7] },
    { nimi: "Jaan", tulemused: [4.3, 4.6, 4.4] },
    { nimi: "Liis", tulemused: [5.0, 5.2, 5.1] },
    { nimi: "Peeter", tulemused: [5.5, 5.3, 5.4] },
    { nimi: "Eva", tulemused: [4.8, 4.9, 4.7] },
    { nimi: "Marten", tulemused: [4.7, 4.6, 4.8] },
    { nimi: "Kairi", tulemused: [5.1, 5.3, 5.0] },
    { nimi: "Rasmus", tulemused: [4.4, 4.5, 4.3] },
];

document.getElementById('kaugushupe').innerHTML = opilased.map(opilane => {
    const parimTulemus = Math.max(...opilane.tulemused);
    const keskmineTulemus = (opilane.tulemused.reduce((summa, tulemus) => summa + tulemus, 0) / opilane.tulemused.length).toFixed(2);
    return `<p>${opilane.nimi} - Parim tulemus: ${parimTulemus}m, Keskmine tulemus: ${keskmineTulemus}m</p>`;
}).join('');
