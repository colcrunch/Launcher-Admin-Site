function killmenow(form, currentPass, newPass, confirmPass) {

  if (newPass.value.length < 6) {
      alert('Passwords must be at least 6 characters long.  Please try again');
      form.newPass.focus();
      return false;
  }

  if (newPass.value != confirmPass.value) {
      alert('Your new password and confirmation do not match. Please try again');
      form.newPass.focus();
      return false;
  }
    // Create a new element input, this will be our hashed password field.
    var cp = document.createElement("input");


    // Add the new element to our form.
    form.appendChild(cp);
    cp.name = "cp";
    cp.type = "hidden";
    cp.value = hex_sha512(currentPass.value);

    var np= document.createElement("input");

    form.appendChild(np);
    np.name = "np";
    np.type = "hidden";
    np.value = hex_sha512(newPass.value);

    // Make sure the plaintext password doesn't get sent.
    currentPass.value = "";
    newPass.value = "";
    confirmPass.value = "";

    // Finally submit the form.
    form.submit();
    return true;

}
function killmelater(form, newPass, confirmPass) {

  if (newPass.value.length < 6) {
      alert('Passwords must be at least 6 characters long.  Please try again');
      form.newPass.focus();
      return false;
  }

  if (newPass.value != confirmPass.value) {
      alert('Your new password and confirmation do not match. Please try again');
      form.newPass.focus();
      return false;
  }
    // Create a new element input, this will be our hashed password field.
    var np= document.createElement("input");

    form.appendChild(np);
    np.name = "np";
    np.type = "hidden";
    np.value = hex_sha512(newPass.value);

    // Make sure the plaintext password doesn't get sent.
    newPass.value = "";
    confirmPass.value = "";

    // Finally submit the form.
    form.submit();
    return true;

}
