module.exports = function (chromy, scenario) {
  var hoverSelector = scenario.hoverSelector;
  var clickSelector = scenario.clickSelector;
  var postInteractionWait = scenario.postInteractionWait; // selector [str] | ms [int]
  var path = scenario.label;

  // hover events
  if (hoverSelector) {
    chromy
      .wait(hoverSelector)
      .rect(hoverSelector)
      .result(function (rect) {
        chromy.mouseMoved(rect.left, rect.top);
      });
  }

  // custom for tutorials
  var tutorialpaths = ['/tutorials', '/tutorials/returning-user-login', '/tutorials/create-a-shipment', '/tutorials/create-a-ppm-shipment', '/tutorials/dual-military-mil-to-mil-move', '/tutorials/cancel-a-shipment', '/tutorials/file-a-claim'];

  if (tutorialpaths.indexOf(path) >= 0) {
    chromy
      .wait('.show-all-steps')
      .click('.show-all-steps');
  }

  // custom for menu
  var menupath = ['/node/65']; // using this alternate node of the homepage to target our menu

  if (menupath.indexOf(path) >= 0) {
    chromy
      .wait('.usa-menu-btn')
      .click('.usa-menu-btn')
      .click('.usa-accordion-button');
  }

  // custom for faqs
  var faqpath = ['/faqs'];

  if (faqpath.indexOf(path) >= 0) {

    chromy
      .wait('.view-faqs')
      .evaluate(() => {
        return document.querySelectorAll('.faq-accordion .usa-accordion-button[aria-expanded=false]')
      })
      .result(() => {
        chromy
        .click('.faq-accordion .usa-accordion-button')
      })
  }

    // click events
  if (clickSelector) {
    chromy
      .wait(clickSelector)
      .click(clickSelector);
  }

  // waits
  if (postInteractionWait) {
    chromy.wait(postInteractionWait);
  }
};
