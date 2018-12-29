// get form
var addForm = document.getElementById('update-user-form');

addForm.addEventListener('submit', function(e) {
  var name       = this.name;
  var surname    = this.surname;

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

  // if name is empty
  if ( name.value == '' ) {
    e.preventDefault();
    name.classList.add('error');
    name.parentNode.appendChild(fieldRequired.cloneNode(true));
  }

  // if surname is empty
  if ( surname.value == '' ) {
    e.preventDefault();
    surname.classList.add('error');
    surname.parentNode.appendChild(fieldRequired.cloneNode(true));
  }
});
