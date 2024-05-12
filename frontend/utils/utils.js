var Utils = {
    get_from_localstorage: function(key) {
      return JSON.parse(window.localStorage.getItem(key));
    }
}