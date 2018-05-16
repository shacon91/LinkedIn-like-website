window.addEventListener("load",function(){
(function(){


	var accept = document.getElementsByClassName("accept"),
        decline = document.getElementsByClassName("decline"),
        editLink = document.getElementsByClassName("editLink"),
        deleteLink = document.getElementsByClassName("deleteLink"),
        readMoreLink = document.getElementsByClassName("readMoreLink"),
        readMore = document.getElementsByClassName("readMore");


   	function listenerForAcceptI( i ) {
        accept[i].addEventListener('click', function(e) {
            if(readMore[i].style.display=="flex"){
                readMore[i].style.display="none";
            }else{
                readMore[i].style.display="flex";
                readMoreLink[i].style.display="none";
            }
        });
    }

    for (var i = 0; i < accept.length; i++) {
        listenerForAcceptI( i );
    }
	
	 function listenerForDeclineI( i ) {
        decline[i].addEventListener('click', function(e) {
            if(editBox[i].style.display=="block"){
                editBox[i].style.display="none";
            }else{
                editBox[i].style.display="block";
                //alert(e.target.id);
            }
        });
    }

    for (var i = 0; i < decline.length; i++) {
        listenerForDeclineI( i );
    }


   

	

})();
});