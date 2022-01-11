// initialise le jQuery
$(function(){ 
    let cart=0;
    $(".add").click(function(){
        $("#cart").text(++cart);
})

});
