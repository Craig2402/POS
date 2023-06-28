var orderarr = [];

// When the "Add to list" button is clicked
$('button[name="addproduct"]').on('click', function() {
    var barcodeProduct = $('#product').val(); // Get the selected product ID
    var quantity = $('#quantity').val(); 
  
    var datum = new FormData();
    datum.append("barcodeProduct", barcodeProduct);
  
    $.ajax({
        url: "ajax/products.ajax.php",
        method: "POST",
        data: datum,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {

            addRow(data["id"], answer["product"], answer["purchaseprice"]);
            
            function addRow(id, product, purchaseprice) {

                var tr = '<tr>' +
                '<td style="text-align:left; vertical-align:middle; font-size:17px;"><span class="badge badge-dark">' + product + '</span><input type="hidden" class="form-control product_c" name="orderarr[]" value="' + product + '"><input type="hidden" class="form-control idTaxdis" name="tax_arr[]" value="' + taxId + '"><input type="hidden" class="form-control barcode" name="barcode_arr[]" value="' + barcode + '"></td>' +
                '<td style="text-align:left; vertical-align:middle; font-size:17px;"><span class="badge badge-warning price" name="price_arr[]" id="price_id' + id + '">' + purchaseprice + '</span><input type="hidden" class="form-control price_c" name="price_c_arr[]" id="price_idd' + id + '" value="' + purchaseprice + '"></td>' +
                '<td><input type="number" class="form-control qty" min="1" name="quantity_arr[]" id="qty_id' + id + '" value="' + 1 + '" size="1"></td>' +
                '<td style="text-align:left; vertical-align:middle; font-size:17px;"><span class="badge badge-danger totalamt" name="netamt_arr[]" id="purchaseprice_id' + id + '">' + purchaseprice + '</span><input type="hidden" class="form-control purchaseprice" name="purchaseprice_c_arr[]" id="purchaseprice_idd' + id + '" value="' + purchaseprice + '"></td>' +
            
                //remove item button
            
                '<td style="text-align:left; vertical-align:middle;"><center><name="remove" class="btnremove" data-id="' + id + '"><span class="fas fa-trash" style="color:red;"></span></center></td>' +
                '</tr>';
            
                $('.orders').append(tr);
            }
            // // Get the current textarea content
            // var currentContent = $('#products').val();
            
            // // Create the new product information
            // var newProductInfo = "Product: " + answer.product + ' - ' + answer.description + ' Quantity:' + quantity;

            // // Check if the product already exists in the textarea
            // if (currentContent.includes(answer.product)) {
            //     Swal.fire({
            //         title: "Error adding product",
            //         text: "The product already exists",
            //         icon: "error",
            //         confirmButtonText: "Close!"
            //     });
            // } else {
            //     // Append the new product information to the current content
            //     var updatedContent = currentContent ? currentContent + '\n' + newProductInfo : newProductInfo;

            //     // Update the textarea with the combined content
            //     $('#products').val(updatedContent);

            //     // Calculate and update the total purchase price
            //     var currentPurchasePrice = parseFloat($('#total').val()) || 0;
            //     var newPurchasePrice = currentPurchasePrice + parseFloat(answer.purchaseprice);
            //     $('#total').val(newPurchasePrice.toFixed(2));
            // }

            // Reset the input fields
            $('#product').val('');
            $('#quantity').val('');
        }

    });

});
  