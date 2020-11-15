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
	<link rel='stylesheet' href='css/fullcalendar.min.css'/>
	<link rel="stylesheet" type="text/css" href="css/index_stylesheet.css"/>
	<link rel="stylesheet" type="text/css" href="css/login_reg.css"/>
	<link rel="stylesheet" type="text/css" href="css/loader_display.css"/>
	<link rel="stylesheet" type="text/css" href="css/icons.css"/>
	<link rel="stylesheet" type="text/css" href="css/all_icons.css"/>
	<link rel="stylesheet" type="text/css" href="css/shared_backend_css.css"/>
	<script src='js/jquery.min.js'></script>
	<script src='js/moment.min.js'></script>
	<script>
		const user_img = '<?php if(!isset($_SESSION['user_img'])){echo "";}else{echo $_SESSION['user_img'];} ?>';
		const email = '<?php if(!isset($_SESSION['email'])){echo "";}else{echo $_SESSION['email'];} ?>'; 
		const user_level = '<?php if(!isset($_SESSION['user_level'])){echo "";}else{echo $_SESSION['user_level'];} ?>';
		const user_id = '<?php if(!isset($_SESSION['client_id'])){echo "";}else{echo $_SESSION['client_id'];} ?>';
		const dob = '<?php if(!isset($_SESSION['dob'])){echo "";}else{echo $_SESSION['dob'];} ?>';
	</script>
	<title>Contact | M.V. Santiago Wellness Center</title>
</head>
<body>
	<div class="wrapper">
		<?php
			echo $top_nav;
			include 'frontend_navbar.php';
		?>
		
		<div id="contact-form-container">
			<h2 id="contact-main-header">CONTACT US</h2>
			<p id="contact-subtitle">Ask and inquire us about anything.</p>
			<div id="nogs">
				<div id="contact-divider">
					<div id="main-container">
						<h3 id="contact-us-header">GET IN TOUCH</h3>
						<p id="contact-us-subtitle">We're happy to answer any questions about our wellness center.</p>
						<form id="contact-form">
							<label class="contact-label">Name:</label>
							<input type="text" class="contact-input" id="contact-form-name"/>
							
							<label class="contact-label">Email:</label>
							<input type="email" class="contact-input" id="contact-form-email"/>
							
							<label class="contact-label">Contact number:</label>
							<input type="number" class="contact-input" id="contact-form-contact" pattern="^[\d]+$"/>
							
							<label class="contact-label">Message:</label>
							<textarea rows="5" class="contact-input" id="contact-form-message"></textarea>
							
							<button type="submit" id="contact-form-submit">Send message</button>
						</form>
					</div>
					<div id="contact-sidebar">
						<div id="contact-sidebar-header">
							Our contact info
						</div>
						
						<div id="contact-sidebar-content">
							<p>
								<span class="contact-icon">&#9742;</span> 
								<span id="company-contact"></span>
							</p>
							
							<p>
								<span class="icon-clock contact-icon"></span> 
								<span id="company-hours"></span>
							</p>
							
							
							<p>
								<span class="contact-icon">&#9993;</span> 
								<span id="company-email"></span>
							</p>
							
							<p>
								<span class="icon-map contact-icon"></span>
								<span id="company-address"></span>
							</p>
						</div>
					</div>
				</div>
				
				<!--<div class="mapouter"><div class="gmap_canvas"><iframe id="gmap_canvas" height="300" width="0" src="https://maps.google.com/maps?q=M.%20V.%20Santiago%20Medical%20Center%2C%20cavite&t=&z=15&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://www.crocothemes.net"></a></div><style>.mapouter{text-align:right;height:520px;width:400px;}.gmap_canvas {overflow:hidden;background:none!important;height:520px;width:400px;}</style></div>-->
			
				<div class="mapouter"><div class="gmap_canvas"><iframe id="gmap_canvas" height="500" src="https://maps.google.com/maps?q=M.%20V.%20Santiago%20Medical%20Center%2C%20cavite&t=&z=15&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://www.crocothemes.net"></a></div><style>.mapouter{text-align:right;}.gmap_canvas {overflow:hidden;background:none!important;}</style></div>
			</div>
		</div>
		
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
		document.querySelector('#header-contact').style.color = "green";
	</script>
	<script src="js/loaderPopupControl.js"></script>
	<script src="js/modalControl.js"></script>
	<script src="js/login_frontend.js"></script>
	<script src="js/login_db.js"></script>
	<script src="js/contact.js"></script>
	<script src="js/notifications.js"></script>
	<script src="js/user-apps-frontend.js"></script>
</body>
</html>