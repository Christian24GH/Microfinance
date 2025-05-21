<style>
    :root {
        --mfc1: #4bc5ec;
        --mfc2: #94dcf4;
        --mfc3: #2c3c8c;
        --mfc4: #5cbc9c;
        --mfc5: #bdccdc;
        --mfc6: #353c61;
        --mfc7: #272c47;
        --mfc8: white;
    }

    body {
        background-color: #f3f4f6;
    }

    #sidebar {
        background-color: var(--mfc6);
        color: var(--mfc1);
        height: 100vh;
        width: 25rem;
        position: fixed;
        left: 0;
        z-index: 10;
        overflow-y: auto;
    }

    #sidebar hr {
        border: 1px solid var(--mfc1);
    }

    #sidebar #profile h5 {
        color: var(--mfc8) !important;
    }

    #sidebar .img_container {
        width: 5rem !important;
        aspect-ratio: 1/1 !important;
    }

    .navs button {
        text-decoration: none;
        color: var(--mfc1);
        padding: 0;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
    }

    .navs a {
        text-decoration: none;
        color: var(--mfc8);
        transition: all 0.3s ease;
    }

    .navs a:hover {
        color: var(--mfc1);
        padding-left: 0.5rem;
    }

    .navs h3 {
        color: var(--mfc1);
    }

    .navs .nav-item {
        position: relative;
        transition: all 0.3s ease;
    }

    .navs .nav-item:hover::after,
    .navs .nav-item.active::after {
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: var(--mfc7);
        z-index: -1;
        border-radius: .5rem;
    }

    .navs .nav-item.active button,
    .navs .nav-item.active a {
        color: var(--mfc1) !important;
    }

    .main-content {
        margin-left: 25rem;
        min-height: 100vh;
        padding: 1.5rem;
    }

    /* Font styles */
    .montserrat-header {
        font-family: "Montserrat", sans-serif;
        font-optical-sizing: auto;
        font-weight: 500;
        font-style: normal;
    }

    .nunito-nav {
        font-family: "Nunito", sans-serif;
        font-optical-sizing: auto;
        font-weight: normal;
        font-style: normal;
        color: var(--mfc8) !important;
        font-variation-settings:
            "wdth" 100,
            "YTLC" 500;
    }

    .lato-regular {
        font-family: "Lato", sans-serif;
        font-weight: 400;
        font-style: normal;
    }

    /* Collapse animations */
    .collapse {
        transition: all 0.3s ease;
    }

    .collapse:not(.show) {
        display: block;
        height: 0;
        overflow: hidden;
        opacity: 0;
    }

    .collapse.show {
        height: auto;
        opacity: 1;
    }

    /* Submenu styling */
    .collapse .nav-link {
        padding-left: 1rem;
        font-size: 0.9rem;
    }

    /* Active effect with highlight + glow */
    .nav-item.active h3,
    .nav-item.active button {
        background-color: var(--mfc7);
        color: var(--mfc1);
        border-radius: 0.5rem;
        box-shadow: 0 0 10px rgba(75, 197, 236, 0.3);
    }

    /* Hover effect */
    .nav-item:hover h3,
    .nav-item:hover button {
        background-color: var(--mfc7);
        color: var(--mfc1);
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        #sidebar {
            width: 100%;
            height: fit-content;
            position: fixed;
            top: 0;
        }
        #sidebar .img_container {
            width: 7em !important;
            aspect-ratio: 1/1 !important;
        }
        .main-content {
            margin-left: 0;
            margin-top: 4rem;
        }
    }
</style> 