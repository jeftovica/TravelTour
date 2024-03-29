$(document).ready(function(){
    getAttractions();
});
getAttractions=()=>{
    $.get("./data/attractions.json",(response)=>{
        console.log(response.data)
        let attractionsHtml='';
        response.data.map(attraction=>{
            attractionsHtml+=`<div class="card px-0" style="width: 18rem;">
            <img class="card-img-top" src="${attraction.image}" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">${attraction.name}</h5>
              <p class="card-text">${attraction.description}</p>
              <div class="container w-100">
                <div class="row d-flex flex-row justify-content-between">
                  <div class="col d-flex justify-content-start px-0 ">
                    <a href="?id=${attraction.id}#view-attraction" class="btn" style="background-color: #84b870; color: white;">View more</a>
                  </div>
                  <div class="col d-flex justify-content-end px-0">
                    <button class="btn trash-btn" data-bs-toggle="modal" data-bs-target="#attractionModalDelete"><i
                        class="fa fa-trash"></i></button>
                    <button class="btn edit-btn" data-bs-toggle="modal" data-bs-target="#attractionModal"><i
                        class="fa fa-edit"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>`;
        });
        $("#attractions-container").html(attractionsHtml);
    });
};
