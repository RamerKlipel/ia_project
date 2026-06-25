export default function mountModalAlert(objModal) {
    const body = (objModal.body ?? '');
    const width = "width:"+(objModal.width ?? '80%');
    const title = (objModal.title ?? 'Atention!');
    const cancelBtnText = (objModal.cancelBtnText ?? 'No');
    const confirmBtnText = (objModal.confirmBtnText ?? 'Yes');
    const confirmBtn = (objModal.showConfirmBtn ?? true) ? `<button type="button" id="modalConfirmBtn" class="btn btn-primary">${confirmBtnText}</button>` : '';
    const cancelBtn = (objModal.showCancelBtn ?? true) ? `<button type="button" id="modalCancelBtn" class="btn btn-secondary" data-dismiss="modal">${cancelBtnText}</button>` : '';
    const onConfirm = (objModal.onConfirm ?? null);

    const existingMmodal = document.getElementById('divModalAlert')
    if (existingMmodal) {
      existingMmodal.remove();
    }

    let div = document.createElement('div');
    div.id = 'divModalAlert';
    div.innerHTML =`
    <div class="modal fade" id="modalAlert" tabindex="-1" role="dialog" aria-labelledby="modalAlertLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="box-modal-header">
                    <h3 class="modal-title" id="modalAlertLabel">${title}</h3>
                </div>
                <div class="box-modal-body"> ${body} </div>
                <div class="box-modal-footer" style="justify-content: center">
                    ${cancelBtn}
                    ${confirmBtn}
                </div>
            </div>
        </div>
    </div>`;
    document.body.appendChild(div);

    setBinds();

    const elmtModal = document.getElementById("modalAlert");
    const modal = new bootstrap.Modal(elmtModal);
    modal.show()

    function setBinds(){
        const elmtConfirmBtn = div.querySelector("#modalConfirmBtn");
        const elmtCancelBtn = div.querySelector('#modalCancelBtn');
        if (elmtCancelBtn) {
            elmtCancelBtn.addEventListener('click', () => modal.hide());
        }
        if (elmtConfirmBtn) {
            elmtConfirmBtn.addEventListener('click', () => {
                if (onConfirm) {
                  onConfirm();
                  modal.hide();
                };
                modal.hide();
            })
        }
    }
}
