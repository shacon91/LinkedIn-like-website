window.addEventListener("load",function(){
(function(){
	var block = document.getElementById('block'),
		initialiser = document.getElementById('initialiser').value,
		reciever = document.getElementById('reciever').value;
		

	function handleServerResponse(){
		if (xmlHttp.readyState===4) {
			if (xmlHttp.status===200) {
				try{
					var retData = this.response;
					console.log(retData);
					if (retData=='0') {
						block.innerHTML = "Unblock";
						block.value = 1;
					}else if(retData=='1'){
						block.innerHTML = "Block";
						block.value = 0;
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
		var parameters = 'data='+data;
			if (xmlHttp.readyState===0 || xmlHttp.readyState===4) {
				xmlHttp.open('POST','./http/php/block.php',true);
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xmlHttp.onreadystatechange=handleServerResponse;
				xmlHttp.send(parameters);
			}
			else{
				setTimeout('ajaxProcess(data)',1000);
			}
		};

	if (typeof(block) != 'undefined' && block != null){	
		block.addEventListener('click',function(){
			var type = block.value;
			switch (type){
				case '0' : //block
					var blocked = true;
					var data = [initialiser, reciever, blocked];
					break;
				case '1' : // unblock
					var blocked = false;
					var data = [initialiser, reciever, blocked];
					break;
			};	

			ajaxProcess(data);
			
		});
	};
	

})();
});