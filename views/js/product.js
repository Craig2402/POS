/*=============================================
LOAD DYNAMIC PRODUCTS TABLE
=============================================*/

// $.ajax({

// 	url: "ajax/datatable-products.ajax.php",
// 	success:function(answer){
// 		console.log("answer", answer);

// 	}

// })
// $(".productsTable").DataTable({
// 	"ajax": "ajax/datatable-products.ajax.php", 
// 	"deferRender": true,
// 	"retrieve": true,
// 	"processing": true,
// 	"responsive": true,
// 	"lengthChange": false,
// 	"autoWidth": false,
// 	"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
// }).buttons().container().appendTo('#productsTable .col-md-6:eq(0)');


$('.productsTable').DataTable({
	"ajax": "ajax/datatable-products.ajax.php", 
	"deferRender": true,
	"retrieve": true,
	"processing": true,
	"responsive": true,
});

/*=============================================
UPLOADING PRODUCT IMAGE
=============================================*/

$(".txtproductimage").change(function(){

	var image = this.files[0];
	
	/*=============================================
  	WE VALIDATE THAT THE FORMAT IS JPG OR PNG
  	=============================================*/

  	if(image["type"] != "image/jpeg" && image["type"] != "image/png"){

  		$(".txtproductimage").val("");

  		 Swal.fire({
		      title: "Error uploading image",
		      text: "The image should be in JPG or PNG format!",
		      icon: "error",
		      confirmButtonText: "Close!"
		    });

  	}else if(image["size"] > 2000000){

  		$(".txtproductimage").val("");

  		 Swal.fire({
		      title: "Error uploading image",
		      text: "The image shouldn't be more than 2MB!",
		      icon: "error",
		      confirmButtonText: "Close!"
		    });

  	}else{

  		var imageData = new FileReader;
  		imageData.readAsDataURL(image);

  		$(imageData).on("load", function(event){

  			var imagePath = event.target.result;

  			$(".preview").attr("src", imagePath);

  		})

  	}
});

/*=============================================
EDIT PRODUCT
=============================================*/

$(".tables tbody").on("click", "button.btnEditProduct", function(){

	var barcodeProduct = $(this).attr("idProduct");
	
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
        
        //  console.log("answer", answer);
          
        var categoryData = new FormData();
        categoryData.append("idCategory",answer["idCategory"]);


         $.ajax({

            url:"ajax/categories.ajax.php",
            method: "POST",
            data: categoryData,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){

				// console.log(answer);
                
                $("#editcategory").val(answer["id"]);
                $("#editcategory").html(answer["Category"]);

            }

        })

		$("#editbarcode").val(answer["barcode"]);

		$("#editproductname").val(answer["product"]);

		$("#editcategory").val(answer["Category"]);

		$("#editdescription").val(answer["description"]);

		$("#editstock").val(answer["stock"]);

		$("#editpurchaseprice").val(answer["purchaseprice"]);

		$("#editsaleprice").val(answer["saleprice"]);

		$("#edittaxcat").val(answer["taxId"]);

		if(answer["image"] != ""){

			  $("#currentImage").val(answer["image"]);

			  $(".preview").attr("src",  answer["image"]);

         }

      }

  })

})

/*=============================================
DELETE PRODUCT
=============================================*/

$(".tables tbody").on("click", "button.btnDeleteProduct", function(){

	var barcodeProduct = $(this).attr("idProduct");
	// var code = $(this).attr("code");
	var image = $(this).attr("image");
	
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

        	window.location = "index.php?route=products&barcodeProduct="+barcodeProduct+"&image="+image;

        }

	})

})

$(document).ready(function() {
	$('#stock').change(function() {
		var selectedProduct = $('#stock option:selected').text();
		$('#sproduct').val(selectedProduct);

		
	var barcode = $('#stock').val();

	// Fetch the product data
	$.ajax({
		url: 'ajax/products.ajax.php',
		method: 'POST',
		data: { barcodeProduct: barcode },
		dataType: 'json',
		success: function(response) {
			// Handle the product data response
			console.log(response);  // You can inspect the response object in the browser's console
			// Update the necessary fields with the received data
			// Example:
				// Set the product image
				$('#productImage').attr('src', response.image);
				$("#cstock").val(response.stock);
		}
	});
});
});