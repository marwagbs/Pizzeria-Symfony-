$(function() {
  $.get("getCountCart", function(data) {
    $("#cart").text(data);
    });
    $(".add").click(function() {
      let id = $(this).attr("id-product");
      
      //AJAX
      $.get("addToCart/" + id, function() {
        //mettre a jour le panier
        $.get("getCountCart", function(data) {
        $("#cart").text(data);
        });
    });

    });
 
  
  })