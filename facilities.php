<?php
	session_start();
	include 'front_end_top_nav.php';
	include 'bottom_links.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" type="text/css" href="css/index_stylesheet.css"/>
	<link rel="stylesheet" type="text/css" href="css/login_reg.css"/>
	<link rel="stylesheet" type="text/css" href="css/loader_display.css"/>
	<link rel="stylesheet" type="text/css" href="css/icons.css"/>
	<link rel="stylesheet" type="text/css" href="css/all_icons.css"/>
	<link rel="stylesheet" type="text/css" href="css/shared_backend_css.css"/>
	<link rel="stylesheet" type="text/css" href="css/testimonials_stylesheet.css"/>
	<link rel="stylesheet" type="text/css" href="css/about.css"/>
	<script src='js/jquery.min.js'></script>
	<script src='js/moment.min.js'></script>
	<script>
		const user_img = '<?php if(!isset($_SESSION['user_img'])){echo "";}else{echo $_SESSION['user_img'];} ?>';
		const email = '<?php if(!isset($_SESSION['email'])){echo "";}else{echo $_SESSION['email'];} ?>'; 
		const user_level = '<?php if(!isset($_SESSION['user_level'])){echo "";}else{echo $_SESSION['user_level'];} ?>';
		const user_id = '<?php if(!isset($_SESSION['client_id'])){echo "";}else{echo $_SESSION['client_id'];} ?>';
		const dob = '<?php if(!isset($_SESSION['dob'])){echo "";}else{echo $_SESSION['dob'];} ?>';
		const name = '<?php $ucInitial=""; $fname=""; $mname=""; $lname=""; $name_ext=""; if(!isset($_SESSION['fname'])){echo "";}else{$fname=$_SESSION['fname'];} if(!isset($_SESSION['mname'])){echo "";}else{$mname=$_SESSION['mname'];} if(!isset($_SESSION['lname'])){echo "";}else{$lname=$_SESSION['lname']; $ucInitial=ucfirst($mname[0]);} if(!isset($_SESSION['name_ext'])){echo "";}else{$name_ext=$_SESSION['name_ext'];} echo $fname . ' ' . $ucInitial . '. ' . $lname . ' ' . $name_ext; ?>';
	</script>
	<title>Facilities | M.V. Santiago Wellness Center</title>
</head>
<body>
	<div class="wrapper">
		<?php
			echo $top_nav;
			include 'frontend_navbar.php';
		?>
		<div id="facilities-container"></div>

		<div id="user-notif-box">
			<div id="user-notif-container">
				<div id="my-appointments"></div>
				<div id="appointments-clickable">
					<h4 name="off">&#9650; Show my upcoming appointments</h4>
				</div>
			</div>
		</div>
		
		<?php
			echo $bottom_links;
			include 'footer.php';
		?>
	</div>
	
	<?php include 'login_and_register.php'; ?>
	<?php include 'loader_popup.php'; ?>
	
	<div class="modal-popup">
		<div class="modal-content">
			<div class="modal-header">
				<span class="modal-header-text"></span>
				<div class="modal-close-btn"><span>&times;</span></div>
			</div>

			<div class="modal-body">
				
				<div class="tab-panel" id="panel-1">
					<form class="modal-form" id="providers-modal-form" method="post" enctype="multipart/form-data">
						<h2 class="centered">Submit a Testimonial</h2>
						<p id="testi-instructions">Fields marked with an asterisk (<span class="required-asterisk">*</span>) are required</p>
						<div class="modal-halved">
							<div>
								<label class="modal-label">Name:</label>
								<input class="modal-input" type="text" id="modal-sender-name" disabled/>

								<label class="modal-label">Address: eg.(Imus, Cavite)<span class="required-asterisk">*</span></label>
								<input class="modal-input" type="text" id="modal-sender-address"/>
								
								<label class="modal-label">Age:</label>
								<input class="modal-input" type="number" id="modal-sender-age" disabled/>
							</div>
							
							<div id="modal-img-container">
								<img src="img/question_mark.png" class="modal-preview-img"/>
							</div>
						</div>
						<label class="modal-label">What did you think of our services? <span class="required-asterisk">*</span></label>
						<textarea class="modal-input" id="modal-testimonial" rows="7"></textarea>	
					</form>
				</div>
			</div>
			<hr id="wew">
			<div class="modal-footer">
				<button type="button" form="providers-modal-form" class="send-testi-btn modal-btn">Send</button>
			</div>
		</div>
	</div>
	
	<script>
		var linkViewer = "php/backend/";
		document.querySelector('#header-facilities').style.color = "green";
	</script>
	<script src="js/loaderPopupControl.js"></script>
	<script src="js/modalControl.js"></script>
	<script src="js/login_frontend.js"></script>
	<script src="js/login_db.js"></script>
	<script src="js/notifications.js"></script>
	<script src="js/facilities.js"></script>
	<script src="js/user-apps-frontend.js"></script>
</body>
</html>