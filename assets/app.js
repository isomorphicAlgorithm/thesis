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

import "./js/filters";
import "./js/links";
import initSwiper from './js/swiper';

import {initFavoriteToggle} from "./js/favorite";

import { searchDropdown } from "./js/search_dropdown.js";

import { initGenresSelect } from './js/genres_select';

initGenresSelect();

import { initBandsSelect } from './js/bands_select';

initBandsSelect();

import { initMusiciansSelect } from './js/musicians_select';

initMusiciansSelect();

import { initAlbumsSelect } from './js/albums_select';

initAlbumsSelect();

import { initSongsSelect } from './js/songs_select';

initSongsSelect();

import { initSongAutocomplete } from './js/songs_autocomplete';

document.addEventListener('DOMContentLoaded', () => {
    initRatingSlider();
    initSwiper();
    initFavoriteToggle();

    const el = document.querySelector('#song-select');

    if (el && !el.dataset.select2Initialized) {
        initSongAutocomplete('#song-select', el.dataset.autocompleteUrl);
        el.dataset.select2Initialized = 'true';
    }
});

window.inlineEditor = inlineEditor;
window.Alpine = Alpine;
Alpine.data("themeSwitcher", themeSwitcher);
Alpine.data("loginForm", loginForm);
Alpine.data("searchDropdown", searchDropdown);

Alpine.start();