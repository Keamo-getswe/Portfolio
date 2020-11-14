/**
 * Validates a registration form and returns true, if validation is successful
 * and returns false if the form is not successfully validated, meaning some of
 * the user inputs are invalid and need the user to re-submit after making the
 * necessary changes to the form.
 *
 * @param userType indicators whether the user is trying to register as a guest
 * or as a host (value is 0).
 *
 * @return Returns true if validation is successful and returns false if not.
 */
function validateRegistration() {
  /* obtain the form inputs to validate*/
  var location = document.forms["register"]["location"];
  var address = document.forms["register"]["address"];
  var title = document.forms["register"]["title"];
  var firstName = document.forms["register"]["firstname"];
  var lastName = document.forms["register"]["lastname"];
  var birthday = document.forms["register"]["birthday"];
  var mobilenum = document.forms["register"]["mobilenum"];
  var gender = document.forms["register"]["gender"];
  var email = document.forms["register"]["username"];
  var password1 = document.forms["register"]["password1"];
  var password2 = document.forms["register"]["password2"];
  var description = document.forms["register"]["description"];

  /* check each form item to check if its value is valid */
    if (!validateText(location.value)) {
      alert("Please enter a valid town/city");
      return false;
    }

    if (!validateText(address.value)) {
      alert("Please enter a valid town/city");
      return false;
    }

    if (isEmpty(title.value)) {
      alert("Please enter a title");
      return false;
    }

    if (!validateLetters(firstName.value)) {
      alert("Please enter a valid first name");
      return false;
    }

    if (!validateLetters(lastName.value)) {
      alert("Please enter a valid last name");
      return false;
    }

    if (isEmpty(birthday.value)) {
      alert("Please enter a birthday");
      return false;
    }

    if (isEmpty(gender.value)) {
      alert("Please enter a gender");
      return false;
    }

    if (!validateEmail(email.value)) {
      alert("Please enter a valid email");
      return false;
    }

    if (isEmpty(password1.value) || isEmpty(password2.value)) {
      alert ("Please enter a password");
      return false;
    } else if (!validateText(password1.value) && !validateText(password2.value)) {
        alert ("Please enter a valid password");
        return false;
    } else if (password1.value != password2.value) {
      alert ("Your passwords do not match");
      return false;
    } else if (!validatePassword(password.value)) {
      alert ("Your password does not meet the strength requirements of the website");
      return false;
    }

    if (!validateText(description)) {
      alert ("Please enter a valid description");
      return false;
    }

    if (!validateNumber(mobilenum.value)) {
      alert ("Please enter a valid mobile number");
      return false;
    }

    alert("Registration form submitted.");
    return true;
}

/**
 * Validates the login form and ensures that password and email inputs are valid
 * before checking
 *
 * @param check_point indicators which part of the login is being validated.
 *
 * @return Returns true if the validation is successful and false otherwise.
 */
function validateLogin(check_point) {
  /* need to check an ordinary login and password recovery option */
  if (check_point == 0) {
    /* ordinary login channel */
    var email = document.forms["login_form"]["username"];
    var password = document.forms["login_form"]["password"];

    if (!validateEmail(email.value)) {
      alert ("Please enter a valid email");
      return false;
    }

    if (password1.value == "" || password1.value ==null) {
      alert ("Please enter a password");
      return false;
    } else if (!validateText(password1.value)) {
      alert ("Please enter a valid password");
      return false;
    }
  } else if (check_point == 1) {
    /* checking if the user put in a valid email to do password recovery */
    var email = document.forms["forgot_pass"]["email"];

    if (!validateEmail(email.value)) {
      alert ("Please enter a valid email");
      return false;
    }
  }
}

/**
 * Validates the bookings page form to ensure that the user has filled it out
 * properly.
 *
 * @param form the form to be validated
 * @return Returns false if a filled in input is not valid.
 */
  function validateBookings(min_booking_days) {
    /* obtain the form inputs and then check them one by one */
    var dateArrival = document.forms["bookings_form"]["arriving"];
    var dateDeparture = document.forms["bookings_form"]["departing"];
    var adultsTotal = document.forms["bookings_form"]["number_of_adults"];
    var childrenTotal = document.forms["bookings_form"]["number_of_children"];
    var firstName =document.forms["bookings_form"]["firstname"];
    var lastName  =document.forms["bookings_form"]["lastname"];
    var email = document.forms["bookings_form"]["emailaddress"];
    var phoneNumber = document.forms["bookings_form"]["phonenumber"];
    var resAddress = document.forms["bookings_form"]["resAddress"];
    var expires = document.forms["bookings_form"]["expires"];
    var cardNumber = document.forms["bookings_form"]["card_number"];
    var csc = document.forms["bookings_form"]["CSC"];

    var date1 = new Date(dateArrival.value);
    var date2 = new Date(dateDeparture.value);
    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

    if (isEmpty(dateArrival.value)) {
      alert("Please enter an arrival date");
      return false;
    }

    if (isEmpty(dateDeparture.value)) {
      alert("Please enter a depature date");
      return false;
    }

    if (diffDays < min_booking_days) {
      var error_msg = "Please make sure your booking is for at least " + min_booking_days + " days.";
      alert(error_msg);
      return false;
    }

    if (isEmpty(adultsTotal.value)) {
      alert("Please enter the number of adults");
      return false;
    } else if (adultsTotal.value <= 0) {
      /* children cannot travel without at least one adult */
      alert("Need at least one adult to make a booking");
      return false;
    }

    if (isEmpty(childrenTotal.value)) {
      alert("Please enter the number of children");
      return false;
    }

    if (!validateLetters(firstName.value)) {
      alert("Please enter a first name");
      return false;
    }

    if (!validateLetters(lastName.value)) {
      alert("Please enter a valid last name");
      return false;
    }

    if (!validateEmail(email.value)) {
      alert("Please enter a valid email");
      return false;
    }

    if (!validateNumber(phoneNumber.value)) {
      alert("Please enter a valid phone number");
      return false;
    }

    if (!validateText(resAddress.value)) {
      alert("Please enter a valid address");
      return false;
    }

    if (!validateNumber(cardNumber.value)) {
      alert("Please enter a valid account number");
      return false;
    }

    if (!validateText(expires.value)) {
      alert("Please enter a valid branch code");
      return false;
    }

    if (!validateNumber(csc.value) && csc.value != 3) {
      alert("Please select a bank");
      return false;
    }

    if (confirm('Are you sure all the information is correct?')) {
      form.submit();
    } else {
      alert("Make necessary corrections");
    }
    return true;
  }

 /**
  * Validates the add unit page form to ensure that the user has filled it out
  * properly.
  *
  * @return Returns false if a filled in input is not valid.
  */
  function validateAddUnit() {
    var accommodationName = document.forms["add_unit_form"]["unit_name"];
    var town = document.forms["add_unit_form"]["city"];
    var address = document.forms["add_unit_form"]["address"];
    var coordinates = document.forms["add_unit_form"]["coor"];
    var accommodationType = document.forms["add_unit_form"]["unit_type"];
    var capacity = document.forms["add_unit_form"]["capacity"];
    var maxperroom = document.forms["add_unit_form"]["maxperroom"];
    var minbookingdays = document.forms["add_unit_form"]["minbookingdays"];
    var description = document.forms["add_unit_form"]["description"];
    var discounts = document.forms["add_unit_form"]["discounts"];
    var tarriff = document.forms["add_unit_form"]["Tarrif"];

    if (!validateText(accommodationName.value)) {
      alert("Please enter a valid accommodation name");
      return false;
    }

    if (!validateText(town.value)) {
      alert("Please enter a valid town");
      return false;
    }

    if (!validateText(address.value)) {
      alert("Please enter a valid address");
      return false;
    }

    if (!validateCoordinates(coordinates.value)) {
      alert("Please enter a valid coordinates");
      return false;
    }

    if (isEmpty(accommodationType.value)) {
      alert("Please enter an accommodation type");
      return false;
    }

    if (!validateNumber(capacity.value)) {
      alert("Please enter a valid capacity");
      return false;
    }

    if (!validateNumber(maxperroom.value)) {
      alert("Please enter a valid max per room value");
      return false;
    }

    if (!validateNumber(minbookingdays.value)) {
      alert("Please enter a valid minimum bookings value");
      return false;
    }

    if (!validateText(description.value)) {
      alert("Please enter a valid description");
      return false;
    }

    if (!validateText(discounts.value)) {
      alert("Please enter a valid discounts offering");
      return false;
    }

    if (!validateNumber(tarriff.value)) {
      alert("Please enter a valid tarrif offering");
      return false;
    }

    return true;
  }

/**
 * Validates the edit unit page form to ensure that the user has filled it out
 * properly.
 *
 * @return Returns false if a filled in input is not valid.
 */
  function validateEditUnit() {
    var accommodationName = document.forms["edit_unit_form"]["name"];
    var town = document.forms["edit_unit_form"]["city"];
    var address = document.forms["edit_unit_form"]["Address"];
    var accommodationType = document.forms["edit_unit_form"]["acctype"];
    var coordinates = document.forms["edit_unit_form"]["coor"];
    var capacity = document.forms["edit_unit_form"]["capacity"];
    var maxPerRoom = document.forms["edit_unit_form"]["maxperroom"];
    var minBookingDays = document.forms["edit_unit_form"]["minbookingdays"];
    var discounts = document.forms["edit_unit_form"]["discounts"];
    var description = document.forms["edit_unit_form"]["description"];
    var tarriff = document.forms["edit_unit_form"]["tarrif"];

    if (!validateText(accommodationName.value)) {
      alert("Please enter a valid accommodation name");
      return false;
    }

    if (!validateText(town.value)) {
      alert("Please enter a valid town");
      return false;
    }

    if (!validateText(address.value)) {
      alert("Please enter a valid address");
      return false;
    }

    if (isEmpty(accommodationType.value)) {
      alert("Please enter a accommodation type");
      return false;
    }

    if (!validateText(coordinates.value)) {
      alert("Please enter valid coordinates type");
      return false;
    }

    if (!validateNumber(capacity.value)) {
      alert("Please enter a valid capacity");
      return false;
    }

    if (!validateNumber(maxPerRoom.value)) {
      alert("Please enter a valid maximum per room");
      return false;
    }

    if (!validateNumber(minBookingDays.value)) {
      alert("Please enter a valid number of days for the minimum booking days");
      return false;
    } else if (minBookingDays.value < 2) {
      /* minimum booking days needs to be at least 2 days according to the
      company policy */
      alert("The minimum booking days needs to be at least 2 days");
      return false;
    }

    if (!validateText(discounts.value)) {
      alert("Please enter a valid discount");
      return false;
    }

    if (!validateText(description.value)) {
      alert("Please enter a valid description");
      return false;
    }

    if (!validateNumber(tarrif.value)) {
      alert("Please enter a valid tarrif");
      return false;
    }

    return true;
  }

/**
 * Validates the edit profile page form to ensure that the user has filled it out
 * properly.
 *
 * @return Returns false if a filled in input is not valid.
 */
  function validateEditProfile() {
    var title = document.forms["edit_profile_form"]["title"];
    var firstname = document.forms["edit_profile_form"]["firstname"];
    var lastname = document.forms["edit_profile_form"]["lastname"];
    var birthday = document.forms["edit_profile_form"]["birthday"];
    var gender = document.forms["edit_profile_form"]["gender"];
    var username = document.forms["edit_profile_form"]["user"];
    var mobilenum = document.forms["edit_profile_form"]["mobile"];
    var description = document.forms["edit_profile_form"]["description"];

    if (isEmpty(title.value)) {
      alert("Please enter a title");
      return false;
    }

    if (!validateLetters(firstname.value)) {
      alert("Please enter a valid first name");
      return false;
    }

    if (!validateLetters(lastname.value)) {
      alert("Please enter a valid last name");
      return false;
    }

    if (isEmpty(birthday.value)) {
      alert("Please enter a birthday");
      return false;
    }

    if (isEmpty(gender.value)) {
      alert("Please enter a gender");
      return false;
    }

    if (validateEmail(user.value)) {
      alert("Please enter a valid email (username)");
      return false;
    }

    if (!validateNumber(mobilenum.value)) {
      alert ("Please enter a valid mobile number");
      return false;
    }

    if (!validateText(description.value)) {
      alert("Please enter a valid description");
      return false;
    }

    return true;
  }

/**
 * Validates a password and alerts the user if there is an error.
 * The password must meet the password requirements of the website.
 *
 */

 function validateFormPassword() {
  var password1 = document.forms["change_password"]["newpassword"];
  var password2 = document.forms["change_password"]["newpassword_check"];

  if (isEmpty(password1.value) || isEmpty(password2.value)) {
    alert ("Please complete all the password fields ");
    return false;
  } else if (!validateText(password1.value) && !validateText(password2.value)) {
      alert ("Please enter a valid password");
      return false;
  } else if (password1.value != password2.value) {
    alert ("Your passwords do not match");
    return false;
  } else if (!validatePassword(password.value)) {
    alert ("Your password does not meet the strength requirements of the website");
    return false;
  }
 }
/**
 * Returns whether an email is valid.
 *
 * @param email is the email address to be checked for validity
 *
 * @return returns true if the email parameter is a valid email and false otherwise.
 */
  function validateEmail(email) {
    var exp = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email == "" || email == null) {
        return false;
    } else if (!exp.test(email)) {
      return false;
    }

    return true;
  }

  /*
   * Returns whether text contains numbers, letters and legal punctuation
   * marks.
   *
   * @param text the text to validate with the regular expression.
   *
   * @return returns true if the text is legal (not malicious) and false otherwise.
   */
  function validateText(text) {
    var allowed = /^[0-9a-zA-Z!.,?""'@$#%^~&-_;:*\s]+$/;

    if (text.match(allowed)) {
      return true;
    }

    return false;
  }

  /*
   * Returns whether the text is alphanumeric
   *
   * @param text the text to validate for alphanumeric characters.
   *
   * @return returns true if the text is alphanumeric and false otherwise.
   */
  function validateAlphanumeric(text) {
    var allowed = /^[0-9a-zA-Z]+$/;

    if (text.match(allowed)) {
      return true;
    }

    return false;
  }

  /*
   * Returns whether the text contains only letters.
   *
   * @param text the text to check for letters.
   *
   * @return returns true if the text contains only letters.
   */
  function validateLetters(text) {
    var allowed = /^[a-zA-Z\s]+$/;

    if (text.match(allowed)) {
      return true;
    }

    return false;
  }

  /*
   * Returns whether a value is empty or not.
   *
   * @param value is the variable to be checked for empty status.
   *
   * @return returns true if the value is empty and false otherwise.
   */
  function isEmpty(value) {
    if (value == "" || value == null) {
      return true;
    }

    return false;
  }

  /*
   * Returns whether a value is a number.
   *
   * @param value is the variable to be checked
   *
   * @return returns true if the value is a number and false if it is not.
   */
  function validateNumber(value) {
    var allowed = /^[0-9]+$/;

    if (value.match(allowed)) {
      return true;
    }

    return false;
  }

  /*
   * Returns whether a value is a valid coordinate.
   *
   * @param value is the variable to be checked
   *
   * @return returns true if the value is a coordinate and false if it is not.
   */
  function validateCoordinates(value) {
    var allowed =  /^[0-9.,-]+$/;

    if (value.match(allowed)) {
      return true;
    }

    return false;

  }


  /*
   * Returns whether a given password is a valid password by the defined
   * password strength standard of the website.
   *
   * @param password is the variable to be checked
   *
   * @return returns true if the value is a valid password and false if it is not.
   */
  function validatePassword(password) {
  	var longEnough = (password.length >= 8);
  	var hasDigit = false;
  	var hasUpper = false;
  	var hasLower = false;
  	var hasNonAlnum = false;

  	for (var i = 0; i < password.length; i++) {
  		var ch = password.charAt(i);
  		if (!isNaN(ch)) {
  			hasDigit = true;
  		} else if (ch == ch.toUpperCase() && validateAlphanumeric(ch)) {
  			hasUpper = true;
  		} else if (ch == ch.toLowerCase() && validateAlphanumeric(ch)) {
  			hasLower = true;
  		} else if (!validateAlphanumeric(ch)) {
  			hasNonAlnum = true;
  		}
  	}

  	return (longEnough && hasDigit && hasUpper && hasLower && hasNonAlnum);
  }
