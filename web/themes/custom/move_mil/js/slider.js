(function($) {
  $(document).ready(function(){

  $( '.show-all-steps' ).on( "click", function() {
    if ($('#carousel-gallery').hasClass('slick-initialized')) {
      $('.move-mil--tutorial__steps').slick('unslick');
    } else {
      $('.move-mil--tutorial__steps').slick({
        appendArrows: '.carousel-gallery-nav__items',
      });
    }
  });

  $('.carousel-gallery__content').slick({
    infinite: false,
    speed: 300,
    slidesToShow: 1,
    slidesToScroll: 1,
    adaptiveHeight: true,
    arrows: true,
    appendArrows: '.carousel-gallery-nav__items',
    focusOnChange: true,
    swipeToSlide: true,
  });
});
})(jQuery);
