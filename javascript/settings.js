window.addEventListener("load",function(){
(function(){


	var genInfoLink = document.getElementById('genInfoLink'),
		genInfoForm = document.getElementById('genInfoForm'),
		newEmailLink = document.getElementById('newEmailLink'),
		newEmailForm = document.getElementById('newEmailForm'),
		newPassLink = document.getElementById('newPassLink'),
		newPassForm = document.getElementById('newPassForm');
	
	genInfoLink.addEventListener('click',function(e){
		if(genInfoForm.style.display=="flex"){
            genInfoForm.style.display="none";
        }else{
            genInfoForm.style.display="flex";
        }
	});

	newEmailLink.addEventListener('click',function(e){
		if(newEmailForm.style.display=="flex"){
            newEmailForm.style.display="none";
        }else{
            newEmailForm.style.display="flex";
        }
	});

	newPassLink.addEventListener('click',function(e){
		if(newPassForm.style.display=="flex"){
            newPassForm.style.display="none";
        }else{
            newPassForm.style.display="flex";
        }
	});

})();
});