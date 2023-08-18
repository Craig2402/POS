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

            const productList = JSON.parse(answer['products']);

            const productDetails = productList.map(product => {
                return `${product.productName} (Quantity: ${product.Quantity})`;
            }).join(', ');

            localStorage.setItem('customerName', answer['customername']);
            localStorage.setItem('Products', productDetails);
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
VIEW INVOICE MODAL
=============================================*/


$(".tables tbody").on("click", "button.viewInvoice", function(){

    var idInvoice = $(this).attr("idInvoice");

    // Get references to the links
    var viewPdfLink = document.getElementById("view-pdf-link");
    var downloadPdfLink = document.getElementById("download-pdf-link");

    // Set the href attributes using JavaScript
    viewPdfLink.href = 'views/pdfs/view-invoice.php?invoiceId=' + idInvoice;
    viewPdfLink.target = '_blank';
    
    downloadPdfLink.href = 'views/pdfs/download-invoice.php?invoiceId=' + idInvoice;
    downloadPdfLink.target = '_blank';

    openInvoiceModal(idInvoice)
  
});

function openInvoiceModal(idInvoice) {

    var invoice_datum = new FormData();
    invoice_datum.append("idInvoice", idInvoice);

    var payment_datum = new FormData();
    payment_datum.append("invoiceid", idInvoice);

    $.ajax({
  
        url:"ajax/payment.ajax.php",
        method: "POST",
        data: invoice_datum,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(answer){
            const customername = answer.customername
            const invoiceid = answer.invoiceId
            const duedate = answer.duedate
            
            // invoice header
            if (answer.customername == "") {
                $(".invoice-name-number").html("Null <br>" + answer.invoiceId + "<br>");
            } else {
                $(".invoice-name-number").html(answer.customername + "<br>" + answer.invoiceId + "<br>");
            }            

            $(".invoice-dates").html(answer.startdate + "<br>" + answer.duedate);
            
            // invoice table
            const productArray = JSON.parse(answer.products);

            productArray.map((product, index) => {
                var barcodeProduct = product.id;
                
                var datum = new FormData();
                datum.append("barcodeProduct", barcodeProduct);

                $.ajax({

                    url:"ajax/products.ajax.php",
                    method: "POST",
                    data: datum,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:"json",
                    success:function(answer){
                        // Get the table body
                        var tbody = document.querySelector('#invoice-table tbody');
                        
                        // Create a new table row
                        var newRow = document.createElement('tr');

                        var Cell1 = document.createElement('td');
                        Cell1.textContent = index + 1;
                        newRow.appendChild(Cell1);
                        
                        var Cell2 = document.createElement('td');
                        Cell2.textContent = product.productName;
                        newRow.appendChild(Cell2);
                        
                        var Cell3 = document.createElement('td');
                        Cell3.textContent = answer.barcode;
                        newRow.appendChild(Cell3);
                        
                        var Cell4 = document.createElement('td');
                        Cell4.textContent = product.Quantity;
                        newRow.appendChild(Cell4);
                        
                        var Cell5 = document.createElement('td');
                        Cell5.textContent = product.salePrice;
                        newRow.appendChild(Cell5);
                        
                        var Cell6 = document.createElement('td');
                        Cell6.textContent = product.salePrice * product.Quantity;
                        newRow.appendChild(Cell6);
                        
                        // Add the new row to the table body
                        tbody.appendChild(newRow);

                    }, error: function() {
                        Swal.fire("Error", "Failed to retrieve product data for the invoice from the server.", "error");
                    }

                });

            });

            // invoice sums
            $(".subtotal").text("Ksh " + formatCurrency(answer.subtotal));
            $(".vat").text("Ksh " + formatCurrency(answer.totaltax));
            $(".discount").text("Ksh " + formatCurrency(answer.discount));
            $(".total").text("Ksh " + formatCurrency(answer.total));
            $(".due").text("Ksh " + formatCurrency(answer.dueamount));

            // var invoiceid = answer.invoiceId
            var payment_datum = new FormData();
            payment_datum.append("invoiceid", answer.invoiceId);
            $.ajax({
        
                url:"ajax/payment.ajax.php",
                method: "POST",
                data: payment_datum,
                cache: false,
                contentType: false,
                processData: false,
                dataType:"json",
                success:function(answer){
                    // Get the table body
                    var tbody = document.querySelector('#payment-table tbody');
                    
                    // initialize the total amount
                    let totalAmount = 0;
                    for (const data in answer) {
                        if (answer.hasOwnProperty(data)) {
                            const payment = answer[data];

                            const amount = parseFloat(payment.amount); // Convert amount to a number
        
                            if (!isNaN(amount)) {
                                totalAmount += amount;
                            }                            
                    
                            // Create a new table row
                            var newRow = document.createElement('tr');

                            var Cell1 = document.createElement('td');
                            var paymentButton = document.createElement('button');
                            paymentButton.className = 'btn btn-sm btn-success';
                            paymentButton.textContent = payment.paymentmethod;
                            Cell1.appendChild(paymentButton);
                            newRow.appendChild(Cell1);
                            
                            var Cell2 = document.createElement('td');
                            Cell2.textContent = payment.receiptNumber;
                            newRow.appendChild(Cell2);
                            

                            var Cell3 = document.createElement('td');
                            Cell3.textContent =  "Ksh " + formatCurrency(payment.amount);
                            newRow.appendChild(Cell3);
                            
                            // Add the new row to the table body
                            tbody.appendChild(newRow);
                        }
                    }                            
                    
                    // Create a new table row element
                    var newRow = document.createElement('tr');

                    // Create the first cell with "colspan" attribute
                    var cell1 = document.createElement('td');
                    cell1.setAttribute('colspan', '2');
                    cell1.className = 'text-end';
                    cell1.textContent = 'Total paid:';
                    newRow.appendChild(cell1);

                    // Create the second cell
                    var cell2 = document.createElement('td');
                    cell2.textContent = "Ksh " + formatCurrency(totalAmount);
                    newRow.appendChild(cell2);

                    // Append the new row to the table body
                    tbody.appendChild(newRow);
                    
                }, error: function() {
                    Swal.fire("Error", "Failed to retrieve payment data from the server.", "error");
                }
        
            });
        }, error: function() {
            Swal.fire("Error", "Failed to retrieve invoice data from the server.", "error");
        }
  
    });
    
}
function openPaymentModal(receiptNumber, customername, invoiceid, duedate) {
    // open the payment modal
    // $('#viewPaymentModal').modal('show');
    // Create a button element
    var button = document.createElement('button');

    // Add the necessary attributes for modal functionality
    button.setAttribute('data-toggle', 'modal');
    button.setAttribute('data-target', '#viewPaymentModal');
    button.className = 'invisible';

    // Append the button to a container element (e.g., a div with the ID 'buttonContainer')
    var buttonContainer = document.getElementById('buttonContainer'); // Change to your container's ID
    buttonContainer.appendChild(button);

    // Get the button element by its class or ID
    var button = document.querySelector('.invisible'); // Change to your class or ID

    // Trigger a click event on the button
    button.click();

    var payment_data = new FormData();
    payment_data.append("receiptNumber", receiptNumber);

    $.ajax({

        url:"ajax/payment.ajax.php",
        method: "POST",
        data: payment_data,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(answer){

            if (customername == "") {
                $(".payment-col1").html("Null <br>" + invoiceid + "<br>" + answer.paymentmethod);
            } else {
                $(".payment-col1").html(customername + "<br>" + invoiceid + "<br>" +answer.paymentmethod);
            }        

            $(".payment-col2").html(answer.amount + "<br>" + receiptNumber + "<br>" + duedate);

                        
            // Get the table body
            var tbody = document.querySelector('#invoice2-table tbody');

            // Create a new table row
            var newRow = document.createElement('tr');

            var Cell1 = document.createElement('td');
            var paymentButton = document.createElement('button');
            paymentButton.className = 'btn btn-sm btn-success';
            paymentButton.textContent = "Invoice";
            Cell1.appendChild(paymentButton);
            newRow.appendChild(Cell1);
            
            var Cell2 = document.createElement('td');
            Cell2.textContent = invoiceid;
            newRow.appendChild(Cell2);
            

            var Cell3 = document.createElement('td');
            Cell3.textContent =  "Ksh " + formatCurrency(answer.amount);
            newRow.appendChild(Cell3);
            
            // Add the new row to the table body
            tbody.appendChild(newRow);

        }

    });
}


$('#viewInvoiceModal').on('hidden.bs.modal', function (e) {

    // Select the table body
    var tbody = $('#invoice-table-body');
    
    // Remove all rows from the table body
    tbody.empty();

    // Select the table body
    var tbody = $('#payment-table-body');
    
    // Remove all rows from the table body
    tbody.empty();

});

$('#viewPaymentModal').on('hidden.bs.modal', function (e) {

    // Select the table body
    var tbody = $('#invoice2-table-body');
    
    // Remove all rows from the table body
    tbody.empty();

});

/*=============================================
view reciept
=============================================*/
$(".tables tbody").on("click", "button.view-receipt", function(){

    var receiptNumber = $(this).attr("receipt");
    

    // Get references to the links
    var viewPdfLink = document.getElementById("view-reciept-pdf-link");
    var downloadPdfLink = document.getElementById("download-reciept-pdf-link");

    // Set the 'receipt' attribute for the elements
    $(viewPdfLink).attr("receipt", receiptNumber);
    $(downloadPdfLink).attr("receipt", receiptNumber);

    var payment_data = new FormData();
    payment_data.append("receiptNumber", receiptNumber);

    $.ajax({

        url:"ajax/payment.ajax.php",
        method: "POST",
        data: payment_data,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(answer){

            const receiptNumber = answer.receiptNumber;
            const customername = answer.customername;
            const idInvoice = answer.invoiceId;
        
            var payment_data = new FormData();
            payment_data.append("idInvoice", idInvoice);
        
            $.ajax({
        
                url:"ajax/payment.ajax.php",
                method: "POST",
                data: payment_data,
                cache: false,
                contentType: false,
                processData: false,
                dataType:"json",
                success:function(answer){

                    const duedate = answer.duedate;

                    // $('#viewPaymentModal').modal('show');
                    // Create a button element
                    var button = document.createElement('button');

                    // Add the necessary attributes for modal functionality
                    button.setAttribute('data-toggle', 'modal');
                    button.setAttribute('data-target', '#viewPaymentModal');
                    button.className = 'invisible';

                    // Append the button to a container element (e.g., a div with the ID 'buttonContainer')
                    var buttonContainer = document.getElementById('buttonContainer'); // Change to your container's ID
                    buttonContainer.appendChild(button);

                    // Get the button element by its class or ID
                    var button = document.querySelector('.invisible'); // Change to your class or ID

                    // Trigger a click event on the button
                    button.click();
                    
                    openPaymentModal(receiptNumber, customername, idInvoice, duedate)
        
                }
                
            });

        }
        
    });



});

$("#view-reciept-pdf-link").on("click", function(){
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


$("#download-reciept-pdf-link").on("click", function(){
    var receipt = $(this).attr("receipt");
    var fileURL = "views/pdfs/receipts/" + receipt + ".pdf";
    var link = document.createElement('a');
    link.href = fileURL;
    link.target = '_blank';
    link.download = receipt + '.pdf';
    
    // Check if the file exists using an AJAX request
    $.ajax({
        type: 'HEAD',
        url: fileURL,
        success: function () {
            // File exists, create and trigger the link
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },
        error: function () {
            // File doesn't exist, display SweetAlert
            swal("File Not Found", "The receipt PDF file doesn't exist.", "error");
        }
    });
    

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

