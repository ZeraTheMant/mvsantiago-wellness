<?php
	include "../dbconfig.php";
	
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


	$sidebar = '
	<div class="backend-sidebar">
		<div class="backend-profile-img-container">
			<img id="backend-profile-img" src="' . $img[0]["user_img"] . '"/>
			<p class="backend-profile-name">' . $_SESSION['fname'] . '</p>
			<p class="backend-profile-name">' . $_SESSION['user_level'] . '</p>
		</div>
		
		<div class="links-container">
			<div class="header-link">
				<a href="backend_provider_dashboard.php" id="dashboard-home-link" class="backend-sidebar-link"><span class="icon-meter generic-icon"></span> &nbsp;&nbsp;Dashboard Home</a>
			</div>
					
			<div class="header-link">
				<a href="backend_provider_appointments.php" id="dashboard-appointments-link" class="backend-sidebar-link"><span class="icon-calendar generic-icon"></span> &nbsp;&nbsp;Appointments</a>
			</div>
			
			<div class="header-link">
				<a href="backend_provider_categories_services.php" id="dashboard-categories-link" class="backend-sidebar-link"><span class="icon-settings-for-categories generic-icon"></span> &nbsp;&nbsp;My Services</a>
			</div>
			
			<div class="header-link">
				<a href="backend_provider_clients_services.php" id="dashboard-users-link" class="backend-sidebar-link"><span class="icon-users generic-icon"></span> &nbsp;&nbsp;My Clients</a>
			</div>
			
			<div class="header-link">
				<a href="backend_reports2.php" id="dashboard-reports-link" class="backend-sidebar-link"><span class="icon-newspaper generic-icon"></span> &nbsp;&nbsp;Reports</a>
			</div>
			
			<div class="header-link">
				<a href="backend_user_info.php" id="dashboard-admin-settings-link" class="backend-sidebar-link"><span class="icon-admin-with-cogwheels generic-icon"></span> &nbsp;&nbsp;Account Settings</a>
			</div>
		</div>
	</div>
	';
	
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