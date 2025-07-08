document.addEventListener('DOMContentLoaded', function () {
    const conteneurCreneaux = document.getElementById('liste-creneaux');
    const boutonAjouter = document.getElementById('ajouter-creneau');
    const boiteAlerte = document.getElementById("alerte");
    const boutonSoumettre = document.querySelector('button[type="submit"]');

   
    function initialiserCalendriers() {
        document.querySelectorAll('input[name$="[start_time]"], input[name$="[end_time]"]')
        .forEach(input => {
            if (!input._flatpickr) {
                flatpickr(input, {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    time_24hr: true,
                    locale: 'fr',
                });
            }
        });
    }

    if (!conteneurCreneaux || !boutonAjouter || !boutonSoumettre) return;

   
    const modeleCreneau = conteneurCreneaux.dataset.prototype;

    
    let compteurCreneaux = conteneurCreneaux.querySelectorAll('.bloc-creneau').length;

    
    const afficherAlerte = (message) => {
        boiteAlerte.textContent = message || '';
        boiteAlerte.style.display = message ? 'block' : 'none';
        boutonSoumettre.disabled = !!message;
    };

   
    const ajouterBoutonSuppression = (bloc) => {
        const bouton = document.createElement('button');
        bouton.type = 'button';
        bouton.classList.add('btn-supprimer-creneau', 'btn', 'btn-danger', 'mt-2');
        bouton.innerText = 'Supprimer ce créneau';
        bouton.addEventListener('click', () => {
            bloc.remove();
            validerCreneaux();
        });
        bloc.appendChild(bouton);
    };

    
    const recupererCreneaux = () => {
        return [...conteneurCreneaux.querySelectorAll('.bloc-creneau')].map(bloc => {
            const debut = bloc.querySelector('input[name$="[start_time]"]');
            const fin = bloc.querySelector('input[name$="[end_time]"]');
            return {
                start: debut?.value ? new Date(debut.value) : null,
                end: fin?.value ? new Date(fin.value) : null
            };
        });
    };

  
    const estCreneauValide = ({ start, end }) => {
        if (!start || !end) return true;
        if (end < start) {
            afficherAlerte("On programme des réunions, pas des voyages dans le temps !");
            return false;
        }
        return true;
    };

  
    const validerCreneaux = () => {
        const tousLesCreneaux = recupererCreneaux();
        for (let creneau of tousLesCreneaux) {
            if (!estCreneauValide(creneau)) return false;
        }
        afficherAlerte('');
        return true;
    };

  
    const ajouterValidationAutomatique = (bloc) => {
        const debut = bloc.querySelector('input[name$="[start_time]"]');
        const fin = bloc.querySelector('input[name$="[end_time]"]');
        debut?.addEventListener('change', validerCreneaux);
        fin?.addEventListener('change', validerCreneaux);
    };

   
    const ajouterCreneau = () => {
        const formulaireHTML = modeleCreneau.replace(/__name__/g, compteurCreneaux);
        
        
        const blocCreneau = document.createElement('div');
        blocCreneau.classList.add('bloc-creneau', 'espace-bas');
        blocCreneau.innerHTML = formulaireHTML;

        ajouterBoutonSuppression(blocCreneau);
        ajouterValidationAutomatique(blocCreneau);
        conteneurCreneaux.appendChild(blocCreneau);
        initialiserCalendriers();

        compteurCreneaux++;
        validerCreneaux();
    };

   
    boutonAjouter.addEventListener('click', ajouterCreneau);

   
    conteneurCreneaux.querySelectorAll('.bloc-creneau').forEach(bloc => {
        ajouterBoutonSuppression(bloc);
        ajouterValidationAutomatique(bloc);
    });

    initialiserCalendriers();
    validerCreneaux();
});
