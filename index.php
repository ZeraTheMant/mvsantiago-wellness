<?php
	session_start();
	include 'front_end_top_nav.php';
	include 'bottom_links.php';
	include 'php/dbconfig.php';
	
	$query = "
	    SELECT home_info FROM outside_links LIMIT 1
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	$output = "";
	
	if($stmt->rowCount() > 0){
	    $oneq = $stmt->fetch();
	    $output = $oneq['home_info'];
	}
	else{
	    $output = "<p>No information here.</p>";
	}
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
		const email = '<?php if(!isset($_SESSION['email'])){echo "";}else{echo $_SESSION['email'];} ?>';
		const user_id = '<?php if(!isset($_SESSION['client_id'])){echo "";}else{echo $_SESSION['client_id'];} ?>';
	</script>
	<title>Home | M.V. Santiago Wellness Center</title>
	<style>
		.index-btn{
			text-decoration: none;
			position: absolute;
		}
		
		#to-services-btn{
			bottom: 50px;
			right: 30px;
		}
		
		#to-showcase-btn{
			bottom: -150px;
			right: 30px;
		}
		
		.index-btn-circle{
			width: 100px;
			height: 100px;
			border-radius: 50%;
			background-color: rgb(196, 196, 196);
			z-index: 9999;
		}
		
		.index-btn-circle img{
			display: block;
			position: relative;
			top: 50%;
			transform: translateY(-50%);
			margin: auto;
		}
	</style>
</head>
<body>
	<a class="index-btn" id="to-services-btn" href="#index-services">
		<div class="index-btn-circle"><img src="img/bottom.png"/></div>
	</a>
	
	<a class="index-btn" id="to-showcase-btn" href="#login-btn-header">
		<div class="index-btn-circle"><img src="img/top.png"/></div>
	</a>
    
	<div class="wrapper">
		<?php
			echo $top_nav;
			include 'frontend_navbar.php';
		?>
		
		<div id="showcase"></div>
		
		<div id="index-services">
			<div id="pork">
				<h1 id="index-services-main-header">What We Do</h1>
			</div>
			<a href="services.php" id="services-link">Learn more<a>
			<div id="index-services-body"></div>
		</div>
		
		<div id="sched-and-testi">
			<div id="sched-container">
				<div class="sched-testi-heading"><span>CLINIC HOURS</span><hr/></div>
				<div id="centered">
					<div>
                        <?php echo $output; ?>
						<!--
						<p>				
							<b>M.V. Santiago Wellness Center</b> is open 7 days a week
								in order to provide quality wellness care at an affordable price to our
								customers in and around the Trece Martires, Cavite area.
						</p>
						<p>
							Drop in anytime during business hours.
						</p>
						<p style="text-align: right;">
							<b>Monday-Saturday</b> 8:00am - 5:00pm
						</p>
						<p>
							<b>OR</b>
						</p>
						<p>Make an appointment via phone by contacting <b>09298418339</b>.</p>
						<p>You can also schedule an appointment <b id="link-to-book" class="modal-btn">online</b>.</p>
						-->
					</div>
				</div>
			</div>
			
			<div id="index-testi">
				<div class="sched-testi-heading"><span>TESTIMONIAL</span><hr/></div>
				<div id="index-right"></div>
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
	<script>
		function stringify(int_value){
			return int_value.toString() + "px";
		}
	
		var linkViewer = "php/backend/";
	
		
		document.querySelector('#header-home').style.color = "green";
	</script>
	<script src="js/loaderPopupControl.js"></script>
	<script src="js/modalControl.js"></script>
	<script src="js/login_frontend.js"></script>
	<script src="js/login_db.js"></script>
	<script src="js/notifications.js"></script>
	<script src="js/index.js"></script>
	<script src="js/user-apps-frontend.js"></script>
</body>
</html>