$("#rate-form").submit(function(e) {
  e.preventDefault();

  // get form
  var form = $(this);

  // remove all error spans
  form.find('span.error').remove();

  // get checked radio button
  var rating =form.find('input[name=rating]:checked').val();

  // if no radio button is checked
  if (!rating) {
    form.append('<span class="error">Zvolte známku.</span>');
  }
  // if no errors, send data via AJAX
  else {
    var url = form.data('path');
    $.ajax({
       type: "POST",
       url: url,
       data: form.serialize(),
       success: function(data) {
         form.after('<div class="already-voted"><p>Tento film jste již hodnotil/a.</p></div>');
         form.prev().remove();
         form.remove();
         location.href=location.href;
       },
       error: function(data) {
         form.append('<div class="already-voted"><span class="error">Chyba databáze.</span></div>');
       }
    });
  }

});
