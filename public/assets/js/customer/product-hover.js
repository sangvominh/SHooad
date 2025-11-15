document.addEventListener("DOMContentLoaded", () => {
  const productCards = document.querySelectorAll(".product-card")

  productCards.forEach((card, index) => {
    // First product should show hover state by default (for demo)
    if (index === 0) {
      card.classList.add("group-hover")
    }
  })
})
