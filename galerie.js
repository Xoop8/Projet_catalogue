const filtercontainer = document.querySelector(".gallery-filter");
const galleryItems = document.querySelectorAll(".gallery-item");

filtercontainer.addEventListener("click", (event) => {
    if (event.target.classList.contains("filter-item")) {
        // Réinitialiser la classe "active" pour tous les éléments de filtre
        filtercontainer.querySelectorAll(".filter-item").forEach((item) => {
            item.classList.remove("active");
        });

        event.target.classList.add("active");
        const filterValue = event.target.getAttribute("data-filter");

        galleryItems.forEach((item) => {
            if (filterValue === "all" || item.classList.contains(filterValue)) {
                item.classList.remove("hide");
                item.classList.add("show");
            } else {
                item.classList.remove("show");
                item.classList.add("hide");
            }
        });
    }
});















