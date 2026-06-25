import { helper } from "../utilities/helper.js";

new class OllamaChat {
    constructor() {
        this.setBinds();
    }

    setBinds() {
        helper.setTrigger(document.getElementById('idSubmitMessage'), 'click', () => this.getAwnserPrompt());

        helper.setTrigger(document.querySelector('#idMessage'), 'keydown', (event) => { this.verifyKeydown(event) });
    }

    verifyKeydown(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            this.getAwnserPrompt();
        }
    }

    async getAwnserPrompt() {
        const elmtHistoryMessages = document.querySelector('#conversation');

        await this._setMessageUser(elmtHistoryMessages);
        this.askChatOllama(elmtHistoryMessages);

        return false;
    }

    askChatOllama(elmtHistoryMessages) {
        const url = helper.getUrlFecth('askChatOllama');
        const response = new EventSource(url);
        let elmtDomHistoryMessages = document.querySelector('.message.ai:last-child .message-ai');
        let resposta = "";

        response.onmessage = (e) => {
            let value = e.data;

            if (value == "[DATA]") {
                response.close();
                elmtDomHistoryMessages.innerHTML = marked.parse(resposta);
                this._scrollToSmoothBottom(elmtHistoryMessages);
                return;
            }

            resposta += JSON.parse(value);
            elmtDomHistoryMessages.innerHTML = marked.parse(resposta);

            this._scrollToSmoothBottom(elmtHistoryMessages);
        }

    }

    _addElmtMessageUser(prompt, elmt) {
        const elmtMessageUser = `
            <article class="message user">
                <div class="meta"><span class="dot"></span> <strong>Você</strong></div>
                <p>${prompt}</p>
            </article>
        `;
        elmt.innerHTML += elmtMessageUser;
        elmt.scrollTo({
            top: elmt.scrollHeight,
            behavior: 'smooth'
        });
    }

    _addElmtMessageAi(elmt) {
        const elmtMessageAi = `
            <article class="message ai">
                <div class="meta"><span class="dot"></span> <strong>Olla IA</strong></div>
                <div class="message-ai"></div>
            </article>
        `;

        elmt.innerHTML += elmtMessageAi;
    }

    _scrollToSmoothBottom(elmt) {
        elmt.scrollTo({
            top: elmt.scrollHeight,
            behavior: 'smooth'
        });
    }

    async _setMessageUser(elmtHistory) {

        const urlSetMessage = helper.getUrlFecth('setUserMessagePrompt');
        const prompt = document.getElementById("idMessage").value;

        if (!prompt) {
            return;
        }

        document.getElementById("idMessage").value = "";

        this._addElmtMessageUser(prompt, elmtHistory);
        this._addElmtMessageAi(elmtHistory);

        this._scrollToSmoothBottom(elmtHistory);

        await fetch(urlSetMessage, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 'prompt': prompt }),
        });

    }
}
