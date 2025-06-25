// assets/js/swiper.js
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

export default function initSwiper() {
  new Swiper('.mySwiper', {
    //slidesPerView: '4',
    slidesPerView: 'auto',
    spaceBetween: 2,
    /*breakpoints: {
      640:  { slidesPerView: 1, spaceBetween: 8 },
      768:  { slidesPerView: 2, spaceBetween: 12 },
      1024: { slidesPerView: 'auto', spaceBetween: 16 },
      1280: { slidesPerView: 'auto', spaceBetween: 16 },
    },*/
  });
}
