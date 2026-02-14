document.addEventListener("DOMContentLoaded", () => {
  const hamburger = document.getElementById("hamburger-icon");
  const navbar = document.querySelector(".navbar");

  if (hamburger) {
    hamburger.addEventListener("click", () => {
      // Toggle the 'active' class on both the menu and the icon
      navbar.classList.toggle("active");
      hamburger.classList.toggle("active");
    });
  }
});
