function loaderPopupControl(flag){
	if(flag){
		document.querySelector('.loader-container').style.display = 'flex';
	}
	else{
		document.querySelector('.loader-container').style.display = 'none';
	}
}