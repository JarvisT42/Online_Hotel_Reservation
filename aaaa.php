<!DOCTYPE html>
<html>

<head>
	<title>reCAPTCHA Example</title>
	<!-- reCAPTCHA API -->
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
	<form action="verify.php" method="POST">
		<label for="name">Name:</label>
		<input type="text" name="name" required><br><br>

		<!-- reCAPTCHA widget -->
		<div class="g-recaptcha" data-sitekey="6LfApqArAAAAAF9ZV0d4kqJnp7ONwWqPYqL6RH_f"></div>

		<br>
		<button type="submit">Submit</button>
	</form>
</body>

</html>