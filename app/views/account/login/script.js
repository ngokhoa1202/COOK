
/**
 * 
 * @param {HTMLElement} radioContainer 
 */
function toggleLoginAndSignupRadios(radioContainer) {
  let accordionClassName = radioContainer.id + "-accordion";
  let selectedAccordion = document.querySelector("." + accordionClassName);
  if (selectedAccordion.classList.contains("hidden")) {
    let activeAccordion = document.querySelector(".active");
    activeAccordion.classList.remove("active");
    activeAccordion.classList.add("hidden");
    selectedAccordion.classList.remove("hidden");
    selectedAccordion.classList.add("active");
  }
}
let radioContainerList = document.querySelectorAll(".radio-container");
radioContainerList.forEach((radioContainer) => radioContainer.addEventListener("click", function (e) {
  toggleLoginAndSignupRadios(this);
}));


