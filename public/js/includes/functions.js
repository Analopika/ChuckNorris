const getFormData = (form, dto, additionalData) => {
    let formDataArray = $(form).serializeArray();
    formDataArray = formDataArray.concat(additionalData);

    for (let i = 0; i < formDataArray.length; i++) {
        let field = formDataArray[i];
        dto[field.name] = field.value;
    }

    return JSON.stringify(dto);
}