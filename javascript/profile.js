window.addEventListener("load",function(){
(function(){


	var profAbout = document.getElementById('profAbout'),
		about = document.getElementById('about'),
		profFriends = document.getElementById('profFriends'),
		friends = document.getElementById('friends'),
		profNotif = document.getElementById('profNotif'),
		notif = document.getElementById('notif'),
		profVacancy = document.getElementById('profVacancy'),
		vacancy = document.getElementById('vacancy'),
		profEdit = document.getElementById('profEdit'),
		edit = document.getElementById('editProfile');
	
	


	
	profAbout.addEventListener('click',function(e){
		e.preventDefault();
		about.style.display="flex";
		if (typeof(profVacancy) != 'undefined' && profVacancy != null){		
			vacancy.style.display="none";
		};
		if (typeof(profFriends) != 'undefined' && profFriends != null){	
			friends.style.display="none";
		}
		if (typeof(profNotif) != 'undefined' && profNotif != null){	
			notif.style.display="none";
		}	
		if (typeof(profEdit) != 'undefined' && profEdit != null){	
			edit.style.display="none";
		}
	});

	if (typeof(profEdit) != 'undefined' && profEdit != null){	
		profFriends.addEventListener('click',function(e){
			e.preventDefault();
			about.style.display="none";
			friends.style.display="flex";
			if (typeof(profVacancy) != 'undefined' && profVacancy != null){		
				vacancy.style.display="none";
			};
			notif.style.display="none";
			edit.style.display="none";
		});
	};


	if (typeof(profVacancy) != 'undefined' && profVacancy != null){		
	
		profVacancy.addEventListener('click',function(e){
		e.preventDefault();
		about.style.display="none";
		vacancy.style.display="flex";
		if (typeof(profFriends) != 'undefined' && profFriends != null){	
			friends.style.display="none";
		}
		if (typeof(profNotif) != 'undefined' && profNotif != null){	
			notif.style.display="none";
		}	
		if (typeof(profEdit) != 'undefined' && profEdit != null){	
			edit.style.display="none";
		}
		});
	};
	

	if (typeof(profNotif) != 'undefined' && profNotif != null){		
		profNotif.addEventListener('click',function(e){
			e.preventDefault();
			about.style.display="none";
			friends.style.display="none";
			if (typeof(profVacancy) != 'undefined' && profVacancy != null){		
				vacancy.style.display="none";
			};
			notif.style.display="flex";
			edit.style.display="none";
		});
	};

	if (typeof(profEdit) != 'undefined' && profEdit != null){		
		profEdit.addEventListener('click',function(e){
			e.preventDefault();
			about.style.display="none";
			friends.style.display="none";
			if (typeof(profVacancy) != 'undefined' && profVacancy != null){		
				vacancy.style.display="none";
			};
			notif.style.display="none";
			edit.style.display="flex";
		});
	};
	
	

})();
});