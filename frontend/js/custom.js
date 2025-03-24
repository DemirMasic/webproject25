$(document).ready(function () {
    $("main#spapp > section").height($(document).height() - 60);
  
    var app = $.spapp({defaultPage: "#home", pageNotFound: "error_404" }); 
    
    
    
    app.route({ view: "carView", load: "carView.html" });
    app.route({ view: "home", load: "home.html" });
    app.route({ view: "listings", load: "listings.html" });
    app.route({ view: "profileView", load: "profileView.html" });
    app.route({ view: "publishCar", load: "publishCar.html" });
    app.route({ view: "login", load: "login.html" });
    app.route({ view: "register", load: "register.html" });
    app.route({ view: "savedListings", load: "savedListings.html" });
    app.route({ view: "messages", load: "messages.html" });
    
    
    app.run();
  });