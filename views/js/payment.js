
/*=============================================
MAKE PAYMENT
=============================================*/

$(".tables tbody").on("click", "button.addPayment", function(){

	var idInvoice = $(this).attr("idInvoice");
	
	var datum = new FormData();
    datum.append("idInvoice", idInvoice);

    
    $.ajax({

        url:"ajax/payment.ajax.php",
        method: "POST",
        data: datum,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(answer){

            const productList = answer['products']
            
            const productNames = productList.flatMap(({ productName }) => productName);

            localStorage.setItem('customerName', answer['customername']);

            localStorage.setItem('Products', productNames);

            localStorage.setItem('Total', answer['total']);

            localStorage.setItem('Due', answer['dueamount']);

            localStorage.setItem('Paid', parseInt(answer['dueamount']) + parseInt(answer['total']));
            
            localStorage.setItem('Invoiceid', answer['invoiceId']);

            window.location.href = 'payment';

        }, error: function() {
            console.log("error occurred showing invoices");
        }

    });

});

if (window.location.href.indexOf('payment') !== -1) {

    var customerName = localStorage.getItem('customerName');

    $("#cname").val(customerName);

    var Products = localStorage.getItem('Products');

    $("#products").val(Products);

    var Total = localStorage.getItem('Total');

    $("#total").val(Total);

    var Due = localStorage.getItem('Due');

    $("#due").val(Due);
    $("#payment").val(Math.abs(Due));

    var Paid = localStorage.getItem('Paid');

    $("#paid").val(Paid);

    var Invoiceid = localStorage.getItem('Invoiceid');

    $("#invoiceId").val(Invoiceid);

    // $(window.on('beforeunload', function() {

        localStorage.removeItem('customerName');

        localStorage.removeItem('Products');
    
        localStorage.removeItem('Total');
    
        localStorage.removeItem('Due');
    
        localStorage.removeItem('Paid');
        
        localStorage.removeItem('invoiceId');

    // }))


}
$("#paymentForm").submit(function() {

    if ($('input[name=r3]:checked').length === 0) {
        
        Swal.fire({
                icon: "warning",
                title: "Select a payment method to proceed",
                showConfirmButton: true,
                confirmButtonColor: '#0069d9',
                confirmButtonText: "Close"
                })

    }

});


$(document).on("keyup change", "#payment", function() {

    var due = Math.abs(parseInt(document.querySelector('#due').value));
    var payment = parseInt(document.querySelector('#payment').value);

    if (payment <= 0) {

        Swal.fire({
                icon: "warning",
                title: "The payment cannot be 0 or less than 0!",
                showConfirmButton: true,
                confirmButtonColor: '#0069d9',
                confirmButtonText: "Close"
                })
        $(this).val(due);

    }

})


/*=============================================
view invoices
=============================================*/

// $(".tables tbody").on("click", "button.viewInvoice", function(){

//     var invoiceId = $(this).attr("idInvoice");

//     window.open('views/pdfs/view-invoice.php?invoiceId=' + invoiceId, '_blank');



// });

// $(".tables tbody").on("click", "button.downloadinvoice", function(){
//     var invoiceId = $(this).attr("idInvoice");
//     window.open('views/pdfs/download-invoices.php?invoiceId=' + invoiceId, '_blank');
    
// });



/*=============================================
view invoices
=============================================*/


$(".tables tbody").on("click", "button.view-receipt", function(){
    var receipt = $(this).attr("receipt");
    var fileURL = "views/pdfs/receipts/" + receipt + ".pdf";

    // Open the PDF in a new blank tab
    var newTab = window.open(fileURL, '_blank');
    
    // Check if the window was opened successfully
    if (!newTab || newTab.closed || typeof newTab.closed === 'undefined') {
        // Display SweetAlert indicating the PDF was not found
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'PDF not found!'
        });
    }
});

$(".tables tbody").on("click", "button.download-reciept", function(){
    var receipt = $(this).attr("receipt");
    var fileURL = "views/pdfs/receipts/" + receipt + ".pdf";
    var link = document.createElement('a');
    link.href = fileURL;
    link.target = '_blank';
    link.download = receipt + '.pdf';

    // Append the anchor to the document
    document.body.appendChild(link);

    // Trigger a click on the anchor to start the download
    link.click();

    // Remove the anchor from the document after download
    document.body.removeChild(link);
    
});

/*=============================================
DELETE TRANSACTION
=============================================*/

$(".tables tbody").on("click", "button.delete-Transaction", function(){

	var reciept = $(this).attr("receipt");
	
	Swal.fire({

		title: 'Are you sure you want to delete the transaction?',
		text: "If you're not sure you can cancel this action!",
		icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0069d9',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Yes, delete transaction!'
        }).then(function(result){
        if (result.value) {

        	window.location = "index.php?route=transactions&reciept="+reciept;

        }

	})

})

    function printReceipt() {
        // Open the generated receipt in a new window or tab
        window.open('views/pdfs/bill-a4.php', '_blank');
    }

