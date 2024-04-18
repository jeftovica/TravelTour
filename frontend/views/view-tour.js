$('.carousel').carousel()

$(document).ready(function(){
    getTour();
    handleVisibilityRole();
});
getTour=()=>{
    var urlParams = new URLSearchParams(window.location.search);
    let id= urlParams.get('id');

    $.get(`./data/view-tour-${id}.json`,(response)=>{
        $(".tour-img").attr("src", response.image);
        $("#tour-title").text(response.name);
        $("#tour-description").text(response.description);
        $("#tour-date").text(response.date);
        $("#tour-price").text(`${response.price}BAM`);

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
            <td>${reservation.number}</td>
          </tr>`;
          i+=1;
        })

        response.attractions.map(attraction=>{
            toursHtml+=`<div class="card px-0" style="width: 18rem;">
            <img class="card-img-top" src="${attraction.image}" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">${attraction.name}</h5>
                <p class="card-text">${attraction.description}</p>
                <a href="?id=${attraction.id}#view-attraction" class="btn" style="background-color: #84b870; color: white;">View more</a>
            </div>
        </div>`;
        if (isFirst){
          carouselHtml+=`<div class="carousel-item active">
          <img src="${attraction.image}" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5>${attraction.name}</h5>
          </div>
        </div>`;
        isFirst=false;}
        else{
            carouselHtml+=`<div class="carousel-item">
          <img src="${attraction.image}" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5>${attraction.name}</h5>
          </div>
        </div>`;
        }
        });
        $("#tours-attractions").html(toursHtml);
        $("#carouselExampleCaptions .carousel-inner").html(carouselHtml);
        $("#reservation-table tbody").html(reservationsHtml);
        });

    
}