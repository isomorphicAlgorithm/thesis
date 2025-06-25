export function initFavoriteToggle() {
    const buttons = document.querySelectorAll(".favorite-btn");

    buttons.forEach(btn => {
        const songId = btn.dataset.songId;
        const csrfToken = btn.dataset.csrfToken;

        const icons = btn.querySelectorAll(".heart-icon");
        const [filledIcon, outlineIcon] = icons;

        const favoriteText = btn.closest("div").querySelector(".favorite-text");

        function updateFavoriteUI(isFavorite) {
            btn.setAttribute("aria-pressed", isFavorite ? "true" : "false");
            btn.title = isFavorite ? "Remove from favorites" : "Add to favorites";

            if (isFavorite) {
                filledIcon.classList.remove("hidden");
                outlineIcon.classList.add("hidden");
            } else {
                filledIcon.classList.add("hidden");
                outlineIcon.classList.remove("hidden");
            }

            if (favoriteText) {
                favoriteText.textContent = isFavorite ? "Favorited" : "Add to Favorites";
            }
        }

        btn.addEventListener("click", async () => {
            try {
                const formData = new FormData();
                formData.append('_csrf_token', csrfToken);

                const response = await fetch(`/songs/song/${songId}/favorite-toggle`, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                if (!response.ok) throw new Error("Network response was not ok");

                const data = await response.json();
                if (typeof data.isFavorite !== "boolean") {
                    throw new Error("Unexpected response from server");
                }

                updateFavoriteUI(data.isFavorite);
            } catch (err) {
                alert("Something went wrong. Please try again.");
                console.error(err);
            }
        });
    });
}