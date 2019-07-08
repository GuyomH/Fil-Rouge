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


//https://developer.mozilla.org/fr/docs/Web/API/Document/cookie
var cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)lang\s*\=\s*([^;]*).*$)|^.*$/, "$1");
var path = window.location.pathname;
var page = path.split("/").pop();
if(page == "index.php" && cookieValue == "")
{
  alert('Cet applicatif est destiné à être visionné sur place avec un smartphone ou une phablette par les visiteurs de la galerie d\'art Grand Angle');
  document.cookie = "lang=fr";
}
