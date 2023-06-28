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
        // Get the current textarea content
        var currentContent = $('#products').val();
  
        // Create the new product information
        var newProductInfo = "Product: " + answer.product + ' - ' + answer.description + ' Quantity:' + quantity;
  
        // Check if the product already exists in the textarea
        if (currentContent.includes(answer.product)) {
            Swal.fire({
                title: "Error adding product",
                text: "The products already exist",
                icon: "error",
                confirmButtonText: "Close!"
              });
        } else {
          // Append the new product information to the current content
          var updatedContent = currentContent ? currentContent + '\n' + newProductInfo : newProductInfo;
  
          // Update the textarea with the combined content
          $('#products').val(updatedContent);
        }
  
        // Reset the input fields
        $('#product').val('');
        $('#quantity').val('');
      }
    });
  });
  