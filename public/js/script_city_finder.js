const inputCP = document.getElementById("annonce_zipcode");
const selectVille = document.getElementById("annonce_city");

const fetchCities = (postalCode) => {
    fetch(`https://geo.api.gouv.fr/communes?codePostal=${postalCode}&fields=region,nom,code,codesPostaux,codeRegion,centre&format=json&geometry=centre`)
        .then(response => response.json())
        .then(data => {
            selectVille.innerHTML = data.map(ville => 
                `<option value='${ville.nom}'>${ville.nom}</option>`
            ).join('');
            // selectVille.disabled = false;
            if (data.length === 1) selectVille.dispatchEvent(new Event('change'));
        })
        .catch(error => console.error('erreur:', error));
};

inputCP.addEventListener("input", () =>  {
    fetchCities(inputCP.value);
});