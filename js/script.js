const modal = document.getElementById("patient-modal");
const visitButtons = document.querySelectorAll("#header-contents button, #visit-us-button");
const closeModal = document.querySelector(".close-modal");
const cards = document.querySelectorAll(".patient-card");
const display = document.getElementById("selected-patient-display");

// Open modal when clicking Visit Us buttons
visitButtons.forEach(btn => {
  btn.addEventListener("click", () => {
    modal.style.display = "flex";
    document.body.style.overflow = "hidden"; // Prevent scroll
  });
});

// Close modal (X button)
closeModal.addEventListener("click", () => {
  modal.style.display = "none";
  document.body.style.overflow = "auto";
});

// Close when clicking backdrop
modal.addEventListener("click", (e) => {
  if (e.target === modal) {
    modal.style.display = "none";
    document.body.style.overflow = "auto";
  }
});

// Select patient type
cards.forEach(card => {
  card.addEventListener("click", () => {
    const type = card.dataset.type;
    display.textContent = `Selected Patient Type: ${type}`;
    modal.style.display = "none";
    document.body.style.overflow = "auto";
  });
});
