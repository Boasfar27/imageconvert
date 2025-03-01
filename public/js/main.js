// Utility Functions
const isMobile = () => window.innerWidth < 768;

// Mobile Menu Functionality
const initMobileMenu = () => {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuOpenIcon = document.getElementById('menu-open-icon');
    const menuCloseIcon = document.getElementById('menu-close-icon');
    const body = document.body;
    let isMenuOpen = false;

    if (mobileMenuButton && mobileMenu) {
        // Fungsi untuk menampilkan menu
        const showMenu = () => {
            mobileMenu.classList.remove('hidden');
            menuOpenIcon.style.opacity = '0';
            menuCloseIcon.style.opacity = '1';
            menuOpenIcon.style.transform = 'rotate(-180deg)';
            menuCloseIcon.style.transform = 'rotate(0)';
            body.style.overflow = 'hidden';
            isMenuOpen = true;

            // Animasi menu items
            const menuItems = mobileMenu.querySelectorAll('a');
            menuItems.forEach((item, index) => {
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, index * 100);
            });
        };

        // Fungsi untuk menyembunyikan menu
        const hideMenu = () => {
            const menuItems = mobileMenu.querySelectorAll('a');
            menuItems.forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
            });

            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 300);

            menuOpenIcon.style.opacity = '1';
            menuCloseIcon.style.opacity = '0';
            menuOpenIcon.style.transform = 'rotate(0)';
            menuCloseIcon.style.transform = 'rotate(180deg)';
            body.style.overflow = '';
            isMenuOpen = false;
        };

        // Reset menu items state
        const menuItems = mobileMenu.querySelectorAll('a');
        menuItems.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            item.style.transition = 'all 0.3s ease-in-out';
        });

        // Event listener untuk tombol menu
        mobileMenuButton.addEventListener('click', () => {
            if (isMenuOpen) {
                hideMenu();
            } else {
                showMenu();
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (isMenuOpen && !mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                hideMenu();
            }
        });

        // Close menu when scrolling
        let lastScroll = 0;
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            if (currentScroll > lastScroll && isMenuOpen) {
                hideMenu();
            }
            lastScroll = currentScroll;
        });

        // Event listener untuk menu items
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                hideMenu();
            });
        });
    }
};

// Desktop Functionality
const initDesktop = () => {
    // Add desktop-specific functionality here if needed
    console.log('Desktop view initialized');
};

// Common Functionality
const initCommon = () => {
    // Back to Top functionality with smooth animation
    const backToTopButton = document.getElementById('backToTop');

    if (backToTopButton) {
        let isScrolling;
        window.addEventListener('scroll', () => {
            // Tampilkan/sembunyikan button dengan debounce
            clearTimeout(isScrolling);
            isScrolling = setTimeout(() => {
                if (window.scrollY > window.innerHeight / 2) {
                    backToTopButton.classList.add('show');
                } else {
                    backToTopButton.classList.remove('show');
                }
            }, 100);
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Tambahkan smooth scroll untuk semua anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
};

// Initialize based on viewport
const init = () => {
    initCommon();
    if (isMobile()) {
        initMobileMenu();
    } else {
        initDesktop();
    }
};

// Handle resize events with debounce
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        const wasMobile = isMobile();
        if (wasMobile !== isMobile()) {
            init();
        }
    }, 250);
});

// Initial setup
document.addEventListener('DOMContentLoaded', init); 