// Toggle circular modules overlay
function toggleModules() {
    const overlay = document.getElementById('modulesOverlay');
    const btn = document.getElementById('windowsStartBtn');

    if (overlay && btn) {
        overlay.classList.toggle('show');
        btn.classList.toggle('active');

        // Update ARIA
        const isExpanded = overlay.classList.contains('show');
        btn.setAttribute('aria-expanded', isExpanded);
    }
}

// Close modules on Escape key
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const overlay = document.getElementById('modulesOverlay');
        const btn = document.getElementById('windowsStartBtn');
        if (overlay && overlay.classList.contains('show')) {
            overlay.classList.remove('show');
            if (btn) {
                btn.classList.remove('active');
                btn.setAttribute('aria-expanded', 'false');
            }
        }
    }
});

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('windowsStartBtn');
    if (btn) {
        btn.addEventListener('click', toggleModules);
    }
});
