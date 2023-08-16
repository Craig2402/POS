localStorage.removeItem("captureRange");
if (localStorage.getItem('captureRange') !== null) {
  $('#daterange-btn span').html(localStorage.getItem('captureRange'));
} else {
  $('#daterange-btn span').html('<i class="far fa-calendar-alt"></i> Date range');
}


// Function to filter invoices based on search input
function filterInvoices() {
    var input = document.getElementById("searchInput");
    var filter = input.value.toLowerCase();
    var tableBody = document.getElementById("invoiceTableBody");
    var rows = tableBody.getElementsByTagName("tr");

    for (var i = 0; i < rows.length; i++) {
        var customerName = rows[i].getElementsByTagName("td")[2];
        var phoneNumber = rows[i].getElementsByTagName("td")[3];
        if (customerName && phoneNumber) {
            var nameValue = customerName.textContent || customerName.innerText;
            var phoneValue = phoneNumber.textContent || phoneNumber.innerText;
            if (nameValue.toLowerCase().indexOf(filter) > -1 || phoneValue.toLowerCase().indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
}

// Add event listener to search input
var searchInput = document.getElementById("searchInput");
searchInput.addEventListener("keyup", filterInvoices);

/*=============================================
DATES RANGE
=============================================*/

$('#daterange-btn').daterangepicker(
  {
    ranges   : {
      'Today'       : [moment(), moment()],
      'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 days' : [moment().subtract(6, 'days'), moment()],
      'Last 30 days': [moment().subtract(29, 'days'), moment()],
      'This month'  : [moment().startOf('month'), moment().endOf('month')],
      'Last month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment(),
    endDate  : moment()
  },
  function (start, end) {
    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

    var initialDate = start.format('YYYY-MM-DD');

    var finalDate = end.format('YYYY-MM-DD');

    var captureRange = $("#daterange-btn span").html();
   
   	localStorage.setItem("captureRange", captureRange);
   	console.log("localStorage", localStorage);

   	window.location = "index.php?route=invoices&initialDate="+initialDate+"&finalDate="+finalDate;

  }

)

/*=============================================
CANCEL DATES RANGE
=============================================*/

$(".daterangepicker.opensright .cancelBtn").on("click", function(){

	localStorage.removeItem("captureRange");
	localStorage.clear();
	window.location = "invoices";
})

/*=============================================
CAPTURE TODAY'S BUTTON
=============================================*/

$(".daterangepicker.opensright .ranges li").on("click", function(){

	var todayButton = $(this).attr("data-range-key");

	if(todayButton == "Today"){

		var d = new Date();
		
		var day = d.getDate();
		var month= d.getMonth()+1;
		var year = d.getFullYear();

		if(month < 10){

			var initialDate = year+"-0"+month+"-"+day;
			var finalDate = year+"-0"+month+"-"+day;

		}else if(day < 10){

			var initialDate = year+"-"+month+"-0"+day;
			var finalDate = year+"-"+month+"-0"+day;

		}else if(month < 10 && day < 10){

			var initialDate = year+"-0"+month+"-0"+day;
			var finalDate = year+"-0"+month+"-0"+day;

		}else{

			var initialDate = year+"-"+month+"-"+day;
	    	var finalDate = year+"-"+month+"-"+day;

		}	

    	localStorage.setItem("captureRange", "Today");

    	window.location = "index.php?route=invoices&initialDate="+initialDate+"&finalDate="+finalDate;

	}

})


// Attach a click event listener to the parent element
document.getElementById('invoiceTable').addEventListener('click', function(event) {
  // Check if the clicked element has a class that corresponds to the buttons
  if (event.target.classList.contains('downloadinvoice')) {
      var invoiceId = event.target.getAttribute('idInvoice');
      // Handle download invoice action
      console.log('Download invoice for invoiceId:', invoiceId);
  } else if (event.target.classList.contains('viewInvoice')) {
      var invoiceId = event.target.getAttribute('idInvoice');
      // Handle view invoice action
      console.log('View invoice for invoiceId:', invoiceId);
  } else if (event.target.classList.contains('addPayment')) {
      var invoiceId = event.target.getAttribute('idInvoice');
      // Handle add payment action
      console.log('Add payment for invoiceId:', invoiceId);
  }
});


/*=============================================
VIEW INVOICE MODAL
=============================================*/

$(".viewInvoice").on("click", function(){
  console.log("clicked");

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
        console.log(answer);
      }, error: function() {
          Swal.fire("Error", "Failed to retrieve invoice data from the server.", "error");
      }

  });

})
