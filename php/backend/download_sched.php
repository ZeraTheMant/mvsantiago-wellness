<?php
	require_once("tcpdf/tcpdf.php");
	require '../dbconfig.php';
	
	$filename = 'all_schedules.pdf';

	function load_pdf_items($conn){
		$output = '';
		if(!isset($_POST['provider_id'])){
			$query = "
				SELECT 
					providers_beta.days_worked,
					providers_beta.provider_level,
					clients.fname,
					clients.mname,
					clients.lname
				FROM	
					providers_beta
				INNER JOIN
					clients
				ON
					providers_beta.client_id = clients.client_id
			";
			$stmt = $conn->prepare($query);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($result as $row){
				$prefix = '';
				if($row['provider_level'] == 'Doctor'){
					$prefix = 'Dr. ';
				}
				
				$fullName = $prefix . $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'];
				$sched = json_decode($row['days_worked'], true);
				$schedText = '';
				
				foreach($sched as $key => $val){
					$start = new DateTime("2000-01-01 " . $val['start']);
					$end = new DateTime("2000-01-01 " . $val['end']);
					//$schedText .= $key . ': ' . var_dump($val) . '<br>';
					$schedText .= $key . ': ' . $start->format("H:i A") . ' to ' . $end->format("h:i A") . '<br>';
				}
				
				$output .= '
					<table style="text-align: center; border: 1px solid black; border-collapse: collapse; margin-top: 10px; margin-bottom: 40px">
						<tr style="border: 1px solid black;">
							<th style="color:white; background-color: green; border: 1px solid black;">Name</th>
							<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $fullName . '</td>
						</tr>
						<tr style="border: 1px solid black;">
							<th style="color:white; background-color: green; border: 1px solid black;">Schedule</th>
							<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $schedText . '</td>
						</tr>
					</table><br><br><br><br>
				';
			}
		}
		else{
			$query = "
				SELECT 
					providers_beta.days_worked,
					providers_beta.provider_level,
					clients.fname,
					clients.mname,
					clients.lname
				FROM	
					providers_beta
				INNER JOIN
					clients
				ON
					providers_beta.client_id = clients.client_id
				WHERE
					providers_beta.provider_id = :provider_id
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':provider_id' => $_POST['provider_id']
				)
			);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$prefix = '';
			if($result['provider_level'] == 'Doctor'){
				$prefix = 'Dr. ';
			}
			
			$fullName = $prefix . $result['fname'] . ' ' . $result['mname'] . ' ' . $result['lname'];
			$sched = json_decode($result['days_worked'], true);
			$schedText = '';
			
			foreach($sched as $key => $val){
				$start = new DateTime("2000-01-01 " . $val['start']);
				$end = new DateTime("2000-01-01 " . $val['end']);
				//$schedText .= $key . ': ' . var_dump($val) . '<br>';
				$schedText .= $key . ': ' . $start->format("H:i A") . ' to ' . $end->format("h:i A") . '<br>';
			}
			
			$GLOBALS['filename'] = $result['lname'] . '_' . $result['fname'] . '_schedule.pdf';
			
			$output .= '
				<table style="text-align: center; border: 1px solid black; border-collapse: collapse; margin-top: 10px; margin-bottom: 40px">
					<tr style="border: 1px solid black;">
						<th style="color:white; background-color: green; border: 1px solid black;">Name</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $fullName . '</td>
					</tr>
					<tr style="border: 1px solid black;">
						<th style="color:white; background-color: green; border: 1px solid black;">Schedule</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $schedText . '</td>
					</tr>
				</table><br><br><br><br>
			';
		}
		return $output;
	}
	
	if(isset($_POST['dl-pdf'])){
		$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
		$obj_pdf->SetCreator(PDF_CREATOR);  
		$obj_pdf->SetTitle("PDF Appointment Report");  
		$obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
		$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
		$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
		$obj_pdf->SetDefaultMonospacedFont('helvetica');  
		$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
		$obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
		$obj_pdf->setPrintHeader(false);  
		$obj_pdf->setPrintFooter(false);  
		$obj_pdf->SetAutoPageBreak(TRUE, 10);  
		$obj_pdf->SetFont('helvetica', '', 12);  
		$obj_pdf->AddPage();  
		$content = '
			<h3 align="center">M.V. Santiago Wellness Center</h3><br /><br />
			<p style="text-align: center; margin-bottom: 90px;"><i>Schedule report</i></p>
		';  
		$content .= load_pdf_items($conn);
		//echo $content;

		$obj_pdf->writeHTML($content); 
		ob_end_clean();		
		$obj_pdf->Output($filename, 'D');
	}

	//$obj_pdf->Output("file.pdf", 'F');  //save pdf
	//$obj_pdf->Output('file.pdf', 'I'); 
	
	

?>