class Navbar {
    constructor() {
        this.navbar = document.querySelector('[data-menu]');
        this.buttonNavbar = document.querySelector('[data-menubtn]');
    }

    toggleClassActive() {
        this.navbar.classList.toggle('active');
    }

    bindEvents() {
        this.toggleClassActive = this.toggleClassActive.bind(this);
    }

    addEvents() {
        this.buttonNavbar.addEventListener('click', this.toggleClassActive);
    }

    init() {
        this.bindEvents();
        this.addEvents();
    }
}

const navbar = new Navbar();
navbar.init();