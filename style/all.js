$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
  });
  
  $(document).on("click", ".action-buttons .dropdown-menu", function(e){
    e.stopPropagation();
  });