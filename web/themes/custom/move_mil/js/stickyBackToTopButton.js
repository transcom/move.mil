(function($) {
  var checkPage = function() {
    var button = $('.back-to-top');

    if(button.length) {
      var position = getPosition(button);
      toggleSticky(button, position);
      $(window).resize(function(){
        position = getPosition(button);
      });
      $(window).on('scroll', function() {
        toggleSticky(button, position);
      });
    }
  };

  var getPosition = function(stickyButton){
    var pos;
    if($('body').hasClass('user-logged-in') && $('body').hasClass('toolbar-fixed')) {
      if($('body').hasClass('toolbar-horizontal') && $('body').hasClass('toolbar-tray-open')) {
        pos = stickyButton.offset().top - 89;
      }
      else {
        pos = stickyButton.offset().top - 49;
      }
    }
    else {
      pos = stickyButton.offset().top - 10;
    }

   return pos;
  };

  var toggleSticky = function(stickyButton, position) {
    var scrollAmount = $(this).scrollTop();
    var buttonLink = $(stickyButton).children('a');

    if(scrollAmount > position && !stickyButton.hasClass('sticky')) {
      stickyButton.addClass('sticky');
    }
    else if (scrollAmount < position && stickyButton.hasClass('sticky')){
      stickyButton.removeClass('sticky');
    }
  };

  $(window).on('load', function() {
    checkPage();
  });
})(jQuery);
