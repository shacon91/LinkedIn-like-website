function createXmlHttpRequestObject(){

	if (window.XMLHttpRequest) {
		var xmlHttp;
		
		xmlHttp = new XMLHttpRequest();
		
	}else{
		try{
			xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
		}catch(e){
			xmlHttp = false;
		}
	}

	if (!xmlHttp) {
		alert('Cannot use AJAX Function with this browser');
	}else{
		return xmlHttp;
	}
};

var xmlHttp = createXmlHttpRequestObject();