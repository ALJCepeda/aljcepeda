<?php
	include $_SERVER['DOCUMENT_ROOT'] . '/startsession.php';
	$response = [ 'status' => 'success', 'error' => '', 'uri' => DOMAIN . $_SERVER['REQUEST_URI'] ];

	$pageParser = GRAB('PageParser');

	$pageParser->parseGet = false;
	$pageParser->setRequiredAtIndex('username', USERNAME);
	$pageParser->setRequiredAtIndex('email', EMAIL);
	$pageParser->setRequiredAtIndex('g-recaptcha-response', ALLCHARACTERS);
	$pageParser->parseRequest(); 

	$url = RECAPTCHAURL;
	$data = [ 'secret' => RECAPTCHASCRT, 'response' => $grecaptcharesponse, 'remoteip' => $_SERVER['REMOTE_ADDR'] ];
	$options = 	[ 'http' => [
						'header' => 'Content-type: application/x-www-form-urlencoded\r\n',
						'method' => 'POST',
						'content' => http_build_query($data)
					]
				];
	$context = stream_context_create($options);
	$result = json_decode(file_get_contents($url, false, $context), true);

	if(!$result['success']) {
		$response['status'] = 'failed';
		$response['error'] = [ 'type' => 'recaptcha', 'message' => 'Whoops looks like you got recaptcha wrong, please try again'];
		$encode = base64_encode(json_encode($response));

		http_response_code(409);
		header("Location:/user/create?redirect=$encode");
		die;
	}

	die;
	$users = GRAB('UsersDB');
	$temp = GRAB('TempDB');

	if($users->exists('Users', "username = $username") || $temp->exists('Users', "username = $username")){
		http_response_code(409);
		$response['status'] = 'failed';
		$response['error'] = [ 'type' => 'duplicate', 'message' => "The username($username) has already been reserved" ];
		echo json_encode($response);
		die;
	}

	if($users->exists('Emails', "local = $email") || $temp->exists('Users', "email = $email")) {
		http_response_code(409);
		$response['status'] = 'failed';
		$response['error'] = [ 'type' => 'duplicate', 'message' => "The email($email) has already been reserved" ];
		echo json_encode($response);
		die;
	}

	$authKey = md5(uniqid());
	$expDays = GET_CONSTANT('TEMP_USER_EXP_DAYS'); 
	$expiresOn= Date('Y/m/d', strtotime("+ $expDays days"));

	//Generate confirmation link
	$confirmationLink = DOMAIN . '/action/confirm/users.php?username={{username}}&authKey={{authKey}}';
	$search = [ '{{username}}', '{{authKey}}' ];
	$replace = [ $username, $authKey ];
	$confirmationLink = str_replace($search, $replace, $confirmationLink);

	$static = file_get_contents(EMAILTEMPLATES . '/confirmation/tempuser.txt');
	$search = array_merge($search, [ '{{expiresOn}}', '{{confirmationLink}}', '{{email}}']);
	$replace = array_merge($replace, [ $expiresOn, $confirmationLink, $email ]);
	$static = str_replace($search, $replace, $static);
	
	include ROOT . '/tmp/supportmailer.php';

	$message->From = 'support@aljcepeda.com';
	$message->FromName = 'Support';
	$message->addAddress($email);
	$message->Subject = 'Thank you for registering to ALJCepeda.com!';
	$message->Body = $static;

	if(!$message->send()) {
		http_response_code(503);
		$error = [ 'status' => 'failed', 'error' => [ 'type' => 'internal', 'message' => "We were unable to send a confirmation email for: $email. Please try again later.", 'details' => $message->ErrorInfo ]];
		echo json_encode($error);
		die;
	} else {
		$insert = [ 'username' => $username,
					'email' => $email,
					'authKey' => $authKey,
					'expiresOn' => $expiresOn ];

		//$temp->insert('Users', $insert);

		$success = [ 'status' => 'success', 'message' => "Successfully reserved username($username) and sent confirmation email to $email" ];
		echo json_encode($success);
		die;
	}
?>