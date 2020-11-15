function loadDashboardInfo(){
	$('#statistics-boxes').html('');
	loaderPopupControl(true);
	$.ajax({
		url: 'get_dashboard_frontpage_notifs.php',
		method: 'GET',
		success: function(result){
			$('#statistics-boxes').html(result);
			//loadPresentTasks();
		}
	});
}

function loadActivityFeed(){
	$('#activity-feed-content').html('');
	$.ajax({
		url: 'get_activity_feed.php',
		method: 'GET',
		data: {client_id: userID, user_level: user_level, offset: offset},
		success: function(result){
			$('#activity-feed-content').html(result);
			removeFeedIcons = document.querySelectorAll(".feed-close-icon");

			for(var i=0; i<removeFeedIcons.length; i++){
				removeFeedIcons[i].addEventListener("click", removeFeedRow);
			}
			
			if(document.querySelectorAll('.feed-row').length < 10){
				document.querySelector("#next-page").disabled = true;
			}
			else{
				document.querySelector("#next-page").disabled = false;
			}
			//loadPresentTasks();
		}
	});
}

function removeFeedRow(e){
	e.target.parentNode.parentNode.parentNode.removeChild(e.target.parentNode.parentNode);
	$.ajax({
		url: "update_activity_feed_content.php",
		method: "POST",
		data: {feed_id: e.target.parentNode.parentNode.id}
	});
}

function changePage(e){
	if(e.target.id == "prev-page"){
		offset -= 10;
	}
	else{
		offset += 10;
	}
	loadActivityFeed();
	
	if(offset == 0){
		document.querySelector("#prev-page").disabled = true;
	}
	else{
		document.querySelector("#prev-page").disabled = false;
	}
}

$(document).ready(function(){
	$.ajax({
		url: "get_appointment_stats.php",
		method: "GET",
		success: function(result){
			var appointments = JSON.parse(result);
			var charData = Object.values(appointments);


			let lineChart = new Chart(CHART, {
				type: 'line',
				data: {
					labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
					datasets: [
						{
							label: "Number of Appointments",
							fill: false,
							lineTension: 0.1,
							backgroundColor: 'rgba(0, 255, 0, 0.4)',
							borderColor: 'rgba(0, 255, 0, 1)',
							borderCapStyle: 'butt',
							borderDash:[],
							borderDashOffset: 0.0,
							borderJoinStyle: 'miter',
							data: charData,
						}
					]
				},
				options: {

					scales: {
						yAxes: [
							{
								stacked: false,
								ticks: {
									min: 0,
									stepSize: 10,
								}
							}
						]
					}
				}
			});
		}
	});
});

var offset = 0;
var removeFeedIcons;
const CHART = document.querySelector('#appointment-stats');

document.querySelector("#prev-page").addEventListener('click', changePage);
document.querySelector("#next-page").addEventListener('click', changePage);
	
loadDashboardInfo();
loadActivityFeed();