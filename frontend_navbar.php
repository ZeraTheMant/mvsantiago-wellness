<?php
	include 'php/dbconfig.php';
	
	$query = "
		SELECT * FROM logo_img limit 1
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	$result = $stmt->fetch();
	$img = $result['img_link'];
	//http://localhost/itsp/img/logo2.png
	$loggedOutDiv2 = '';
	$loggedInDiv2 = '';
	$name2 = '';
	$backendLink2 = '';
	$top_nav2 = '';

	if(!isset($_SESSION['email'])){
		$loggedOutDiv2 .= '<div style="display: inline;">';
		$loggedInDiv2 .= '<div style="display: none;">';
	}
	else{
		$name2 = $_SESSION['fname'];
		$loggedOutDiv2 .= '<div style="display: none;">';
		$loggedInDiv2 .= '<div style="display: inline;">';
		
		if($_SESSION['user_level'] == 'admin' || $_SESSION['user_level'] == 'semiadmin'){
			$backendLink2 .= 'http://mvsantiagowellness.com/php/backend/backend_admin_dashboard.php';
		}
		else if($_SESSION['user_level'] == 'provider'){
			$backendLink2 .= 'http://mvsantiagowellness.com/php/backend/backend_provider_dashboard.php';
		}
		else{
			$backendLink2 .= 'http://mvsantiagowellness.com/php/backend/backend_user_dashboard.php';
		}
	}
	
	$top_nav2 .= '
			' . $loggedOutDiv2 . '
				<a href="#" class="header-link mobile-link" id="login-btn-header2">Login</a>
				<a href="#" class="header-link mobile-link" id="register-btn-header2">Register</a>
			</div>

			' . $loggedInDiv2. '

					<label class="mobile-link">Hello, ' . $name2 . '.</label>
					

					
					<a href="http://mvsantiagowellness.com/logout.php" class="header-link mobile-link" id="logout-btn-header">Logout</a>
					<a href="' . $backendLink2 . '" class="header-link mobile-link">User settings</a>


			</div>
	';
	//<?php echo $img;
?>

		<div class="frontend-navbar">
			<div class="banner-holder">
				<a href="http://mvsantiagowellness.com">
					<img class="img-banner" src="<?php echo $img; ?>"/>
				</a>
			</div>
			
			<div class="navbar-menu">
				<a href="http://mvsantiagowellness.com" class="header-link with-margin" id="header-home">HOME</a>
				<a href="http://mvsantiagowellness.com/about.php" class="header-link with-margin" id="header-about">ABOUT US</a>
				<a href="http://mvsantiagowellness.com/services.php" class="header-link with-margin" id="header-services">SERVICES</a>
				<a href="http://mvsantiagowellness.com/facilities.php" class="header-link" id="header-facilities">FACILITIES</a>
				<a href="http://mvsantiagowellness.com/testimonials.php" class="header-link with-margin" id="header-testimonials">TESTIMONIALS</a>
				<a href="http://mvsantiagowellness.com/contact.php" class="header-link" id="header-contact">CONTACT</a>
			</div>
			
			<div class="mobile-toggle">&#9776;</div>
		</div>
	<div class="navbar-menu-mobile">
		<a href="http://mvsantiagowellness.com" class="header-link mobile-link">Home</a>
		<a href="http://mvsantiagowellness.com/about.php" class="header-link mobile-link">About us</a>
		<a href="http://mvsantiagowellness.com/services.php" class="header-link mobile-link">Services</a>
		<a href="http://mvsantiagowellness.com/facilities.php" class="header-link mobile-link">Facilities</a>
		<a href="http://mvsantiagowellness.com/testimonials.php" class="header-link mobile-link">Testimonials</a>
		<a href="http://mvsantiagowellness.com/contact.php" class="header-link mobile-link">Contact</a>
		<a href="https://www.mvsantiago.com" class="header-link mobile-link">Medical Center site</a>
		<br><br>
		<?php echo $top_nav2; ?>
	</div>
</div>
	</div>
	



