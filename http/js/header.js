window.addEventListener("load",function(){
(function(){
	var search = document.getElementById('search'),
		searchBox = document.getElementById('searchBox');

	function handleServerResponse(){
		if (xmlHttp.readyState===4) {
			if (xmlHttp.status===200) {
				try{
					var retData = JSON.parse(this.responseText);
					
					
					searchBox.innerHTML="";

					if (retData.data.length===0) {
						var div = document.createElement('div');
						div.classList.add("searchLink");
						div.innerHTML="No result found";
						searchBox.appendChild(div);
					}else{

						for(var i=0; i<retData.data.length; i++){
							var a = document.createElement('a');
							a.href= retData.data[i][3];
							a.classList.add("searchLink");
							a.innerHTML=retData.data[i][1]+" "+retData.data[i][2];
							searchBox.appendChild(a);
						}
					}


					
					

					

				}catch(e){
					alert("here1");
					alert(e.toString());
				}
			}else{
				alert("here");
				alert(xmlHttp.statusText);
			}
		};
	};

	function ajaxProcess(data){
			if (xmlHttp.readyState===0 || xmlHttp.readyState===4) {
				xmlHttp.open('GET','./http/php/search.php?search='+data,true);
				xmlHttp.onreadystatechange=handleServerResponse;
				xmlHttp.send();
			}
			else{
				setTimeout('ajaxProcess()',1000);
			}
		};

	search.addEventListener('keyup',function(){
		var data = search.value;

		if (data.length!==0) {
			searchBox.style.display="block";
			ajaxProcess(data);
		}else{
			searchBox.style.display="none";
		}
			
		
	});

	search.addEventListener('focusout',function(){
		//searchBox.style.display="none";
	})
	

})();
});