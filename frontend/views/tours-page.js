$(document).ready(function () {
  getTours();
  handleVisibilityRole();
  getAttractions();
  $("#add-tour-form").get(0).reset();
  $('.js-example-basic-multiple').select2({
    dropdownParent: $('#add-tour-form .modal-body #choice-div'),
    dropdownAutoWidth: true,
    width: "100%",
    padding: "0px"
  });
});

let deletionId = -1;
let editId = -1;
let isEditTour = false;
let allAttractons = []
getTours = () => {
  $.ajax({url: Constants.API_BASE_URL + 'tours',
  type: "GET",
  beforeSend: function (xhr) {
    if (Utils.get_from_localstorage("user")) {
      xhr.setRequestHeader(
        "Authentication",
        Utils.get_from_localstorage("user")
      );
    }
  },
  success: (response) => {
    let searchText = document.querySelector('#search-tour').value;
    if (searchText != "") {
      response.data = response.data.filter(function (tour) {
        return tour.name.toLowerCase().includes(searchText.toLowerCase());
      });
    }
    let toursHtml = '';
    response.data.map(tour => {
      toursHtml += `<div class="card px-0" style="width: 18rem; height: 20rem;">
            <img class="card-img-top" src="${tour.image_url}" alt="Card image cap">
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
              <p class="card-text mt-3 mb-3">${tour.start_date} - ${tour.end_date}</p>
              <div class="container w-100">
                <div class="row d-flex flex-row justify-content-between">
                  <div class="col d-flex justify-content-start px-0 ">
                    <a href="?id=${tour.id}#view-tour" class="btn" style="background-color: #84b870; color: white;">View more</a>
                  </div>
                  <div class="col d-flex justify-content-end px-0">
                    <button class="btn trash-btn admin-only" onclick="setDeletionId(${tour.id})" data-bs-toggle="modal" data-bs-target="#tourModalDelete"><i
                        class="fa fa-trash"></i></button>
                    <button class="btn edit-btn admin-only" onclick="setEditData(${tour.id})" data-bs-toggle="modal" data-bs-target="#tourModal"><i
                        class="fa fa-edit"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>`;
    });
    $("#tours-container").html(toursHtml);
    handleVisibilityRole();
  }});
};
setDeletionId = (id) => {
  deletionId = id;
}
setAddTour = () => {
  isEditTour = false;
}
sendTour = () => {
  if (isEditTour) {
    console.log("edit")
    editTour();
  }
  else {
    console.log("add");
    addTour();
  }
}
editTour = () => {
  let attractions = []
  $('#multiple-select').select2('data').forEach((val) => { attractions.push(val['id']) })
  let name = document.querySelector("#name").value;
  let description = document.querySelector('#description').value;
  let date1 = document.querySelector("#formDate1").value;
  let date2 = document.querySelector("#formDate2").value;
  let bam = document.querySelector("#valute").value;
  let editedTour = {id:editId, "name": name, "description": description, "startDate": date1, "endDate": date2, "price": bam, "attractions": attractions }
  $.ajax({
    url: Constants.API_BASE_URL + `tours/edit`,
    type: 'PUT',
    beforeSend: function (xhr) {
      if (Utils.get_from_localstorage("user")) {
        xhr.setRequestHeader(
          "Authentication",
          Utils.get_from_localstorage("user")
        );
      }
    },
    data: JSON.stringify(editedTour),
    contentType: 'application/json',
    success: function (result) {
      $("#add-tour-form").get(0).reset();
      location.reload();
    }
  });
}
setEditData = (id) => {
  isEditTour = true;
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
  success: (response) => {
    console.log(response);
    response=response.data;
    document.querySelector("#name").value = response.name;
    document.querySelector('#description').value = response.description;
    document.querySelector("#formDate1").value = response.start_date;
    document.querySelector("#formDate2").value = response.end_date;
    document.querySelector("#valute").value = response.price;
    editId = id;
    let selectedAttractions = [];
    response.attractions.forEach((attr) => {
        selectedAttractions.push(attr.id)
    })
    console.log(selectedAttractions)
    $('#multiple-select').val(selectedAttractions).change()
  }})
}

getAttractions = () => {
  $.ajax({url: Constants.API_BASE_URL + 'attractions',
  type: "GET",
  beforeSend: function (xhr) {
    if (Utils.get_from_localstorage("user")) {
      xhr.setRequestHeader(
        "Authentication",
        Utils.get_from_localstorage("user")
      );
    }
  },
  success: (response) => {
    allAttractons = response.data;
    let attractionsHtml = '';
    response.data.map(attraction => {
      attractionsHtml += `<option value="${attraction.id}">${attraction.name}</option>`;
    });
    console.log(response);
    $("#multiple-select").html(attractionsHtml);
    handleVisibilityRole();
  }});
};

addTour = () => {
  let attractions = []
  $('#multiple-select').select2('data').forEach((val) => { attractions.push(val['id']) })
  let name = document.querySelector("#name").value;
  let description = document.querySelector('#description').value;
  let image = './assets/1.jpg';
  let date1 = document.querySelector("#formDate1").value;
  let date2 = document.querySelector("#formDate2").value;
  let bam = document.querySelector("#valute").value;
  let newTour = { "name": name, "description": description, "image": image, "startDate": date1, "endDate": date2, "price": bam, "attractions": attractions }
  $.ajax({
    url: Constants.API_BASE_URL + 'tours/add', data: JSON.stringify(newTour), contentType: 'application/json',
    type: "POST",
    beforeSend: function (xhr) {
      if (Utils.get_from_localstorage("user")) {
        xhr.setRequestHeader(
          "Authentication",
          Utils.get_from_localstorage("user")
        );
      }
    },
    success: (response) => {
      $("#add-tour-form").get(0).reset();
      alert("Adding successful!");
      location.reload();
    }
  });
}
deleteTour = () => {
  $.ajax({
    url: Constants.API_BASE_URL + `tours/delete/${deletionId}`,
    type: 'DELETE',
    beforeSend: function (xhr) {
      if (Utils.get_from_localstorage("user")) {
        xhr.setRequestHeader(
          "Authentication",
          Utils.get_from_localstorage("user")
        );
      }
    },
    success: function (result) {
      $('#tourModalDelete').modal('hide');
      location.reload();
    }
  });
}

cancelAction=()=>{
  $("#add-tour-form").get(0).reset();
}
