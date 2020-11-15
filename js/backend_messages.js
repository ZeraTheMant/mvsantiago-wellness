function viewMessage(e){
	var arg = e.target.parentNode.children;
	document.querySelector('#first-modal-element').style.display = 'block';
	
	if(document.querySelector('#inbox-status').value === "Unread messages"){
		document.querySelector('.mark-as-read-btn').style.display = 'inline';
		document.querySelector('.reply-btn').style.display = 'inline';
		document.querySelector('.delete-msg-btn').style.display = 'inline';
	}
	else{
		document.querySelector('.mark-as-read-btn').style.display = 'none';
		document.querySelector('.reply-btn').style.display = 'inline';
		document.querySelector('.delete-msg-btn').style.display = 'inline';
	}
	
	modalControl(true, 'View Message', '600px');
	fillCategoryForm(arg);
}

function fillCategoryForm(arg){
	document.querySelector('.modal-selected-id').textContent = arg[0].textContent;
		document.querySelector('.modal-selected-id').name = arg[7].textContent;
	document.querySelector('#message-sender').textContent = arg[2].textContent;
	document.querySelector('#message-email').textContent = arg[3].textContent;
	document.querySelector('#date-sent').textContent = arg[1].textContent;
	document.querySelector('#message-contact').textContent = arg[4].textContent;
	document.querySelector('#message').value = arg[6].textContent;
}

function changeMessageView(e){
	if(e.target.value === "Unread messages"){
		loadData('get_messages.php', 'GET', {is_read: '1', view: 'backend'}, viewMessage);
	}
	else{
		loadData('get_messages.php', 'GET', {is_read: '0', view: 'backend'}, viewMessage);
	}
}

function manipulateMessages(action, testimonial_id){
	$.ajax({
		url: "manipulate_messages.php",
		method: "POST",
		data: {action: action, msg_id: testimonial_id},
		success: function(result){
			if(document.querySelector('#inbox-status').value === "Unread messages"){
				loadData('get_messages.php', 'GET', {is_read: '1', view: 'backend'}, viewMessage);
			}
			else{
				loadData('get_messages.php', 'GET', {is_read: '0', view: 'backend'}, viewMessage);
			}
			
			if(action === "mark as read"){
				alert("Message marked as read.");
			}
			else if(action === "reply"){
				alert("Reply successfully sent.");
			}
			else{
				alert("Message deleted.");
			}
			
			document.querySelector('.modal-popup').style.display = 'none';
			
			loaderPopupControl(false);
		}
	});
}

function changeTestimonialStatus(e){
	var action;
	if(e.target.textContent === "Mark as read"){
		action = "mark as read";
	}
	else if(e.target.textContent === "Reply"){
		action = "reply";
	}
	else{
		action = "delete";
	}
	
	if(action === "delete"){
		var confirmDelete = confirm("Delete this message permanently?");
		if(confirmDelete){
			loaderPopupControl(true);
			manipulateMessages(action, document.querySelector('.modal-selected-id').name);
		}
	}
	else if(action === "mark as read"){
		loaderPopupControl(true);
		manipulateMessages(action, document.querySelector('.modal-selected-id').name);
	}
}

function emailPopup(flag){ 
	if(flag){
		document.querySelector('#emailPopup').style.display = 'flex';
		document.querySelector('#emailPopup').style.alignItems = 'center';
	}
	else{
		document.querySelector('#emailPopup').style.display = 'none';
	}
}

function replyToMsg(){
	modalControl(false, "", "");
	emailPopup(true);
	const msgID = document.querySelector('.modal-selected-id').textContent;
	//document
	document.querySelector('#msg-to').value = document.querySelector('#message-email').textContent;
	document.querySelector('#msg-id').value = msgID;
}

function closeEmailPopup(e){
	emailPopup(false);
	document.querySelector('#emailForm').reset();
}

function isReplyMsgValid(){
	return document.querySelector('#msg-msg').value !== '' && document.querySelector('#msg-msg').value.length > 9;
}

function prepareEmailReply(e){
	e.preventDefault();
	if(isReplyMsgValid()){
		sendEmailReply();
	}
	else{
		alert("Your reply should contain at least 10 characters.");
	}
}

function sendEmailReply(){
	loaderPopupControl(true);
	$.ajax({
		url: 'send_email_reply.php',
		method: 'POST',
		data: {
			to_address: document.querySelector('#msg-to').value,
			message: document.querySelector('#msg-msg').value
		},
		success: function(result){
			alert("Message successfully sent.");
			emailPopup(false);
			loaderPopupControl(false);
		},
		error: function(result){
			alert("Network connection failed. Your reply was not sent. Please try again.");
			emailPopup(false);
			loaderPopupControl(false);
		}
	});
}

document.querySelector('#inbox-status').addEventListener('change', changeMessageView);
document.querySelector('.mark-as-read-btn').addEventListener('click', changeTestimonialStatus);
document.querySelector('.reply-btn').addEventListener('click', replyToMsg);
document.querySelector('.delete-msg-btn').addEventListener('click', changeTestimonialStatus);

document.querySelector('#emailPopup .popupCloseBtn').addEventListener('click', closeEmailPopup);
document.querySelector('#emailPopup .popupCloseBtn > span').addEventListener('click', closeEmailPopup);
document.querySelector('#submit-reply-btn').addEventListener('click', prepareEmailReply);

loadData('get_messages.php', 'GET', {is_read: '1', view: 'backend'}, viewMessage);