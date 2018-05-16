window.addEventListener("load",function(){
(function(){
	var apply = document.getElementsByClassName('apply');

	function handleServerResponse(){
		if (xmlHttp.readyState===4) {
			if (xmlHttp.status===200) {
				try{
					var retData = this.responseText;
					if (retData!=="false") {
						document.getElementById('vac'+retData).getElementsByClassName('apply')[0].style.display="none";
						var div =document.createElement('div');
						div.innerHTML="You have successfully applied for this job";
						div.className="success";
						document.getElementById('vac'+retData).appendChild(div);
					}
					

				}catch(e){
					alert(e.toString());
				}
			}else{
				alert(xmlHttp.statusText);
			}
		};
	};

	function ajaxProcess(i){
			if (xmlHttp.readyState===0 || xmlHttp.readyState===4) {
				xmlHttp.open('GET','./http/php/vacancy.php?vac_info='+i,true);
				xmlHttp.onreadystatechange=handleServerResponse;
				xmlHttp.send();
			}
			else{
				setTimeout('ajaxProcess('+i+')',1000);
			}
		};


   function listenerForApplyI( i ) {
   		apply[i].addEventListener('click',function(e){
   			e.preventDefault();
   			e.stopPropagation();
			ajaxProcess(event.target.id);
		});
    }

    for (var i = 0; i < apply.length; i++) {
        listenerForApplyI( i );
    }
		
	
	

})();
});