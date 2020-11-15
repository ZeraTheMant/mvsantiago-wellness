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
	<title>Terms and Conditions | M.V. Santiago Wellness Center</title>
</head>
<body>
	<div class="wrapper">
		<?php
			echo $top_nav;
			include 'frontend_navbar.php';
		?>
		
		<div id="contact-form-container">
			<h2 id="contact-main-header">TERMS AND CONDITIONS</h2>
			<p id="contact-subtitle">Please read carefully.</p><br><br>
<p>By signing this document or attending Jarin Dental Clinic, you agree to abide by all terms and conditions bellow.</p>
<p>While we always try our best to inform you of your current or potential dental disease or dental problem, its diagnosis and prognosis, the treatment options and best treatment plan, potential side effects of treatment or possible accidental damages to your tooth, mouth or general health, detailed and total cost of treatment and the time span needed for performing the treatment plan, It is only your responsibility to ask for whatever information which you possibly could be interested in to know before start of treatment. By agreeing to us to start the treatment for you, you give us the consent that you have been informed enough about your treatment and all other related matters mentioned above. In case of a partially done treatment which you do not want to have it finished for any reason, we keep the right to ourselves to decide to charge you in full or in partial. We keep the right to change our service prices without any notice. All prices mentioned on your treatment plans which we print and hand out to you are only valid for 3 month from the date of issue. <p> We keep the right to change your treatment plan whenever we recognize it is in your best interest. We will inform you from the change before performing it. Change in treatment plan may cause change in total cost of treatment and is payable by you. </p> We keep the right to refer you to specialist whenever we feel it is in your best interest. We will inform you from decision and the reasons and you will get charged partially depending on the amount of work has been done. We are not liable for any cost you incur from seeing the specialist and it is your responsibility to pay the cost of treatment by specialist. All the services which involve ordering any product from dental laboratories are guaranteed by us but the product will be guaranteed by laboratory. For providing you with a replacement for the guaranteed product we may need to redo some procedures which we cover them under our service warranty. We are not liable for any damage or harm those products or replacing them may cause for you. We keep the right to work with any dental lab which we prefer and feel it is in the best interest of our patients. We are not liable for any dental product or oral hygiene product which we sell or give you as sample for free. We do our best to make sure of the quality of those products but we are not liable for any possible damage or harm they may cause you for any reason. All the information and instructions provided on our website is purely for your general knowledge and is not a substitute for proper dental or medical advice. We are not liable for any consequences of following or using those information and instructions. We keep the right to change the content of our website without any notice. Our special offers are available to you only after a careful evaluation of your teeth and oral environment. We keep the right to deny doing any treatment that we think is not in your best interest. We keep the right to change our special offers at anytime without notice. Our free consultation is not a comprehensive exam and does not include any x-ray. It is mainly for a quick assessment of your teeth and giving you the treatment options, treatment plan or a quote regarding what is the concern for you. Our no gap payment offer is valid for anyone who has private health fund insurance with dental cover. We keep the right to charge you as normal if, for any reason, you fail to provide us with your valid private health insurance card at the end of treatment session. </p> <p> A valid private health insurance card is a card that is not expired and has your name on it. If you provide us with a valid private health insurance card but your health insurer does not pay anything at all ($0 payment) for the provided services with no gap offer, we will treat you as you do not have private health insurance with dental cover which means you need to pay normal full price.</p>
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
	</script>
	<script src="js/loaderPopupControl.js"></script>
	<script src="js/modalControl.js"></script>
	<script src="js/login_frontend.js"></script>
	<script src="js/login_db.js"></script>
	<script src="js/notifications.js"></script>
	<script src="js/user-apps-frontend.js"></script>
</body>
</html>