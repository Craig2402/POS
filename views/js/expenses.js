/*=============================================
EDIT EXPENSES
=============================================*/

$(".tables").on("click", "button.btnEditExpense", function(){
	
	var expenseId = $(this).attr("expenseId");

	var data = new FormData();
	data.append("expenseId", expenseId);

	$.ajax({
		url: "ajax/expenses.ajax.php",
		method: "POST",
		data: data,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(answer){
			
			console.log("answer", answer);
			// $("#editExpenseId").val(answer["id"]);
			// $("#expenseId").val(answer["expense"]);
		}
	});
});


/*=============================================
DELETE PRODUCT
=============================================*/

$(".tables tbody").on("click", "button.btnDeleteExpense", function(){

	var expenseId = $(this).attr("expenseId");
	
	Swal.fire({

		title: 'Are you sure you want to delete the product?',
		text: "If you're not sure you can cancel this action!",
		icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Yes, delete product!'
        }).then(function(result){
        if (result.value) {

        	window.location = "index.php?route=expenses&expenseId="+expenseId;

        }

	})

})
