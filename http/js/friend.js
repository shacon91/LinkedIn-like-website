window.addEventListener("load",function(){
(function(){
	var add = document.getElementById('addFriend'),
		initialiser = document.getElementById('initialiser').value,
		reciever = document.getElementById('reciever').value,
		block = document.getElementById('block');

	function handleServerResponse(){
		if (xmlHttp.readyState===4) {
			if (xmlHttp.status===200) {
				try{
					var retData = this.response;
					console.log(retData);
					if (retData=='0') {
						add.innerHTML = "Cancel Request";
						add.value = 1;
					}else if(retData=='1'){
						add.innerHTML = "Add Friend";
						add.value = 0;
					}else if(retData=='2'){
						add.innerHTML = "Remove Friend";
						add.value = 3;
					}

					/*
						else if(retData=='2'){
						add.innerHTML = "Respond to request";
						add.value = 2;
					}this is on main php

					*/

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
				xmlHttp.open('POST','./http/php/friend.php',true);
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xmlHttp.onreadystatechange=handleServerResponse;
				xmlHttp.send(parameters);
			}
			else{
				setTimeout('ajaxProcess(data)',1000);
			}
		};

	if (typeof(add) != 'undefined' && add != null){	
		add.addEventListener('click',function(){
			var type = add.value;
			switch (type){
				case '0' : //add friend
					var cancel = false;
					var accept = false;
					var remove = false;
					var data = [initialiser, reciever, cancel, accept,remove];
					break;
				case '1' : // cancel request
					var cancel = true;
					var accept = false;
					var remove = false;
					var data = [initialiser, reciever, cancel, accept,remove];
					break;
				case '2' ://respond to friend
					var option = confirm("Accept friend request?");
					if(option===true){
						var cancel = false;
						var accept = true;
						var remove = false;
						var data = [initialiser, reciever, cancel, accept,remove];
					}else{
						var cancel = false;
						var accept = false;
						var remove = true;
						var data = [initialiser, reciever, cancel, accept,remove];
					}
					break;
				case '3' ://removes friend
					var cancel = false;
					var accept = false;
					var remove = true;
					var data = [initialiser, reciever, cancel, accept,remove];
					break;
			};	

			ajaxProcess(data);
			
		});
	};
	

})();
});