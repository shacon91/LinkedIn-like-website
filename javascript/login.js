window.addEventListener("load",function(){
(function(){


	var about = document.getElementById('loginAbout'),
		aboutBox = document.getElementById('loginAboutBox');
	
	about.addEventListener('click',function(e){
		e.preventDefault();
		if(aboutBox.style.display=="inline-block"){
            aboutBox.style.display="none";
        }else{
            aboutBox.style.display="inline-block";
        }
	});

})();
});