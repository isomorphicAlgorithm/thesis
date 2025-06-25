import $ from 'jquery';
import 'select2';

export function initSongAutocomplete(selector = '#song-select', url) {
    const $el = $(selector);

    if (!$el.length) return;

    if (!url) {
        console.error('Autocomplete URL missing');
        return;
    }

    $el.select2({
        ajax: {
            url: url,
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data.results }),
            cache: true
        },
        placeholder: 'Select songs...',
        minimumInputLength: 2,
        width: '100%',
        dropdownParent: $(document.body),
        dropdownCssClass: 'w-full',

        templateResult: (song) => {
            if (!song.id) return song.text;

            const container = document.createElement('div');
            container.className = 'flex items-center gap-3 px-3 py-2 cursor-pointer rounded transition';

            const svgNS = 'http://www.w3.org/2000/svg';
            const svg = document.createElementNS(svgNS, 'svg');
            svg.setAttribute('class', 'w-5 h-5 text-yellow-300 flex-shrink-0');
            svg.setAttribute('fill', 'currentColor');
            svg.setAttribute('viewBox', '0 0 20 20');

            const path = document.createElementNS(svgNS, 'path');
            path.setAttribute('d', 'M9 4v12l10-6-10-6z');
            svg.appendChild(path);

            const text = document.createElement('span');
            text.className = 'text-yellow-200 font-medium text-sm';
            text.textContent = song.text;

            container.appendChild(svg);
            container.appendChild(text);

            return container;
        },

        templateSelection: (song) => {
            if (!song.id) return song.text;

            const span = document.createElement('span');
            span.className = 'text-yellow-400 font-medium text-sm';
            span.textContent = song.text;
            return span;
        },

        escapeMarkup: m => m,
    });
}