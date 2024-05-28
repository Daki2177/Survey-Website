function validateForm() {
    let full_name = document.forms["surveyForm"]["full_name"].value;
    let email = document.forms["surveyForm"]["email"].value;
    let date_of_birth = document.forms["surveyForm"]["date_of_birth"].value;
    let contact_number = document.forms["surveyForm"]["contact_number"].value;

    if (full_name === "" || email === "" || date_of_birth === "" || contact_number === "") {
        alert("All fields must be filled out");
        return false;
    }

    if (!validateEmail(email)) {
        alert("Invalid email format");
        return false;
    }

    return true;
}

function validateEmail(email) {
    let re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return re.test(email);
}