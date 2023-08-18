


  // Get the input fields for new password and repeat password
  // var userId = document.querySelector(".userId");
  var oldPasswordInput = document.querySelector(".oldpass");
  var newPasswordInput = document.querySelector(".newpass");
  var repeatPasswordInput = document.querySelector('.rnewpass');
  var oldpasswordMismatchWarning = document.getElementById('oldpasswordMismatchWarning');
  var emptyFieldWarning = document.getElementById('emptyFieldWarning');
// Variable to keep track of ongoing AJAX request for old password check
var checkingOldPassword = false;

// Function to check if the old password is correct
function checkOldPassword() {
  var oldpassword = document.querySelector(".oldpass").value;
  var userIdInput = document.querySelector(".userId");
  var userId = userIdInput.value;

  var data = new FormData();
  data.append("oldpassword", oldpassword);
  data.append("user-id", userId);

  // Set the checkingOldPassword flag to true while AJAX request is ongoing
  checkingOldPassword = true;

  // Make an AJAX request to fetch the user profile data
  $.ajax({
    type: "POST",
    url: "ajax/user.ajax.php",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    dataType: "json",
    success: function(answer) {
      console.log(answer);
      checkingOldPassword = false; // Reset the flag when AJAX request is completed
      if (!answer) {
        oldpasswordMismatchWarning.style.display = 'block';
      } else {
        oldpasswordMismatchWarning.style.display = 'none';
      }
    }

  });
}

// Event listener to trigger the check when the user types in the old password field
oldPasswordInput.addEventListener('keyup', checkOldPassword);

// Function to check if the new and repeat passwords match
function checkPasswordMatch() {
  var newPassword = newPasswordInput.value;
  var repeatPassword = repeatPasswordInput.value;
  if (newPassword !== repeatPassword) {
    passwordMismatchWarning.style.display = 'block';
  } else {
    passwordMismatchWarning.style.display = 'none';
  }
}

// Event listener to trigger the check when the user types in the repeat password field
repeatPasswordInput.addEventListener('input', checkPasswordMatch);

 // Function to check if all required fields are filled
 function checkFormFields() {
    var oldPassword = oldPasswordInput.value.trim();
    var newPassword = newPasswordInput.value.trim();
    var repeatPassword = repeatPasswordInput.value.trim();

    if (oldPassword === "" || newPassword === "" || repeatPassword === "") {
      event.preventDefault();
      emptyFieldWarning.style.display = 'block';
      return false;
    }

    // If all checks pass, return true to allow form submission
    return true;
  }

  // Add an event listener to the "Update password" button
  $(".updatePasswordFooter2 .btn-primary").on("click", function(event) {
    // Check if old password is being checked through AJAX request
    if (checkingOldPassword) {
      event.preventDefault();
      return;
    }

    // Check if any warning message is being displayed
    if (oldpasswordMismatchWarning.style.display === 'block' || passwordMismatchWarning.style.display === 'block') {
      event.preventDefault();
      return;
    }

    // Check if all required fields are filled
    if (!checkFormFields()) {
      event.preventDefault();
      return;
    }

    // If all checks pass, submit the form
    $("#passwordChangeForm").submit();
  });
  
$(document).on("click", "#store_id", function(){

  var storeid = $(this).attr("value");

  var data= new FormData();
  data.append("store_id", storeid);

  $.ajax({
      type: "POST",
      url: "ajax/session.ajax.php",
      data: data, // Send the userId as a query parameter
      contentType:false,
      caches:false,
      processData:false,
      dataType: "json",
      success: function(answer) {
        window.location = "dashboard"
      }
    });
});
$(document).on("click", "#exit_store", function(){

var storeid = $(this).attr("value");

var data= new FormData();
data.append("exit_store", storeid);

$.ajax({
    type: "POST",
    url: "ajax/session.ajax.php",
    data: data, // Send the userId as a query parameter
    contentType:false,
    caches:false,
    processData:false,
    dataType: "json",
    success: function(answer) {
      window.location = "dashboard"
    }
  });
});

// Wait for the document to be ready
$(document).ready(function() {
  // Add an event listener to the "Change password" button
  $(".changePasswordButton").on("click", function() {
    // Hide the current content (profile information)
    $(".modalBodyContent, .updatePasswordFooter1").fadeOut(200, function() {
      // Display the password change content
      $(".passwordChangeContent").fadeIn(200);
      $(".updatePasswordFooter2").fadeIn(200);
    });
  });
  
    // Add an event listener to the modal's hidden.bs.modal event
    $('#userProfileModal').on('hidden.bs.modal', function() {
      $("#passwordChangeForm")[0].reset();
      // Reset the modal content when the modal is closed
      $(".passwordChangeContent, .updatePasswordFooter2").fadeOut(200, function() {
        // Display the profile information content
        oldpasswordMismatchWarning.style.display = 'none';
        passwordMismatchWarning.style.display = 'none';
        emptyFieldWarning.style.display = 'none';
        $(".modalBodyContent, .updatePasswordFooter1").fadeIn(200);
      });
    });
});

// Listen for changes in the profile picture input
$("#profilePictureInput").change(function () {
    // Submit the form when an image is selected
    $("#profilePictureForm").submit();

    $("#profilePictureInput").val('');
    
});

// Reset the settings modal content when the modal is closed
// $('#settingsModal').on('hidden.bs.modal', function () {
//   // Clear the checkboxes and form fields
//   $('#lipaMdogo').prop('checked', false);
//   $('#loyaltyPoints').prop('checked', false);
//   $('#logoInput').val('');
//   $('#loyaltyValueConversion').val('');
//   $('#loyaltyPointValue').val('');
// });

// fetch settings data
$(".settings").on("click", function() {

  $.ajax({
    type: "GET",
    url: "ajax/settings.ajax.php",
    cache: false,
    success: function(settingsData) {
      // Parse the JSON response
      try {
        settingsData = JSON.parse(settingsData);
      } catch (error) {
        console.error("Error parsing JSON response:", error);
        return;
      }
  
      // Find the objects with SettingName "LoyaltyPointValue" and "LoyaltyValueConversion"
      var LoyaltyPointValue = settingsData.find(setting => setting.SettingName === "LoyaltyPointValue");
      var LoyaltyValueConversion = settingsData.find(setting => setting.SettingName === "LoyaltyValueConversion");
      var lipaMdogoSetting = settingsData.find(setting => setting.SettingName === "Lipamdogomdogo");
      var loyaltyPointsSetting = settingsData.find(setting => setting.SettingName === "Loyaltypoints");

      // Set values for form fields
      $('#loyaltyPointValue').val(LoyaltyPointValue.SettingValue);
      $('#loyaltyValueConversion').val(LoyaltyValueConversion.SettingValue);
  
      // Check if both settings have SettingValue of "1"
      if (lipaMdogoSetting && loyaltyPointsSetting) {
        if (lipaMdogoSetting.SettingValue === "1" && loyaltyPointsSetting.SettingValue === "1") {  
          // Check both checkboxes
          $('#lipaMdogo').prop('checked', true);
          $('#loyaltyPoints').prop('checked', true);
        } else {          
          // Check a checkbox based on the condition
          if (lipaMdogoSetting.SettingValue === "1") {
            $('#lipaMdogo').prop('checked', true);
          } else if (loyaltyPointsSetting.SettingValue === "1") {
            $('#loyaltyPoints').prop('checked', true);
          }
        }
      }
    },
    error: function(xhr, status, error) {
      console.error("AJAX request error:", error);
    }
  });
  
});

// this function holds the ajax request
function settingsAjax(item, value) {
  
  var data= new FormData();
  data.append("item", item);
  data.append("value", value);

  $.ajax({
    type: "POST",
    url: "ajax/settings.ajax.php",
    data: data, 
    contentType:false,
    caches:false,
    processData:false,
    dataType: "json",
    success: function(answer) {
      console.log(answer);
    },
    error: function(xhr, status, error) {
      console.error("AJAX request error:", error);
    }
  });
}

// Listen for changes in the "Activate Lipa Mdogo Mdogo" checkbox
$('#lipaMdogo').on('change', function() {
  var item = "Lipamdogomdogo";
  if (this.checked) {
    // Checkbox is checked
    console.log("Lipa Mdogo Mdogo activated");
    var value = 1;
    settingsAjax(item, value);
    // Perform your action here
  } else {
    // Checkbox is unchecked
    console.log("Lipa Mdogo Mdogo deactivated");
    var value = 0;
    settingsAjax(item, value);
    // Perform your action here
  }
});

// Listen for changes in the "Activate Loyalty Points" checkbox
$('#loyaltyPoints').on('change', function() {
  var item = "Loyaltypoints";
  if (this.checked) {
    // Checkbox is checked
    console.log("Loyalty Points activated");
    var value = 1;
    settingsAjax(item, value);
    // Perform your action here
  } else {
    // Checkbox is unchecked
    console.log("Loyalty Points deactivated");
    var value = 0;
    settingsAjax(item, value);
    // Perform your action here
  }
});

