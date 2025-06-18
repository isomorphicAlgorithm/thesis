import "./styles/app.css";

import Alpine from "alpinejs";
import themeSwitcher from "./js/theme";
import loginForm from "./js/login_form";
import {inlineEditor} from "./components/inline-editor.js";
import "@fortawesome/fontawesome-free/css/all.min.css";

import $ from "jquery";
window.$ = $;
window.jQuery = $;

import "./js/review_progress";
import "./js/review_delete";
import "./js/reviews";

import reviewsComponent from "./js/reviews_component";

window.reviewsComponent = reviewsComponent;

import {initRatingSlider} from "./js/rating";

document.addEventListener("DOMContentLoaded", () => {
  initRatingSlider();
});

import "./js/filters";
import initSwiper from './js/swiper';

document.addEventListener('DOMContentLoaded', () => {
  initSwiper();
});

import {initFavoriteToggle} from "./js/favorite";

document.addEventListener("DOMContentLoaded", () => {
  initFavoriteToggle();
});

import { searchDropdown } from "./js/search_dropdown.js";

window.inlineEditor = inlineEditor;
window.Alpine = Alpine;
Alpine.data("themeSwitcher", themeSwitcher);
Alpine.data("loginForm", loginForm);
Alpine.data("searchDropdown", searchDropdown);

Alpine.start();