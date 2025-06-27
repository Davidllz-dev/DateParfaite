document.addEventListener('DOMContentLoaded', () => {
    const blocEmails = document.getElementById('liste-invites');
    const boutonAjouterEmail = document.getElementById('ajouter-invite');
    let compteurEmail = blocEmails.querySelectorAll('.bloc-invite').length;

   
    const formName = document.querySelector('form').getAttribute('name');

    const creerBlocEmail = () => {  
        const div = document.createElement('div');
        div.classList.add('bloc-invite', 'espace-bas');

        const input = document.createElement('input');
        input.type = 'email';
        input.name = `${formName}[inviteEmails][${compteurEmail}]`; 
        input.classList.add('formulaire');
        input.placeholder = 'Adresse e-mail de l\'invité';

        const boutonSupprimer = document.createElement('button');
        boutonSupprimer.type = 'button';
        boutonSupprimer.innerText = 'Supprimer cet email';
        boutonSupprimer.classList.add('bouton', 'bouton-rouge');
        boutonSupprimer.addEventListener('click', () => div.remove());

        div.appendChild(input);
        div.appendChild(boutonSupprimer);

        blocEmails.appendChild(div);
        compteurEmail++;
    };

    boutonAjouterEmail.addEventListener('click', creerBlocEmail);
});
