let toode = {
    nimetus: "Arvuti",
    hind: 1200,
    kogus: 5,

    koguhind: function() {
        return this.hind * this.kogus;
    },

    muudabKogust: function(uusKogus) {
        this.kogus = uusKogus;
    },

    kuvatudSisu: function() {
        return `Toode: ${this.nimetus}, Hind: ${this.hind}€, Kogus: ${this.kogus}, Koguhind: ${this.koguhind()}€`;
    }
};

console.log(`Nimetus: ${toode.nimetus}`);
console.log(`Hind: ${toode.hind}€`);
console.log(`Kogus: ${toode.kogus}`);

console.log(`Toote koguhind: ${toode.koguhind()}€`);

toode.muudabKogust(10);

console.log(toode.kuvatudSisu());

document.getElementById("tooteInfo").innerHTML = `
    <p><strong>Nimetus:</strong> ${toode.nimetus}</p>
    <p><strong>Hind:</strong> ${toode.hind}€</p>
    <p><strong>Kogus:</strong> ${toode.kogus}</p>
    <p><strong>Koguhind:</strong> ${toode.koguhind()}€</p>
`;

const ostukorv = {
    tooted: [
        { nimi: 'Piim', hind: 3.60, kogus: 2 },
        { nimi: 'Leib', hind: 2.00, kogus: 1 },
        { nimi: 'Munad', hind: 1.50, kogus: 6 },
        { nimi: 'Juust', hind: 4.20, kogus: 1 },
        { nimi: 'Tomatid', hind: 2.30, kogus: 3 },
    ],

    kuvatudSisu: function() {
        this.tooted.forEach(toode => {
            console.log(`${toode.nimi} - ${toode.hind} EUR - Kogus: ${toode.kogus}`);
        });
    },

    lisaToode: function(nimi, hind, kogus) {
        this.tooted.push({ nimi, hind, kogus });
    },

    koguSumma: function() {
        return this.tooted.reduce((summa, toode) => summa + (toode.hind * toode.kogus), 0);
    }
};

console.log("\nOstukorvi tooted:");
ostukorv.kuvatudSisu();

ostukorv.lisaToode('Kohv', 5.80, 2);

console.log("\nUuendatud ostukorv:");
ostukorv.kuvatudSisu();

console.log('\nOstukorvi kogu summa:', ostukorv.koguSumma(), 'EUR');

document.getElementById("ostukorviSisu").innerHTML = `
    <h2>Ostukorvi tooted:</h2>
    <ul>
        ${ostukorv.tooted.map(toode => 
            `<li>${toode.nimi} - ${toode.hind} EUR - Kogus: ${toode.kogus}</li>`
        ).join('')}
    </ul>
    <h3>Kogu summa: ${ostukorv.koguSumma()} EUR</h3>
`;
