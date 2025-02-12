const form = document.getElementById('newAnnonceForm');
const form_category = document.getElementById('annonce_Category');
const form_subcategory = document.getElementById('annonce_subcategory');


console.log(form);
console.log(form_category);
console.log(form_subcategory);

const updateForm = async (data, url, method) => {
  const req = await fetch(url, {
    method: method,
    body: data,
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'charset': 'utf-8'
    }
  });

  const text = await req.text();

  return text;
};

const parseTextToHtml = (text) => {
  const parser = new DOMParser();
  const html = parser.parseFromString(text, 'text/html');

  return html;
};

const changeOptions = async (e) => {
  const requestBody = e.target.getAttribute('name') + '=' + e.target.value;
  const updateFormResponse = await updateForm(requestBody, form.getAttribute('action'), form.getAttribute('method'));
  const html = parseTextToHtml(updateFormResponse);

  const newFormSelectSubcategory = html.getElementById('annonce_subcategory');
  form_subcategory.innerHTML = newFormSelectSubcategory.innerHTML;
};

form_category.addEventListener('change', (e) => changeOptions(e));

// document.addEventListener('DOMContentLoaded', function() {
//   const categoryField = document.getElementById('annonce_Category');
//   const subcategoryField = document.getElementById('annonce_subcategory');

//   categoryField.addEventListener('change', function() {
//       const categoryId = this.value;

//       fetch('/get-subcategories/' + categoryId)
//           .then(response => response.json())
//           .then(data => {
//               // Clear the current options
//               subcategoryField.innerHTML = '';

//               // Add the new options
//               data.subcategories.forEach(subcategory => {
//                   const option = document.createElement('option');
//                   option.value = subcategory.id;
//                   option.textContent = subcategory.name;
//                   subcategoryField.appendChild(option);
//               });
//           });
//   });
// });