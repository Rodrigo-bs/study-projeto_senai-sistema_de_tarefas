class Mensagem {
    constructor() {
        this.mensagem = document.querySelector('[data-mensagem]');
        this.button = document.querySelector('[data-mensagem-button]')
    }

    removeElement() {
        this.mensagem.remove();
    }

    bindEvents() {
        this.removeElement = this.removeElement.bind(this);
    }

    addEvents() {
        if (this.button) {
            this.button.addEventListener('click', this.removeElement);
        }
    }

    init() {
        this.bindEvents();
        this.addEvents();
    }
}

const mensagem = new Mensagem();
mensagem.init();