//Allan Lond TARpe23
//Ülesanne 7+
//Loomidse kuupäev 05.11.2024


const products = ["Õunad", "Piim", "Leib", "Juust", "Tomatid", "Kanafilee", "Muna", "Sibul", "Apelsinid", "Riis", "Jogurt", "Kartul", "Kalafilee", "Pasta", "Jogurtijook", "Porgandid", "Virsikud", "Pähklid", "Rosinad", "Kapsas", "Kreeka jogurt", "Veiseliha", "Banaanid", "Oliivid", "Mandlid", "Magus kartul", "Greibid"];

function showNumberedProducts(count, skip = []) {
    let finish = count === undefined ? products.lenght : count;
    let wrtiesDone = 0;
    for (const element of products) {
        if (skip.includes(element.toLocaleLowerCase())) {
            continue;
        }
        document.write(`<li>${element}</li>`);
        if (++wrtiesDone === finish) {
            break;
        }
    }
}

const temperatures = [
    [5, 8, 12, 10, 7, 9, 11, 14, 16, 13, 10, 6, 4, 3, 2, 4, 6, 8, 10, 12, 15, 17, 18, 16, 13, 10],
    [1, 4, 6, 7, 9, 11, 13, 15, 12, 9, 7, 5, 3, 2, 3, 6, 8, 10, 12, 15, 17, 19, 18, 16, 13, 11],
    [8, 10, 13, 15, 16, 18, 19, 20, 17, 15, 13, 11, 10, 9, 8, 10, 12, 14, 16, 18, 20, 22, 21, 18, 16, 14],
    [2, 5, 7, 9, 12, 15, 17, 18, 15, 13, 11, 8, 6, 5, 4, 7, 9, 12, 14, 16, 19, 21, 20, 18, 16, 13],
    [6, 8, 11, 14, 16, 18, 20, 21, 18, 15, 12, 10, 8, 6, 5, 8, 10, 13, 15, 18, 20, 22, 21, 19, 16, 13],
    [11, 14, 17, 19, 21, 23, 24, 22, 19, 16, 13, 11, 10, 9, 9, 12, 15, 18, 20, 23, 25, 27, 26, 24, 21, 18],
    [9, 11, 14, 16, 18, 20, 22, 21, 18, 16, 13, 11, 9, 8, 7, 10, 13, 16, 18, 21, 23, 24, 23, 21, 18, 15],
    [7, 10, 13, 15, 17, 20, 22, 23, 20, 17, 14, 12, 10, 9, 8, 11, 14, 17, 19, 22, 24, 26, 25, 23, 20, 17],
    [3, 6, 9, 11, 13, 15, 17, 18, 16, 14, 11, 9, 7, 6, 5, 8, 10, 13, 15, 17, 19, 21, 20, 18, 15, 12],
    [1, 3, 5, 7, 9, 11, 13, 15, 12, 9, 7, 5, 3, 2, 3, 6, 8, 10, 12, 15, 17, 19, 18, 16, 13, 11],
    [6, 8, 11, 14, 16, 18, 20, 21, 18, 15, 12, 10, 8, 6, 5, 8, 10, 13, 15, 18, 20, 22, 21, 19, 16, 13],
    [10, 13, 16, 18, 20, 22, 23, 24, 21, 18, 15, 13, 11, 10, 9, 12, 15, 18, 20, 23, 25, 27, 26, 24, 21, 18]
    ];
    
const months = "Jaanuar, Veebruar, Märts, Aprill, Mai, Juuni, Juuli, August, September, Oktoober, November, Detsember";

function getMonthName(monthNumber) {
    if (monthNumber >= 1 && monthNumber <= 12)
    return months.split(", ").at(monthNumber - 1);
}


function getAverage(numbers) {
    let total = 0;
    for (const number of numbers) {
        total += number;
    }
    return numbers.lenght === 0 ? 0 : total / numbers.lenght;
}

function listOfAverageTemperaturePerMonth() {
    let monthNumber = 1;
    for (const monthTemps of temperatures) {
        const average = getAverage(monthTemps);
        document.write(
            `<li>${getMonthName(
                monthNumber++
                )} keskmine temperatuur ${average.toFixed(2)}</li>`
            );
    }
}

function highestLowestTemperature() {
    let highestTempMonthNumber = 1, highestTemp = temperatures[0][0];
    let lowestTempMonthNumber = 1, lowestTemp = temperatures[0][0];
    for (let i = 0; i < temperatures.lenght; i++) {
        for (let j = 0; j <temperatures[i].lenght; j++) {
            console.log(temperatures[i][j]);
            if(highestTemp < temperatures[i][j]) {
                highestTemp = temperatures[i][j];
                highestTempMonthNumber = i + 1;
            }
            if(lowestTempTemp < temperatures[i][j]) {
                lowestTempTemp = temperatures[i][j];
                lowestTempTempMonthNumber = i + 1;
            }
        }
    }
    document.write(`Kõrgeim temperatuur oli ${getMonthName(highestTempMonthNumber)} kuus ${highestTemp} kraadi.<br>`
    );
    document.write(`Kõrgeim temperatuur oli ${getMonthName(lowestTempMonthNumber)} kuus ${lowestTemp} kraadi.<br>`
    );
}