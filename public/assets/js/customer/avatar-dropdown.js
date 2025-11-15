// Dropdown avatar menu for customer header
// Show/hide dropdown on avatar hover and click

document.addEventListener('DOMContentLoaded', function() {
    const avatarContainer = document.getElementById('avatarContainer');
    const avatarBtn = document.getElementById('avatarMenuBtn');
    const dropdown = document.getElementById('avatarDropdown');
    
    if (!avatarContainer || !avatarBtn || !dropdown) return;
    
    let hideTimeout;
    
    // Show dropdown on hover
    function showDropdown() {
        clearTimeout(hideTimeout);
        dropdown.classList.remove('hidden');
    }
    
    // Hide dropdown with delay
    function hideDropdown() {
        hideTimeout = setTimeout(() => {
            dropdown.classList.add('hidden');
        }, 200);
    }
    
    // Avatar button hover
    avatarBtn.addEventListener('mouseenter', showDropdown);
    avatarBtn.addEventListener('mouseleave', hideDropdown);
    
    // Dropdown hover
    dropdown.addEventListener('mouseenter', showDropdown);
    dropdown.addEventListener('mouseleave', hideDropdown);
    
    // Click to toggle
    avatarBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        if (dropdown.classList.contains('hidden')) {
            showDropdown();
        } else {
            clearTimeout(hideTimeout);
            dropdown.classList.add('hidden');
        }
    });
    
    // Close on outside click
    document.addEventListener('click', function(e) {
        if (!avatarContainer.contains(e.target)) {
            clearTimeout(hideTimeout);
            dropdown.classList.add('hidden');
        }
    });
});
