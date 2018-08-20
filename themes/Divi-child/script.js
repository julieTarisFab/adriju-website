// Accordeon bio

function readMore(){
  var accordeon = document.getElementById('biographie')
  accordeon.classList.toggle("compact")
}

(function($) {

  $('.focus-carroussel').owlCarousel({
    items:1,
    loop:false,
    // center:true,
    nav:true,
    touchDrag: false,
    margin:10,
    navText: ["<i class='fas fa-caret-left'></i>","<i class='fas fa-caret-right'></i>"],
    // navClass: ["mfp-arrow mfp-arrow-left mfp-prevent-close" , "mfp-arrow mfp-arrow-right mfp-prevent-close"],
    URLhashListener:true,
    autoplayHoverPause:true,
    startPosition: 'URLHash'
  });

})( jQuery );
