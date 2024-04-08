$(document).ready(function () {
  getTours();
  handleVisibilityRole();
  $("#add-tour-form").get(0).reset();
});
getTours = () => {
  $.get("./data/tours.json", (response) => {
    let searchText = document.querySelector('#search-tour').value;
    if (searchText != "") {
      response.data = response.data.filter(function (tour) {
        return tour.name.toLowerCase().includes(searchText.toLowerCase());
      });
    }
    let toursHtml = '';
    response.data.map(tour => {
      toursHtml += `<div class="card px-0" style="width: 18rem; height: 20rem;">
            <img class="card-img-top" src="${tour.image}" alt="Card image cap">
            <div class="card-body">
              <div class="container w-100">
                <div class="row d-flex flex-row justify-content-between">
                  <div class="col d-flex justify-content-start px-0  align-items-center">
                    <h5 class="card-title mb-0">${tour.name}</h5>
                  </div>
                  <div class="col d-flex justify-content-end px-0 align-items-center">
                    <p class="mb-0" style="font-weight: 600; font-size: 20px;">${tour.price}BAM</p>
                  </div>
                </div>
              </div>
              <p class="card-text mt-3 mb-3">${tour.date}</p>
              <div class="container w-100">
                <div class="row d-flex flex-row justify-content-between">
                  <div class="col d-flex justify-content-start px-0 ">
                    <a href="?id=${tour.id}#view-tour" class="btn" style="background-color: #84b870; color: white;">View more</a>
                  </div>
                  <div class="col d-flex justify-content-end px-0">
                    <button class="btn trash-btn admin-only" data-bs-toggle="modal" data-bs-target="#tourModalDelete"><i
                        class="fa fa-trash"></i></button>
                    <button class="btn edit-btn admin-only" data-bs-toggle="modal" data-bs-target="#tourModal"><i
                        class="fa fa-edit"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>`;
    });
    $("#tours-container").html(toursHtml);
    handleVisibilityRole();
  });
};

addTour = () => {
  let name = document.querySelector("#name").value;
  let description = document.querySelector('#description').value;
  let image = document.querySelector("#image").value;
  let date1 = document.querySelector("#formDate1").value;
  let date2 = document.querySelector("#formDate2").value;
  let bam = document.querySelector("#valute").value;
  let attractions = document.querySelector("#attractions").value;
  let newTour = { "name": name, "description": description, "image": image, "image": image, "formDate1": date1, "formDate2": date2, "bam": bam, "attractions": attractions }
  console.log(newTour);
  $("#add-tour-form").get(0).reset();
  alert("Adding successful!");
}

