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

  // function show()
  // {
  //  var elem = document.getElementById('show');
  //  // var attVal = elem.getAttribute('type');
  //  var attVal=elem.type;
  //
  //  var elem2 = document.getElementById('eye');
  //
  //
  //  if(attVal == 'password')
  //  {
  //    elem.setAttribute('type', 'text');
  //    //elem2.setAttribute('class', 'far fa-eye-slash');
  //    elem2.className = "far fa-eye-slash";
  //  } else {
  //    elem.setAttribute('type', 'password');
  //    elem2.setAttribute('class', 'far fa-eye');
  //  }
  // }

  $(".far").click(function(){
    // TODO
  });

});
