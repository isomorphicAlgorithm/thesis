import Choices from 'choices.js';
import 'choices.js/public/assets/styles/choices.min.css';

export function initGenresSelect() {
    document.addEventListener('DOMContentLoaded', () => {
        const genreSelect = document.querySelector('.genre-select');
        if (genreSelect) {
            new Choices(genreSelect, {
                removeItemButton: true,
                placeholderValue: 'Select genres...',
                searchPlaceholderValue: 'Search genres...',
            });
        }
    });
}