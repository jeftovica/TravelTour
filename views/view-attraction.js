$(document).ready(function(){
    getAttraction();
});

getAttraction=()=>{
    var urlParams = new URLSearchParams(window.location.search);
    let id= urlParams.get('id');

    $.get(`./data/view-attraction-${id}.json`,(response)=>{
        console.log(response);
        $(".attraction-img").attr("src", response.image);
        $("#attraction-title").text(response.name);
        $("#attraction-description").text(response.description);
        });

    
}