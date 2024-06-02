var Constants = {

    get_api_base_url: function () {
        if (location.hostname == "localhost") {
          return "http://localhost:80/TravelTour/backend/";
        } else {
          return "http://localhost:80/TravelTour/backend/";
        }
      },

    //API_BASE_URL: 'http://localhost/TravelTour/backend/'
};