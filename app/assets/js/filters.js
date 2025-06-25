document.addEventListener("DOMContentLoaded", function () {
    const typeSelect = document.getElementById('type');
    const bandSelect = document.getElementById('band_id');

    if (typeSelect && bandSelect) {
        function updateBandFilterState() {
            if (typeSelect.value === 'solo') {
                bandSelect.disabled = true;
                bandSelect.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                bandSelect.disabled = false;
                bandSelect.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        updateBandFilterState(); // Set initial state

        typeSelect.addEventListener('change', updateBandFilterState);
    }
});