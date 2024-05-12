$(document).ready(function () {
    $("#login-form").get(0).reset();
});
$("#login-btn").on("click", function(event){
    event.preventDefault();
    login();
 });
login = () => {
    let credentials = {
        email: document.querySelector("#email-login").value,
        password: document.querySelector("#password-login").value
    }
    
    $.ajax({url: Constants.API_BASE_URL + 'users',
     data: JSON.stringify(credentials),
     contentType: "application/json",
     type: "POST",
     success: (response) => {
        
        localStorage.setItem("user", JSON.stringify(response.data));
        $("#login-form").get(0).reset();
        var errorMessage = document.querySelector("#invalid-login-message");
        if (errorMessage)
            errorMessage.style.display = "none"
        window.location.hash = "#home-page"; 
    }}).fail(() => {
        var errorMessage = document.querySelector("#invalid-login-message");
        if (errorMessage)
            errorMessage.style.display = "block" 
    })
}