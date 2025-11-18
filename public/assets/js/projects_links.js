document.addEventListener("DOMContentLoaded", () => {

    const linksList = document.getElementById("links-list");
    const addButton = document.getElementById("add-link");
    let index = linksList.children.length;

    function applyStylesToInputs(container) {
        container.querySelectorAll("input, select, textarea").forEach((el) => {
            el.classList.add("uk-input", "uk-width-1-1");
        });
    }

    addButton.addEventListener("click", () => {
        const prototype = linksList.dataset.prototype;
        const newForm = prototype.replace(/__name__/g, index);

        const li = document.createElement("li");
        li.innerHTML = `
            ${newForm}
            <button 
                type="button" 
                class="remove-link uk-button uk-button-danger uk-button-small uk-border-pill uk-box-shadow-hover-small uk-margin-small-top"
            >
                Supprimer
            </button>
        `;

        linksList.appendChild(li);

        // appliquer la largeur aux inputs du nouvel élément
        applyStylesToInputs(li);

        li.querySelector(".remove-link").addEventListener("click", () => li.remove());

        index++;
    });

    // Pour les lignes existantes :
    linksList.querySelectorAll("li").forEach(li => applyStylesToInputs(li));

    linksList.querySelectorAll(".remove-link").forEach((button) => {
        button.className =
            "remove-link uk-button uk-button-danger uk-button-small uk-border-pill uk-box-shadow-hover-small uk-margin-small-top";

        button.addEventListener("click", (e) => {
            e.currentTarget.parentElement.remove();
        });
    });
});
