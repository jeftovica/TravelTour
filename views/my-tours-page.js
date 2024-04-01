$(document).ready(function () {
  getTours();
});
getTours = () => {
  $.get("./data/tours.json", (response) => {


    let myTours = response.data.slice(0, 2);

    let searchText = document.querySelector('#search-my-tour').value;
    if (searchText != "") {
      myTours = myTours.filter(function (tour) {
        return tour.name.toLowerCase().includes(searchText.toLowerCase());
      });
    }

    let toursHtml = '';
    myTours.map(tour => {
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
                <button class="btn cancel-btn" data-bs-toggle="modal" data-bs-target="#tourModalCancel">Cancel Tour</button>
              </div>
                </div>
              </div>
            </div>
          </div>`;
    });
    $("#my-tours-container").html(toursHtml);
  });
};
