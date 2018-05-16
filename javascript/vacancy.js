window.addEventListener("load",function(){
(function(){


	var edit = document.getElementsByClassName("edit"),
        editBox = document.getElementsByClassName("editBox"),
        editLink = document.getElementsByClassName("editLink"),
        deleteLink = document.getElementsByClassName("deleteLink"),
        readMoreLink = document.getElementsByClassName("readMoreLink"),
        readMore = document.getElementsByClassName("readMore");


   	function listenerForReadMoreI( i ) {
        readMoreLink[i].addEventListener('click', function(e) {
            if(readMore[i].style.display=="flex"){
                readMore[i].style.display="none";
            }else{
                readMore[i].style.display="flex";
                readMoreLink[i].style.display="none";
            }
        });
    }

    for (var i = 0; i < readMoreLink.length; i++) {
        listenerForReadMoreI( i );
    }
	
	 function listenerForEditI( i ) {
        edit[i].addEventListener('click', function(e) {
            if(editBox[i].style.display=="block"){
                editBox[i].style.display="none";
            }else{
                editBox[i].style.display="block";
                //alert(e.target.id);
            }
        });
    }

    for (var i = 0; i < edit.length; i++) {
        listenerForEditI( i );
    }
/*
    function listenerForDeleteI( i ) {
        deleteLink[i].addEventListener('click', function(e) {
           e.preventDefault();
            data = edit[i].id;
            ajaxProcess(data);

    		function handleServerResponse(){
				if (xmlHttp.readyState===4) {
					if (xmlHttp.status===200) {
						try{
							var retData = JSON.parse(this.responseText);
							
							if (retData.data.length===0) {
							}else{	
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
					xmlHttp.open('POST','../http/php/vacancy.php',true);
					xmlHttp.onreadystatechange=handleServerResponse;
					xmlHttp.send(data);
				}
				else{
					setTimeout('ajaxProcess(data)',1000);
				}
			};
	            
	   });
    }

    for (var i = 0; i < deleteLink.length; i++) {
        listenerForDeleteI( i );
    }*/

   

	

})();
});