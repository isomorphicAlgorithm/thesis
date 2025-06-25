document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('review-carousel');
  const leftBtn = document.getElementById('scroll-left');
  const rightBtn = document.getElementById('scroll-right');

  if (!container || !leftBtn || !rightBtn) {
    console.warn('Review carousel elements not found.');
    return;
  }

  const updateArrowVisibility = () => {
    const scrollLeft = container.scrollLeft;
    const maxScrollLeft = container.scrollWidth - container.clientWidth;

    leftBtn.classList.toggle('hidden', scrollLeft <= 0);
    rightBtn.classList.toggle('hidden', scrollLeft >= maxScrollLeft - 1);
  };

  updateArrowVisibility();

  leftBtn.addEventListener('click', () => {
    container.scrollBy({ left: -300, behavior: 'smooth' });
  });

  rightBtn.addEventListener('click', () => {
    container.scrollBy({ left: 300, behavior: 'smooth' });
  });

  container.addEventListener('scroll', updateArrowVisibility);
  window.addEventListener('resize', updateArrowVisibility);
});