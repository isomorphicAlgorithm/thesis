import './styles/app.css';

import Alpine from 'alpinejs';
import themeSwitcher from './js/theme';
import loginForm from './js/login_form';
import { inlineEditor } from './components/inline-editor.js';
import '@fortawesome/fontawesome-free/css/all.min.css';

import $ from 'jquery';
window.$ = $;
window.jQuery = $;

import { initRatingSlider } from './js/rating';

document.addEventListener('DOMContentLoaded', () => {
    initRatingSlider();
});

import './js/filters';
import { initSwiper } from './js/swiper';

document.addEventListener('DOMContentLoaded', () => {
    initSwiper();
});

window.inlineEditor = inlineEditor;
window.Alpine = Alpine;
Alpine.data('themeSwitcher', themeSwitcher);
Alpine.data('loginForm', loginForm);

Alpine.start();