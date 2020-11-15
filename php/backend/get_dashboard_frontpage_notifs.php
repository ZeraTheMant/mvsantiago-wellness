<?php
//insert.php
	require '../dbconfig.php';

	$query = "SELECT COUNT(is_read) FROM message_inbox WHERE is_read = 1;";
	$stmt = $conn->prepare($query);
	$stmt->execute();	
	$result = $stmt->fetch();
	
	$newMessages = $result['COUNT(is_read)'];
	
	$query = "SELECT COUNT(approval_status) FROM testimonials WHERE approval_status = 0;";
	$stmt = $conn->prepare($query);
	$stmt->execute();	
	$result = $stmt->fetch();
	
	$newTestimonials = $result['COUNT(approval_status)'];

	$query = "SELECT COUNT(client_id) FROM clients WHERE user_level = 'client'";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$result = $stmt->fetch();
	
	$pendingApps = $result['COUNT(client_id)'];
	
	$query = "SELECT COUNT(is_cancelled) FROM appointments WHERE is_cancelled = 1";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$result = $stmt->fetch();
	
	$cancellations = $result['COUNT(is_cancelled)'];
	
	
	$output = '
		<div class="dashboard-info-box" id="upcoming-apps-box">
			<a href="backend_clients.php" class="info-box-link"></a>
			<div class="inner-box">
				<div>
					<p class="fat-icon icon-users" id="appointments-icon"></p>
				</div>
				<div>
					<p class="number">' . $pendingApps . '</p>
					<p class="box-info">Registered Users</p>
				</div>
			</div>
		</div>	
		
		<div class="dashboard-info-box" id="new-mesages-box" >
			<a href="backend_messages.php" class="info-box-link"></a>
			<div class="inner-box">
				<div>
					<p class="fat-icon" id="messages-icon">&#9993;</p>
				</div>
				<div>
					<p class="number">' . $newMessages . '</p>
					<p class="box-info">New Messages</p>
				</div>
			</div>
		</div>
		
		<div class="dashboard-info-box" id="new-testimonials-box" >
			<a href="backend_testimonials.php" class="info-box-link"></a>
			<div class="inner-box">
				<div>
					<p class="fat-icon icon-quotes-left" id="testimonials-icon"></p>
				</div>
				<div>
					<p class="number">' . $newTestimonials . '</p>
					<p class="box-info">New Testimonials</p>
				</div>
			</div>
		</div>
		
		<div class="dashboard-info-box" id="cancellations-box" >
			<a href="backend_appointments.php" class="info-box-link"></a>
			<div class="inner-box">
				<div>
					<p class="fat-icon" id="cancellations-icon">&#x274c;</p>
				</div>
				<div>
					<p class="number">' . $cancellations . '</p>
					<p class="box-info">Cancellations</p>
				</div>
			</div>
		</div>
	';
	
	echo $output;
?>