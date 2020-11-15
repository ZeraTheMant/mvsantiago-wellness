function addCategory(){
	tabButtons[0].style.backgroundColor = "white";
	tabButtons[1].style.backgroundColor = "#eee";
	tabPanels[0].style.display = "block";
	tabPanels[1].style.display = "none";
	$('#category-services-table-body').html('');
	document.querySelector('#first-modal-element').style.display = 'none';
	document.querySelector('.add-modal-btn').style.display = 'inline';
	document.querySelector('.update-modal-btn').style.display = 'none';
	document.querySelector('.delete-modal-btn').style.display = 'none';
	resetCategoryForm();
	modalControl(true, 'Add Category', '400px');
}

function resetCategoryForm(){
	document.querySelector('.modal-preview-img').src = "../../img/question_mark.png";
	document.querySelector('.modal-selected-id').textContent = '';
	document.querySelector('#modal-category-name').value = "";
	tinyMCE.get('modal-description').setContent("");
}

function isFormValid(){
	return (
		document.querySelector('#modal-category-name').value.trim()  !== '' &&
		tinyMCE.get('modal-description').getContent()  !== ''
	);
}

function verify_submit_modal_form(e){
	e.preventDefault();
	//alert(document.querySelector('#individual-category-table').rows.length);
	if(isFormValid()){
		submitCategoryForm(e);
	}
	else{
		alert("Please fill out all the required fields.");
	}
}

function submitCategoryForm(e){	
	loaderPopupControl(true);
	var actionTaken;
	if(document.querySelector('.modal-selected-id').textContent === ''){
		actionTaken = 'insert';
	}
	else{
		actionTaken = 'update';
	}
	var dataArray = getCategoryFormValues();
	if(document.querySelector('#modal-img').value === ''){
		dataArray.push(document.querySelector('.modal-preview-img').src);
		manipulateCategories(dataArray, actionTaken);
	}
	else{		
		var $files = $('#modal-img').get(0).files;
		var formData = new FormData();
		formData.append("image", $files[0]);
		var settings = getImgurDict();		
		settings.data = formData;
		
		settings.success = function(response){
			const imgurResponse = JSON.parse(response);
			const imgLink = imgurResponse.data.link;
			dataArray.push(imgLink);
			manipulateCategories(dataArray, actionTaken);
		};
		
		$.ajax(settings);
	}
}

function manipulateCategories(dataArray, actionTaken){
	$.ajax({
		url: 'manipulate_categories.php',
		method: 'POST',
		data: {
			action: actionTaken,
			category_id: dataArray[2],
			category_name: dataArray[0],
			category_description: dataArray[1],
			category_img: dataArray[3],
			service_ids: getServiceIDs()
		},
		success: function(response){
			if(response == ''){
				alert("Information not updated. Please alter data if you wish to update this category's information.");
			}
			else{
				alert(response);
			}
			loadData('get_categories.php', 'GET', {none: 'none'}, viewCategory);
			modalControl(false, 'Add Category', '400px');
		}
	});
}

function getCategoryFormValues(){
	return [
		document.querySelector('#modal-category-name').value,
		tinyMCE.get('modal-description').getContent(),
		document.querySelector('.modal-selected-id').textContent.slice(1)
	];
}

function viewCategory(e){
	tabButtons[0].style.backgroundColor = "white";
	tabButtons[1].style.backgroundColor = "#eee";
	tabPanels[0].style.display = "block";
	tabPanels[1].style.display = "none";

	$('#category-services-table-body').html('');
	loaderPopupControl(true);
	var arg;
	if(e.target.className != 'backend-img-thumb'){
		arg =  e.target.parentNode.children;
	}
	else{
		arg = e.target.parentNode.parentNode.children;
	}
	
	$.ajax({
		url: "get_category_services2.php",
		method: "GET",
		data: {category_id: arg[0].textContent.slice(1)},
		success: function(result){
			var category_services = JSON.parse(result);
			
			for(var i=0; i<category_services.length; i++){
				getService(category_services[i].service_name)
			}
			
			document.querySelector('#first-modal-element').style.display = 'block';
			document.querySelector('.add-modal-btn').style.display = 'none';
			document.querySelector('.update-modal-btn').style.display = 'inline';
			document.querySelector('.delete-modal-btn').style.display = 'inline';
			modalControl(true, 'View Category', '400px');
			fillCategoryForm(arg);
			loaderPopupControl(false);
		}
	});
}

function fillCategoryForm(arg){
	document.querySelector('.modal-selected-id').textContent = arg[0].textContent;
	document.querySelector('#modal-category-name').value = arg[1].textContent;
	tinyMCE.get('modal-description').setContent(arg[3].textContent);
	document.querySelector('.modal-preview-img').src = arg[4].textContent;
}

function deleteCategory(){
	const confirmDelete = confirm("Delete this category?");
	if(confirmDelete){
		loaderPopupControl(true);
		var dataArray = getCategoryFormValues();
		manipulateCategories(dataArray, 'delete');
	}
}

function getPanel(tab){
	var panel;
	if(tab === 'Category Information'){
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

function addService(){
	getService();
}

function removeService(e){
	var confirmDelete = confirm("Remove this service?");
	if(confirmDelete){
		var myID;
		for(i=0; i<e.target.parentNode.parentNode.firstElementChild.children[0].children.length; i++){
			if(e.target.parentNode.parentNode.firstElementChild.children[0].value == e.target.parentNode.parentNode.firstElementChild.children[0].children[i].textContent){
				myID = e.target.parentNode.parentNode.firstElementChild.children[0].children[i].name;
				break;
			}
		}
		
		$.ajax({
			url: 'disassociate_service.php',
			method: 'POST',
			data: {service_id: myID},
			success: function(result){
				e.target.parentNode.parentNode.parentNode.removeChild(e.target.parentNode.parentNode);
			}
		})
		//e.target.parentNode.parentNode.parentNode.removeChild(e.target.parentNode.parentNode);
	}
}

function addService(){
	getService('');
}

function getService(service_val){
	var newRow = document.createElement('tr');
	var newCol = document.createElement('td');
	var newCol2 = document.createElement('td');
	
	var service = document.createElement('select');
	
	for(var i=0; i<list_of_services.length; i++){
		var option = document.createElement('option');
		option.name = list_of_services[i].service_id;
		option.textContent = list_of_services[i].service_name;
		service.appendChild(option);
	}
	
	service.style.width = "100%";
	service.style.boxSizing = "border-box";
	
	service.value = service_val;
	
	var removeServiceBtn = document.createElement('button');
	removeServiceBtn.textContent = "Remove service";
	removeServiceBtn.addEventListener('click', removeService);
	removeServiceBtn.setAttribute('class', 'modal-btn full-width-btn');
	
	
	newCol.appendChild(service);
	newCol2.appendChild(removeServiceBtn);
	newRow.appendChild(newCol);
	newRow.appendChild(newCol2);
	document.querySelector('#category-services-table-body').appendChild(newRow);
}

function getServiceIDs(){
	var val_per_row = [];
	for(var i=0; i<document.querySelectorAll('#category-services-table-body tr').length; i++){

		const service_value = document.querySelectorAll('#category-services-table-body tr')[i].children[0].firstChild;
		var service_id;
		if(service_value.value !== ''){
			for(var x=0; x<service_value.children.length; x++){
				if(service_value.value === service_value.children[x].textContent){
					service_id = service_value.children[x].name;
					break;
				}
			}
		}
		
		val_per_row.push(service_id);
	}
	return val_per_row;
}

var tabButtons = document.querySelectorAll('.tab-container button');
var tabPanels = document.querySelectorAll('.tab-panel');
var services_dict;
var list_of_services;

tabButtons[0].style.backgroundColor = "white";
tabPanels[0].style.display = "block";
tabPanels[0].style.backgroundColor = "white";
tabPanels[0].style.color = "black";

tabButtons.forEach(function(node){
	node.addEventListener('click', showPanels);
});

document.querySelector('.tab-container').style.display = 'grid';
document.querySelector('.tab-container').style.gridTemplateColumns = '1fr 1fr';
document.querySelector('.backend-body-add-btn').addEventListener('click', addCategory);
document.querySelector('#modal-img-container').style.marginTop = '30px';
document.querySelector('.modal-instructions').style.marginTop = '-30px';
document.querySelector('.modal-form').addEventListener('submit', verify_submit_modal_form);
document.querySelector('#add-category-service').addEventListener('click', addService);

loadData('get_categories.php', 'GET', {none: 'none'}, viewCategory);

$.ajax({
	url: 'get_services3.php',
	method: 'GET',
	success: function(result){
		list_of_services = JSON.parse(result);
	}
})

//document.querySelector('#upload-img').addEventListener('click', saveImgSettings);
document.querySelector('.delete-modal-btn').addEventListener('click', deleteCategory);