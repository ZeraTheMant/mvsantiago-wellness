function saveSettings(){
	const confirm_save = confirm("Save new clinic hours settings?");
	if(confirm_save){
		if(document.querySelector("#max-num").value > 0){
			var closed_days_array = [];
			for(var i=0; i<document.querySelectorAll('.closed-days').length; i++){
				var closed_day_dict = {
					month: document.querySelectorAll('.closed-days')[i].children[0].lastChild.value,
					day: document.querySelectorAll('.closed-days')[i].children[1].lastChild.value,
				}
				closed_days_array.push(closed_day_dict);
			}
			
			loaderPopupControl(true);
			$.ajax({
				url: 'update_clinic_hours.php',
				method: 'POST',
				data: {
					sunday: document.querySelector('#sunday-status').value,
					monday: document.querySelector('#monday-status').value,
					tuesday: document.querySelector('#tuesday-status').value,
					wednesday: document.querySelector('#wednesday-status').value,
					thursday: document.querySelector('#thursday-status').value,
					friday: document.querySelector('#friday-status').value,
					saturday: document.querySelector('#saturday-status').value,
					closed_days: closed_days_array,
					max_num: document.querySelector("#max-num").value
				},
				success: function(result){
					alert("Clinic hours settings successfully updated.");
					loaderPopupControl(false);
				}
			});
		}
		else{
			alert("Max number of online appointments must be greater than 0.");
		}
	}
}

function loadClinicHours(){
	document.querySelector('#sunday-status').value = working_schedule['Sunday'];
	document.querySelector('#monday-status').value = working_schedule['Monday'];
	document.querySelector('#tuesday-status').value = working_schedule['Tuesday'];
	document.querySelector('#wednesday-status').value = working_schedule['Wednesday'];
	document.querySelector('#thursday-status').value = working_schedule['Thursday'];
	document.querySelector('#friday-status').value = working_schedule['Friday'];
	document.querySelector('#saturday-status').value = working_schedule['Saturday'];
}

function populateMonthSelect(monthSelect){
	var jan = document.createElement('option');
	jan.textContent = "January";
	var feb = document.createElement('option');
	feb.textContent = "February";
	var mar = document.createElement('option');
	mar.textContent = "March";
	var apr = document.createElement('option');
	apr.textContent = 'April';
	var may = document.createElement('option');
	may.textContent = "May";
	var jun = document.createElement('option');
	jun.textContent = "June";
	var jul = document.createElement('option');
	jul.textContent = "July";
	var aug = document.createElement('option');
	aug.textContent = "August";
	var sep = document.createElement('option');
	sep.textContent = "September";
	var oct = document.createElement('option');
	oct.textContent = "October";
	var nov = document.createElement('option');
	nov.textContent = "November";
	var dec = document.createElement('option');
	dec.textContent = "December";
	monthSelect.appendChild(jan);
	monthSelect.appendChild(feb);
	monthSelect.appendChild(mar);
	monthSelect.appendChild(apr);
	monthSelect.appendChild(may);
	monthSelect.appendChild(jun);
	monthSelect.appendChild(jul);
	monthSelect.appendChild(aug);
	monthSelect.appendChild(sep);
	monthSelect.appendChild(oct);
	monthSelect.appendChild(nov);
	monthSelect.appendChild(dec);
}

function populateDaySelect(month_value, daySelect){
	for(var i=1; i<32; i++){
		if(i === 30 && month_value === 'February'){
			break;
		}
		if(i === 31 && (
			month_value === 'April' ||
			month_value === 'June' ||
			month_value === 'September' ||
			month_value === 'November'
			)){
			break;
		}
		var day = document.createElement('option');
		day.textContent = i;
		daySelect.appendChild(day);
	}
}

function changeDays(e){
	if(e.target.value === "April" ||
       e.target.value === "June" ||
	   e.target.value === "September" ||
	   e.target.value === "November"){
		   if(e.target.parentNode.nextElementSibling.lastChild.lastChild.value === "31"){
			  e.target.parentNode.nextElementSibling.lastChild.removeChild(e.target.parentNode.nextElementSibling.lastChild.lastChild);
		   }else if(e.target.parentNode.nextElementSibling.lastChild.lastChild.value === "29"){
			   var newDay = document.createElement('option');
			   newDay.textContent = "30";
			   e.target.parentNode.nextElementSibling.lastChild.appendChild(newDay);
		   }
	}else if(e.target.value === "February"){
		   if(e.target.parentNode.nextElementSibling.lastChild.lastChild.value === "31"){
			  e.target.parentNode.nextElementSibling.lastChild.removeChild(e.target.parentNode.nextElementSibling.lastChild.lastChild);
			  e.target.parentNode.nextElementSibling.lastChild.removeChild(e.target.parentNode.nextElementSibling.lastChild.lastChild);
		   }else if(e.target.parentNode.nextElementSibling.lastChild.lastChild.value === "30"){
			   e.target.parentNode.nextElementSibling.lastChild.removeChild(e.target.parentNode.nextElementSibling.lastChild.lastChild);
		   }
	}else{
		  if(e.target.parentNode.nextElementSibling.lastChild.lastChild.value === "30"){
			   var newDay = document.createElement('option');
			   newDay.textContent = "31";
			  e.target.parentNode.nextElementSibling.lastChild.appendChild(newDay);
		   }else if(e.target.parentNode.nextElementSibling.lastChild.lastChild.value === "29"){
			   var newDay = document.createElement('option');
			   newDay.textContent = "30";
			   e.target.parentNode.nextElementSibling.lastChild.appendChild(newDay);
			   var newDay = document.createElement('option');
			   newDay.textContent = "31";
			   e.target.parentNode.nextElementSibling.lastChild.appendChild(newDay);
		   }
	}
}

function deleteSpecifiedDay(e){
	e.target.parentNode.parentNode.parentNode.removeChild(e.target.parentNode.parentNode);
}

function loadClosedDays(){
	if(closed_days !== ''){
		closed_days = JSON.parse(closed_days);

		for(var i=0; i<closed_days.length; i++){
			createDayRow(closed_days[i].month, closed_days[i].day);
		}
	}
}

function createDayRow(month_val, day_val){
	var newRow = document.createElement('tr');
	newRow.setAttribute('class', 'closed-days');
	var newCol1 = document.createElement('td');
	var newCol2 = document.createElement('td');
	var newCol3 = document.createElement('td');
	
	var monthSelect = document.createElement('select');
	populateMonthSelect(monthSelect);
	monthSelect.value = month_val;
	monthSelect.addEventListener('change', changeDays);
	
	var daySelect = document.createElement('select');
	populateDaySelect('January', daySelect);
	daySelect.value = day_val;
	
	var deleteSpecifiedDayBtn = document.createElement('button');
	deleteSpecifiedDayBtn.setAttribute('type', 'button');
	deleteSpecifiedDayBtn.textContent = "Remove day";
	deleteSpecifiedDayBtn.addEventListener('click', deleteSpecifiedDay);
	
	newCol1.appendChild(monthSelect);
	newCol2.appendChild(daySelect);
	newCol3.appendChild(deleteSpecifiedDayBtn);
	newRow.appendChild(newCol1);
	newRow.appendChild(newCol2);
	newRow.appendChild(newCol3);
	
	document.querySelector('#specific-closed-days-table tbody').appendChild(newRow);
}

function addClosedDay(){
	createDayRow('January', '1');
}

loadClinicHours();
loadClosedDays();
save_settings_btn = document.querySelector('#save-clinic-hours');
add_closed_day_btn = document.querySelector('#add-closed-day');

save_settings_btn.addEventListener('click', saveSettings);
add_closed_day_btn.addEventListener('click', addClosedDay);
