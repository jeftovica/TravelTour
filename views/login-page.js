$(document).ready(function () {
    $("#login-form").get(0).reset();
});

login = () => {
    $.get("./data/users.json", (response) => {
        let email = document.querySelector("#email-login").value;
        let password = document.querySelector("#password-login").value;
        let isFound = false;
        response.data.map((user) => {
            if (user.email === email && user.password === password) {
                localStorage.setItem("user", JSON.stringify(user));
                $("#login-form").get(0).reset();
                var errorMessage = document.querySelector("#invalid-login-message");
                if (errorMessage)
                    errorMessage.style.display = "none"
                window.location.hash = "#home-page";
                isFound = true;
            }
        })
        if (isFound == false) {
            var errorMessage = document.querySelector("#invalid-login-message");
            if (errorMessage)
                errorMessage.style.display = "block"
        }

    })
}