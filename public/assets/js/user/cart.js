document.addEventListener("DOMContentLoaded", () => {
  // Helper to send updates to server
  async function updateCartOnServer(formObj) {
    try {
      const fd = new FormData()
      for (const k in formObj) fd.append(k, formObj[k])
      const res = await fetch('/SHooad/app/Routes/update-cart.php', { method: 'POST', body: fd })
      const json = await res.json()
      return json
    } catch (err) {
      console.error(err)
      return { success: false }
    }
  }

  async function removeFromServer(cartId) {
    try {
      const fd = new FormData(); fd.append('cart_id', cartId)
      const res = await fetch('/SHooad/app/Routes/remove-from-cart.php', { method: 'POST', body: fd })
      return await res.json()
    } catch (err) {
      console.error(err)
      return { success: false }
    }
  }

  async function toggleSelectServer(cartId, selected) {
    try {
      const fd = new FormData(); fd.append('cart_id', cartId); fd.append('selected', selected ? 1 : 0)
      const res = await fetch('/SHooad/app/Routes/toggle-cart-select.php', { method: 'POST', body: fd })
      return await res.json()
    } catch (err) {
      console.error(err)
      return { success: false }
    }
  }

  // Handle checkbox selection
  document.querySelectorAll(".cart-select").forEach((checkbox) => {
    checkbox.addEventListener("change", async function () {
      const cartId = this.dataset.itemId
      const selected = this.checked ? 1 : 0
      await toggleSelectServer(cartId, selected)
      updateSummary()
    })
  })

  // Handle quantity changes (plus/minus)
  document.querySelectorAll(".qty-plus").forEach((btn) => {
    btn.addEventListener("click", async function () {
      const itemId = this.dataset.itemId
      const input = document.querySelector(`.qty-input[data-item-id="${itemId}"]`)
      const stock = parseInt(input.dataset.stock || 0)
      let newVal = Number.parseInt(input.value) + 1
      if (stock > 0 && newVal > stock) newVal = stock
      input.value = newVal
      const resp = await updateCartOnServer({ cart_id: itemId, quantity: newVal })
      if (resp && resp.cart_total !== undefined && window.updateCartBadge) updateCartBadge(resp.cart_total)
      updateSummary()
    })
  })

  document.querySelectorAll(".qty-minus").forEach((btn) => {
    btn.addEventListener("click", async function () {
      const itemId = this.dataset.itemId
      const input = document.querySelector(`.qty-input[data-item-id="${itemId}"]`)
      let newVal = Math.max(1, Number.parseInt(input.value) - 1)
      input.value = newVal
      const resp = await updateCartOnServer({ cart_id: itemId, quantity: newVal })
      if (resp && resp.cart_total !== undefined && window.updateCartBadge) updateCartBadge(resp.cart_total)
      updateSummary()
    })
  })

  // Direct quantity input change
  document.querySelectorAll(".qty-input").forEach((input) => {
    input.addEventListener("change", async function () {
      const itemId = this.dataset.itemId
      const stock = parseInt(this.dataset.stock || 0)
      let newVal = Number.parseInt(this.value) || 1
      if (newVal < 1) newVal = 1
      if (stock > 0 && newVal > stock) newVal = stock
      this.value = newVal
      const resp = await updateCartOnServer({ cart_id: itemId, quantity: newVal })
      if (resp && resp.cart_total !== undefined && window.updateCartBadge) updateCartBadge(resp.cart_total)
      updateSummary()
    })
  })

  // Handle remove button
  document.querySelectorAll(".remove-btn").forEach((btn) => {
    btn.addEventListener("click", async function () {
      const id = this.dataset.itemId
      if (!confirm('Are you sure you want to remove this item from your cart?')) return;
      const resp = await removeFromServer(id)
      if (resp && resp.success) {
        this.closest('.bg-white').remove()
        if (resp.cart_total !== undefined && window.updateCartBadge) updateCartBadge(resp.cart_total)
        updateSummary()
      } else {
        alert('Could not remove item')
      }
    })
  })

  // Handle edit button (simple prompt-based edit)
  document.querySelectorAll(".edit-btn").forEach((btn) => {
    btn.addEventListener("click", async function () {
      const cartId = this.dataset.itemId
      const availColors = (this.dataset.availableColors || '').split(',').map(s=>s.trim()).filter(Boolean)
      const availSizes = (this.dataset.availableSizes || '').split(',').map(s=>s.trim()).filter(Boolean)
      let newColor = null
      let newSize = null
      if (availColors.length) {
        const choice = prompt('Choose color from: ' + availColors.join(', '))
        if (choice !== null) newColor = choice.trim()
        if (newColor && availColors.indexOf(newColor) === -1) { alert('Invalid color'); return; }
      }
      if (availSizes.length) {
        const choice2 = prompt('Choose size from: ' + availSizes.join(', '))
        if (choice2 !== null) newSize = choice2.trim()
        if (newSize && availSizes.indexOf(newSize) === -1) { alert('Invalid size'); return; }
      }
      const payload = { cart_id: cartId }
      if (newColor !== null) payload.color = newColor
      if (newSize !== null) payload.size = newSize
      const resp = await updateCartOnServer(payload)
      if (resp && resp.success) {
        // reload page to reflect changes simply
        window.location.reload()
      } else {
        alert('Could not update item')
      }
    })
  })

  function updateSummary() {
    let selectedCount = 0
    let originalPrice = 0
    let salePrice = 0

    document.querySelectorAll(".cart-select:checked").forEach((checkbox) => {
      const quantity = Number.parseInt(checkbox.dataset.quantity) || 1
      const qtyInput = document.querySelector(`.qty-input[data-item-id="${checkbox.dataset.itemId}"]`)
      const actualQty = Number.parseInt(qtyInput.value) || quantity

      selectedCount += actualQty
      originalPrice += Number.parseFloat(checkbox.dataset.originalPrice) * actualQty
      salePrice += Number.parseFloat(checkbox.dataset.price) * actualQty
    })

    const savings = originalPrice - salePrice
    const total = salePrice

    document.getElementById("selected-count").textContent = selectedCount
    document.getElementById("original-price-display").textContent = "$" + originalPrice.toFixed(2)
    document.getElementById("savings-display").textContent = "$" + savings.toFixed(2)
    document.getElementById("sale-price-display").textContent = "$" + salePrice.toFixed(2)
    document.getElementById("total-display").textContent = "$" + total.toFixed(2)
  }
})
