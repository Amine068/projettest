document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('newAnnonceForm');
    const progressBar = document.getElementById('progressBar');

    if (form && progressBar) {
        // Filter valid form elements
        const formElements = Array.from(form.elements).filter(el => 
            el.tagName !== 'BUTTON' && 
            el.type !== 'hidden' &&
            el.type !== 'submit' &&
            !el.disabled
        );
        
        const totalElements = formElements.length;

        formElements.forEach(element => {
            element.addEventListener('input', updateProgressBar);
        });

        function updateProgressBar() {
            const filledElements = formElements.filter(el => {
                if (el.type === 'file') {
                    return el.files && el.files.length > 0;
                }
                return el.value && el.value.toString().trim() !== '';
            }).length;

            const progressPercentage = (filledElements / totalElements) * 100;
            progressBar.style.width = `${progressPercentage}%`;
        }
    }
});