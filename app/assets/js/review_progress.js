document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.review-form');
    const reviewTextarea = document.getElementById('review');
    const counter = document.getElementById('review-counter');
    const progressBar = document.getElementById('review-progress');

    console.log({ form, reviewTextarea, counter, progressBar });

    if (form && reviewTextarea && counter && progressBar) {
        console.log('Binding review input listener...');

        // Submit validation
        form.addEventListener('submit', function (e) {
            const reviewText = reviewTextarea.value.trim();
            if (!reviewText || reviewText.length < 600) {
            alert('Your review must be at least 600 characters long.');
            e.preventDefault();
            }
        });

        // Live counter + progress
        reviewTextarea.addEventListener('input', function () {
            const len = reviewTextarea.value.length;
            const percentage = Math.min((len / 600) * 100, 100);

            counter.textContent = `${len}/600 characters`;
            counter.style.color = len < 600 ? 'red' : 'green';

            progressBar.style.width = `${percentage}%`;
            progressBar.style.backgroundColor = len < 600 ? '#f59e0b' : '#10b981'; // yellow or green
        });

        // Initialize on load
        reviewTextarea.dispatchEvent(new Event('input'));
    } else {
        console.warn('Review form elements not found.');
    }
});

