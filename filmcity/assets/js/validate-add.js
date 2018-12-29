// get form
var addForm = document.getElementById('add-post');

addForm.addEventListener('submit', function(e) {
  var title       = this.title;
  var category    = this.category;
  var description = this.description;
  var rating      = this.rating;

  // remove all error spans
  var errorBlocks = this.querySelectorAll('span.error');
  errorBlocks.forEach(function(block) {
    block.remove();
  });

  // remove error classes from inputs
  var errorInputs = this.querySelectorAll('input.error');
  errorInputs.forEach(function(input) {
    input.classList.remove('error');
  });

  // create errror message span
  var fieldRequired = document.createElement('span');
  fieldRequired.className = 'error';
  fieldRequired.innerHTML = 'Toto pole je povinné.';

  // if title is empty
  if ( title.value == '' ) {
    e.preventDefault();
    title.classList.add('error');
    title.parentNode.appendChild(fieldRequired.cloneNode(true));
  }

  // if category is empty
  if ( category.value == '' ) {
    e.preventDefault();
    category.classList.add('error');
    category.parentNode.appendChild(fieldRequired.cloneNode(true));
  }

  // if decsription is too short
  if ( description.value.length < 10 ) {
    e.preventDefault();
    description.classList.add('error');
    var tmp = fieldRequired.cloneNode(true);
    tmp.innerHTML = "Popis musí obsahovat alespoň 10 znaků.";
    description.parentNode.appendChild(tmp);
  }

  // if no rating radio button is checked
  if ( rating[0].checked == false && rating[1].checked == false && rating[2].checked == false && rating[3].checked == false && rating[4].checked == false ) {
    e.preventDefault();
    rating[0].classList.add('error');
    rating[0].parentNode.appendChild(fieldRequired.cloneNode(true));
  }
});
