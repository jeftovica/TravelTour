$(document).ready(function () {

  var app = $.spapp({
    pageNotFound: 'error_404',
    defaultView: "#home-page",
    templateDir: "./views/"
  }); // initialize

  // define routes

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
function hideUserBased() {
  $('#navbar').hide();
  $('#footer').hide();
  console.log("AAAAAAAAAAAAAA")

}
function showUserBased() {
  $('#navbar').show();
  $('#footer').show();
  console.log("BBBBBBBBBBBBBBBBBBBB")

}