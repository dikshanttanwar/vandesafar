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
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  const revealElements = document.querySelectorAll(".smooth-reveal");
  revealElements.forEach((el) => observer.observe(el));

  // AJAX Form Submission
  const form = document.querySelector(".modern-form");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const submitBtn = form.querySelector(".btn-submit");
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML =
        '<span>Sending...</span><i class="ri-loader-4-line ri-spin"></i>';
      submitBtn.disabled = true;

      const formData = new FormData(form);
      formData.append("ajax", "1");

      fetch("contact.php", {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest", // Helps server identify AJAX requests
        },
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          showToast(data.message, data.status);
          if (data.status === "success") {
            form.reset();
          }
        })
        .catch((error) => {
          console.error(error);
          showToast("Something went wrong. Please try again.", "error");
        })
        .finally(() => {
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
        });
    });
  }

  function showToast(message, type) {
    let toastContainer = document.getElementById("toast-container");
    if (!toastContainer) {
      toastContainer = document.createElement("div");
      toastContainer.id = "toast-container";
      toastContainer.className = "toast-container";
      document.body.appendChild(toastContainer);
    }

    const toast = document.createElement("div");
    toast.className = `toast toast-${type}`;

    const icon =
      type === "success" ? "ri-checkbox-circle-fill" : "ri-error-warning-fill";
    toast.innerHTML = `<i class="${icon}"></i> <span>${message}</span>`;

    toastContainer.appendChild(toast);

    // Trigger animation slide-in
    setTimeout(() => {
      toast.classList.add("show");
    }, 10);

    // Remove toast smoothly after 4 seconds
    setTimeout(() => {
      toast.classList.remove("show");
      setTimeout(() => toast.remove(), 300);
    }, 4000);
  }
});
