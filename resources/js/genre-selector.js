// Track the previously selected radio button
let previouslySelected = null;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const checkedRadio = document.querySelector('input[name="genre"]:checked');
    if (checkedRadio) {
        previouslySelected = checkedRadio;
    }
});

function handleRadioClick(radio, genreId) {
    if (previouslySelected === radio) {
        radio.checked = false;
        previouslySelected = null;
        radio.dispatchEvent(new Event('change'));
        onGenreUnselected(genreId);
    } else {
        previouslySelected = radio;
        onGenreSelected(genreId);
    }
}

function clearRadioSelection() {
    const radios = document.querySelectorAll('input[name="genre"]');
    radios.forEach(radio => {
        if (radio.checked) {
            radio.checked = false;
            radio.dispatchEvent(new Event('change'));
        }
    });
    previouslySelected = null;
    onSelectionCleared();
}