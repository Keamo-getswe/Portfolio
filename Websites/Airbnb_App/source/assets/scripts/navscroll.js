/*
 * Change navbar colour on scroll
 */
window.onscroll = function (){navChangeColor()};
var navTag = document.getElementById("myNav");

function navChangeColor (){
  //Console.log(document.body.scrollTop);
    if (document.body.scrollTop > 1000 || document.documentElement.scrollTop > 1000) {
    navTag.style.backgroundColor = "#000000";
    }
}
