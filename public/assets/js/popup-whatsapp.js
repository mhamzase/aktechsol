/* Announcement popup — show once, "don't show again" via localStorage versioning. */
(function () {
    const overlay = document.getElementById('announcement-popup-overlay');
    if (!overlay) return;

    const closeButton = document.getElementById('announcement-popup-close-btn');
    const closeIcon = document.getElementById('announcement-popup-icon-close');
    const hideCheckbox = document.getElementById('announcement-popup-hide');
    const storageKey = 'aktechsol_popup_hidden_version';
    const version = overlay.dataset.popupVersion || 'default';

    if (localStorage.getItem(storageKey) === version) return;

    // Small delay so it doesn't flash instantly on page load.
    setTimeout(() => {
        overlay.classList.add('is-open');
        overlay.setAttribute('aria-hidden', 'false');
    }, 1200);

    const closePopup = () => {
        if (hideCheckbox && hideCheckbox.checked) {
            localStorage.setItem(storageKey, version);
        } else {
            localStorage.removeItem(storageKey);
        }
        overlay.classList.remove('is-open');
        overlay.setAttribute('aria-hidden', 'true');
    };

    closeButton?.addEventListener('click', closePopup);
    closeIcon?.addEventListener('click', closePopup);
    overlay.addEventListener('click', (e) => { if (e.target === overlay) closePopup(); });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && overlay.classList.contains('is-open')) closePopup();
    });
})();
