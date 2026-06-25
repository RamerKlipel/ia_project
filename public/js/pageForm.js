import mountModalAlert from "../utilities/mountmodalalert.js";
import { helper } from "../utilities/helper.js";
new class pageForm {
    constructor() {
        this.elmtForm = document.querySelector('form');
        this.setBinds();
    }

    setBinds() {
        this.elmtForm?.addEventListener('submit', (e) => {
            e.preventDefault();
        });

        document.getElementById('btnSubmit')?.addEventListener('click', (e) => {
            e.preventDefault();
            if (!helper.verifyRequired(document)) {
                return false;
            }
            this.submit();
        });

        document.querySelectorAll('.btn-action[data-action]').forEach(elmt => {
            elmt.addEventListener('click', (e) => {
                const action = elmt.getAttribute('data-action');
                const href = elmt.getAttribute('href');
                if (action == "d") {
                    e.preventDefault();

                    mountModalAlert({
                        'width': '50%',
                        'title': 'Attention',
                        'body': 'you sure you want delete this?',
                        'icon': 'warning',
                        onConfirm:() => {
                            fetch(href, {
                                method: 'get'
                            })
                            .then(res => {
                                if (!res.ok) {
                                    throw new Error('server error ' + res.status);
                                }
                                window.location.reload();
                            })
                            .catch(res => {
                                mountModalAlert({
                                    'body': res,
                                    'icon': 'error',
                                    'showCancelBtn' : false,
                                    'confirmBtnText': 'Ok'
                                })
                            })
                        }
                    })
                }
            });
        });
    }

    submit() {
        const submitBtn = document.getElementById('btnSubmit');
        if (submitBtn) {
            submitBtn.disabled = true;
        }

        const formData = new FormData(this.elmtForm);
        const url = this.getActionForm();
        const completeUrl = this.getFetchUrl(url).trim('/').replace('/', '');

        fetch(completeUrl, {
            method: 'post',
            body: formData
        })
        .then(async res => {
            const text = await res.text();

            if (text.length > 0) {
                throw new Error('debug: ' + text);
            }

            if (res.ok == false) {
                throw new Error('server error: ' + res.statusText);
            }
            window.location.href = url+'?complete=true';
        })
        .catch(err => {
            mountModalAlert({
                'body': err.message,
                'showCancelBtn': false,
                'confirmBtnText': 'ok',
                onConfirm:() => {
                    submitBtn.disabled = false;
                }
            })
        })
    }

    getActionForm() {
        if (this.elmtForm) {
            return this.elmtForm.getAttribute('action');
        } else {
            return document.querySelector('form').getAttribute('action');
        }
    }

    getFetchUrl(url) {
        const action = this.elmtForm.dataset.action;
        const id = this.elmtForm.dataset.id;
        const completeUrl = `${url}@submit?action=${action}&id=${id}`;

        return completeUrl;
    }
}
