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

document.addEventListener('DOMContentLoaded', function() {
  const categoryField = document.getElementById('annonce_Category');
  const subcategoryField = document.getElementById('annonce_subcategory');

  categoryField.addEventListener('change', function() {
      const categoryId = this.value;

      fetch('/get-subcategories/' + categoryId)
          .then(response => response.json())
          .then(data => {
              // retirer les options actuelles du select
              subcategoryField.innerHTML = '';

              // ajout des nouvelles options au select
              data.subcategories.forEach(subcategory => {
                  const option = document.createElement('option');
                  option.value = subcategory.id;
                  option.textContent = subcategory.name;
                  subcategoryField.appendChild(option);
              });

              // rendre le seleect actif (rendu inutilisable par defaut)
              subcategoryField.disabled = false;
          })
          .catch(error => {
              console.error('Error fetching subcategories:', error);
          });
  });
});

// ------------------------------

// document.addEventListener('DOMContentLoaded', function() {
//   const countrySelectEl = document.getElementById('annonce_Category');

//   countrySelectEl.addEventListener('change', function(e) {
//       const formEl = countrySelectEl.closest('form');

//       fetch(formEl.action, {
//           method: formEl.method,
//           body: new FormData(formEl)
//       })
//       .then(response => response.text())
//       .then(html => {
//           const parser = new DOMParser();
//           const doc = parser.parseFromString(html, 'text/html');
//           const newCityFormFieldEl = doc.getElementById('annonce_subcategory');

//           newCityFormFieldEl.addEventListener('change', function(e) {
//               e.target.classList.remove('is-invalid');
//           });

//           document.getElementById('annonce_subcategory').replaceWith(newCityFormFieldEl);
//       })
//       .catch(function (err) {
//           console.warn('Something went wrong.', err);
//       });
//   });
// });