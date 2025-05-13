// Sidebar Toggle Persistence
const sidebarToggle = document.querySelector("#sidebar-toggle");
const sidebar = document.querySelector("#sidebar");

// Check and load saved sidebar state on page load
window.addEventListener('DOMContentLoaded', function() {
    const sidebarState = localStorage.getItem("sidebar-collapsed");
    if (sidebarState === "true") {
        sidebar.classList.add("collapsed");
    } else {
        sidebar.classList.remove("collapsed");
    }
});

// Toggle sidebar and save state
sidebarToggle.addEventListener("click", function () {
    const isCollapsed = sidebar.classList.contains("collapsed");
    sidebar.classList.toggle("collapsed", !isCollapsed);
    localStorage.setItem("sidebar-collapsed", !isCollapsed);
});

// Theme Toggle
document.querySelector(".theme-toggle").addEventListener("click", () => {
    toggleLocalStorage();
    toggleRootClass();
});

function toggleRootClass() {
    const current = document.documentElement.getAttribute('data-bs-theme');
    const inverted = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-bs-theme', inverted);
}

function toggleLocalStorage() {
    if (isLight()) {
        localStorage.removeItem("light");
    } else {
        localStorage.setItem("light", "set");
    }
}

function isLight() {
    return localStorage.getItem("light");
}

// Load saved theme on page load
if (isLight()) {
    toggleRootClass();
}
