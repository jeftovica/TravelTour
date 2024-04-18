$(document).ready(function () {
    $("#login-form").get(0).reset();
});

login = () => {
    let credentials = {
        email: document.querySelector("#email-login").value,
        password: document.querySelector("#password-login").value
    }
    $.post(Constants.API_BASE_URL + 'login.php', credentials, (response) => {
        localStorage.setItem("user", JSON.stringify(JSON.parse(response).data));
        $("#login-form").get(0).reset();
        var errorMessage = document.querySelector("#invalid-login-message");
        if (errorMessage)
            errorMessage.style.display = "none"
        window.location.hash = "#home-page"; 
    }).fail(() => {
        var errorMessage = document.querySelector("#invalid-login-message");
        if (errorMessage)
            errorMessage.style.display = "block" 
    })
}