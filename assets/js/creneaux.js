console.log("creneaux.js chargé");

document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('creneaux-wrapper');
    const addButton = document.getElementById('add-creneau');
    let index = wrapper.querySelectorAll('.creneau-item').length;

    const addRemoveButton = (container) => {
        const removeBtn = document.createElement('button');
        removeBtn.innerText = "Supprimer ce créneau";
        removeBtn.type = "button";
        removeBtn.classList.add('btn', 'btn-danger', 'mt-2', 'remove-creneau');

        removeBtn.addEventListener('click', () => {
            container.remove();
        });

        container.appendChild(removeBtn);
    };

    addButton.addEventListener('click', function () {
        const prototype = wrapper.dataset.prototype;
        const newForm = prototype.replace(/__name__/g, index);
        const div = document.createElement('div');
        div.classList.add('creneau-item');
        div.innerHTML = newForm;
        addRemoveButton(div);
        wrapper.appendChild(div);
        index++;
    });

    // Ajoute les boutons de suppression aux éléments existants
    document.querySelectorAll('.creneau-item').forEach((item) => {
        addRemoveButton(item);
    });
});
