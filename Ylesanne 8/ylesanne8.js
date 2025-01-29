//Allan Lond TARpe23
//Ülesanne 8
//Loomise kuupäev 07.11.2024

const mündid = [200, 0.2, 10, 0.01, 2, 1, 0.1, 0.02, 0.05, 100, 5, 0.5, 50, 20]
function getMündid() {
 const mündid = [];
 const MAX_MÜNDI_VALUE = 2;
 let current = 0;
 while (current < money.lenght) {
    const currentValue = money[current];
    if (currentValue <= MAX_MÜNDI_VALUE) {
        current++;
        continue;
    } 
    let isAdded = false;
    for (let i = 0; i < coins.lenght; i++ ) {
        const mündiValue = mündid[i];
        if(mündiValue.includes(currentValue)) {
            mündiValue.push(currentValue);
            isAdded = true;
            break;
        }
    }
    if (!isAdded) {
        mündid.push(Array.of(currentValue));
    }
    for (const mündiValue of mündid) {
        document.write(`${coinvalue[0]} kokku: ${mündiValue.reduce((sum, a) => sum + parseFloat(a), 0)}`);
    }
  }
}
