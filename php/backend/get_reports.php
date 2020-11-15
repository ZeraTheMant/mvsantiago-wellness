<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	
	if($_GET['user_level'] == "admin"){
		if($_GET['view'] == "all"){
			$query = "
			    SELECT 
			        appointments.*,
			       	services.service_name,
    				category.category_name,
    				clients.lname as prov_lname,
    				clients.mname as prov_mname,
    				clients.fname as prov_fname,
    				clients.name_ext as prov_name_ext,
    				customer.lname,
    				customer.mname,
    				customer.fname,
    				providers_beta.provider_level,
    				providers_beta.client_id
    			FROM
    				appointments
    			INNER JOIN
    				services
    			ON
    				appointments.service_id = services.service_id
    			INNER JOIN
    				providers_beta
    			ON
    				appointments.provider_id = providers_beta.provider_id
    			INNER JOIN 
    				category
    			ON
    				category.category_id = services.category_id
    			INNER JOIN 
    				clients
    			ON
    				providers_beta.client_id = clients.client_id
    			INNER JOIN 
    				clients as customer
    			ON
    				appointments.client_id = customer.client_id
			    WHERE 
			        appointments.start_datetime >= :from_date 
			    AND 
			        appointments.start_datetime <= :to_date
			    ORDER BY 
			        appointments.start_datetime DESC 
			    LIMIT 10 
			    OFFSET " . $_GET['offset'] . "
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':from_date' => $_GET['from_date'],
					':to_date' => $_GET['to_date'] . ' 23:59:59'
				)
			);	
		}
		else{
			$query = "
			    SELECT 
			        appointments.*,
			       	services.service_name,
    				category.category_name,
    				clients.lname as prov_lname,
    				clients.mname as prov_mname,
    				clients.fname as prov_fname,
    				clients.name_ext as prov_name_ext,
    				customer.lname,
    				customer.mname,
    				customer.fname,
    				providers_beta.provider_level,
    				providers_beta.client_id
    			FROM
    				appointments
    			INNER JOIN
    				services
    			ON
    				appointments.service_id = services.service_id
    			INNER JOIN
    				providers_beta
    			ON
    				appointments.provider_id = providers_beta.provider_id
    			INNER JOIN 
    				category
    			ON
    				category.category_id = services.category_id
    			INNER JOIN 
    				clients
    			ON
    				providers_beta.client_id = clients.client_id
    			INNER JOIN 
    				clients as customer
    			ON
    				appointments.client_id = customer.client_id
			    WHERE 
			        appointments.start_datetime >= :from_date 
			    AND 
			        appointments.start_datetime <= :to_date
			    AND
			        appointments.is_finished = :is_finished
			    AND
			        appointments.is_cancelled = :is_cancelled
			    AND
			        appointments.is_confirmed = :is_confirmed
			    ORDER BY 
			        appointments.start_datetime DESC 
			    LIMIT 10 
			    OFFSET " . $_GET['offset'] . "
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':from_date' => $_GET['from_date'],
					':to_date' => $_GET['to_date'] . ' 23:59:59',
					':is_finished' => $_GET['is_finished'],
					':is_cancelled' => $_GET['is_cancelled'],
					':is_confirmed' => $_GET['is_confirmed']
				)
			);	
		}
	}
		else if($_GET['user_level'] == "client"){
		if($_GET['view'] == "all"){
			$query = "
			    SELECT 
			        appointments.*,
			       	services.service_name,
    				category.category_name,
    				clients.lname as prov_lname,
    				clients.mname as prov_mname,
    				clients.fname as prov_fname,
    				clients.name_ext as prov_name_ext,
    				customer.lname,
    				customer.mname,
    				customer.fname,
    				providers_beta.provider_level,
    				providers_beta.client_id
    			FROM
    				appointments
    			INNER JOIN
    				services
    			ON
    				appointments.service_id = services.service_id
    			INNER JOIN
    				providers_beta
    			ON
    				appointments.provider_id = providers_beta.provider_id
    			INNER JOIN 
    				category
    			ON
    				category.category_id = services.category_id
    			INNER JOIN 
    				clients
    			ON
    				providers_beta.client_id = clients.client_id
    			INNER JOIN 
    				clients as customer
    			ON
    				appointments.client_id = customer.client_id
			    WHERE 
			        appointments.start_datetime >= :from_date 
			    AND 
			        appointments.start_datetime <= :to_date
			    AND
			        appointments.client_id = :client_id
			    ORDER BY 
			        appointments.start_datetime DESC 
			    LIMIT 10 
			    OFFSET " . $_GET['offset'] . "
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':from_date' => $_GET['from_date'],
					':to_date' => $_GET['to_date'] . ' 23:59:59',
					':client_id' => $_GET['client_id']
				)
			);	
		}
		else{
			$query = "
			    SELECT 
			        appointments.*,
			       	services.service_name,
    				category.category_name,
    				clients.lname as prov_lname,
    				clients.mname as prov_mname,
    				clients.fname as prov_fname,
    				clients.name_ext as prov_name_ext,
    				customer.lname,
    				customer.mname,
    				customer.fname,
    				providers_beta.provider_level,
    				providers_beta.client_id
    			FROM
    				appointments
    			INNER JOIN
    				services
    			ON
    				appointments.service_id = services.service_id
    			INNER JOIN
    				providers_beta
    			ON
    				appointments.provider_id = providers_beta.provider_id
    			INNER JOIN 
    				category
    			ON
    				category.category_id = services.category_id
    			INNER JOIN 
    				clients
    			ON
    				providers_beta.client_id = clients.client_id
    			INNER JOIN 
    				clients as customer
    			ON
    				appointments.client_id = customer.client_id
			    WHERE 
			        appointments.start_datetime >= :from_date 
			    AND 
			        appointments.start_datetime <= :to_date
			    AND
			        appointments.is_finished = :is_finished
			    AND
			        appointments.is_cancelled = :is_cancelled
			    AND
			        appointments.is_confirmed = :is_confirmed
			    AND 
			        appointments.client_id = :client_id
			    ORDER BY 
			        appointments.start_datetime DESC 
			    LIMIT 10 
			    OFFSET " . $_GET['offset'] . "
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':from_date' => $_GET['from_date'],
					':to_date' => $_GET['to_date'] . ' 23:59:59',
					':is_finished' => $_GET['is_finished'],
					':is_cancelled' => $_GET['is_cancelled'],
					':is_confirmed' => $_GET['is_confirmed'],
					':client_id' => $_GET['client_id']
				)
			);	
		}
	}
	else{
		if($_GET['view'] == "all"){
			$query = "
			    SELECT 
			        appointments.*,
			       	services.service_name,
    				category.category_name,
    				clients.lname as prov_lname,
    				clients.mname as prov_mname,
    				clients.fname as prov_fname,
    				clients.name_ext as prov_name_ext,
    				customer.lname,
    				customer.mname,
    				customer.fname,
    				providers_beta.provider_level,
    				providers_beta.client_id
    			FROM
    				appointments
    			INNER JOIN
    				services
    			ON
    				appointments.service_id = services.service_id
    			INNER JOIN
    				providers_beta
    			ON
    				appointments.provider_id = providers_beta.provider_id
    			INNER JOIN 
    				category
    			ON
    				category.category_id = services.category_id
    			INNER JOIN 
    				clients
    			ON
    				providers_beta.client_id = clients.client_id
    			INNER JOIN 
    				clients as customer
    			ON
    				appointments.client_id = customer.client_id
			    WHERE 
			        appointments.start_datetime >= :from_date 
			    AND 
			        appointments.start_datetime <= :to_date
			    AND
			        appointments.provider_id = :provider_id
			    ORDER BY 
			        appointments.start_datetime DESC 
			    LIMIT 10 
			    OFFSET " . $_GET['offset'] . "
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':from_date' => $_GET['from_date'],
					':to_date' => $_GET['to_date'] . ' 23:59:59',
					':provider_id' => $_GET['provider_id']
				)
			);	
		}
		else{
			$query = "
			    SELECT 
			        appointments.*,
			       	services.service_name,
    				category.category_name,
    				clients.lname as prov_lname,
    				clients.mname as prov_mname,
    				clients.fname as prov_fname,
    				clients.name_ext as prov_name_ext,
    				customer.lname,
    				customer.mname,
    				customer.fname,
    				providers_beta.provider_level,
    				providers_beta.client_id
    			FROM
    				appointments
    			INNER JOIN
    				services
    			ON
    				appointments.service_id = services.service_id
    			INNER JOIN
    				providers_beta
    			ON
    				appointments.provider_id = providers_beta.provider_id
    			INNER JOIN 
    				category
    			ON
    				category.category_id = services.category_id
    			INNER JOIN 
    				clients
    			ON
    				providers_beta.client_id = clients.client_id
    			INNER JOIN 
    				clients as customer
    			ON
    				appointments.client_id = customer.client_id
			    WHERE 
			        appointments.start_datetime >= :from_date 
			    AND 
			        appointments.start_datetime <= :to_date
			    AND
			        appointments.is_finished = :is_finished
			    AND
			        appointments.is_cancelled = :is_cancelled
			    AND
			        appointments.is_confirmed = :is_confirmed
			    AND 
			        appointments.provider_id = :provider_id
			    ORDER BY 
			        appointments.start_datetime DESC 
			    LIMIT 10 
			    OFFSET " . $_GET['offset'] . "
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':from_date' => $_GET['from_date'],
					':to_date' => $_GET['to_date'] . ' 23:59:59',
					':is_finished' => $_GET['is_finished'],
					':is_cancelled' => $_GET['is_cancelled'],
					':is_confirmed' => $_GET['is_confirmed'],
					':provider_id' => $_GET['provider_id']
				)
			);	
		}
	}


	$output = '';
	
	if($stmt->rowCount() > 0){
		$output .= '
			<h3>Appointment Reports</h3>
			<p id="instruction">Contains appointment reports which can be downloaded.</p>
			<br>
			<table>
				<tr>
    				<th>ID</th>
    				<th>Category</th>
    				<th>Service</th>
    				<th>Provider</th>
    				<th>Booked by</th>
    				<th>Start</th>
    				<th>End</th>
				</tr>

		';
		$result = $stmt->fetchAll();
		foreach($result as $row){
		    //' . $row['link'] . '

			
    		$provider_ext = "";
    		if($row['provider_level'] == 'Doctor' || $row['provider_level'] == 'Dentist'){
    			$provider_ext = "Dr. ";
    		}
    		
    		$status = 'Pending';
    		if($row['is_finished'] == '1'){
    		    $status = 'Finished';
    		}
    		else if($row['is_cancelled'] == '1'){
    		    $status = 'Cancelled';
    		}
    		else{
    		    $status = 'Confirmed';
    		}
    		
    		$remarks = $row['remarks'];
    
            if($row['remarks'] == ""){
                $remarks = "Appointment finished. The doctor/therapist has not added any remarks yet.";
            }
            
            $sdate = new DateTime($row['start_datetime']);
            $edate = new DateTime($row['end_datetime']);
            
            $linkVal = '			
				<form action="downloadPDF.php" method="post">
					<input type="text" style="display:none;" value="'. $row['category_name'] . '" name="category"/>
					
				    <input type="text" style="display:none;" value="'. $row['service_name'] . '" name="service"/>
				    
				    <input type="text" style="display:none;" value="' . $provider_ext . $row['prov_fname'] . ' ' . $row['prov_mname'] . ' ' . $row['prov_lname'] . ' ' . $row['prov_name_ext'] . '" name="provider"/>
				    
				    <input type="text" style="display:none;" value="' . $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'] . '" name="client"/>		
				    
				    <input type="text" style="display:none;" value="' . $sdate->format("F d Y") . '" name="date"/>
				    
				    <input type="text" style="display:none;" value="' . $sdate->format("h:i A") . '" name="start"/>
				    
				    <input type="text" style="display:none;" value="' . $edate->format("h:i A") . '" name="end"/>
				    
				    <input type="text" style="display:none;" value="'. $status. '" name="status"/>
				    
				    <input type="text" style="display:none;" value="'. $row['cancellation_reason'] . '" name="reason"/>
				    
				    <input type="text" style="display:none;" value="'. $remarks. '" name="remarks"/>
					<button type="submit" name="dl-pdf" style="cursor: pointer;">Download PDF</button>
				</form>
			';
    		
    		$output .='
    			<tr class="actual-row">
    				<td>MVRPRT_' . $row['appointment_id'] . '</td>
    				<td>' . $row['category_name'] . '</td>
    				<td>' . $row['service_name'] . '</td>
    				<td>' . $provider_ext . $row['prov_fname'] . ' ' . $row['prov_mname'] . ' ' . $row['prov_lname'] . ' ' . $row['prov_name_ext'] . '</td>
    				<td>' . $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'] . '</td>
    				<td>' . $row['start_datetime'] . '</td>
    				<td>' . $row['end_datetime'] . '</td>
    				<td>' . $linkVal . '</td>
    				<td style="display: none;">' . $row['cancellation_reason'] . '</td>
    				<td style="display: none;">' . $remarks . '</td>
    				<td style="display: none;">' . $status . '</td>
    			</tr>
    		';
		}
		
		$output .= '
			</table>
		';
		echo $output;
	}
	else{
		echo '<p>No reports found in database.</p>';
	}

?>