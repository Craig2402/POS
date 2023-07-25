var orderarr = [];
var products = [];

// When the "Add to list" button is clicked
$('#product').on('change', function() {
    var barcodeProduct = $('#product').val(); // Get the selected product ID
  
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
            if (answer && answer["id"]) {
                if (jQuery.inArray(answer["id"], orderarr) != -1) {

                    // Existing order
                    var actualqty = parseInt($('#qty_id' + answer["id"]).val()) + 1;
                    $('#qty_id' + answer["id"]).val(actualqty).trigger('change');
                    $('#product').val('');
                    updateProducts();
                    calculateTotal()

                }else{

                    addRow(answer["id"], answer["product"], answer["purchaseprice"]);
                    orderarr.push(answer["id"]);
                    $('#product').val('');
                    
                    function addRow(id, product, purchaseprice) {
                        
                        var qty = 1; // Default quantity
                        products.push({"id":id, "productName":product, "Quantity":qty, "Price":purchaseprice})
                        updateProducts();

                        var tr ='<tr>' +
                                    '<td style="text-align:left; vertical-align:middle; font-size:17px;"><span class="badge badge-dark">' + product + '</span></td>' +
                                    '<td style="text-align:left; vertical-align:middle; font-size:17px;"><span class="badge badge-warning price" name="price_arr[]" id="price_id' + id + '">' + purchaseprice + '</span></td>' +
                                    '<td><input type="number" class="form-control qty" min="1" name="quantity_arr[]" id="qty_id' + id + '" value="' + 1 + '" size="1"></td>' +
                                    '<td style="text-align:left; vertical-align:middle; font-size:17px;"><span class="badge badge-danger totalamt" name="netamt_arr[]" id="purchaseprice_id' + id + '">' + purchaseprice + '</span><input type="hidden" class="form-control purchaseprice" name="purchaseprice_c_arr[]" id="purchaseprice_idd' + id + '" value="' + purchaseprice + '"></td>' +
                                    '<td style="text-align:left; vertical-align:middle;"><center><name="remove" class="btnremove" data-id="' + id + '"><span class="fas fa-trash" style="color:red;"></span></center></td>' +
                                '</tr>';
                    
                        $('.orders').append(tr);
                        calculateTotal()
                    }

                }

            }else{
                Swal.fire({
                    icon: "warning",
                    title: "Select a product to add it to the order list.",
                    showConfirmButton: false,
                    timer: 2000 // Set the timer for 2 seconds (2000 milliseconds)
                }).then(function () {
                    // Redirect to "orders" page after the timer expires
                    $('#product').val('');
                });
            }

        }

    });

});

// Removing a product from the order list
$(document).on('click', '.btnremove', function() {
    var id = $(this).data('id'); // Get the product ID from the data attribute
  
    // Remove the product ID from the orderarr array
    var index = orderarr.indexOf(id);
    if (index !== -1) {
      orderarr.splice(index, 1);
    }
  
    // Remove the product details from the products array
    products = products.filter(function(product) {
      return product.id !== id;
    });
  
    // Remove the row from the table
    $(this).closest('tr').remove();
  
    // Update the products input field
    updateProducts();
  
    // Recalculate the total price
    calculateTotal();
});

// increase or change the quantity of a product in the order list 
$(document).on('keyup change', '.qty', function() {
    var quantity = $(this).val();
    var tr = $(this).closest('tr');
    var id = tr.find('.btnremove').data('id');
  
    // Find the index of the object with a specific id
    const productIndex = products.findIndex(obj => obj.id === id);
  
    // Check if the object with the specified id exists in the array
    if (productIndex !== -1) {
      // Update the Quantity property
      products[productIndex].Quantity = parseInt(quantity);
    }
  
    updateProducts();
  
    tr.find('.totalamt').text(quantity * tr.find('.price').text());
    tr.find('.saleprice').val(quantity * tr.find('.price').text());
    calculateTotal();
});
  
// Function to update the array and set it to the productsList input
function updateProducts() {
    const updatedArrayJSON = JSON.stringify(products);
    $('#products').val(updatedArrayJSON);
}

// calculate totals for all the products on the list
function calculateTotal() {
    total = 0;

    $(".orders tr").each(function() {
        var tr = $(this);
        var price = parseFloat(tr.find(".price").text());
        var quantity = parseInt(tr.find(".qty").val());
        total += price * quantity;
    });

    $('#total').val(total.toFixed(2));
}
