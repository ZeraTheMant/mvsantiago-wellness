<?php
	include "../dbconfig.php";
	//http://localhost/itsp/img/logo_trans.png
	$query = "
		SELECT user_img FROM clients WHERE client_id = :client_id LIMIT 1
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':client_id' => $_SESSION['client_id']
		)
	);
	
	$img = $stmt->fetchAll();

    $account_link = '';
    $cms_link = '';

    if($_SESSION['user_level'] == 'admin'){
        $accounts_link = '
        		<div class="header-link">
    				<a href="backend_clients.php" id="dashboard-users-link" class="backend-sidebar-link"><span class="icon-users generic-icon"></span> &nbsp;&nbsp;Accounts</a>
    			</div>
        ';
        
        $cms_link = '
        		<div class="header-link">
    				<a href="backend_cms.php" id="dashboard-cms-link" class="backend-sidebar-link"><span class="icon-cogs generic-icon"></span> &nbsp;&nbsp;Front-end Control</a>
    			</div>
        ';
    }


	$sidebar = '
	<div class="backend-sidebar">
		<div class="backend-profile-img-container">
			<img id="backend-profile-img" src="' . $img[0]["user_img"] . '"/>
			<p class="backend-profile-name">' . $_SESSION['fname'] . '</p>
			<p class="backend-profile-name">' . $_SESSION['user_level'] . '</p>
		</div>
		
		<div class="links-container">
			<div class="header-link">
				<a href="backend_admin_dashboard.php" id="dashboard-home-link" class="backend-sidebar-link"><span class="icon-meter generic-icon"></span> &nbsp;&nbsp;Dashboard Home</a>
			</div>
			
			<div class="header-link">
				<a href="backend_messages.php" id="dashboard-messages-link" class="backend-sidebar-link"><span class="icon-msg generic-icon">&#x2709;</span> &nbsp;&nbsp;Message Inbox</a>
			</div>
			
			<div class="header-link">
				<a href="backend_appointments.php" id="dashboard-appointments-link" class="backend-sidebar-link"><span class="icon-calendar generic-icon"></span> &nbsp;&nbsp;Appointments</a>
			</div>
			
			<div class="header-link">
				<a href="backend_categories.php" id="dashboard-categories-link" class="backend-sidebar-link"><span class="icon-settings-for-categories generic-icon"></span> &nbsp;&nbsp;Categories</a>
			</div>
			
			<div class="header-link">
				<a href="backend_services.php" id="dashboard-services-link" class="backend-sidebar-link"><span class="icon-aid-kit generic-icon"></span> &nbsp;&nbsp;Services</a>
			</div>
			
            ' . $accounts_link . '
			
			<div class="header-link">
				<a href="backend_providers_beta.php" id="dashboard-providers-link" class="backend-sidebar-link"><span class="icon-doctor generic-icon"></span> &nbsp;&nbsp;Providers</a>
			</div>
			
			<div class="header-link">
				<a href="backend_testimonials.php" id="dashboard-testimonials-link" class="backend-sidebar-link"><span class="icon-quotes-left generic-icon"></span> &nbsp;&nbsp;Testimonials</a>
			</div>
			
			<div class="header-link">
				<a href="backend_clinic_hours.php" id="dashboard-hours-link" class="backend-sidebar-link"><span class="icon-clock generic-icon"></span> &nbsp;&nbsp;Business Hours</a>
			</div>
			
            ' . $cms_link . '
			
			<div class="header-link">
				<a href="backend_reports.php" id="dashboard-reports-link" class="backend-sidebar-link"><span class="icon-newspaper generic-icon"></span> &nbsp;&nbsp;Reports</a>
			</div>
			
			<div class="header-link">
				<a href="backend_user_info.php" id="dashboard-admin-settings-link" class="backend-sidebar-link"><span class="icon-admin-with-cogwheels generic-icon"></span> &nbsp;&nbsp;Account Settings</a>
			</div>
			
		</div>
	</div>
	';
	
	/*$query = "
		SELECT * FROM logo_img limit 1
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	$result = $stmt->fetch();
	$img2 = $result['img_link'];*/
	
	$logo_holder = '
		<div class="backend-logo-container">
			<div>
				<a href="http://mvsantiagowellness.com">
					<img class="img-banner-small" src="http://mvsantiagowellness.com/img/logo_trans.png"/>
				</a>
			</div>
			<h1>Backend Area</h1>
		</div>
	';
?>