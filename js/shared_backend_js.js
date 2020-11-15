function clearModalImg(e){
	var link;
	if(e.target.id === 'img-clear-btn-from-backend-providers'){
		link = '../../img/empty_imgs.png';
	}
	else{
		link = '../../img/question_mark.png';
	}
	document.querySelector('.modal-preview-img').src = link;
	document.querySelector('#modal-img').value = '';
}

function prevImg(input){
	var reader = new FileReader();
	reader.onload = function(e){
		document.querySelector('.modal-preview-img').src = e.target.result;
	};
	reader.readAsDataURL(input.files[0]);
}

function prePrevImg(){
	prevImg(this);
}

if(document.querySelector('.modal-clear-img')){
	document.querySelector('.modal-clear-img').addEventListener('click', clearModalImg);
}	

if(document.querySelector('#modal-img')){
	document.querySelector('#modal-img').addEventListener('change', prePrevImg);
}