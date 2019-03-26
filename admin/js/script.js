$( document ).ready(function()
{

  // Alert
  //alert( "ready!" );

  $(".delete").click(function(){
    if(confirm( "Voulez-vous vraiment supprimer l'utilisateur ?" ))
    {
      return true; // Code à éxécuter si le l'utilisateur clique sur "OK"
    } else {
      return false; // Code à éxécuter si l'utilisateur clique sur "Annuler"
    }
  });

});
