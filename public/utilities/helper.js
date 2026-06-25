import mountModalAlert from "../utilities/mountmodalalert.js";
export class helper {
    static toggleAnimation(elmt, fl) {
        elmt?.classList?.toggle('show', fl);
        elmt?.classList?.toggle('hide', !fl);
    }

    static verifyRequired(elmt) {
        const obj = [...document.querySelectorAll('[required]')].filter((elmt) => {
            return (!(elmt.parentElement.classList.contains("hide")) && (elmt.value == "" || elmt.value == "0,0"  || elmt.value == "0,00" || elmt.value == "0"));
        })

        if (obj.length > 0) {
            mountModalAlert({
                'body': 'All fields must be completed to continue.',
                'showCancelBtn': false,
                'confirmBtnText': 'ok',
                onConfirm:() => {
                    [...document.querySelectorAll('[required]')].forEach( elmt => elmt.classList.remove('required'));
                    obj.forEach(elmt => elmt.classList.add('required'))
                    document.getElementById("btnSubmit").removeAttribute('disabled')
                }
            });
            return false;
        }
        return true;
    }

    static setTrigger(elmt, type, fn) {
        elmt?.addEventListener(type, (e) => fn(e));
    }

    static getActionForm() {
        const elmtForm = document.querySelector('form');
        const action = elmtForm?.getAttribute('action');
        return action;
    }

    static getUrlFecth(method) {
        return window.location.href + "@" + method;
    }
}
