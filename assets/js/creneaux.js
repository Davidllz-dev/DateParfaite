

document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('liste-creneaux');
    const addBtn = document.getElementById('ajouter-creneau');
    const alertBox = document.getElementById("alerte");
    const submitBtn = document.querySelector('button[type="submit"]');

    function initFlatpickrInputs() {
    document.querySelectorAll('input[name$="[start_time]"]').forEach(el => {
        if (!el._flatpickr) {
            flatpickr(el, {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true
            });
        }
    });

    document.querySelectorAll('input[name$="[end_time]"]').forEach(el => {
        if (!el._flatpickr) {
            flatpickr(el, {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true
            });
        }
    });
}

    if (!wrapper || !addBtn || !submitBtn) return;

    const prototype = wrapper.dataset.prototype;
    let index = wrapper.querySelectorAll('.bloc-creneau').length;

    const showAlert = (msg) => {
        alertBox.textContent = msg || '';
        alertBox.style.display = msg ? 'block' : 'none';
        submitBtn.disabled = !!msg;
    };

    const addRemoveBtn = (container) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.classList.add('btn-supprimer-creneau', 'btn', 'btn-danger', 'mt-2');
        btn.innerText = 'Supprimer ce créneau';
        btn.addEventListener('click', () => {
            container.remove();
            validateSlots();
        });
        container.appendChild(btn);
    };

    const getSlots = () => {
        return [...wrapper.querySelectorAll('.bloc-creneau')].map(bloc => {
            const startInput = bloc.querySelector('input[name$="[start_time]"]');
            const endInput = bloc.querySelector('input[name$="[end_time]"]');
            return {
                start: startInput?.value ? new Date(startInput.value) : null,
                end: endInput?.value ? new Date(endInput.value) : null
            };
        });
    };

    const isSlotValid = ({ start, end }) => {
        if (!start || !end) return true;
        if (end < start) {
            showAlert("On programme des réunions, pas des voyages dans le temps!               Veuillez corriger les horaires. ;)");
            return false;
        }
        return true;
    };

    const validateSlots = () => {
        const all = getSlots();
        for (let slot of all) {
            if (!isSlotValid(slot)) return false;
        }
        showAlert('');
        return true;
    };

    const addValidation = (bloc) => {
        const startInput = bloc.querySelector('input[name$="[start_time]"]');
        const endInput = bloc.querySelector('input[name$="[end_time]"]');
        startInput?.addEventListener('change', validateSlots);
        endInput?.addEventListener('change', validateSlots);
    };

    const addSlot = () => {
        const newForm = prototype.replace(/__name__/g, index);
        const newBloc = document.createElement('div');
        newBloc.classList.add('bloc-creneau', 'espace-bas');
        newBloc.innerHTML = newForm;

        addRemoveBtn(newBloc);
        wrapper.appendChild(newBloc);
        addValidation(newBloc);
        initFlatpickrInputs();

        index++;
        validateSlots();
    };

    addBtn.addEventListener('click', addSlot);

    wrapper.querySelectorAll('.bloc-creneau').forEach(bloc => {
        addRemoveBtn(bloc);
        addValidation(bloc);
    });

    initFlatpickrInputs();
    validateSlots();
});
