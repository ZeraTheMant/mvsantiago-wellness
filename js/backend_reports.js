function viewReport(){
	return null;
}


function loadData(link, chosen_method, sent_data){
	$('.backend-body-content').html('');
	loaderPopupControl(true);
	$.ajax({
		url: link,
		method: chosen_method,
		data: sent_data,
		success: function(result){
			$('.backend-body-content').html(result);
			
			document.querySelector("#prev-reports").addEventListener('click', changePage);
			document.querySelector("#next-reports").addEventListener('click', changePage);
			
			prevReportsDisplay();
			
		
			
			if(document.querySelectorAll('.actual-row').length < 10){
				document.querySelector("#next-reports").style.display = 'none';
			}
			else{
				document.querySelector("#next-reports").style.display = 'inline';
			}
			
			loaderPopupControl(false);
		}
	});
}

function changePage(e){
	if(e.target.id == "prev-reports"){
		offset -= 10;
	}
	else{
		offset += 10;
	}

	loadData('get_reports.php', 'GET', returnAppointmentType(document.querySelector('#reports-filter').value));
	prevReportsDisplay();
}

function returnAppointmentType(reportView){
	if(reportView === "Pending appointments"){
		return {from_date: document.querySelector("#from-date").value, to_date: document.querySelector("#to-date").value, client_id: userID, user_level: user_level, offset: offset, provider_id: provID, view: '', is_finished: 0, is_cancelled: 0, is_confirmed: 0};
	}
	else if(reportView === "Confirmed appointments"){
		return {from_date: document.querySelector("#from-date").value, to_date: document.querySelector("#to-date").value, client_id: userID, user_level: user_level, offset: offset, provider_id: provID, view: '', is_finished: 0, is_cancelled: 0, is_confirmed: 1};
	}
	else if(reportView === "Finished appointments"){
		return {from_date: document.querySelector("#from-date").value, to_date: document.querySelector("#to-date").value, client_id: userID, user_level: user_level, offset: offset, provider_id: provID, view: '', is_finished: 1, is_cancelled: 0, is_confirmed: 1};
	}
	else if(reportView === "Cancelled appointments"){
		return {from_date: document.querySelector("#from-date").value, to_date: document.querySelector("#to-date").value, client_id: userID, user_level: user_level, offset: offset, provider_id: provID, view: '', is_finished: 0, is_cancelled: 1, is_confirmed: 0};
	}
	else{
		return {from_date: document.querySelector("#from-date").value, to_date: document.querySelector("#to-date").value, client_id: userID, user_level: user_level, offset: offset, provider_id: provID, view: 'all', is_finished: 0, is_cancelled: 0, is_confirmed: 0};
	}
}

function prevReportsDisplay(){
	if(offset == 0){
		document.querySelector("#prev-reports").style.display = 'none';
	}
	else{
		document.querySelector("#prev-reports").style.display = 'inline';
	}
}

function changeReportsView(){
	if(document.querySelector("#to-date").value == "" || document.querySelector("#from-date").value == ""){
		alert("Please enter date filter values.");
	}
	else{
		offset = 0;
    	loadData('get_reports.php', 'GET', returnAppointmentType(document.querySelector('#reports-filter').value));
	}
}

function changeDateFilter(e){
	if(moment(document.querySelector('#to-date').value).isBefore(moment(document.querySelector('#from-date').value))){
		alert("To date value must be after the from date.");
		document.querySelector('#to-date').value = "";
		document.querySelector('#from-date').value = "";
	}
}

var offset = 0;

document.querySelector('#search-btn').addEventListener('click', changeReportsView);
document.querySelector('#to-date').addEventListener('change', changeDateFilter);
document.querySelector('#from-date').addEventListener('change', changeDateFilter);
//loadData('get_reports.php', 'GET', {client_id: userID, user_level: user_level, offset: offset, view: 'all', provider_id: provID});
