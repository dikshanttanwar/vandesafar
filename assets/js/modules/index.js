import "../utils/header.js";
import "../utils/footer.js";

document.addEventListener("DOMContentLoaded", () => {
  // Scroll Reveal Animation Initialization
  const observerOptions = {
    root: null,
    rootMargin: "0px",
    threshold: 0.15,
  };

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        // Stop observing once revealed
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  const revealElements = document.querySelectorAll(".smooth-reveal");
  revealElements.forEach((el) => observer.observe(el));
});
