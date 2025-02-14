// const form = document.getElementById('newAnnonceForm');
// const form_category = document.getElementById('annonce_Category');
// const form_subcategory = document.getElementById('annonce_subcategory');


// console.log(form);
// console.log(form_category);
// console.log(form_subcategory);

// const updateForm = async (data, url, method) => {
//   const req = await fetch(url, {
//     method: method,
//     body: data,
//     headers: {
//         'Content-Type': 'application/x-www-form-urlencoded',
//         'charset': 'utf-8'
//     }
//   });

//   const text = await req.text();

//   return text;
// };

// const parseTextToHtml = (text) => {
//   const parser = new DOMParser();
//   const html = parser.parseFromString(text, 'text/html');

//   return html;
// };

// const changeOptions = async (e) => {
//   const requestBody = e.target.getAttribute('name') + '=' + e.target.value;
//   const updateFormResponse = await updateForm(requestBody, form.getAttribute('action'), form.getAttribute('method'));
//   const html = parseTextToHtml(updateFormResponse);

//   const newFormSelectSubcategory = html.getElementById('annonce_subcategory');
//   form_subcategory.innerHTML = newFormSelectSubcategory.innerHTML;
// };

// form_category.addEventListener('change', (e) => changeOptions(e));


// ------------------------------


// //event listener sur le chargement de la page
// document.addEventListener('DOMContentLoaded', function() {
//     // recupération de l'élement select de la categorie
//     const categoryField = document.getElementById('annonce_Category');
//     // recupération de l'élement select de la sous-categorie
//     const subcategoryField = document.getElementById('annonce_subcategory');

//     // ecoute d'evenement sur le changement de la categorie
//     categoryField.addEventListener('change', function() {
    
//         //recuperation de l'id de la categorie
//         const categoryId = this.value;

//         //requete ajax pour recuperer les sous-categories via l'URL /get-subcategories/{id} recupéré par le controller
//         fetch('/getsubcategories/' + categoryId)
//             // convertir la reponse en json
//             .then(response => response.json())

//             // recuperer les données
//             .then(data => {
//                 // retirer les options actuelles du select
//                 subcategoryField.innerHTML = '';

//                 // ajout des nouvelles options au select
//                 data.subcategories.forEach(subcategory => {
//                     // création d'un element option (qui va être ajouté au select)
//                     const option = document.createElement('option');
//                     // ajout de l'id de la souscategorie à la valeur de l'option
//                     option.value = subcategory.id;
//                     // ajout du nom de la souscategorie au texte de l'option (sera affiché)
//                     option.textContent = subcategory.name;
//                     // ajout de l'option au select de sous catégorie
//                     subcategoryField.appendChild(option);
//                 });

//                 // rendre le seleect actif (rendu inutilisable par defaut)
//                 subcategoryField.disabled = false;
//             })
//             //affichage de l'erreur een cas d'echec de la requete ajax
//             .catch(error => {
//                 console.error('Erreur a la recuperation des sous categorie:', error);
//         });
//     });
// });


document.addEventListener('DOMContentLoaded', function() {

    // recupération de l'élement select de la categorie
    const categoryElement = document.getElementById('annonce_Category');

    // ecoute de levenement sur le chagement de la categorie
    categoryElement.addEventListener('change', function(e) {
        // recuperation du formulaire a partir de l'element select categorie
        const formElement = categoryElement.closest('form');

        // envoi de la requete a partir des données du formulaire (method et action)
        fetch(formElement.action, {
            method: formElement.method,
            body: new FormData(formElement)
        })
        .then(response => response.text())
        .then(html => {
            // recuperation de l'element select de la sous-categorie du formulaire
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newSubCategoryElement = doc.getElementById('annonce_subcategory');

            // remplacement du select de la sous-categorie par le nouveau select
            document.getElementById('annonce_subcategory').replaceWith(newSubCategoryElement);
        })
        // recuperation de l'erreur de la requete
        .catch(function (error) {
            console.warn('Un problème est survenue.', error);
        });
    });
});