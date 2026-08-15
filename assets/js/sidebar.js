document.addEventListener("DOMContentLoaded", function () {
    const body = document.body;
    const menuIcon = document.querySelector(".menu-icon");
    const closeBtn = document.getElementById("sidebar-close");
    const overlay = document.getElementById("sidebar-overlay");
    const sidebar = document.getElementById("sidebar");

    function openSidebar() {
        body.classList.add("sidebar-open");
        sidebar.setAttribute("aria-hidden", "false");
    }

    function closeSidebar() {
        body.classList.remove("sidebar-open");
        sidebar.setAttribute("aria-hidden", "true");
    }

    menuIcon.addEventListener("click", function (e) {
        e.preventDefault(); // impede o "#" de rolar a página
        body.classList.contains("sidebar-open") ? closeSidebar() : openSidebar();
    });

    closeBtn.addEventListener("click", closeSidebar);
    overlay.addEventListener("click", closeSidebar);

    sidebar.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", closeSidebar);
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeSidebar();
    });
});