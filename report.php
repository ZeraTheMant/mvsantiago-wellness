<?php
	session_start();
	include 'front_end_top_nav.php';
	
	function load_pdf_items(){
		if(!isset($_GET['reason'])){
			return '
				<tbody>
					<tr style="border: 1px solid black;">
						<th style="color:white; background-color: green; border: 1px solid black;">Category</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_GET['category'] . '</td>
					</tr>
					<tr style="border: 1px solid black">
						<th style="color:white; background-color: green; border: 1px solid black;">Service</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_GET['service'] . '</td>
					</tr>
					<tr style="border: 1px solid black">
						<th style="color:white; background-color: green; border: 1px solid black;">Date</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_GET['date'] . '</td>
					</tr>
					<tr style="border: 1px solid black">
						<th style="color:white; background-color: green; border: 1px solid black;">Time</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_GET['start'] . ' to ' . $_GET['end'] . '</td>
					</tr>
					<tr style="border: 1px solid black">
						<th style="color:white; background-color: green; border: 1px solid black;">Provider</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_GET['provider'] . '</td>
					</tr>
					<tr style="border: 1px solid black">
						<th style="color:white; background-color: green; border: 1px solid black;">Client</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_GET['client'] . '</td>
					</tr>
					<tr style="border: 1px solid black">
						<th style="color:white; background-color: green; border: 1px solid black;">Remarks</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_GET['remarks'] . '</td>
					</tr>
				</tbody>
			';
		}
		else{
			return '
				<tbody>
					<tr style="border: 1px solid black;">
						<th style="color:white; background-color: green; border: 1px solid black;">Category</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_GET['category'] . '</td>
					</tr>
					<tr style="border: 1px solid black">
						<th style="color:white; background-color: green; border: 1px solid black;">Service</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_GET['service'] . '</td>
					</tr>
					<tr style="border: 1px solid black">
						<th style="color:white; background-color: green; border: 1px solid black;">Date</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_GET['date'] . '</td>
					</tr>
					<tr style="border: 1px solid black">
						<th style="color:white; background-color: green; border: 1px solid black;">Time</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_GET['start'] . ' to ' . $_GET['end'] . '</td>
					</tr>
					<tr style="border: 1px solid black">
						<th style="color:white; background-color: green; border: 1px solid black;">Provider</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_GET['provider'] . '</td>
					</tr>
					<tr style="border: 1px solid black">
						<th style="color:white; background-color: green; border: 1px solid black;">Client</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_GET['client'] . '</td>
					</tr>
					<tr style="border: 1px solid black">
						<th style="color:white; background-color: green; border: 1px solid black;">Cancellation Reason</th>
						<td style="padding: 5px; border: 1px solid black; font-size: 12px;">' . $_GET['reason'] . '</td>
					</tr>
				</tbody>
			';
		}
	}
	
	function load_result(){
		if(!isset($_GET['reason'])){
			return '
				<div id="report-info">
					<div>
						<div class="colored-div">Category</div>
						<div class="colored-div">Service</div>
						<div class="colored-div">Date</div>
						<div class="colored-div">Time</div>
						<div class="colored-div">Provider</div>
						<div class="colored-div">Client</div>
						<div class="colored-div">Remarks</div>
					</div>
					
					<div id="report-content">
						<div class="white-div">' . $_GET['category'] . '</div>
						<div class="white-div">' . $_GET['service'] . '</div>
						<div class="white-div">' . $_GET['date'] . '</div>
						<div class="white-div">' . $_GET['start'] . ' to ' . $_GET['end'] . '</div>
						<div class="white-div">' . $_GET['provider'] . '</div>
						<div class="white-div">' . $_GET['client'] . '</div>
						<div class="white-div">' . $_GET['remarks'] . '</div>
					</div>
				</div>
			';
		}
		else{
			return '
				<div id="report-info">
					<div>
						<div class="colored-div">Category</div>
						<div class="colored-div">Service</div>
						<div class="colored-div">Date</div>
						<div class="colored-div">Time</div>
						<div class="colored-div">Provider</div>
						<div class="colored-div">Client</div>
						<div class="colored-div">Cancellation Reason</div>
					</div>
					
					<div id="report-content">
						<div class="white-div">' . $_GET['category'] . '</div>
						<div class="white-div">' . $_GET['service'] . '</div>
						<div class="white-div">' . $_GET['date'] . '</div>
						<div class="white-div">' . $_GET['start'] . ' to ' . $_GET['end'] . '</div>
						<div class="white-div">' . $_GET['provider'] . '</div>
						<div class="white-div">' . $_GET['client'] . '</div>
						<div class="white-div">' . $_GET['reason'] . '</div>
					</div>
				</div>
			';
		}
	}
	
	if(isset($_POST["create_pdf"])){
		require_once("php/backend/tcpdf/tcpdf.php");


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
		$content .= '</table>';

		$obj_pdf->writeHTML($content); 
		ob_end_clean();		
		$obj_pdf->Output('sample.pdf', 'I'); 
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
		const user_img = '<?php if(!isset($_SESSION['user_img'])){echo "";}else{echo $_SESSION['user_img'];} ?>';
		const email = '<?php if(!isset($_SESSION['email'])){echo "";}else{echo $_SESSION['email'];} ?>'; 
		const user_level = '<?php if(!isset($_SESSION['user_level'])){echo "";}else{echo $_SESSION['user_level'];} ?>';
		const user_id = '<?php if(!isset($_SESSION['client_id'])){echo "";}else{echo $_SESSION['client_id'];} ?>';
		const dob = '<?php if(!isset($_SESSION['dob'])){echo "";}else{echo $_SESSION['dob'];} ?>';
	</script>
	<title>Appointment Report | M.V. Santiago Wellness Center</title>
</head>
<body>
	<div class="wrapper">
		<?php
			echo $top_nav;
			include 'frontend_navbar.php';
		?>
		
		<div id="report-container">
			<h1>Appointment Report</h1>
			<div id="report-main">
			<?php
				echo load_result();
			?>
			</div>
			<form method="post"> 
				<div style="text-align: center;">
					<input style="border: 0; font-size: 1.5rem; background-color: green; padding: 20px; color: white;" type="submit" name="create_pdf" class="modal-btn" value="Generate PDF" />  
				</div>
			</form> 
		</div>
		<?php
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
		
		for(var i=0; i<document.querySelectorAll('.colored-div').length; i++){
			var row = window.getComputedStyle(document.querySelectorAll('.colored-div')[i]);
			document.querySelectorAll('.white-div')[i].style.minHeight = stringify(parseInt(row.getPropertyValue("height").slice(0, -2)));
		}
	</script>
	<script src="js/loaderPopupControl.js"></script>
	<script src="js/login_frontend.js"></script>
	<script src="js/login_db.js"></script>
	<script src="js/notifications.js"></script>
</body>
</html>