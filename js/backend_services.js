function addService(){
	tabButtons[0].style.backgroundColor = "white";
	tabButtons[1].style.backgroundColor = "#eee";
	tabPanels[0].style.display = "block";
	tabPanels[1].style.display = "none";
	document.querySelector('#first-modal-element').style.display = 'none';
	document.querySelector('.add-modal-btn').style.display = 'inline';
	document.querySelector('.update-modal-btn').style.display = 'none';
	document.querySelector('.delete-modal-btn').style.display = 'none';
	is_service_available_for_first_time_patients = '1';
	resetServiceForm();
	modalControl(true, 'Add Service', '450px');
	resetCategorySelect();
	selectedServiceImgLink = '';
	document.querySelector('#delete-img').style.display = 'none';
}

function resetCategorySelect(){
	document.querySelector('#modal-category').length = 0;
	var category = document.createElement('option');
	category.textContent = "---Select category ---";
	category.selected = true;
	category.disabled = true;
	document.querySelector('#modal-category').appendChild(category);
	
	for(var x=0; x<categories.length; x++){
		var category = document.createElement('option');
		category.setAttribute('id', categories[x].category_id);
		category.textContent = categories[x].category_name;
		document.querySelector('#modal-category').appendChild(category);
	}
}

function resetServiceForm(){
	document.querySelector('.modal-preview-img').src = "../../img/question_mark.png";
	document.querySelector('.modal-selected-id').textContent = '';
	document.querySelector('#modal-service-name').value = "";
	document.querySelector('#modal-description').value = "";
	document.querySelector('#modal-category').value = "---Select category ---";
	document.querySelector('#duration-hours').value = "";
	document.querySelector('#duration-mins').value = "0";
	document.querySelector('#si-id').value = "";
	document.querySelector('#img-order').value = "1";
	$('#service-imgs-tbl tbody').html('');
}

function isFormValid(){
	return (
		document.querySelector('#modal-service-name').value.trim()  !== '' &&
		tinyMCE.get('modal-description').getContent() !== "" &&
		document.querySelector('#duration-hours').value !== '' &&
		document.querySelector('#duration-mins').value !== '' &&
		document.querySelector('#modal-category').value !== '---Select category---' &&
		document.querySelector('#is-service-available-for-first-time-patients').value !== ""
	);
}

function verify_submit_modal_form(e){
	e.preventDefault();
	//alert(document.querySelector('#individual-category-table').rows.length);
	if(isFormValid()){
		submitServiceForm(e);
	}
	else{
		alert("Please fill out all the required fields.");
	}
}

function submitServiceForm(e){
	if(document.querySelector('#service-imgs-tbl').rows.length == 1){
		alert("Please submit at least 1 image for this service.");
	}
	else{
		loaderPopupControl(true);
		var actionTaken;
		if(document.querySelector('.modal-selected-id').textContent === ''){
			actionTaken = 'insert';
		}
		else{
			actionTaken = 'update';
		}
		var dataArray = getServiceFormValues();
		manipulateServices(dataArray, actionTaken);	
	}
}

function manipulateServices(dataArray, actionTaken){
	$.ajax({
		url: 'manipulate_services.php',
		method: 'POST',
		data: {
			action: actionTaken,
			service_id: dataArray[2],
			service_name: dataArray[0],
			service_description: dataArray[1],
			price: dataArray[6],
			duration: dataArray[3],
			category_id: dataArray[4],
			appears_on_first_time: dataArray[5],
			dummy_id: dataArray[7]
		},
		success: function(response){
			if(response == ''){
				alert("Information not updated. Please alter data if you wish to update this service's information.");
			}
			else{
				alert(response);
			}
			loadData('get_services.php', 'GET', {none: 'none'}, viewService);
			modalControl(false, 'Add Service', '450px');
		}
	});
}

function getSelectedModalCategoryID(e){
	for(var i=0; i<document.querySelector('#modal-category').children.length; i++){
		if(document.querySelector('#modal-category').value === document.querySelector('#modal-category').children[i].textContent){
			return document.querySelector('#modal-category').children[i].id
		}
	}
	return null;
}

function getServiceFormValues(){
	return [
		document.querySelector('#modal-service-name').value,
		tinyMCE.get('modal-description').getContent(),
		document.querySelector('.modal-selected-id').textContent.slice(2),
		[{
			hours: document.querySelector('#duration-hours').value,
			minutes: document.querySelector('#duration-mins').value
		}],
		getSelectedModalCategoryID(),
		is_service_available_for_first_time_patients,
		document.querySelector('#modal-service-price').value,
		dummy_id
	];
}

function viewService(e){
	document.querySelector('#delete-img').style.display = 'none';
	document.querySelector('#img-order').value = "";
	selectedServiceImgLink = '';
	tabButtons[0].style.backgroundColor = "white";
	tabButtons[1].style.backgroundColor = "#eee";
	tabPanels[0].style.display = "block";
	tabPanels[1].style.display = "none";
	var arg;
	if(e.target.className != 'backend-img-thumb'){
		arg =  e.target.parentNode.children;
	}
	else{
		arg = e.target.parentNode.parentNode.children;
	}
	document.querySelector('#first-modal-element').style.display = 'block';
	document.querySelector('.add-modal-btn').style.display = 'none';
	document.querySelector('.update-modal-btn').style.display = 'inline';
	document.querySelector('.delete-modal-btn').style.display = 'inline';
	modalControl(true, 'View Service', '450px');
	resetCategorySelect();
	fillServiceForm(arg);
}

function fillServiceForm(arg){
	document.querySelector('.modal-selected-id').textContent = arg[0].textContent;
	document.querySelector('#modal-service-name').value = arg[1].textContent;
	//document.querySelector('#modal-description').value = arg[5].textContent;

	document.querySelector('#modal-category').value = arg[2].textContent;
	document.querySelector('#modal-service-price').value = arg[8].textContent;
	document.querySelector('#is-service-available-for-first-time-patients').value = arg[7].textContent;

	var duration = JSON.parse(arg[6].textContent);
	document.querySelector('#duration-hours').value = duration[0]['hours'];
	document.querySelector('#duration-mins').value = duration[0]['minutes'];
	
	if(arg[7].textContent === "Yes"){
		is_service_available_for_first_time_patients = '1';
	}
	else{
		is_service_available_for_first_time_patients = '0';
	}
	
	$.ajax({
		url: "get_service_desc.php",
		method: "GET",
		data: {id: arg[0].textContent.slice(2)},
		success: function(result){

			tinyMCE.get('modal-description').setContent(result);
		}
	})
	getThumbnails(arg[0].textContent.slice(2));
}

function deleteService(){
	const confirmDelete = confirm("Delete this service?");
	if(confirmDelete){
		loaderPopupControl(true);
		var dataArray = getServiceFormValues();
		manipulateServices(dataArray, 'delete');
	}
}

function changeServiceStatus(e){
	if(e.target.options[e.target.selectedIndex].textContent === "Yes"){
		is_service_available_for_first_time_patients = '1';
	}
	else{
		is_service_available_for_first_time_patients = '0';
	}
}

function getPanel(tab){
	var panel;
	if(tab === 'Service Information'){
		panel = '#panel-1';
	}
	else{
		panel = '#panel-2';
	}
	return panel;
}

function showPanels(e){
	tabButtons.forEach(function(node){
		node.style.backgroundColor = "";
		node.style.color = "black";
	});
	
	tabPanels.forEach(function(node){
		node.style.display = "none";
	});
	
	e.target.style.backgroundColor = "white";
	var panel = getPanel(e.target.textContent);
	
	document.querySelector(panel).style.display = "block";
	document.querySelector(panel).style.backgroundColor = "white";
	document.querySelector(panel).style.color = "black";
}

function getThumbnails(service_id){
	$('#service-imgs-tbl tbody').html('');
	$.ajax({
		url: 'get_services_thumbnails.php',
		method: 'GET',
		data: {service_id: service_id},
		success: function(response){
			$('#service-imgs-tbl tbody').html(response);
			addShowcaseThumbTableRowListeners();
		}
	});
}

function addShowcaseThumbTableRowListeners(){
	for(var i=0; i<document.querySelectorAll('#service-imgs-tbl tbody .actual-row').length; i++){
		document.querySelectorAll('#service-imgs-tbl tbody .actual-row')[i].addEventListener('dblclick', viewShowcaseImg);
	}
}

function viewShowcaseImg(e){
	if(e.target.className != 'img-cms-thumb'){
		arg =  e.target.parentNode.children;
	}
	else{
		arg = e.target.parentNode.parentNode.children;
	}
	document.querySelector('#img-order').value = arg[0].textContent;
	document.querySelector('.modal-preview-img').src = arg[3].textContent;
	selectedServiceImgLink = arg[3].textContent;
	document.querySelector('#si-id').value = arg[2].textContent;
	if(arg[0].textContent == '1'){
		document.querySelector('#delete-img').style.display = 'none';
	}
	else{
		document.querySelector('#delete-img').style.display = 'inline';
	}
}

function uploadImg(imgLink, si_id, action){
	$.ajax({
		url: 'manipulate_service_imgs.php',
		method: 'POST',
		data: {
			img_link: imgLink,
			display_order: document.querySelector('#img-order').value,
			service_id: si_id,
			action: action
		},
		success: function(response){
			getThumbnails(si_id)
			/*if(response == '1'){
				alert("Image settings successfully updated.");
			}
			else{
				alert("Image already exists in database");
			}*/
			alert("Image settings successfully updated.");
		}
	});
}

function uploadImg2(imgLink, si_id, action, service_id){
	loaderPopupControl(true);
	$.ajax({
		url: 'manipulate_service_imgs.php',
		method: 'POST',
		data: {
			img_link: imgLink,
			display_order: document.querySelector('#img-order').value,
			service_id: service_id,
			si_id: si_id,
			action: action
		},
		success: function(response){
			loaderPopupControl(false);
			getThumbnails(service_id)
			/*if(response == '1'){
				alert("Image settings successfully updated.");
			}
			else{
				alert("Image already exists in database");
			}*/
			alert("Image settings successfully updated.");
		}
	});
}

function saveImgSettings(){
	const displayOrders = [];
	for(var i=1; i<document.querySelector('#service-imgs-tbl').rows.length; i++){
		displayOrders.push(document.querySelector('#service-imgs-tbl').rows[i].firstElementChild.textContent);
	}
	if(uploadImgPrepare()){
		if(document.querySelector('#si-id').value == ''){
			if(document.querySelector('#modal-img').value === ''){
				alert("Please select an image.");
			}
			else{					
				loaderPopupControl(true);
				var $files = $('#modal-img').get(0).files;
				var formData = new FormData();
				formData.append("image", $files[0]);
				var settings = getImgurDict();		
				settings.data = formData;
				settings.success = function(response){
					const imgurResponse = JSON.parse(response);
					const imgLink = imgurResponse.data.link;
					if(document.querySelector('.modal-header-text').textContent === "Add Service"){
						uploadImg(imgLink, dummy_id, 'insert');
					}
					else{
						uploadImg2(imgLink, document.querySelector('#si-id').value, 'insert', document.querySelector('.modal-selected-id').textContent.slice(2));
					}
					loaderPopupControl(false);
				};
				
				$.ajax(settings);
			}	
		}
		else{
			if(selectedServiceImgLink == document.querySelector('.modal-preview-img').src){
				uploadImg2(selectedServiceImgLink, document.querySelector('#si-id').value, 'update', document.querySelector('.modal-selected-id').textContent.slice(2));
			}
			else{
				loaderPopupControl(true);
				var $files = $('#modal-img').get(0).files;
				var formData = new FormData();
				formData.append("image", $files[0]);
				var settings = getImgurDict();		
				settings.data = formData;
				
				settings.success = function(response){
					const imgurResponse = JSON.parse(response);
					const imgLink = imgurResponse.data.link;
					uploadImg2(imgLink, document.querySelector('#si-id').value, 'update', document.querySelector('.modal-selected-id').textContent.slice(2));
					loaderPopupControl(false);
				}	
				$.ajax(settings);
			}
		}
	}
	else{
		alert("Please enter display order.");
	}

}

function uploadImgPrepare(){
	return document.querySelector('#img-order').value !== "";
}

function deleteServiceImg(){
	var will_delete = confirm("Delete this image?");
	if(will_delete){
		loaderPopupControl(true);
		$.ajax({
			url: 'manipulate_service_imgs.php',
			method: 'POST',
			data: {
				display_order: document.querySelector('#img-order').value,
				service_id: document.querySelector('.modal-selected-id').textContent.slice(2),
				si_id: document.querySelector('#si-id').value,
				action: 'delete'
			},
			success: function(response){
				loaderPopupControl(false);
				getThumbnails(document.querySelector('.modal-selected-id').textContent.slice(2));
				/*if(response == '1'){
					alert("Image settings successfully updated.");
				}
				else{
					alert("Image already exists in database");
				}*/
				alert("Image successfully deleted.");
			}
		});
	}
}

var tabButtons = document.querySelectorAll('.tab-container button');
var tabPanels = document.querySelectorAll('.tab-panel');
var is_service_available_for_first_time_patients;
var dummy_id = Math.floor((Math.random() * 5000) + 1000);
var selectedServiceImgLink = '';

tabButtons[0].style.backgroundColor = "white";
tabPanels[0].style.display = "block";
tabPanels[0].style.backgroundColor = "white";
tabPanels[0].style.color = "black";

tabButtons.forEach(function(node){
	node.addEventListener('click', showPanels);
});

document.querySelector('.tab-container').style.display = 'grid';
document.querySelector('.tab-container').style.gridTemplateColumns = '1fr 1fr';
document.querySelector('.backend-body-add-btn').addEventListener('click', addService);
document.querySelector('#modal-img-container').style.marginTop = '30px';
document.querySelector('.modal-instructions').style.marginTop = '-30px';

document.querySelector('.modal-form').addEventListener('submit', verify_submit_modal_form);
loadData('get_services.php', 'GET', {none: 'none'}, viewService);
getProviderServices("get_categories_alone.php", '#shit', 'backend');


document.querySelector('.delete-modal-btn').addEventListener('click', deleteService);
document.querySelector('#delete-img').addEventListener('click', deleteServiceImg);
document.querySelector('#is-service-available-for-first-time-patients').addEventListener('change', changeServiceStatus);
document.querySelector('#upload-img').addEventListener('click', saveImgSettings);