export function initRatingSlider() {
    const container = document.getElementById('rating-slider');
    if (!container) return;

    const arc = container.querySelector('#arc');
    const knob = container.querySelector('#knob');
    const ratingValue = container.querySelector('#rating-value');

    const radius = 45;
    const cx = 50;
    const cy = 50;

    let dragging = false;

    let rating = parseInt(container.dataset.initialRating || "50");

    const polarToCartesian = (cx, cy, r, angle) => ({
        x: cx + r * Math.cos(angle),
        y: cy + r * Math.sin(angle)
    });

    const describeArc = (x, y, radius, startAngle, endAngle) => {
        const start = polarToCartesian(x, y, radius, endAngle);
        const end = polarToCartesian(x, y, radius, startAngle);
        const largeArcFlag = (endAngle - startAngle) <= Math.PI ? "0" : "1";

        return [
        "M", start.x, start.y,
        "A", radius, radius, 0, largeArcFlag, 0, end.x, end.y
        ].join(" ");
    };

    const updateSlider = (value) => {
        rating = Math.min(Math.max(value, 1), 100);
        ratingValue.textContent = rating;

        // Update hidden input value so backend gets it on form submit:
        document.getElementById('ratingInput').value = rating;

        const startAngle = -Math.PI / 2;
        const angle = startAngle + (rating / 100) * 2 * Math.PI;

        arc.setAttribute('d', describeArc(cx, cy, radius, startAngle, angle));
        const knobPos = polarToCartesian(cx, cy, radius, angle);
        knob.setAttribute('cx', knobPos.x);
        knob.setAttribute('cy', knobPos.y);

        // Background color logic
        if (rating >= 75) {
            container.style.background = '#4ade80';
        } else if (rating >= 50) {
            container.style.background = '#facc15';
        } else {
            container.style.background = '#f87171';
        }
    };

    function getRatingFromPosition(clientX, clientY) {
        const rect = container.getBoundingClientRect();
        const cx = rect.width / 2;
        const cy = rect.height / 2;
        const x = clientX - rect.left - cx;
        const y = clientY - rect.top - cy;

        let angle = Math.atan2(y, x) + Math.PI / 2;
        if (angle < 0) angle += 2 * Math.PI;

        let newRating = Math.round((angle / (2 * Math.PI)) * 100);
        return newRating === 0 ? 1 : newRating;
    }

    const onPointerMove = (event) => {
        if (!dragging) return;
        updateSlider(getRatingFromPosition(event.clientX, event.clientY));
    };

    container.addEventListener('click', (e) => {
        updateSlider(getRatingFromPosition(e.clientX, e.clientY));
    });

    const onPointerUp = () => {
        if (dragging) {
        dragging = false;
        knob.style.cursor = 'grab';
        }
    };

    knob.addEventListener('pointerdown', (e) => {
        dragging = true;
        knob.setPointerCapture(e.pointerId);
    });

    knob.addEventListener('pointermove', onPointerMove);
    knob.addEventListener('pointerup', onPointerUp);
    knob.addEventListener('pointercancel', onPointerUp);
    knob.addEventListener('pointerleave', onPointerUp);

    updateSlider(rating);
}