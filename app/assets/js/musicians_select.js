import Choices from 'choices.js';
import 'choices.js/public/assets/styles/choices.min.css';

export function initMusiciansSelect() {
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.querySelector('.musician-select');
        if (!select) return;

        new Choices(select, {
            removeItemButton: true,
            placeholderValue: 'Select musicians...',
            searchPlaceholderValue: 'Search musicians...',
            itemSelectText: '',
            callbackOnCreateTemplates: (template, classNames) => ({
                choice: (_, data) => {
                    const el = data.element;
                    const coverUrl = el?.dataset.cover || '/uploads/musicians/default.png';
                    const label = data.label;
                    return template(
                        `<div class="${classNames.item} ${classNames.itemChoice} ${classNames.itemSelectable} flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-yellow-600/20 hover:text-black transition-colors"
                            data-choice data-id="${data.id}" data-value="${data.value}" data-choice-selectable role="option" title="${label}">
                            <img src="${coverUrl}" alt="${label}" class="h-9 w-9 rounded-full object-cover border border-gray-700" />
                            <span class="text-yellow-500 ml-2 truncate flex-1 hover:text-black">${label}</span>
                        </div>`
                    );
                }
            })
        });
    });
};
