document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("surveyForm");
    const successMessage = document.getElementById("successMessage");

    form.addEventListener("submit", function(event) {
        event.preventDefault();
        
        if (validateForm()) {
            const formData = new FormData(form);
            
            fetch("save_survey.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    form.reset();
                    successMessage.style.display = "block";
                } else {
                    alert("There was an error submitting the form.");
                }
            })
            .catch(error => {
                alert("There was an error submitting the form.");
                console.error("Error:", error);
            });
        }
    });
});