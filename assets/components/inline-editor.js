export function inlineEditor(entityType, entityId, field, initialValue, url, csrfToken) {
    return {
        editing: false,
        value: initialValue,
        startEditing() {
            this.editing = true;
        },
        save() {
            /*if (this.value.trim() === '') {
                alert('Bio cannot be empty.');
                return;
            }*/

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ entityType, entityId, field, value: this.value }),
            }).then(response => {
                if (response.ok) {
                    this.editing = false;
                } else {
                    alert('Error saving changes.');
                }
            });
        }
    };
}