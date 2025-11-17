// Dropdown functionality - optional since we use CSS hover
// This can be used for mobile menu support or more complex interactions

document.addEventListener("DOMContentLoaded", () => {
  const dropdownButtons = document.querySelectorAll("[data-dropdown-toggle]")

  dropdownButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault()
      const dropdownId = this.getAttribute("data-dropdown-toggle")
      const dropdown = document.getElementById(dropdownId)

      if (dropdown) {
        dropdown.classList.toggle("hidden")
      }
    })
  })

  // Close dropdowns when clicking outside
  document.addEventListener("click", (e) => {
    dropdownButtons.forEach((button) => {
      const dropdownId = button.getAttribute("data-dropdown-toggle")
      const dropdown = document.getElementById(dropdownId)

      if (dropdown && !button.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add("hidden")
      }
    })
  })
})
