<?php
	if(isset($_SESSION['notification'])) {
		//A notification was set from the previous page
		$notifications[] = $_SESSION['notification'];
		$_SESSION['notification'] = NULL;
	}

	$notify = '';
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
			
			$notify .= 		"<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" .
							"<span>$status - </span> $message" .
						"</div>";
		}
	}

	$menuHTML = arrayTo_HTMLList($menu, function($label, $value) use ($path) {
		return ($value === $path) ? "class='active'" : "";
	});
?>

<ul class="navigation">
	<h4>ALJCepeda</h4>
	<?=$menuHTML?>
</ul>

<?=$notify?>