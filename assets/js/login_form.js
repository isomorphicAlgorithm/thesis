export default function loginForm() {
    return {
        async submit(event) {
            event.preventDefault();

            try {
                const response = await fetch(event.target.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(event.target),
                });

                const content = await response.text();

                if (response.status === 422) {
                    // Form had validation errors
                    document.querySelector('[x-data]').innerHTML = content;
                } else if (response.ok) {
                    // Login successful — redirect to dashboard or reload
                    window.location.reload(); // or a custom redirect
                } else {
                    alert('An unexpected error occurred.');
                }

            } catch (e) {
                console.error('AJAX error:', e);
            }
        }
    };
}