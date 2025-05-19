document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('main');
    const toggler = document.getElementById('sidebartoggler');
    const sidebarcloser = document.getElementById('sidebarclose');
    const savedScroll = localStorage.getItem("sidebarScrollTop");

    const sidebarW = "17rem";
    const sidebarVW = "100vw";
    const saveState = (value) => sessionStorage.setItem('state', value);
    const loadState = () => sessionStorage.getItem('state');

    
    if (savedScroll !== null) {
        sidebar.scrollTop = parseInt(savedScroll, 10);
    }

    function applySidebarState(state) {
        const isMobile = window.innerWidth <= 768;

        if (state === 'close') {
            sidebar.style.left = isMobile ? `-${sidebarVW}` : `-${sidebarW}`;
            main.style.marginLeft = '0';
        } else {
            sidebar.style.left = '0';
            main.style.marginLeft = isMobile ? '0' : sidebarW;
        }
    }

    function toggleSidebar() {
        const currentState = loadState();
        const newState = (currentState === 'open') ? 'close' : 'open';
        applySidebarState(newState);
        saveState(newState);
    }

    // Initialize sidebar state
    const initialState = loadState() || 'open';
    applySidebarState(initialState);

    // Event listeners
    toggler.addEventListener('click', toggleSidebar);
    sidebarcloser.addEventListener('click', toggleSidebar);

    // Adjust sidebar when window is resized
    window.addEventListener('resize', () => {
        applySidebarState(loadState() || 'open');
    });

    sidebar.classList.remove('visually-hidden');
    main.classList.remove('visually-hidden');

    document.querySelectorAll("#sidebar a").forEach(link => {
        link.addEventListener("click", () => {
            localStorage.setItem("sidebarScrollTop", sidebar.scrollTop);
        });
    });
});