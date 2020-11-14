<?php

/*
 * Author: Ibrahim Sheriff Sururu
 * This php file contains functions for register pages. It handes user type
 * differentiation to ensure the correct form is displayed.
 */

/*
 * Displays the appropriate form for the user registration.
 *
 * register_type indicates the type of user.
 */
function registerTop($register_type) {
  if ($register_type == "host") { ?>
    <h1>Register now as a Host</h1>
    <h4>potentially make R23,690 per month on Disa Travels</h4>
    <?php
  } else { ?>
    <h1>Register now as a Guest</h1>
    <h4>and travel the beautiful Western Cape with Disa Travels!</h4>
    <?php
  }
}

function registerFormBody() {
  ?>
  <label for="location">Town/City</label>
  <input type="text" name="location" id="location" required><br>

  <label for="address">Address</label>
  <input type="text" name="address" id="address" required><br>

  <select name="title" id="title">
    <option value="Mr">Mr</option>
    <option value="Mrs">Mrs</option>
    <option value="Miss">Miss</option>
    <option value="Sir">Sir</option>
    <option value="Dr">Dr</option>
    <option value="Prof">Prof</option>
  </select>

  <label for="firstname">First Name</label>
  <input type="text" name="firstname" id="firstname" required>

  <label for="lastname">Last Name</label>
  <input type="text" name="lastname" id="lastname" required><br>

  <label for="birthday">Birthday</label>
  <input type="date" id="birthday" name="birthday" required><br><br>

  <label for="mobilenum">Mobile Number (with your country code) +</label>
  <input type="text" name="mobilenum" id="mobilenum" required><br><br>

  <label>Gender</label><br>
    <input type="radio" name="gender" value="female" id="female">
    <label for="female">Female</label><br>
    <input type="radio" name="gender" value="male" id="male">
    <label for="male">Male</label><br>
    <input type="radio" name="gender" value="other" id="other">
    <label for="other">Other</label><br><br>

  <label for="username">Email Address(Username)</label><br>
  <input type="email" name="username" id="username" required><br>

  <p>
    Please make sure that your password contains the following:
    <ul>
      <li>At least 8 characters</li>
      <li>Contains at least a digit</li>
      <li>Contains at least an upper case letter</li>
      <li>Contains at least an lower case letter</li>
      <li>Contains a special character that is not alphanumeric
        <br>From this list: ! @ . # , ? $ # % ^ = + ~ & - _ ; : *
      </li>
    </ul>
  <p>
  <label for="password1">Create a Password (at least 8 digits)</label><br>
  <input type="password" name="password1" id="password1" pattern=".{8,}" required>
  <br>

  <label for="password2">Re-enter your Password</label><br>
  <input type="password" name="password2" id="password2" pattern=".{8,}" required>
  <br><br>

  <p>
    Upload Profile Picture:
    <input type="file" name="fileToUpload" id="fileToUpload">
  </p><br>

  <p>
    Please put a short (short but spicy) wonderful description about yourself.
  </p>
  <textarea name="description" form="register" required>
    Tell us your awesome story!
  </textarea><br>

  <button type="submit">Register</button>
  <?php
}
?>
