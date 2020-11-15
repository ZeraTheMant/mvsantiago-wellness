function loadDashboardInfo(){
	$('#statistics-boxes').html('');
	loaderPopupControl(true);
	$.ajax({
		url: 'get_dashboard_frontpage_notifs_user.php',
		method: 'GET',
		data: {client_id: userID},
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


var removeFeedIcons;
var offset = 0;

loadDashboardInfo();
loadActivityFeed();

document.querySelector("#prev-page").addEventListener('click', changePage);
document.querySelector("#next-page").addEventListener('click', changePage);