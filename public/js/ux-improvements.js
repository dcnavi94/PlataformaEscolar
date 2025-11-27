// UX/UI Improvements - Responsive & Dark Mode
(function () {
    'use strict';

    // ========================================
    // 1. DARK MODE
    // ========================================
    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');

    if (darkModeToggle) {
        // Load saved preference
        const savedDarkMode = localStorage.getItem('darkMode');
        if (savedDarkMode === 'true') {
            document.body.classList.add('dark-mode');
            updateDarkModeIcon(true);
        }

        // Toggle dark mode
        darkModeToggle.addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
            const isDarkMode = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDarkMode);
            updateDarkModeIcon(isDarkMode);
        });
    }

    function updateDarkModeIcon(isDarkMode) {
        if (darkModeIcon) {
            darkModeIcon.className = isDarkMode ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
        }
    }

    // ========================================
    // 2. WINDOWS START MENU STYLE SIDEBAR
    // ========================================
    const windowsStartBtn = document.getElementById('windowsStartBtn');
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');

    // Use Windows Start button or fallback to hamburger menu
    const toggleButton = windowsStartBtn || menuToggle;

    if (toggleButton && sidebar && sidebarBackdrop) {
        // Toggle sidebar
        toggleButton.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = sidebar.classList.contains('show');

            if (isOpen) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        // Close sidebar when clicking backdrop
        sidebarBackdrop.addEventListener('click', closeSidebar);

        // Close sidebar when clicking a link
        const sidebarLinks = sidebar.querySelectorAll('.nav-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function () {
                closeSidebar();
            });
        });

        // Close on window resize if too large
        window.addEventListener('resize', function () {
            if (window.innerWidth > 768 && sidebar.classList.contains('show')) {
                // Keep open on desktop for Windows-style menu
            }
        });
    }

    function openSidebar() {
        if (sidebar) sidebar.classList.add('show');
        if (sidebarBackdrop) sidebarBackdrop.classList.add('show');
        if (windowsStartBtn) windowsStartBtn.classList.add('active');
        if (toggleButton) toggleButton.setAttribute('aria-expanded', 'true');
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('show');
        if (sidebarBackdrop) sidebarBackdrop.classList.remove('show');
        if (windowsStartBtn) windowsStartBtn.classList.remove('active');
        if (toggleButton) toggleButton.setAttribute('aria-expanded', 'false');
    }

    // ========================================
    // 3. ACCESSIBILITY - Keyboard Navigation
    // ========================================
    // ESC key closes sidebar
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && sidebar && sidebar.classList.contains('show')) {
            closeSidebar();
            if (toggleButton) {
                toggleButton.focus(); // Return focus
            }
        }
    });

    // ========================================
    // 4. SMOOTH SCROLL for Skip Link
    // ========================================
    const skipLink = document.querySelector('.skip-link');
    if (skipLink) {
        skipLink.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.focus();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }

})();
