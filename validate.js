function validateForm() {
    let form = document.forms["surveyForm"];
    let full_names = form["full_names"].value;
    let email = form["email"].value;
    let date_of_birth = form["date_of_birth"].value;
    let contact_number = form["contact_number"].value;

    if (full_names === "" || email === "" || date_of_birth === "" || contact_number === "") {
        alert("All fields must be filled out");
        return false;
    }

    if (!validateEmail(email)) {
        alert("Invalid email format");
        return false;
    }

    let age = calculateAge(date_of_birth);
    if (age < 5 || age > 120) {
        alert("Age must be between 5 and 120");
        return false;
    }

    if (!isRatingSelected(form, "watch_movies") ||
        !isRatingSelected(form, "listen_radio") ||
        !isRatingSelected(form, "eat_out") ||
        !isRatingSelected(form, "watch_tv")) {
        alert("Please select a rating for all activities");
        return false;
    }

    return true;
}

function validateEmail(email) {
    let re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return re.test(email);
}

function calculateAge(dateOfBirth) {
    let dob = new Date(dateOfBirth);
    let diff_ms = Date.now() - dob.getTime();
    let age_dt = new Date(diff_ms);

    return Math.abs(age_dt.getUTCFullYear() - 1970);
}

function isRatingSelected(form, ratingName) {
    let ratings = form[ratingName];
    for (let i = 0; i < ratings.length; i++) {
        if (ratings[i].checked) {
            return true;
        }
    }
    return false;
}