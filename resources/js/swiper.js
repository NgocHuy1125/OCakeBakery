document.addEventListener("DOMContentLoaded", function () {
  new Swiper(".category-swiper", {
    slidesPerView: 5,
    spaceBetween: 20,
    loop: true,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
      pauseOnMouseEnter: true
    },
    navigation: {
      nextEl: ".category-next",
      prevEl: ".category-prev",
    },
    breakpoints: {
      320: { slidesPerView: 2, spaceBetween: 10 },
      576: { slidesPerView: 3, spaceBetween: 15 },
      768: { slidesPerView: 4, spaceBetween: 18 },
      992: { slidesPerView: 5, spaceBetween: 20 },
    },
  });

  new Swiper(".review-swiper", {
    slidesPerView: 4,
    spaceBetween: 24,
    loop: true,
    centeredSlides: false,
    autoplay: {
      delay: 2800,
      disableOnInteraction: false,
      pauseOnMouseEnter: true
    },
    navigation: {
      nextEl: ".review-next",
      prevEl: ".review-prev",
    },
    breakpoints: {
      320: { slidesPerView: 1.2, spaceBetween: 10 },
      576: { slidesPerView: 2, spaceBetween: 15 },
      768: { slidesPerView: 3, spaceBetween: 18 },
      992: { slidesPerView: 4, spaceBetween: 24 },
    },
  });
});
