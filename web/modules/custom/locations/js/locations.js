window.onload = function() {

  jQuery("#edit-actions-submit").click(function() {
    var checkBoxes = document.getElementsByClassName("form-checkbox");
    var message = [];

    Array.prototype.forEach.call(checkBoxes, (cb) => {
      if (cb.checked) {
        message.push(" " + cb.id);
      }
    })

    if (message != "") {
      if (!confirm(message + " table(s) will be permanently deleted \nAre you sure you want to perform this operation?")) {
        event.preventDefault();
      }
    }
  });
}

