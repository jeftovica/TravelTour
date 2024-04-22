$(document).ready(function () {
    $("#register-form").get(0).reset();
});

register = () => {
    let name = document.querySelector("#name").value;
    let surname = document.querySelector("#surname").value;
    let phone = document.querySelector("#phoneNumber").value;
    let email = document.querySelector("#inputEmail").value;
    let password = document.querySelector("#inputPassword").value;
    let repeatPassword = document.querySelector("#inputRepeatPassword").value;

    if (password !== repeatPassword) {
        var errorMessage = document.querySelector("#invalid-register-message");
        if (errorMessage) {
            errorMessage.style.display = "block"
            errorMessage.textContent = "Passwords do not match."
        }
    }
    /* TODO
    $.get("./data/users.json", (response) => {
        response.data.map((user) => {
            if (user.email === email) {
                var errorMessage = document.querySelector("#invalid-register-message");
                if (errorMessage) {
                    console.log(email)
                    errorMessage.style.display = "block"
                    errorMessage.textContent = "User with that email already exists."
                }
            }
        })
    }) */
    let newUser = { "name": name, "surname": surname, "phone": phone, "email": email, "password": password }
    $.post(Constants.API_BASE_URL + 'add_user.php', newUser).done(() => {
        $("#register-form").get(0).reset();
        window.location.hash = "#login-page";
    });


}

