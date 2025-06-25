document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.getElementById('links-wrapper');
    if (!wrapper) return;

    let index = wrapper.querySelectorAll('input').length;
    const addButton = document.getElementById('add-link-btn');
    if (!addButton) return;

    addButton.addEventListener('click', () => {
        const prototype = wrapper.dataset.prototype;
        const newForm = prototype.replace(/__name__/g, index);

        const div = document.createElement('div');
        div.classList.add('flex', 'items-center', 'gap-2');

        div.innerHTML = `
            ${newForm}
            <button type="button" class="remove-link-btn shrink-0 rounded-xl text-white bg-red-600 px-2 py-1 hover:text-black custom-shadow cursor-pointer text-lg leading-none">
                &times;
            </button>
        `;

        const input = div.querySelector('input');
        input?.classList.add(
            'mt-1',
            'w-full',
            'bg-zinc-800/80',
            'text-yellow-50',
            'border',
            'border-gray-700',
            'rounded-lg',
            'px-4',
            'py-2',
            'focus:ring-yellow-500',
            'focus:border-yellow-500',
            'transition'
        );

        wrapper.appendChild(div);
        index++;
    });

    wrapper.addEventListener('click', (e) => {
        if (e.target.matches('.remove-link-btn')) {
            e.target.closest('div')?.remove();
        }
    });
});