<?php
$_SESSION['redirect'] = 'actions/create/tempuser.php';
?>

<script>
$(document).ready(function() {
	$( "#registration" ).submit(function( event ) {
  		if(grecaptcha.getResponse() === '') {
  			alert('Please complete the recaptcha before submitting');
  			event.preventDefault();
  		}
	});
});
</script>

<style type="text/css">

input:required:invalid, input:focus:invalid {
	background-image: url(/assets/images/invalid.png);
	background-position: right bottom;
	background-repeat: no-repeat;
}
input:required:valid {
	background-image: url(/assets/images/valid.png);
	background-position: right top;
	background-repeat: no-repeat;
}

form {
	height:100%;
}
.form-wrapper {
	height: 50%;
	width: 100%;
	margin-left: auto;
	margin-right: auto;
	overflow-y:auto;

	display: flex;
	flex-flow: row wrap;
	align-content: space-around;
	justify-content: center;
}

.form-wrapper > h1,label,input {
	flex: 1 100%;
	align-self:center;
}

.form-wrapper > label {
	flex: 1 20%;
}

.form-wrapper > input {
	flex: 1 80%;
}

/*Expand parent container so flex can space out children evenly*/
.content-container {
	height: 100%;
}
</style>

<form id="registration" action="/" method="POST" role="form">
	<div class="form-wrapper">
		<h1>Sign up</h1>
		
		<label class="aside" for="username">Username:</label>
		<input class="aside" type="text" name="username" placeholder="6-32 Alphanumeric characters" required pattern="\w{6,32}">

		<label class="aside" for="email">Email:</label>
		<input class="aside" type="email" name="email" placeholder="Confirmation email will be sent" required>

		<div class="g-recaptcha" data-sitekey="6LfnLgcTAAAAAAcAl1syoGOcnGxTU7UyR-N0CbQF"></div>
	</div>

	<button type="submit" class="btn btn-default">Submit</button>
</form>