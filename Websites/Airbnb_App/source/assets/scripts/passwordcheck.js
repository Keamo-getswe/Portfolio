function passwordValidator(password) {
	var longEnough = (password.length >= 8);
	var hasDigit = false;
	var hasUpper = false;
	var hasLower = false;
	var hasNonAlnum = false;

	for (var i = 0; i < password.length; i++) {
		var ch = password.charAt(i);
		if (!isNaN(ch)) {
			hasDigit = true;
		} else if (ch == ch.toUpperCase()) {
			hasUpper = true;
		} else if (ch == ch.toLowerCase()) {
			hasLower = true;
		} else if (!validateAlphanumeric(ch)) {
			hasNonAlnum = true;
		}
	}

	return (longEnough && hasDigit && hasUpper && hasLower && hasNonAlnum);
}
