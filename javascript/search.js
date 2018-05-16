window.addEventListener("load",function(){
(function(){


	var searchType = document.getElementById('searchType'),
		typeUser = document.getElementById('typeUser'),
		typeOrg = document.getElementById('typeOrg'),
		userBackLink = document.getElementById('userBackLink'),
		orgBackLink = document.getElementById('orgBackLink'),
		searchUser = document.getElementById('searchUser'),
		searchOrg = document.getElementById('searchOrg');
	
	typeUser.addEventListener('click',function(e){
		e.preventDefault();
		searchType.style.display="none";
		searchUser.style.display="flex";
		searchOrg.style.display="none";
	});

	typeOrg.addEventListener('click',function(e){
		e.preventDefault();
		searchType.style.display="none";
		searchUser.style.display="none";
		searchOrg.style.display="flex";
	});

	userBackLink.addEventListener('click',function(e){
		e.preventDefault();
		searchType.style.display="flex";
		searchUser.style.display="none";
		searchOrg.style.display="none";
	});

	orgBackLink.addEventListener('click',function(e){
		e.preventDefault();
		searchType.style.display="flex";
		searchUser.style.display="none";
		searchOrg.style.display="none";
	});

})();
});