// Wait for the DOM to be fully loaded
document.addEventListener("DOMContentLoaded", function() {
  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Initialize popovers
  var popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
  );
  var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener("click", function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start"
        });
      }
    });
  });

  // Add animation classes on scroll
  const animateOnScroll = function() {
    const elements = document.querySelectorAll(".animate-on-scroll");
    elements.forEach(element => {
      const elementTop = element.getBoundingClientRect().top;
      const elementBottom = element.getBoundingClientRect().bottom;
      const isVisible = elementTop < window.innerHeight && elementBottom >= 0;

      if (isVisible) {
        element.classList.add("animate__animated", "animate__fadeInUp");
      }
    });
  };

  // Run animation check on load and scroll
  window.addEventListener("load", animateOnScroll);
  window.addEventListener("scroll", animateOnScroll);

  // Handle form submissions with AJAX
  document.querySelectorAll('form[data-ajax="true"]').forEach(form => {
    form.addEventListener("submit", function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const submitButton = this.querySelector('button[type="submit"]');
      const originalText = submitButton.innerHTML;

      // Show loading state
      submitButton.disabled = true;
      submitButton.innerHTML =
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Chargement...';

      fetch(this.action, {
        method: this.method,
        body: formData,
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content")
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Show success message
            const alert = document.createElement("div");
            alert.className = "alert alert-success alert-dismissible fade show";
            alert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
            this.insertAdjacentElement("beforebegin", alert);

            // Reset form if needed
            if (data.reset) {
              this.reset();
            }

            // Redirect if needed
            if (data.redirect) {
              window.location.href = data.redirect;
            }
          } else {
            // Show error message
            const alert = document.createElement("div");
            alert.className = "alert alert-danger alert-dismissible fade show";
            alert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
            this.insertAdjacentElement("beforebegin", alert);
          }
        })
        .catch(error => {
          console.error("Error:", error);
          const alert = document.createElement("div");
          alert.className = "alert alert-danger alert-dismissible fade show";
          alert.innerHTML = `
                    Une erreur est survenue. Veuillez réessayer.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
          this.insertAdjacentElement("beforebegin", alert);
        })
        .finally(() => {
          // Reset button state
          submitButton.disabled = false;
          submitButton.innerHTML = originalText;
        });
    });
  });

  // Handle image preview for file inputs
  document
    .querySelectorAll('input[type="file"][data-preview]')
    .forEach(input => {
      input.addEventListener("change", function() {
        const preview = document.querySelector(this.dataset.preview);
        if (preview && this.files && this.files[0]) {
          const reader = new FileReader();
          reader.onload = function(e) {
            preview.src = e.target.result;
          };
          reader.readAsDataURL(this.files[0]);
        }
      });
    });

  // Handle quantity changes in cart
  document.querySelectorAll(".quantity-input").forEach(input => {
    input.addEventListener("change", function() {
      const form = this.closest("form");
      if (form) {
        form.submit();
      }
    });
  });

  // Handle favorite toggles
  document.querySelectorAll(".favorite-toggle").forEach(button => {
    button.addEventListener("click", function(e) {
      e.preventDefault();
      const icon = this.querySelector("i");
      const isFavorite = icon.classList.contains("fas");

      fetch(this.href, {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content")
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            icon.classList.toggle("fas");
            icon.classList.toggle("far");
            if (data.message) {
              const alert = document.createElement("div");
              alert.className =
                "alert alert-success alert-dismissible fade show";
              alert.innerHTML = `
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
              this.insertAdjacentElement("beforebegin", alert);
            }
          }
        })
        .catch(error => console.error("Error:", error));
    });
  });
});
