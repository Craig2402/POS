/*=============================================
UPLOADING USER PICTURE
=============================================*/

$(".userphoto").change(function(){

	var img = this.files[0];

	/*===============================================
	=            validating image format            =
	===============================================*/
	
	if (img["type"] != "image/jpeg" && img["type"] != "image/png"){

		$(".userphoto").val("");

		Swal.fire({
			icon: "error",
			title: "Error uploading image",
			text: "Image has to be JPEG or PNG!",
			showConfirmButton: true,
			confirmButtonText: "Close"
		});

	}else if(img["size"] > 2000000){

		$(".userphoto").val("");
 
		Swal.fire({
			icon: "error",
			title: "Error uploading image",
			text: "Image too big. It has to be less than 2Mb!",
			showConfirmButton: true,
			confirmButtonText: "Close"
		});

	}else{

		var imgData = new FileReader;
		imgData.readAsDataURL(img);

		$(imgData).on("load", function(event){
			
			var routeImg = event.target.result;

			$(".preview").attr("src", routeImg);

		});

	}
		
	/*=====  End of validating image format  ======*/
	
})


/*=============================================
EDITING USER PICTURE
=============================================*/
$(document).on("click", ".btnEditUser", function(){

 	var userId = $(this).attr("userId");

 	var data = new FormData();
 	data.append("userId", userId);

 	$.ajax({

 		url: "ajax/user.ajax.php",
 		method: "POST",
 		data: data,
 		cache: false,
 		contentType: false,
 		processData: false,
 		dataType: "json",
 		success: function(answer){
 			
 			console.log("answer", answer);

 			$("#editName").val(answer["name"]);

 			$("#editUsername").val(answer["username"]);

 			$("#editRoleOptions").html(answer["role"]);

 			$("editRoleOptions").val(answer["role"]);

 			$("#actualPassword").val(answer["userpassword"]);

 			$("#actualPhoto").val(answer["userphoto"]);
 			
 			if(answer["userphoto"] != ''){

 				$('.preview').attr('src', answer["userphoto"]);

 			}

 		}

 	});

 });


/*=============================================
ACTIVATE USER
=============================================*/
$(document).on("click", ".btnActivate", function(){

	var userId = $(this).attr("userid");
	var userStatus = $(this).attr("status");

	var datum = new FormData();
 	datum.append("activateId", userId);
  	datum.append("activateUser", userStatus);

  	$.ajax({

	  url:"ajax/user.ajax.php",
	  method: "POST",
	  data: datum,
	  cache: false,
      contentType: false,
      processData: false,
      success: function(answer){
      	
      	// console.log("answer", answer);

      	if(window.matchMedia("(max-width:767px)").matches){
		
			Swal.fire({
				title: "The user status has been updated",
				icon: "success",
				confirmButtonText: "Close"	
			}).then(function(result) {

				if (result.value) {
					window.location = "registration";
				}

			})

		}
		
      }

  	})

  	if(userStatus == 0){

  		$(this).removeClass('btn-success');
  		$(this).addClass('btn-danger');
  		$(this).html('Deactivated');
  		$(this).attr('status',1);

  	}else{

  		$(this).addClass('btn-success');
  		$(this).removeClass('btn-danger');
  		$(this).html('Activated');
  		$(this).attr('status',0);

  	}

});


/*=============================================
VALIDATE IF USER ALREADY EXISTS
=============================================*/

$("#username").change(function(){

	$(".alert").remove();

	var user = $(this).val();

	var data = new FormData();
 	data.append("validateUser", user);

  	$.ajax({

	  url:"ajax/user.ajax.php",
	  method: "POST",
	  data: data,
	  cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function(answer){ 

      	//console.log("answer", answer);

      	if(answer){

      		$("#username").parent().after('<div class="alert alert-warning">This user is already taken</div>');
      		
      		$("#username").val('');
      	}

      }

    });

});

/*=============================================
 DELETE USER
 =============================================*/

$(document).on("click", ".btnDeleteUser", function(){

	var userId = $(this).attr("userId");
	var userphoto = $(this).attr("userphoto");
	var username = $(this).attr("username");

	Swal.fire({
		title: 'Are you sure you want to delete the user?',
		text: "if you're not sure you can cancel!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancel',
		  confirmButtonText: 'Yes, delete user!'
		}).then(function(result){

		if(result.value){

		  window.location = "index.php?route=registration&userId="+userId+"&username="+username+"&userphoto="+userphoto;

		}

	})

});



