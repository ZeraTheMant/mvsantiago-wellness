function changeNotifBoxDisplay(e){
	if(e.target.getAttribute('name') === "off"){
		e.target.setAttribute("name", "on");
		e.target.textContent = "\u25BC Hide my upcoming appointments";
		document.querySelector("#my-appointments").style.display = "block";
	}
	else{
		e.target.setAttribute("name", "off");
		e.target.textContent = "\u25B2 Show my upcoming appointments";
		document.querySelector("#my-appointments").style.display = "none";
	}
}

function loadMyAppointments(){
	$("#my-appointments").html("");
	$.ajax({
		url: linkViewer + "load_my_appointments.php",
		method: "GET",
		data: {
			client_id: user_id
		},
		success: function(result){
			$("#my-appointments").html(result);
		}
	})
}

if(email !== ""){
	document.querySelector('#user-notif-box').style.display = "block";
	document.querySelector("#appointments-clickable h4").addEventListener("click", changeNotifBoxDisplay);
}

loadMyAppointments();



