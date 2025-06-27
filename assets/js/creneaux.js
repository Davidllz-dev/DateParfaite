document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('liste-creneaux');
    const addButton = document.getElementById('ajouter-creneau');

    if (!wrapper || !addButton) return;

    
    const prototype = wrapper.dataset.prototype;
    let index = wrapper.querySelectorAll('.bloc-creneau').length;

   
    const addRemoveButton = (container) => {
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.classList.add('btn-supprimer-creneau', 'btn', 'btn-danger', 'mt-2');
        removeBtn.innerText = 'Supprimer ce créneau';

        removeBtn.addEventListener('click', () => {
            container.remove();
        });

        container.appendChild(removeBtn);
    };

   
    const addCreneau = () => {
        let newFormHtml = prototype.replace(/__name__/g, index);
        const newElement = document.createElement('div');
        newElement.classList.add('bloc-creneau', 'espace-bas');
        newElement.innerHTML = newFormHtml;

        addRemoveButton(newElement);
        wrapper.appendChild(newElement);

        index++;
    };

    
    addButton.addEventListener('click', addCreneau);

   
    wrapper.querySelectorAll('.bloc-creneau').forEach((bloc) => {
        addRemoveButton(bloc);
    });
});
