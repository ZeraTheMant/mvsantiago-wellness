<?php
	require '../dbconfig.php';
	
	$data = array();

	$query = "
		select 
			appointments.appointment_id,
			appointments.service_id,
			appointments.provider_id,
			appointments.client_id,
			appointments.start_datetime,
			appointments.end_datetime,
			services.service_name,
			category.category_name,
			provider.fname as prov_fname,
			provider.mname as prov_mname,
			provider.lname as prov_lname,
			provider.name_ext as prov_name_ext,
			clients.fname,
			clients.mname,
			clients.lname,
			clients.name_ext
		FROM
			appointments
		inner join
			providers_beta
		on
			appointments.provider_id = providers_beta.provider_id
		inner join 
			services
		on
			appointments.service_id = services.service_id
		inner join category
		on
			services.category_id = category.category_id
		inner join 
			clients as provider
		on
			providers_beta.client_id = provider.client_id
		inner join 
			clients
		ON
			appointments.client_id = clients.client_id
		where 
			is_finished = 0 
		and 
			is_cancelled = 0 
		and
			start_datetime like '" . $_GET['start_datetime'] . "%'
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	$result = $stmt->fetchAll();
	
	foreach($result as $row){
		$title = "";

		$data[] = array(
			'appointment_id' => $row['appointment_id'],
			'title' => $row['service_name'],
			'start' => $row['start_datetime'],
			'end' => $row['end_datetime'],
			'category' => $row['category_name'],
			'provider_name' => $row['prov_fname'] . ', ' . $row['prov_mname'] . ' ' . $row['prov_lname'] . ' ' . $row['prov_name_ext'],
			'client_name' => $row['fname' ] . ', ' . $row['mname'] . ' ' . $row['lname'] . ' ' . $row['name_ext'],
		);
	}

	
	echo json_encode($data);
?>