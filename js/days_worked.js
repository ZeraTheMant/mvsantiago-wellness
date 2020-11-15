var checkDay = document.querySelectorAll('.checkDay');
var sundayStart = document.querySelector('#sunday-time-start');
var sundayEnd = document.querySelector('#sunday-time-end');
var mondayStart = document.querySelector('#monday-time-start');
var mondayEnd = document.querySelector('#monday-time-end');
var tuesdayStart = document.querySelector('#tuesday-time-start');
var tuesdayEnd = document.querySelector('#tuesday-time-end');
var wednesdayStart = document.querySelector('#wednesday-time-start');
var wednesdayEnd = document.querySelector('#wednesday-time-end');
var thursdayStart = document.querySelector('#thursday-time-start');
var thursdayEnd = document.querySelector('#thursday-time-end');
var fridayStart = document.querySelector('#friday-time-start');
var fridayEnd = document.querySelector('#friday-time-end');
var saturdayStart = document.querySelector('#saturday-time-start');
var saturdayEnd = document.querySelector('#saturday-time-end');

for(var i=0; i<checkDay.length; i++){
	checkDay[i].addEventListener('change', activateDay);
}

function activateDay(e){
	var timeBoxes = document.querySelectorAll('.' + e.target.id + '-time');
	if(e.target.checked){
		for(var i=0; i<timeBoxes.length; i++){
			//timeBoxes[i].style.opacity = '1';
			timeBoxes[i].disabled = false;
		}
	}else{
		for(var i=0; i<timeBoxes.length; i++){
			//timeBoxes[i].style.opacity = '0';
			timeBoxes[i].value = '';
			timeBoxes[i].disabled = true;
		}
	}
}

sundayStart.oninput = function(){		
	sundayStart.max = sundayEnd.value;
	sundayEnd.min = sundayStart.value;
}

sundayEnd.oninput = function(){
	sundayStart.max = sundayEnd.value;
	sundayEnd.min = sundayStart.value;
}

mondayStart.oninput = function(){
	mondayStart.max = mondayEnd.value;
	mondayEnd.min = mondayStart.value;
}

mondayEnd.oninput = function(){
	mondayStart.max = mondayEnd.value;
	mondayEnd.min = mondayStart.value;
}

tuesdayStart.oninput = function(){
	tuesdayStart.max = tuesdayEnd.value;
	tuesdayEnd.min = tuesdayStart.value;
}

tuesdayEnd.oninput = function(){
	tuesdayStart.max = tuesdayEnd.value;
	tuesdayEnd.min = tuesdayStart.value;
}

wednesdayStart.oninput = function(){
	wednesdayStart.max = wednesdayEnd.value;
	wednesdayEnd.min = wednesdayStart.value;
}

wednesdayEnd.oninput = function(){
	wednesdayStart.max = wednesdayEnd.value;
	wednesdayEnd.min = wednesdayStart.value;
}

thursdayStart.oninput = function(){
	thursdayStart.max = thursdayEnd.value;
	thursdayEnd.min = thursdayStart.value;
}

thursdayEnd.oninput = function(){
	thursdayStart.max = thursdayEnd.value;
	thursdayEnd.min = thursdayStart.value;
}

fridayStart.oninput = function(){
	fridayStart.max = fridayEnd.value;
	fridayEnd.min = fridayStart.value;
}

fridayEnd.oninput = function(){
	fridayStart.max = fridayEnd.value;
	fridayEnd.min = fridayStart.value;
}

saturdayStart.oninput = function(){
	saturdayStart.max = saturdayEnd.value;
	saturdayEnd.min = saturdayStart.value;
}

saturdayEnd.oninput = function(){
	saturdayStart.max = saturdayEnd.value;
	saturdayEnd.min = saturdayStart.value;
}