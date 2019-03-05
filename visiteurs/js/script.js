$( document ).ready(function() {

// https://api.jquery.com/category/effects/
$( ".toggle" ).click(function() {
 $(this).parent().next().slideToggle( "slow", function() {
  // Animation complete.
 });
   if($(this).html() == "˅")
   {
     $(this).html("˄")  ;
   } else {
     $(this).html("˅") ;
   }
});
});
