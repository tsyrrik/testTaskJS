document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('type-select');
    const formFields = document.querySelectorAll('.form-field');

    function updateFields() {
        const selectedType = typeSelect.value;

        formFields.forEach(field => {
            const fieldType = field.getAttribute('data-type');
            if (fieldType === selectedType) {
                field.style.display = 'block';
            } else {
                field.style.display = 'none';
            }
        });
    }

    typeSelect.addEventListener('change', updateFields);
    updateFields();
});
