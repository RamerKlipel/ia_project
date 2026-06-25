export class maskhelper {
    constructor() {
        this.setBinds();
    }

    setBinds() {
        document.querySelectorAll('[data-mask]')?.forEach(elmt => this.handleMaskType(elmt));
    }

    handleMaskType(elmt) {
        const maskType = elmt.dataset.mask;
        switch (maskType) {
            case 'coin-decimal-152':
                elmt.addEventListener('input', (e) => {
                    let val = elmt.value.match(/\d+/g)?.join('');

                    if (!val) {
                        return;
                    }

                    const vlDecimal = val.length > 0 ? val.slice(-2).slice(0,2) : '00';
                    const value = val.length > 3 ? BigInt(val.slice(0, -2).slice(0,15)).toLocaleString('pt-BR') : '0';

                    elmt.value = value+","+vlDecimal;
                })
                break;
            case 'BRdate':
                elmt.addEventListener("input", (e) => {
                    const val = elmt.value.match(/\d+/g)?.join('');

                    if (!val) {
                        return;
                    }

                    let date = "";
                    for (let i = 0; i < 8; i++) {
                        if (val[i] != undefined) {
                            if ([2, 4].includes(i)) {
                                date += "/";
                            }
                            date += val[i];
                        }
                    }
                    elmt.value = date;
                })
                break;
            case 'BRdatehour':
                elmt.addEventListener("input", (e) => {
                    const val = elmt.value.match(/\d+/g)?.join('');

                    if (!val) {
                        return;
                    }

                    let date = "";
                    let vali = '';

                    for (let i = 0; i < 14; i++) {
                        if (val[i] == undefined) {
                            break;
                        }

                        if ([2, 4].includes(i)) {
                            date += "/";
                        }

                        if ([8].includes(i)) {
                            date += " ";
                        }

                        if ([10, 12].includes(i)) {
                            date += ":";
                        }

                        vali = this.handleDataHourActualValue(i, val);

                        date += vali;
                    }

                    elmt.value = date;
                })
                break;

        }
    }

    handleDataHourActualValue(idx, val) {
        let vali = val[idx];
        switch(idx) {
            case 0:
                if (vali > 3) {
                    vali = 3;
                }
                break;
            case 1:
                if ((val[idx-1]+val[idx]) > 31) {
                    vali = 1;
                }
                break;
            case 2:
                if (vali > 1) {
                    vali = 1;
                }
                break;
            case 3:
                if ((val[idx-1]+val[idx]) > 12) {
                    vali = 2;
                }
                break;
            case 4:
                if (vali < 1) {
                    vali = 1;
                }
                break;
            case 5:
                if (val[idx-1] == 1 && val[idx] < 9) {
                    vali = 9;
                }
                break;
            case 8:
                if (vali > 2) {
                    vali = 2;
                }
                break;
            case 9:
                if (val[idx-1] == 2 && val[idx] > 3) {
                    vali = 3;
                }
                break;
            case 10:
                if (vali > 5) {
                    vali = 5;
                }
                break;
            case 12:
                if (vali > 5) {
                    vali = 5;
                }
                break;
        }
        return vali;
    }
}

new maskhelper();
