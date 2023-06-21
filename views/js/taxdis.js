/*=============================================
EDIT CATEGORY
=============================================*/

$(".tables").on("click", ".btnEditTaxdis", function(){

	var idTaxdis = $(this).attr("taxId");

	var datum = new FormData();
	datum.append("idTaxdis", idTaxdis);

	$.ajax({
		url: "ajax/taxdis.ajax.php",
		method: "POST",
      	data: datum,
      	cache: false,
     	contentType: false,
     	processData: false,
     	dataType:"json",
     	success: function(answer){
     		
     		// console.log("answer", answer);

     		 $("#actualtaxId").val(answer["taxId"]);
     		 $("#editVAT").val(answer["VAT"]);
     		 $("#editdiscount").val(answer["VATName"]);

     	}

	})

})

/*=============================================
DELETE CATEGORY
=============================================*/
$(".tables").on("click", ".btnDeleteTaxdis", function(){

	 var idTaxdis = $(this).attr("taxId");

	 Swal.fire({
	 	title: 'Are you sure you want to delete the Tax?',
		text: "If you're not sure you can cancel!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancel',
		confirmButtonText: 'Yes, delete Tax!'
	 }).then(function(result){

	 	if(result.value){

	 		window.location = "index.php?route=taxdis&idTaxdis="+idTaxdis;

	 	}

	 })

})