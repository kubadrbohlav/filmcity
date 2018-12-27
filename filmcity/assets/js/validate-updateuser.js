var addForm = document.getElementById('update-user-form');

addForm.addEventListener('submit', function(e) {
  var name       = this.name;
  var surname    = this.surname;

  var errorBlocks = this.querySelectorAll('span.error');
  errorBlocks.forEach(function(block) {
    block.remove();
  });

  var errorInputs = this.querySelectorAll('input.error');
  errorInputs.forEach(function(input) {
    input.classList.remove('error');
  });

  var fieldRequired = document.createElement('span');
  fieldRequired.className = 'error';
  fieldRequired.innerHTML = 'Toto pole je povinné.';

  if ( name.value == '' ) {
    e.preventDefault();
    name.classList.add('error');
    name.parentNode.appendChild(fieldRequired.cloneNode(true));
  }

  if ( surname.value == '' ) {
    e.preventDefault();
    surname.classList.add('error');
    surname.parentNode.appendChild(fieldRequired.cloneNode(true));
  }
});
