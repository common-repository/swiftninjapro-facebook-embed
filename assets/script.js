var swiftninjaprofacebook_facebook = document.getElementsByClassName("facebook");
var swiftninjaprofacebook_container = document.getElementsByClassName("facebook-container");
function swiftninjaprofacebook_fixFacebookWidth(){
  for(var i = 0; i < swiftninjaprofacebook_facebook.length; i++){
    var facebook = swiftninjaprofacebook_facebook[i];
    var container = swiftninjaprofacebook_container[i];
    
    var cWidth = container.offsetWidth;
    var newZoom = (cWidth/500);
    
    swiftninjaprofacebook_setZoom(i, newZoom);
    
    if(cWidth > facebook.offsetHeight+20 || cWidth < facebook.offsetHeight-20){
      if(newZoom >= 1){
        swiftninjaprofacebook_facebook[i].height = cWidth;
        swiftninjaprofacebook_container[i].style.height = '100%';
      }else{
        swiftninjaprofacebook_facebook[i].height = cWidth*10;
        var newContainerHeight = (newZoom*400)+(newZoom*100);
        swiftninjaprofacebook_container[i].style.height = newContainerHeight+'px';
      }
    }
  }
}

function swiftninjaprofacebook_setZoom(i, zoom){
  swiftninjaprofacebook_facebook[i].setAttribute("style", "-ms-zoom: "+zoom+"");
  swiftninjaprofacebook_facebook[i].setAttribute("style", "-moz-transform: scale("+zoom+")");
  swiftninjaprofacebook_facebook[i].setAttribute("style", "-o-transform: scale("+zoom+")");
  swiftninjaprofacebook_facebook[i].setAttribute("style", "-webkit-transform: scale("+zoom+")");
}

setInterval(function(){
  swiftninjaprofacebook_fixFacebookWidth();
}, 50);