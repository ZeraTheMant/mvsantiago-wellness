<?php
	require_once("tcpdf/tcpdf.php");

	function load_pdf_items(){
		return '
			<tbody>
				<tr style="border: 1px solid black;">
					<th style="color:white; background-color: green; border: 1px solid black;">Category</th>
					<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_POST['category'] . '</td>
				</tr>
				<tr style="border: 1px solid black">
					<th style="color:white; background-color: green; border: 1px solid black;">Service</th>
					<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_POST['service'] . '</td>
				</tr>
				<tr style="border: 1px solid black">
					<th style="color:white; background-color: green; border: 1px solid black;">Date</th>
					<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_POST['date'] . '</td>
				</tr>
				<tr style="border: 1px solid black">
					<th style="color:white; background-color: green; border: 1px solid black;">Time</th>
					<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_POST['start'] . ' to ' . $_POST['end'] . '</td>
				</tr>
				<tr style="border: 1px solid black">
					<th style="color:white; background-color: green; border: 1px solid black;">Provider</th>
					<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_POST['provider'] . '</td>
				</tr>
				<tr style="border: 1px solid black">
					<th style="color:white; background-color: green; border: 1px solid black;">Client</th>
					<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_POST['client'] . '</td>
				</tr>
				<tr style="border: 1px solid black">
					<th style="color:white; background-color: green; border: 1px solid black;">Status</th>
					<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_POST['status'] . '</td>
				</tr>
		';
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
    		<p style="text-align: center;"><i>Your appointment report</i></p>
    		
    		<table style="text-align: center; border: 1px solid black; border-collapse: collapse; margin-top: 100px;">
    
    	';  
    	$content .= load_pdf_items();
    	
    	if($_POST['status'] == "Finished"){
    		$content .= '
    			<tr style="border: 1px solid black">
    				<th style="color:white; background-color: green; border: 1px solid black;">Remarks</th>
    				<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_POST['remarks'] . '</td>
    			</tr>
    		';
    	}
    	else if($_POST['status'] == "Cancelled"){
    		$content .= '
    			<tr style="border: 1px solid black">
    				<th style="color:white; background-color: green; border: 1px solid black;">Cancellation Reason</th>
    				<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_POST['reason'] . '</td>
    			</tr>
    		';
    	}
    	
    	$content .= '</tbody></table>';
    
    	$obj_pdf->writeHTML($content); 
    	ob_end_clean();		
    	$obj_pdf->Output('sample.pdf', 'D'); 
	}
?>