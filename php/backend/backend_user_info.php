<?php
	session_start();
	
	if($_SESSION['user_level'] == "admin"){
		include 'backend_sidebar.php';
	}
	else if($_SESSION['user_level'] == "provider"){
		include 'backend_sidebar_provider.php';
	}
	else{
		include 'backend_sidebar_client.php';
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" type="text/css" href="../../css/loader_display.css"/>
	<link rel='stylesheet' href='../../css/icons.css'/>
	<link rel="stylesheet" type="text/css" href="../../css/all_icons.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/index_stylesheet.css"/>
	<link rel='stylesheet' href='../../css/real_backend_css.css'/>
	<link rel="stylesheet" type="text/css" href="../../css/backend_user_info.css"/>

	<script src='../../js/jquery.min.js'></script>
	<script src='../../js/moment.min.js'></script>
	<script>			
		var currentUserID = '<?php echo $_SESSION['client_id']; ?>';
	</script>
	<title>Account Settings | Backend</title>
</head>
<body>
	<?php
		if(!isset($_SESSION['email'])){
			echo '
				<script type="text/javascript">
					alert("You need to log-in first to access this page.");
					window.location = "http://localhost/itsp/index.php";
				</script>
			';
			exit();
		}
	?>
	
	<div class="backend-container">
		<?php echo $sidebar; ?>
	
		<div class="backend-page-body">
			<?php echo $logo_holder; ?>
		
			<div class="backend-page-body-container">
				<div class="backend-body-header">
					<div>
						<h3>
							Account settings
							<button type="submit" form="user-settings-form" id="save-user-settings" name="save-user-settings" class="modal-btn">&#128190; Save new settings</button>
						</h3>
					</div>
				</div>
				<hr>
				
				<div class="backend-body-content"></div>
			</div>
		</div>
	</div>

	<label class="modal-clear-img actual-row" style="display: none; position: absolute; top:0; left: 0;"></label>
	<select id="modal-img" style="display: none; position: absolute; top:0; left: 0;"></select>
	
	<?php include '../../footer.php'; ?>
	<?php include '../../loader_popup.php'; ?>
	
	<script>
		var linkViewer = "";
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		document.querySelector("#dashboard-admin-settings-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-admin-settings-link").style.color = "green";
	</script>	
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/getImgurDict.js"></script>
	<script src="../../js/shared_backend_js.js"></script>
	<script src="../../js/backend_user_info.js"></script>
	<script src="../../js/notifications.js"></script>
</body>
</html>