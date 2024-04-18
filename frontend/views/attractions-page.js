$(document).ready(function(){
    getAttractions();
    handleVisibilityRole();
    $("#add-attraction-form").get(0).reset();
});
getAttractions=()=>{
    $.get("./data/attractions.json",(response)=>{
      let searchText=document.querySelector('#search-attraction').value;
      if(searchText!=""){
        response.data = response.data.filter(function(attraction) {
          return attraction.name.toLowerCase().includes(searchText.toLowerCase());
      });
      }

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
                    <button class="btn trash-btn admin-only" data-bs-toggle="modal" data-bs-target="#attractionModalDelete"><i
                        class="fa fa-trash"></i></button>
                    <button class="btn edit-btn admin-only" data-bs-toggle="modal" data-bs-target="#attractionModal"><i
                        class="fa fa-edit"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>`;
        });
        $("#attractions-container").html(attractionsHtml);
        handleVisibilityRole();
    });
};

addAttraction=()=>{
  let name = document.querySelector("#name").value;
  let description= document.querySelector('#description').value;
  let image= document.querySelector("#formFile").value;
  let newAttraction={"name": name,"description":description,"image":image}
    console.log(newAttraction);
    $("#add-attraction-form").get(0).reset();
    alert("Adding successful!");
}
