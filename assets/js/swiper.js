import Swiper from 'swiper/bundle';
import 'swiper/swiper-bundle.css';

export function initSwiper() {
    new Swiper('.mySwiper', {
        slidesPerView: 2,
        spaceBetween: 20,
        breakpoints: {
            640: { slidesPerView: 3 },
            1024: { slidesPerView: 4 },
        },
    });
}