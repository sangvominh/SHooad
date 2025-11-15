// Dropdown avatar menu for user header
// Show/hide dropdown on avatar click

document.addEventListener('DOMContentLoaded', function() {
    var avatarBtn = document.getElementById('avatarMenuBtn');
    var dropdown = document.getElementById('avatarDropdown');
    if (avatarBtn && dropdown) {
        avatarBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', function(e) {
            if (!avatarBtn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }
});
