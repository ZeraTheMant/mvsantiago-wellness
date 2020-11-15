<?php
	$admin_backend_link = '<a href="http://mvsantiagowellness.com/admin_login.php" id="admin-backend-link" class="header-link with-margin">Admin area</a>';
	
	if(isset($_SESSION['email']) && isset($_SESSION['user_level'])){
		$admin_backend_link = '<a style="display: none;" href="http://mvsantiagowellness.com/admin_login.php" id="admin-backend-link" class="header-link with-margin">Admin area</a>';
	}
	
	$book_link = '<a id="go-to-book-appointment" href="#" class="header-link with-margin">Book an appointment</a>';
	
	if(isset($_SESSION['user_level']) && $_SESSION['user_level'] != 'client'){
    	$book_link = '<a id="go-to-book-appointment" href="#" class="header-link with-margin" style="display: none;">Book an appointment</a>';
	}
	
	$top_nav = '
		<div class="menus-container">
			<div class="menus">
				<div class="frontend-top-nav">
					<div class="top-nav-left">
						<a href="https://www.mvsantiago.com" class="medical-center-link header-link with-margin">Medical Center site</a>
                        ' . $book_link . '
						' . $admin_backend_link . '
					</div>
	';

	$loggedOutDiv = '';
	$loggedInDiv = '';
	$name = '';
	$backendLink = '';
	
	if(!isset($_SESSION['email'])){
		$loggedOutDiv .= '<div class="logged-out-btns" style="display: inline;">';
		$loggedInDiv .= '<div class="logged-in-div" style="display: none;">';
	}
	else{
		$name = $_SESSION['fname'];
		$loggedOutDiv .= '<div class="logged-out-btns">';
		$loggedInDiv .= '<div class="logged-in-div">';
		
		if($_SESSION['user_level'] == 'admin' || $_SESSION['user_level'] == 'semiadmin'){
			$backendLink .= 'http://mvsantiagowellness.com/php/backend/backend_admin_dashboard.php';
		}
		else if($_SESSION['user_level'] == 'provider'){
			$backendLink .= 'http://mvsantiagowellness.com/php/backend/backend_provider_dashboard.php';
		}
		else{
			$backendLink .= 'http://mvsantiagowellness.com/php/backend/backend_user_dashboard.php';
		}
	}
	
	$top_nav .= '
		<div class="top-nav-controls">
			' . $loggedOutDiv . '
				<a href="#" class="header-link with-margin" id="login-btn-header">Login</a>
				<a href="#" class="header-link with-margin" id="register-btn-header">Register</a>
			</div>

			' . $loggedInDiv. '

					<label class="marginned">Hello, ' . $name . '.</label>
					

					
					<a href="http://mvsantiagowellness.com/logout.php" class="header-link with-margin" id="logout-btn-header">Logout</a>
					<a href="' . $backendLink . '" class="header-link with-margin">User settings</a>


			</div>
			
			<div class="outside-links">
				<a class="inline-link" id="fb-link1" href="https://facebook.com/TIAGOWELLNESS">
					<img class="icon-outside-links" src="http://mvsantiagowellness.com/img/fb.png"/>
				</a>
				<a id="ig-link1" class="inline-link" href="https://instagram.com/tiagowellness2018">
					<img class="icon-outside-links" src="http://mvsantiagowellness.com/img/ig.png"/>
				</a>
			</div>
		</div>
	';
	
	$top_nav .= '</div>';
?>