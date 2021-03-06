// get form
var loginForm = document.getElementById('login-form');

loginForm.addEventListener('submit', function(e) {
  var email   = this.email;
  var passwd  = this.passwd;

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

  // email pattern
  var patt = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;

  // if email is empty
  if (email.value == '') {
    e.preventDefault();
    email.classList.add('error');
    email.parentNode.appendChild(fieldRequired.cloneNode(true));
  }

  // if email does not match pattern
  else if (!patt.test(email.value)) {
    e.preventDefault();
    email.classList.add('error');
    var tmp = document.createElement('span');
    tmp.classList.add('error');
    tmp.innerHTML = 'Zadejte platný email.';
    email.parentNode.appendChild(tmp);
  }
  else {}

  // if password if empty
  if ( passwd.value == '' ) {
    e.preventDefault();
    passwd.classList.add('error');
    passwd.parentNode.appendChild(fieldRequired.cloneNode(true));
  }
});
