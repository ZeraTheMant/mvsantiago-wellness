function getImgurDict(){
	return {
		async: true,
		crossDomain: true,
		processData: false,
		contentType: false,
		type: 'POST',
		url: 'https://api.imgur.com/3/image',
		headers: {
			Authorization: 'Client-ID e355d4fbf969c1e',
			Accept: 'application/json'
		},
		mimeType: 'multipart/form-data',
	}
}