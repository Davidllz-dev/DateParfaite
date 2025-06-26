document.addEventListener('DOMContentLoaded', () => {
            const collectionHolder = document.getElementById('inviteEmailss');
            const addButton = document.getElementById('add-inviteEmail');

            
            let index = collectionHolder.querySelectorAll('.invite-email-item').length;

            addButton.addEventListener('click', () => {
                const prototype = collectionHolder.dataset.prototype;
                const newForm = prototype.replace(/__name__/g, index);
                const div = document.createElement('div');
                div.classList.add('invite-email-item', 'mb-2');
                div.innerHTML = newForm + '<button type="button" class="btn btn-danger btn-sm btn-remove-email">Supprimer cet email</button>';
                collectionHolder.appendChild(div);

                index++;

               
                div.querySelector('.btn-remove-email').addEventListener('click', () => {
                    div.remove();
                });
            });

           
            collectionHolder.querySelectorAll('.btn-remove-email').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.currentTarget.closest('.invite-email-item').remove();
                });
            });
        });