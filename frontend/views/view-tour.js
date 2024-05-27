$('.carousel').carousel()

$(document).ready(function(){
    getTour();
    handleVisibilityRole();
});
let isBooked=false;
getTour=()=>{
    var urlParams = new URLSearchParams(window.location.search);
    let id= urlParams.get('id');

    $.ajax({url: Constants.API_BASE_URL + `tours/one/${id}`,
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
        console.log(response);
        $(".tour-img").attr("src", response.image_url);
        $("#tour-title").text(response.name);
        $("#tour-description").text(response.description);
        $("#tour-date").text(response.start_date+" - "+response.end_date);
        $("#tour-price").text(`${response.price}BAM`);
      if(response.isBooked!=undefined){
        isBooked=response.isBooked;
        if(response.isBooked==true){
          $("#reserve-button").text("Cancel Tour");
          $("#modalLabel").text("Cancel Tour");
          $("#reserve-question").text("Are you sure you want to cancel tour?");
          $("#reserve-modal-button").text("Unbook");
        }else{
          $("#reserve-button").text("Book Tour");
          $("#modalLabel").text("Book Tour");
          $("#reserve-question").text("Are you sure you want to book tour?");
          $("#reserve-modal-button").text("Book");
        }
      }

        let toursHtml='';
        let carouselHtml='';
        let reservationsHtml='';
        let i=1;
        let isFirst=true;
        response.reservations.map(reservation=>{
            reservationsHtml+=`<tr>
            <th scope="row">${i}</th>
            <td>${reservation.name}</td>
            <td>${reservation.surname}</td>
            <td>${reservation.phone_number}</td>
          </tr>`;
          i+=1;
        })

        response.attractions.map(attraction=>{
            toursHtml+=`<div class="card px-0" style="width: 18rem;">
            <img class="card-img-top" src="${attraction.image_url}" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">${attraction.name}</h5>
                <p class="card-text">${attraction.description}</p>
                <a href="?id=${attraction.id}#view-attraction" class="btn" style="background-color: #84b870; color: white;">View more</a>
            </div>
        </div>`;
        if (isFirst){
          carouselHtml+=`<div id="carousel-item-id-${attraction.id}" class="carousel-item active">
          <img src="${attraction.image_url}" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5>${attraction.name}</h5>
          </div>
        </div>`;
        isFirst=false;}
        else{
            carouselHtml+=`<div id="carousel-item-id-${attraction.id}" class="carousel-item">
          <img src="${attraction.image_url}" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5>${attraction.name}</h5>
          </div>
        </div>`;
        }
        });
        $("#tours-attractions").html(toursHtml);
        $("#carouselExampleCaptions .carousel-inner").html(carouselHtml);
        $("#reservation-table tbody").html(reservationsHtml);
        }});

    
}
reserve=()=>{
  var urlParams = new URLSearchParams(window.location.search);
  let id= urlParams.get('id');
  if(isBooked==true){
    $.ajax({
      url: Constants.API_BASE_URL + `tours/reservation/${id}`,
      type: 'DELETE',
      beforeSend: function (xhr) {
        if (Utils.get_from_localstorage("user")) {
          xhr.setRequestHeader(
            "Authentication",
            Utils.get_from_localstorage("user")
          );
        }
      },
      contentType: 'application/json',
      success: function (result) {
        location.reload();
      }
    });
  }else{
    $.ajax({
      url: Constants.API_BASE_URL + `tours/reservation`,
      type: 'POST',
      beforeSend: function (xhr) {
        if (Utils.get_from_localstorage("user")) {
          xhr.setRequestHeader(
            "Authentication",
            Utils.get_from_localstorage("user")
          );
        }
      },
      data: JSON.stringify({tour_id:id}),
      contentType: 'application/json',
      success: function (result) {
        location.reload();
      }
    });
  }
  
}