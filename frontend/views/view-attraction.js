$(document).ready(function(){
    handleVisibilityRole();
    getAttraction();
});

getAttraction=()=>{
    var urlParams = new URLSearchParams(window.location.search);
    let id= urlParams.get('id');

    $.get(Constants.API_BASE_URL + `get_attraction.php?id=${id}`,(response)=>{
        response=JSON.parse(response).data;
        document.getElementById("attraction-img").src = response.image_url;
        $("#attraction-title").text(response.name);
        $("#attraction-description").text(response.description);
        });

    
}