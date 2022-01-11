$(function() {
  $.get("getCountCart", function(data) {
    $("#cart").text(data);
    });
    $(".remove").click(function() {
      let id = $(this).attr("id-product");
      
      //AJAX
      $.get("remove/" + id, function() {
        //mettre a jour le panier
        $.get("getCountCart", function(data) {
        $("#cart").text(data);
        });
    });

    });
 
  
  })