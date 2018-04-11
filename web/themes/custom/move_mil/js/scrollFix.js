(function($) {
  var offsetJumpScroll = function() {
    var jumpLink = $('.jump-link a');
    jumpLink.click(function(e) {
      e.preventDefault();
      var hrefRaw = $(this).attr('href');
      href = hrefRaw.substring(1, hrefRaw.length);
      var target = $("div[id*='" + href + "']");
      var offset = $(target).offset();
      offset.top -= 200;
      $('html, body').animate({
        scrollTop: offset.top,
        scrollLeft: 0
      }, 1000);
      return;
    });
  };

  $(window).on('load', function() {
    offsetJumpScroll();
  });
})(jQuery);
