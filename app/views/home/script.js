const carouselContainer = document.querySelector(".carousel-container");
const carouselItems = document.querySelectorAll(".carousel-item");
const maxScrollingWidth =
  carouselContainer.scrollWidth - carouselContainer.clientWidth;
const carouselButtons = document.querySelectorAll(".carousel-btn");

carouselButtons.forEach((btn) => {
  btn.addEventListener("click", function (e) {
    const direction = this.classList.contains("carousel-back-btn") ? -1 : 1;
    const scrollingAmount = carouselContainer.clientWidth * direction;
    carouselContainer.scrollBy({ left: scrollingAmount, behavior: "smooth" });
  });
});
