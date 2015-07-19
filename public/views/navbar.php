<?php
	if(ISLOCAL){
		$blog = "#";
	} else {
		$blog = "http://blog.aljcepeda.com";
	}


	$notify = '';
	$notifications = [];
	if(isset($_SESSION['notification'])) {
		//A notification was set from the previous page
		$notifications[] = $_SESSION['notification'];
		$_SESSION['notification'] = NULL;
	}

	if(isset($parameters['notification'])) {
		//Notification was provided by the router
		$notifications[] = $parameters['notification'];
	}

	if(count($notifications)) {
		foreach($notifications as $index => $notification) {
			$status = isset($notification['status']) ? $notification['status'] : '';
			$type = isset($notification['type']) ? $notification['type'] : 'alert-warning';
			$dismissable = isset($notification['dismissable']) ? 'alert-dismissable' : '';
			$message = $notification['message'];

			$notify .= "<div class='alert $type $dismissable' role='alert'>";
			if($dismissable) {
				$notify .= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
			}
			
			$notify .= "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" .
							"<span class='sr-only'>$status</span>" .
							$message .
						"</div>";
		}
	}


?>

<script>
	$(document).ready(function() {
		$('a[href="' + this.location.pathname + '"]').parent().addClass('active');
	});
</script>
	
<div class="masthead clearfix">
	<?=$notify?>
	<div class="inner">
		<h3 class="masthead-brand">ALJCepeda</h3>
		<nav>
			<ul class="nav masthead-nav">
				<li><a href="/">Home</a></li>
				<li><a href="/user/create">Register</a></li>
				<li><a href="<?=DOMAIN . ':3000'?>">Chat</a></li>
			</ul>
		</nav>
	</div>
</div>