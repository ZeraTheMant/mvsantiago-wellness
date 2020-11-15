<?php
	session_start();
	include '../front_end_top_nav.php';
	include '../bottom_links.php';
	include '../php/dbconfig.php';
	
	$query = "SELECT service_description FROM services WHERE service_id = :service_id LIMIT 1";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':service_id' => $_GET['id']
		)
	);
	
	$result = $stmt->fetch();
	
	$desc = $result['service_description'];
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" type="text/css" href="../css/index_stylesheet.css"/>
	<link rel="stylesheet" type="text/css" href="../css/login_reg.css"/>
	<link rel="stylesheet" type="text/css" href="../css/loader_display.css"/>
	<link rel="stylesheet" type="text/css" href="../css/icons.css"/>
	<link rel="stylesheet" type="text/css" href="../css/all_icons.css"/>
	<link rel="stylesheet" type="text/css" href="../css/shared_backend_css.css"/>
	<link rel="stylesheet" type="text/css" href="../css/testimonials_stylesheet.css"/>
	<link rel="stylesheet" type="text/css" href="../css/service_slider.css"/>
	<script src='../js/jquery.min.js'></script>
	<script src='../js/moment.min.js'></script>
	<script>
		const service_id ='<?php echo $_GET['id']; ?>';
		const service_name ='<?php echo $_GET['title']; ?>';
		const service_desc ='<?php echo $_GET['desc']; ?>';
		const service_price ='<?php echo $_GET['price']; ?>';
		const user_img = '<?php if(!isset($_SESSION['user_img'])){echo "";}else{echo $_SESSION['user_img'];} ?>';
		const email = '<?php if(!isset($_SESSION['email'])){echo "";}else{echo $_SESSION['email'];} ?>'; 
		const user_level = '<?php if(!isset($_SESSION['user_level'])){echo "";}else{echo $_SESSION['user_level'];} ?>';
		const user_id = '<?php if(!isset($_SESSION['client_id'])){echo "";}else{echo $_SESSION['client_id'];} ?>';
		const dob = '<?php if(!isset($_SESSION['dob'])){echo "";}else{echo $_SESSION['dob'];} ?>';
		const name = '<?php $ucInitial=""; $fname=""; $mname=""; $lname=""; $name_ext=""; if(!isset($_SESSION['fname'])){echo "";}else{$fname=$_SESSION['fname'];} if(!isset($_SESSION['mname'])){echo "";}else{$mname=$_SESSION['mname'];} if(!isset($_SESSION['lname'])){echo "";}else{$lname=$_SESSION['lname']; $ucInitial=ucfirst($mname[0]);} if(!isset($_SESSION['name_ext'])){echo "";}else{$name_ext=$_SESSION['name_ext'];} echo $fname . ' ' . $ucInitial . '. ' . $lname . ' ' . $name_ext; ?>';
	</script>
	<title><?php echo $_GET['title']; ?> | M.V. Santiago Wellness Center</title>
	<style>
		@media (max-width: 1000px){
			.imgbanbtn{
				margin-top: 50px;
			}
			
			#service-info, #service-info-left .modal-halved{
				grid-template-columns: 1fr;
			}
			
			.modal-halved h1{
				font-size: 3rem;
			}
			
			#service-info-right{
				margin-top: 30px;
			}
			

		}
	</style>
</head>
<body>
	<div class="wrapper">
		<?php
			echo $top_nav;
			include '../frontend_navbar.php';
		?>

		<div class="main-banner" id="main-banner"></div>

		<div id="service-info">
			<div id="service-info-left">
				<div class="modal-halved aligned-to-center">
					<h1><?php echo $_GET['title']; ?></h1>
					<h3>Category: <?php echo $_GET['category']; ?></h3>
				</div>
				<br><br><br><br>
				
				<div class="outer-testimonial-box">
					<div class="inner-testimonial-box">
						<div id="desc-container"><?php echo $desc; ?></div>
					</div>
					<div class="quotation-design-box"><div>&#8221;</div></div>
				</div>
			
			</div>
			<div id="service-info-right">
				<div class="modal-halved aligned-to-center">
					<h2>Price</h2>
					<div>
						<h3>&#8369;<?php echo $_GET['price']; ?></h3>
					</div>
				</div>
				<br><br><br><br>
				<h2>Providers</h2><br>
				<div id="service-providers"></div>
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
			include '../footer.php';
		?>
	</div>
	
	<?php include '../login_and_register.php'; ?>
	<?php include '../loader_popup.php'; ?>
	
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
		var linkViewer = "../php/backend/";
		document.querySelector('#header-services').style.color = "green";
	</script>
	<script src="../js/loaderPopupControl.js"></script>
	<script src="../js/modalControl.js"></script>
	<script src="../js/login_frontend.js"></script>
	<script src="../js/login_db.js"></script>
	<script src="../js/view_service.js"></script>
	<script src="../js/notifications.js"></script>
	<script src="../js/user-apps-frontend.js"></script>
</body>
</html>