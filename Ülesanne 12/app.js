const ostukorv = {
    tooted: [
        { nimi: 'Piim', hind: 3.60, kogus: 2 },
        { nimi: 'Leib', hind: 2.00, kogus: 1 },
        { nimi: 'Munad', hind: 1.50, kogus: 6 },
        { nimi: 'Juust', hind: 4.20, kogus: 1 },
        { nimi: 'Tomatid', hind: 2.30, kogus: 3 }
    ],

    kuvaTooted: function() {
        const loend = document.getElementById("ostukorv");
        loend.innerHTML = '';

        this.tooted.forEach(toode => {
            const listItem = document.createElement('li');
            listItem.textContent = `${toode.nimi} - ${toode.hind} EUR - Kogus: ${toode.kogus}`;
            loend.appendChild(listItem);
        });
    },

    koguSumma: function() {
        let summa = 0;
        this.tooted.forEach(toode => {
            summa += toode.hind * toode.kogus;
        });
        return summa.toFixed(2);
    }
};

ostukorv.kuvaTooted();

document.getElementById('kogusumma').textContent = `Ostukorvi kogusumma: ${ostukorv.koguSumma()} EUR`;
