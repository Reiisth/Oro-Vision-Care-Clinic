document.querySelectorAll(".group-toggle").forEach(button => {
    button.addEventListener("click", () => {

        // Toggle open class on the button itself
        button.classList.toggle("open");

        // Toggle the submenu below it
        const submenu = button.nextElementSibling;
        submenu.classList.toggle("show");
    });
});
