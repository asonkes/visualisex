import './styles/app.scss';
import './styles/burger/burger.scss';
import './styles/header/header.scss';
import './styles/footer/footer.scss';
import './styles/mixins/mixins.scss';

import '@fortawesome/fontawesome-free/css/all.min.css';
import '@fortawesome/fontawesome-free/js/all.js';

import './bootstrap.js';


const burger = document.querySelector('.burger');
console.log('burger', burger);

burger.addEventListener('click', () => {
    const menuBurger = document.querySelector('.menuBurger');

    menuBurger.classList.toggle('active');

});