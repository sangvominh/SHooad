// public/js/dashboard.js
// Sidebar toggle and helpers; delete modal functions will be added in US3

(function() {
  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  ready(function() {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebar-toggle');

    if (sidebar && toggle) {
      const openClass = 'translate-x-0';
      const hiddenClass = '-translate-x-full';

      function openSidebar() {
        sidebar.classList.add(openClass);
        sidebar.classList.remove(hiddenClass);
      }
      function closeSidebar() {
        sidebar.classList.remove(openClass);
        sidebar.classList.add(hiddenClass);
      }
      function toggleSidebar() {
        if (sidebar.classList.contains(openClass)) closeSidebar();
        else openSidebar();
      }

      toggle.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleSidebar();
      });

      // Close on outside click (mobile)
      document.addEventListener('click', function(e) {
        if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
          closeSidebar();
        }
      });

      // Close on ESC key
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSidebar();
      });
    }
  });
})();

// Delete confirmation modal logic (US3)
window.showDeleteModal = function(productId, productName) {
  const modal = document.getElementById('delete-modal');
  const input = document.getElementById('delete-confirm-input');
  const btn = document.getElementById('delete-confirm-button');
  const nameDisplay = document.getElementById('product-name-display');
  const form = document.getElementById('delete-form');

  if (!modal || !input || !btn || !nameDisplay || !form) return;
  nameDisplay.textContent = productName;
  const bp = window.__BASE_PATH__ || '';
  form.action = `${bp}/seller/dashboard/products/${productId}/delete`;
  input.value = '';
  btn.disabled = true;

  function onInput() {
    btn.disabled = input.value !== productName;
  }
  input.removeEventListener('input', onInput); // ensure no duplicates
  input.addEventListener('input', onInput);

  modal.classList.remove('hidden');
  input.focus();
};

window.hideDeleteModal = function() {
  const modal = document.getElementById('delete-modal');
  if (modal) modal.classList.add('hidden');
};
