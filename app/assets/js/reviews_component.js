export default function reviewsComponent() {
  let reviews = [];

  try {
    const jsonEl = document.getElementById("reviews-json");
    if (jsonEl) {
      reviews = JSON.parse(jsonEl.textContent);
    } else {
      console.warn("Missing #reviews-json script tag.");
    }
  } catch (err) {
    console.error("Failed to parse reviews JSON:", err);
  }

  return {
    reviews,
    openModal(index) {
      const review = this.reviews[index];
      window.dispatchEvent(new CustomEvent("open-modal", {
        detail: {
          content: review.review,
          title: "Review by " + (review.user.username ?? "Unknown")
        }
      }));
    }
  };
}
