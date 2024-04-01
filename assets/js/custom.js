$(document).ready(function () {
  handleChangePage();
  handleVisibilityRole();
  setUsersName();
  var app = $.spapp({
    pageNotFound: 'error_404',
    defaultView: "#home-page",
    templateDir: "./views/"
  });

  app.route({
    view: 'home-page', load: 'home-page.html',
  });
  app.route({
    view: 'attractions-page', load: 'attractions-page.html',
  });
  app.route({
    view: 'my-tours-page', load: 'my-tours-page.html',
  });
  app.route({
    view: 'tours-page', load: 'tours-page.html',
  });
  app.route({
    view: 'view-attraction', load: 'view-attraction.html',
  });
  app.route({
    view: 'view-tour', load: 'view-tour.html',
  });
  app.route({
    view: 'register-page', load: 'register-page.html'
  });
  app.route({
    view: 'login-page', load: 'login-page.html'
  });

  app.run();


});

handleChangePage = () => {
  var hashFragment = window.location.hash;
  var nav = document.querySelector('nav');
  var footer = document.querySelector('footer');
  var body = document.querySelector('body');
  if (hashFragment === '#register-page' || hashFragment === '#login-page') {
    if (nav) {
      nav.style.display = 'none';
    }
    if (footer) {
      footer.style.display = 'none';
    }
    if (body) {
      body.style.backgroundColor = '#84b870'
    }
  } else {
    if (nav) {
      nav.style.display = 'block';
    }
    if (footer) {
      footer.style.display = 'block';
    }
    if (body) {
      body.style.backgroundColor = 'white';
    }
  }
}

logout = () => {
  localStorage.removeItem("user");
  window.location.hash = "#login-page";
}
setUsersName = () => {
  let user = JSON.parse(localStorage.getItem("user"));
  if (user)
    $("#current-user").text(user.name + " " + user.surname);
}

handleVisibilityRole = () => {
  let user = JSON.parse(localStorage.getItem("user"));
  if (user == null) {
    if (window.location.hash != "#login-page" && window.location.hash != "#register-page")
      window.location.hash = "#login-page";
  }
  if (user) {
    if (user.role === "admin") {
      let adminElements = document.querySelectorAll(".admin-only");
      adminElements.forEach((element) => {
        element.style.display = "block";
      })
      let userElements = document.querySelectorAll(".user-only");
      userElements.forEach((element) => {
        element.style.display = "none";
      })
    }
    if (user.role === "user") {
      let adminElements = document.querySelectorAll(".admin-only");
      adminElements.forEach((element) => {
        element.style.display = "none";
      })
      let userElements = document.querySelectorAll(".user-only");
      userElements.forEach((element) => {
        element.style.display = "block";
      })
    }
  }
}

$(window).on('hashchange', function (e) {
  handleChangePage();
  handleVisibilityRole();
  setUsersName();
});
