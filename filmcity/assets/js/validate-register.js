// get form
var registerForm = document.getElementById('signup-form');

registerForm.addEventListener('submit', function(e) {
  var forename  = this.forename;
  var surename  = this.surename;
  var email     = this.emailSignup;
  var passwd    = this.passwdSignup;
  var passwdA   = this.passwdAgain;

  // remove all error spans
  var errorBlocks = this.querySelectorAll('span.error, div.error');
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

  // if forename is empty
  if ( forename.value == '' ) {
    e.preventDefault();
    forename.classList.add('error');
    forename.parentNode.appendChild(fieldRequired.cloneNode(true));
  }

  // if surname is empty
  if ( surename.value == '' ) {
    e.preventDefault();
    surename.classList.add('error');
    surename.parentNode.appendChild(fieldRequired.cloneNode(true));
  }

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

  // password pattern
  patt = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/;

  // if password does not match pattern
  if (!patt.test(passwd.value)) {
    e.preventDefault();
    passwd.classList.add('error');
    var tmp = document.createElement('div');
    tmp.classList.add('error');
    tmp.innerHTML = '<span>Heslo musí obsahovat:</span><ul><li>Alespoň 6 znaků</li><li>Alespoň jeden znak [a-z]</li><li>Alespoň jeden znak [A-Z]</li><li>Alespoň jeden znak [0-9]</li></ul>';
    passwd.parentNode.appendChild(tmp);
    passwd.value = '';
    passwdA.value = '';
  }
  else {
    // if passwords do not match
    if (passwd.value != passwdA.value) {
      e.preventDefault();
      passwd.classList.add('error');
      passwdA.classList.add('error');
      var tmp = document.createElement('span');
      tmp.classList.add('error');
      tmp.innerHTML = 'Hesla se neshodují.';
      passwdA.parentNode.appendChild(tmp);
      passwd.value = '';
      passwdA.value = '';
    }
  }
});
