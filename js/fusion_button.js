document.getElementById('bmount').setAttribute("disabled","");
function bascule(id)
{
   if (document.getElementById(id).disabled == true)
   {
      document.getElementById(id).removeAttribute("disabled");
      document.getElementById(id).style.cursor = "pointer";
   }
   else
   document.getElementById(id).style.cursor = "pointer";
}
