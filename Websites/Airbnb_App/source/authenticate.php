<?php
/*
 * General sanitizing of user input.
 */
function sanitize($input) {
  $input = trim($input);
  $input = stripslashes($input);
  $input = htmlspecialchars($input);
  return $input;
}

/*
 * Verify if user is logged in, in the current session.
 * This function must be called at the beginning of each page
 * before any html tags are outputted.
 * Return GUEST, HOST or ADMIN if logged in, else FALSE.
 */
function isLoggedIn() {
  session_start();
  if (!isset($_SESSION['login']) || $_SESSION['login'] == "") {
    return FALSE;
  }
  return $_SESSION['login'];
}

/**
 * Returns "correct" if an image is a valid jpeg or an error message othewise.
 *
 * @param $image_filename the image file to be checked for validity.
 * @return the error message if image is invalid or correct if its valid.
 */
function validImageFile($image_filename) {
  $max_file_size = 4294967295;

  if (!isset($_FILES[$image_filename]) || $_FILES[$image_filename]["tmp_name"] == NULL) {
    return "Invalid image";
  }

  if (getimagesize($_FILES[$image_filename]["tmp_name"]) === false) {
    return "This is not an image.";
  } else if ($_FILES[$image_filename]["name"] > $max_file_size) {
    return "Image is too big";
  } else if ($_FILES[$image_filename]["error"] != UPLOAD_ERR_OK) {
    return "Error while uploading image";
  } else if ($_FILES[$image_filename]["type"] != "image/jpeg") {
    return "Invalid image type. Not jpeg.";
  }

  return "correct";
}
/**
 * Returns "correct" if an image is a valid jpeg or an error message othewise.
 *
 * @param $image_filename the image file to be checked for validity.
 * @return the error message if image is invalid or correct if its valid.
 */
 function validImage($image_filename) {
   $max_file_size = 4294967295;

   if ($image_filename["tmp_name"] == NULL) {
     return "Invalid image";
   }
   if (getimagesize($image_filename["tmp_name"]) === false) {
     return "This is not an image.";
   } else if ($image_filename["name"] > $max_file_size) {
     return "Image is too big";
   } else if ($image_filename["error"] != UPLOAD_ERR_OK) {
     return "Error while uploading image";
   } else if ($image_filename["type"] != "image/jpeg") {
     return "Invalid image type. Not jpeg.";
   }

   return "correct";
 }


function passwordCheck($password) {
  $long_enough = (strlen($password) >= 8); 
	$has_digit = false;
	$has_upper = false;
	$has_lower = false;
	$has_nonalnum = false;

	for ($i = 0; $i < strlen($password); $i++) {
		$ch = $password{$i};
		if (is_numeric($ch)) {
			$has_digit = true;
		} else if (($ch == strtoupper($ch)) && ctype_alpha($ch)) {
			$has_upper = true;
		} else if (($ch == strtolower($ch)) && ctype_alpha($ch)) {
			$has_lower = true;
		} else if ($ch != ctype_alnum($ch)) {
			$has_nonalnum = true;
		}   
	}

	return ($long_enough && $has_digit && $has_upper && $has_lower && $has_nonalnum);

}

?>
