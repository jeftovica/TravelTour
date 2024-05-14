$(document).ready(function () {
  getAttractions();
  handleVisibilityRole();
  $("#add-attraction-form").get(0).reset();
});
let deletionIdAttraction = -1;
let editId=-1;
let isEditAttraction=false;
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
    let searchText = document.querySelector('#search-attraction').value;
    if (searchText != "") {
      response.data = response.data.filter(function (attraction) {
        return attraction.name.toLowerCase().includes(searchText.toLowerCase());
      });
    }

    let attractionsHtml = '';
    response.data.map(attraction => {
      attractionsHtml += `<div class="card px-0" style="width: 18rem;">
            <img class="card-img-top" src="${attraction.image_url}" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">${attraction.name}</h5>
              <p class="card-text">${attraction.description}</p>
              <div class="container w-100">
                <div class="row d-flex flex-row justify-content-between">
                  <div class="col d-flex justify-content-start px-0 ">
                    <a href="?id=${attraction.id}#view-attraction" class="btn" style="background-color: #84b870; color: white;">View more</a>
                  </div>
                  <div class="col d-flex justify-content-end px-0">
                    <button class="btn trash-btn admin-only" onclick="setDeletionId(${attraction.id})" data-bs-toggle="modal" data-bs-target="#attractionModalDelete"><i
                        class="fa fa-trash"></i></button>
                    <button class="btn edit-btn admin-only" onclick="setEditData(${attraction.id},'${attraction.name}','${attraction.description}')" data-bs-toggle="modal" data-bs-target="#attractionModal"><i
                        class="fa fa-edit"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>`;
    });
    $("#attractions-container").html(attractionsHtml);
    handleVisibilityRole();
  }});
};
setDeletionId = (id) => {
  deletionIdAttraction = id;
}
setAddAttraction=()=>{
  isEditAttraction=false;
}
sendAttraction=()=>{
  if (isEditAttraction){
    console.log("edit")
    editAttraction();
  }
  else{
    console.log("add");
    addAttraction();
  }
}
setEditData = (id,name,description) => {
  isEditAttraction=true;
  document.querySelector("#name").value=name;
  document.querySelector('#description').value=description;
  editId=id;
}
addAttraction = () => {
  let name = document.querySelector("#name").value;
  let description = document.querySelector('#description').value;
  let image = document.querySelector("#formFile").files[0];
  console.log(image);
  const formData = new FormData();
  formData.append("name",name);
  formData.append("description",description);
  formData.append("image",image);
  $.ajax({
    url: Constants.API_BASE_URL + 'attractions/add', data: formData,
    cache: false,
    contentType: false,
    processData: false,
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
      $("#add-attraction-form").get(0).reset();
      alert("Adding successful!");
      location.reload();
    }
  });
}
deleteAttraction = () => {
  $.ajax({
    url: Constants.API_BASE_URL + `attractions/delete/${deletionIdAttraction}`,
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
      $('#attractionModalDelete').modal('hide');
      location.reload();
    }
  });
}
cancelAction=()=>{
  $("#add-attraction-form").get(0).reset();
}

editAttraction = () => {
  let name = document.querySelector("#name").value;
  let description = document.querySelector('#description').value;
  let image = document.querySelector("#formFile").files[0];
  let formData= new FormData();
  formData.append("name",name);
  formData.append("id",editId);
  formData.append("description",description);
  if (image)
    formData.append("image",image);
  $.ajax({
    url: Constants.API_BASE_URL + `attractions/edit`,
    type: 'POST',
    beforeSend: function (xhr) {
      if (Utils.get_from_localstorage("user")) {
        xhr.setRequestHeader(
          "Authentication",
          Utils.get_from_localstorage("user")
        );
      }
    },
    cache: false,
    contentType: false,
    processData: false,
    data:formData,
    success: function (result) {
      $("#add-attraction-form").get(0).reset();
      location.reload();
    }
  });
}
