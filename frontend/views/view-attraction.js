$(document).ready(function(){
    handleVisibilityRole();
    getAttraction();
});

getAttraction=()=>{
    var urlParams = new URLSearchParams(window.location.search);
    let id= urlParams.get('id');

    $.ajax({url: Constants.get_api_base_url() + `attractions/one/${id}`,
    type: "GET",
    beforeSend: function (xhr) {
        if (Utils.get_from_localstorage("user")) {
          xhr.setRequestHeader(
            "Authentication",
            Utils.get_from_localstorage("user")
          );
        }
      },
    success: (response)=>{
        response=response.data;
        document.getElementById("attraction-img").src = response.image_url;
        $("#attraction-title").text(response.name);
        $("#attraction-description").text(response.description);
        }});

    
}