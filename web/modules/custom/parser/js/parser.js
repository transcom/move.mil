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
      if (!confirm("Clearing the tables of:" + message + "\nAre you sure?")) {
        event.preventDefault();
      }
    }
  });
}

