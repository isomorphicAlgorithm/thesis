document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.delete-review-btn');

    deleteButtons.forEach(deleteBtn => {
        deleteBtn.addEventListener('click', () => {
            if (!confirm('Are you sure you want to delete your review?')) {
                return;
            }

            const albumId = deleteBtn.dataset.albumId;
            const ratingId = deleteBtn.dataset.ratingId;
            const csrfToken = deleteBtn.dataset.csrfToken;
            const redirectUrl = deleteBtn.dataset.redirectUrl;

            const body = new URLSearchParams();
            body.append('_token', csrfToken);
            body.append('redirectUrl', redirectUrl);

            fetch(`/albums/${albumId}/rating/${ratingId}/delete-review`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: body.toString()
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirectUrl;
                } else {
                    alert('Failed to delete review.');
                }
            })
            .catch(() => alert('Failed to delete review due to network error.'));
        });
    });
});