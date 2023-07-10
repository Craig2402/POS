// // mark as read ajax
// function MarkReadNotification(type, session) {

//     var datum = new FormData();
//     datum.append("session", session);
//     datum.append("type", type);
    
//     $.ajax({
//         url: "ajax/notifications.ajax.php",
//         method: "POST",
//         data: datum,
//         cache: false,
//         contentType: false,
//         processData: false,
//         dataType: "json",
//         success: function(answer) {
//             // Handle the AJAX success response here
//         }

//     });
// }

// //  mark as read ajax rejected
// function MarkReadNotificationRejected(type, session, status, user, answer) {
    
//     var datum = new FormData();
//     datum.append("session", session);
//     datum.append("type", type);
//     datum.append("status", status);
//     datum.append("user", user);
//     datum.append("name", answer);
    
//     $.ajax({
//         url: "ajax/notifications.ajax.php",
//         method: "POST",
//         data: datum,
//         cache: false,
//         contentType: false,
//         processData: false,
//         dataType: "json",
//         success: function(answer) {
//             // Handle the AJAX success response here
//         }

//     });

// }

// // Add event listener to the parent element containing notification items
// var notificationItemsContainer = document.getElementById('notificationItems');
// notificationItemsContainer.addEventListener('click', function(event) {
//     var clickedElement = event.target;

//     // Check the class name or other attributes of the clicked element to determine the type of notification
//     if (clickedElement.classList.contains('stock-notification')) {

//         // Stock notification item clicked
//         var barcode = clickedElement.getAttribute('data-value1');
//         var session = clickedElement.getAttribute('data-value2');
//         var type = "Stock notification," + barcode;
        
//         window.location = "index.php?route=viewproduct&barcode=" + barcode;
//         MarkReadNotification(type, session);

//     } else if (clickedElement.classList.contains('product-deletion')) {

//         // Product deletion notification item clicked
//         var barcodeProduct = clickedElement.getAttribute('data-value1');
//         var session = clickedElement.getAttribute('data-value2');
//         var name = clickedElement.getAttribute('data-value3');
//         var message = clickedElement.getAttribute('data-value4');
//         var type = "Product deletion,"+barcodeProduct;
//         // Handle product deletion
//         var datum = new FormData();
//         datum.append("barcodeProduct", barcodeProduct);

//         $.ajax({

//             url:"ajax/products.ajax.php",
//             method: "POST",
//             data: datum,
//             cache: false,
//             contentType: false,
//             processData: false,
//             dataType:"json",
//             success:function(answer){

//                 // Show specific SweetAlert for product deletion
//                 Swal.fire({
//                     title: 'Product Deletion',
//                     html: `Reason: ${message}<br>Are you sure you want to delete ${answer['product']} ?<br>Changes made can not be reverted.`,
//                     icon: 'warning',
//                     showCancelButton: true,
//                     confirmButtonText: 'Confirm',
//                     cancelButtonText: 'Cancel',
//                 }).then((result) => {
//                     // If the user clicks on 'Confirm', handle the product deletion
//                     if (result.isConfirmed) {
//                         window.location = "index.php?route=products&barcodeProduct="+barcodeProduct;
//                         MarkReadNotification(type, session);
//                     }else if (result.dismiss === Swal.DismissReason.cancel) {
//                         MarkReadNotification(type, session);
//                     }

//                 });

//             }

//         });

//     } else if (clickedElement.classList.contains('category-deletion')) {
//         // Category deletion notification item clicked
//         var idCategory = clickedElement.getAttribute('data-value1');
//         var session = clickedElement.getAttribute('data-value2');
//         var user = clickedElement.getAttribute('data-value3');
//         var message = clickedElement.getAttribute('data-value4');
//         var type = "Category deletion,"+idCategory;
//         var status = "Rejected";

//         // Handle category deletion
//         var datum = new FormData();
//         datum.append("idCategory", idCategory);

//         $.ajax({
//             url: "ajax/categories.ajax.php",
//             method: "POST",
//             data: datum,
//             cache: false,
//             contentType: false,
//             processData: false,
//             dataType:"json",
//             success: function(answer){
//                 var answer = answer['Category']
//                 // Show specific SweetAlert for category deletion
//                 Swal.fire({
//                     title: 'Category Deletion',
//                     html: `Reason: ${message}<br>Are you sure you want to delete the ${answer} category?<br>All the products in this category will also be deleted.<br>Changes made can not be reverted.`,
//                     icon: 'warning',
//                     showCancelButton: true,
//                     confirmButtonText: 'Confirm',
//                     cancelButtonText: 'Cancel',
//                 }).then((result) => {
//                     // If the user clicks on 'Confirm', handle the category deletion
//                     if (result.isConfirmed) {
//                         window.location = "index.php?route=category&idCategory="+idCategory;
//                         MarkReadNotification(type, session);
//                     }else if (result.dismiss === Swal.DismissReason.cancel) {
//                         MarkReadNotificationRejected(type, session, status, user, answer);
//                         MarkReadNotification(type, session);
//                     }

//                 });
//             }

//         });
        
//     } else if (clickedElement.classList.contains('admin-feedback')) {
//         // Admin feedback notification item clicked
//         var type = clickedElement.getAttribute('data-value1');
//         var session = clickedElement.getAttribute('data-value2');
        
//         MarkReadNotification(type, session);
//     }
// });
  

// // Create an empty array to store all notifications
// var unreadNotifications = [];
// // Create an empty array to store all notifications
// var presentNotifications = [];
// // validates the new notifications
// function validateNewNotification() {
//     var datum = new FormData();

//     $.ajax({
//         url: "ajax/notifications.ajax.php",
//         method: "POST",
//         data: datum,
//         cache: false,
//         contentType: false,
//         processData: false,
//         dataType: "json",
//         success: function(notifications) {
    
//             $.ajax({
//                 url: "ajax/session.ajax.php",
//                 method: "GET",
//                 dataType: "json",
//                 success: function(data) {

//                     // Handle the AJAX success response here
//                     var sessionId = data.sessionId;
//                     var sessionRole = data.sessionRole;

//                     // Initialize an array to hold the allowed notification types for each role
//                     let allowedNotificationTypes = [];
//                     if (sessionRole === 'Administrator') {
//                         // Admin can see all notification types
//                         allowedNotificationTypes = ['Stock notification', 'Category deletion', 'Product deletion', 'Admin feedback'];
//                     } else if (sessionRole === 'Store') {
//                         // Storekeeper can see 'Orders', 'Stock', and 'Acceptions' notification types
//                         allowedNotificationTypes = ['Admin feedback', 'Stock', 'Acceptions'];
//                     } else if (sessionRole === 'Seller') {
//                         // Seller can see 'Stock' and 'Acceptions' notification types
//                         allowedNotificationTypes = ['Stock', 'Acceptions'];
//                     }
                    
//                     notifications.forEach((notification) => {
//                         var message = notification.message;
//                         var notification_type = notification.notification_type;
//                         var createDate = new Date(notification.create_date);
//                         var status = notification.status;
//                         var viewedBy = notification.viewed_by;
//                         var name = notification.name;
//                         var notificationId = notification.id;

//                         // add the notification to the list of present notifications
//                         presentNotifications.push(notificationId);


//                         // Check if the notification type is allowed for the user's role
//                         let notificationTypeAllowed = false;

//                         allowedNotificationTypes.forEach(allowedType => {
//                             if (notification.notification_type.includes(allowedType)) {
//                                 notificationTypeAllowed = true;
//                             }
//                         });

//                         // If the notification type is not allowed for the user's role, skip this notification
//                         if (!notificationTypeAllowed) {
//                             return; // Skip this notification
//                         }

//                         let productName = '';
//                         let categoryName = '';
//                         let viewUser = '';
                        
//                         if (notification.notification_type.includes('Stock notification')) {
//                             var notificationTypeParts = notification.notification_type.split(',');
//                             var notificationType = notificationTypeParts[0].trim();
//                             var barcode = notificationTypeParts[1].trim();
                        
//                             href = 'index.php?route=viewproduct&barcode=' + barcode;
//                             icon = '<i class="fa-solid fa-boxes-stacked"></i>';
//                         } else if (notification.notification_type.includes('Category deletion')) {
//                             var notificationTypeParts = notification.notification_type.split(',');
//                             var notificationType = notificationTypeParts[0].trim();
//                             var categoryid = notificationTypeParts[1].trim();
                        
//                             var item = 'id';
//                             var value = categoryid;
                            
//                             var datum = new FormData();
//                             datum.append("item", item);
//                             datum.append("value", value);
                            
//                             $.ajax({
//                                 url: "ajax/categories.ajax.php",
//                                 method: "POST",
//                                 data: datum,
//                                 cache: false,
//                                 contentType: false,
//                                 processData: false,
//                                 dataType:"json",
//                                 success: function(answer){

//                                     categoryName = answer.Category;
//                                     icon = '<i class="fa-solid fa-sitemap"></i>';

//                                 }
//                             });
//                             // var answer = categoriesController.ctrShowCategories(item, value);
//                         } else if (notification.notification_type.includes('Product deletion')) {
//                             var notificationTypeParts = notification.notification_type.split(',');
//                             var notificationType = notificationTypeParts[0].trim();
//                             var barcode = notificationTypeParts[1].trim();
                        
//                             var item = 'barcode';
//                             var value = barcode;
//                             var order = 'id';
                            
//                             var datum = new FormData();
//                             datum.append("item", item);
//                             datum.append("value", value);
//                             datum.append("order", order);
                            
//                             $.ajax({
//                                 url: "ajax/products.ajax.php",
//                                 method: "POST",
//                                 data: datum,
//                                 cache: false,
//                                 contentType: false,
//                                 processData: false,
//                                 dataType:"json",
//                                 success: function(answer){

//                                     productName = answer.product;                                
//                                     icon = '<i class="fa-solid fa-boxes-stacked"></i>';

//                                 }
//                             });
//                             // var answer = productController.ctrShowProducts(item, value, order);
//                         } else if (notification.notification_type.includes('Admin feedback')) {
//                             var notificationTypeParts = notification.notification_type.split(',');
//                             var notificationType = notificationTypeParts[0].trim();
//                             viewUser = notificationTypeParts[1].trim();
                        
//                             icon = '<i class="fa-solid fa-xmark"></i>';
//                         }

//                         if (!viewedBy.includes(sessionId)) {
//                             // Get the notification datetime from your data with the timezone information
//                             var createDate = new Date(notification.create_date);
//                             var currentDate = new Date();
//                             var timeDifference = Math.abs(currentDate - createDate) / 1000; // in seconds

//                             let timeDifferenceString = '';

//                             if (timeDifference <= 10) {
//                                 timeDifferenceString = 'just now';
//                             } else if (timeDifference <= 60) {
//                                 timeDifferenceString = timeDifference + ' seconds ago';
//                             } else if (timeDifference <= 60 * 60) {
//                                 var minutes = Math.floor(timeDifference / 60);
//                                 timeDifferenceString = minutes + ' minutes ago';
//                             } else if (timeDifference <= 60 * 60 * 24) {
//                                 var hours = Math.floor(timeDifference / (60 * 60));
//                                 timeDifferenceString = hours + ' hours ago';
//                             } else if (timeDifference <= 60 * 60 * 24 * 7) {
//                                 var days = Math.floor(timeDifference / (60 * 60 * 24));
//                                 timeDifferenceString = days + ' days ago';
//                             } else if (timeDifference <= 60 * 60 * 24 * 7 * 4) {
//                                 var weeks = Math.floor(timeDifference / (60 * 60 * 24 * 7));
//                                 timeDifferenceString = weeks + ' weeks ago';
//                             } else if (timeDifference <= 60 * 60 * 24 * 7 * 4 * 12) {
//                                 var months = Math.floor(timeDifference / (60 * 60 * 24 * 7 * 4));
//                                 timeDifferenceString = months + ' months ago';
//                             } else {
//                                 var years = Math.floor(timeDifference / (60 * 60 * 24 * 7 * 4 * 12));
//                                 timeDifferenceString = years + ' years ago';
//                             }

//                             var currentRoute = window.location.pathname;
//                             var routeParts = currentRoute.split('/');
//                             var lastPart = routeParts[routeParts.length - 1];

//                             unreadNotifications.push({
//                                 name: name,
//                                 message: message,
//                                 notificationType: notification_type,
//                                 href: href,
//                                 icon: icon,
//                                 value: value,
//                                 type: notificationType,
//                                 session: sessionId,
//                                 category: categoryName,
//                                 viewUser: viewUser,
//                                 product: productName,
//                                 formattedDuration: timeDifferenceString,
//                                 currentRoute: lastPart,
//                                 barcode: barcode
//                             });

//                         }

//                     });

//                     var notificationCount = unreadNotifications.length;

//                     var rowCountSpan = document.getElementById('rowCountSpan');
//                     rowCountSpan.textContent = notificationCount;
                    
//                     var notificationCountElement = document.getElementById('notificationCount');
//                     notificationCountElement.textContent = `${notificationCount} Notifications`;
                    
//                     var notificationItemsContainer = document.getElementById('notificationItems');
//                     unreadNotifications.forEach(notification => {
//                       var notificationItem = document.createElement('a');
//                       notificationItem.classList.add('dropdown-item', 'notification-link');
//                     //   notificationItem.href = notification.currentRoute;
                    
//                       if (notification.type === 'Stock notification') {
//                         notificationItem.classList.add('stock-notification');
//                         notificationItem.setAttribute('data-value1', notification.barcode);
//                         notificationItem.setAttribute('data-value2', notification.session);
//                         notificationItem.innerHTML = `
//                           <i class="fa-solid fa-boxes-stacked"></i> ${notification.message}<br>
//                           <span class="float-right text-muted text-sm">${notification.formattedDuration}</span><br>
//                         `;
//                       } else if (notification.type === 'Product deletion') {
//                         notificationItem.classList.add('product-deletion');
//                         notificationItem.setAttribute('data-value1', notification.value);
//                         notificationItem.setAttribute('data-value2', notification.session);
//                         notificationItem.setAttribute('data-value3', notification.name);
//                         notificationItem.setAttribute('data-value4', notification.message);
//                         notificationItem.innerHTML = `
//                           <i class="fa-solid fa-boxes-stacked"></i> ${notification.name} wants to delete the product ${notification.product}<br>
//                           <span class="float-right text-muted text-sm">${notification.formattedDuration}</span><br>
//                         `;
//                       } else if (notification.type === 'Category deletion') {
//                         notificationItem.classList.add('category-deletion');
//                         notificationItem.setAttribute('data-value1', notification.value);
//                         notificationItem.setAttribute('data-value2', notification.session);
//                         notificationItem.setAttribute('data-value3', notification.name);
//                         notificationItem.setAttribute('data-value4', notification.message);
//                         notificationItem.innerHTML = `
//                           <i class="fa-solid fa-sitemap"></i> ${notification.name} wants to delete the ${notification.category} category<br>
//                           <span class="float-right text-muted text-sm">${notification.formattedDuration}</span><br>
//                         `;
//                       } else if (notification.type === 'Admin feedback' && notification.viewUser === sessionStorage.username) {
//                         notificationItem.classList.add('admin-feedback');
//                         notificationItem.setAttribute('data-value1', notification.notificationType);
//                         notificationItem.setAttribute('data-value2', notification.session);
//                         notificationItem.innerHTML = `
//                           <i class="fa-solid fa-xmark"></i> ${notification.message}<br>
//                           <span class="float-right text-muted text-sm">${notification.formattedDuration}</span><br>
//                         `;
//                       }

//                       notificationItemsContainer.appendChild(notificationItem);
//                       notificationItemsContainer.appendChild(document.createElement('div')).classList.add('dropdown-divider');
  
//                     });
//                     // Run the function every second
//                     setInterval(sendAjaxRequest, 1000);
//                 }

//             });

//         }

//     });

// }

// validateNewNotification();

// // Function to create a new HTML notification
// function createNotification(notification) {
//     // Extract the required properties from the notification object
//     var message = notification.message;
//     var notification_type = notification.notification_type;
//     var createDate = new Date(notification.create_date);
//     var viewedBy = notification.viewed_by;
//     var name = notification.name;
    
//     getSessionInfo(function(data) {
//         if (data !== null) {
//             // Data retrieval was successful
//             var sessionId = data.sessionId;
            
//             // Check if the notification type is allowed for the user's role
//             if (!isNotificationTypeAllowed(notification_type)) {
//                 return; // Skip this notification if not allowed
//             }

//             // Check if the notification has been viewed by the current logged in user
//             if (!viewedBy.includes(sessionId)) {
//                 // Get the notification datetime from your data with the timezone information
//                 var createDate = new Date(notification.create_date);
//                 var currentDate = new Date();
//                 var timeDifference = Math.abs(currentDate - createDate) / 1000; // in seconds
        
//                 let timeDifferenceString = '';
        
//                 if (timeDifference <= 10) {
//                     timeDifferenceString = 'just now';
//                 } else if (timeDifference <= 60) {
//                     timeDifferenceString = timeDifference + ' seconds ago';
//                 } else if (timeDifference <= 60 * 60) {
//                     var minutes = Math.floor(timeDifference / 60);
//                     timeDifferenceString = minutes + ' minutes ago';
//                 } else if (timeDifference <= 60 * 60 * 24) {
//                     var hours = Math.floor(timeDifference / (60 * 60));
//                     timeDifferenceString = hours + ' hours ago';
//                 } else if (timeDifference <= 60 * 60 * 24 * 7) {
//                     var days = Math.floor(timeDifference / (60 * 60 * 24));
//                     timeDifferenceString = days + ' days ago';
//                 } else if (timeDifference <= 60 * 60 * 24 * 7 * 4) {
//                     var weeks = Math.floor(timeDifference / (60 * 60 * 24 * 7));
//                     timeDifferenceString = weeks + ' weeks ago';
//                 } else if (timeDifference <= 60 * 60 * 24 * 7 * 4 * 12) {
//                     var months = Math.floor(timeDifference / (60 * 60 * 24 * 7 * 4));
//                     timeDifferenceString = months + ' months ago';
//                 } else {
//                     var years = Math.floor(timeDifference / (60 * 60 * 24 * 7 * 4 * 12));
//                     timeDifferenceString = years + ' years ago';
//                 }
        
//                 unreadNotifications.push({
//                     name: name,
//                     message: message,
//                     notificationType: notification_type,
//                     href: href,
//                     icon: icon,
//                     value: value,
//                     type: notificationType,
//                     session: sessionId,
//                     category: categoryName,
//                     viewUser: viewUser,
//                     product: productName,
//                     formattedDuration: timeDifferenceString,
//                     barcode: barcode
//                 });
        
//             }
//         } else {
//             // Error occurred during data retrieval
//             // console.error("Failed to retrieve session information.");
//             Swal.fire({
//                 title: 'Error',
//                 text: 'Error fetching session information.',
//                 icon: 'error',
//                 timer: 2000,
//                 showConfirmButton: false,
//                 allowOutsideClick: false
//             });
            
//         }

//     });

    
    
//     var notificationItemsContainer = document.getElementById('notificationItems');
    
//     var notificationItem = document.createElement('a');
//     notificationItem.classList.add('dropdown-item', 'notification-link');
    
//     if (notification.type === 'Stock notification') {
//         notificationItem.classList.add('stock-notification');
//         notificationItem.setAttribute('data-value1', notification.barcode);
//         notificationItem.setAttribute('data-value2', notification.session);
//         notificationItem.innerHTML = `
//         <i class="fa-solid fa-boxes-stacked"></i> ${notification.message}<br>
//         <span class="float-right text-muted text-sm">${notification.formattedDuration}</span><br>
//         `;
//     } else if (notification.type === 'Product deletion') {
//         notificationItem.classList.add('product-deletion');
//         notificationItem.setAttribute('data-value1', notification.value);
//         notificationItem.setAttribute('data-value2', notification.session);
//         notificationItem.setAttribute('data-value3', notification.name);
//         notificationItem.setAttribute('data-value4', notification.message);
//         notificationItem.innerHTML = `
//         <i class="fa-solid fa-boxes-stacked"></i> ${notification.name} wants to delete the product ${notification.product}<br>
//         <span class="float-right text-muted text-sm">${notification.formattedDuration}</span><br>
//         `;
//     } else if (notification.type === 'Category deletion') {
//         notificationItem.classList.add('category-deletion');
//         notificationItem.setAttribute('data-value1', notification.value);
//         notificationItem.setAttribute('data-value2', notification.session);
//         notificationItem.setAttribute('data-value3', notification.name);
//         notificationItem.setAttribute('data-value4', notification.message);
//         notificationItem.innerHTML = `
//         <i class="fa-solid fa-sitemap"></i> ${notification.name} wants to delete the ${notification.category} category<br>
//         <span class="float-right text-muted text-sm">${notification.formattedDuration}</span><br>
//         `;
//     } else if (notification.type === 'Admin feedback' && notification.viewUser === sessionStorage.username) {
//         notificationItem.classList.add('admin-feedback');
//         notificationItem.setAttribute('data-value1', notification.notificationType);
//         notificationItem.setAttribute('data-value2', notification.session);
//         notificationItem.innerHTML = `
//         <i class="fa-solid fa-xmark"></i> ${notification.message}<br>
//         <span class="float-right text-muted text-sm">${notification.formattedDuration}</span><br>
//         `;
//     }
    
//     notificationItemsContainer.appendChild(notificationItem);
//     notificationItemsContainer.appendChild(document.createElement('div')).classList.add('dropdown-divider');

// }

// // Function to get session information
// function getSessionInfo(callback) {
//     $.ajax({
//         url: "ajax/session.ajax.php",
//         method: "GET",
//         dataType: "json",
//         success: function(data) {
//             callback(data); // Invoke the callback with the retrieved data
//         },
//         error: function() {
//             callback(null); // Invoke the callback with null if an error occurs
//         }
//     });
// }



// // Function to check if the notification type is allowed for the user's role
// async function isNotificationTypeAllowed(notificationType) {
//     getSessionInfo(function(data) {
//         if (data !== null) {
//             // Data retrieval was successful
//             var sessionRole = data.sessionRole; // Get the current user's role from the session data


//             // Define the allowed notification types for each role
//             var allowedNotificationTypes = {
//                 'Administrator': ['Stock notification', 'Category deletion', 'Product deletion', 'Admin feedback'],
//                 'Store': ['Admin feedback', 'Stock', 'Acceptions'],
//                 'Seller': ['Stock', 'Acceptions']
//             };
//             return allowedNotificationTypes[sessionRole].includes(notificationType);
//         } else {
//             // Error occurred during data retrieval
//             // console.error("Failed to retrieve session information.");
//             Swal.fire({
//                 title: 'Error',
//                 text: 'Error fetching session information.',
//                 icon: 'error',
//                 timer: 2000,
//                 showConfirmButton: false,
//                 allowOutsideClick: false
//             });

//         }

//     });

// }


// // Define the sendAjaxRequest function
// function sendAjaxRequest() {
//     var datum = new FormData();

//     $.ajax({
//         url: "ajax/notifications.ajax.php",
//         method: "POST",
//         data: datum,
//         cache: false,
//         contentType: false,
//         processData: false,
//         dataType: "json",
//         success: function (notifications) {
//             notifications.forEach((notification) => {
//                 var notificationId = notification.id;

//                 if (!presentNotifications.includes(notificationId)) {
//                     // New notification ID found, perform necessary actions
//                     console.log("New notification ID found:", notificationId);

//                     // add the notification ID to the presentNotifications array
//                     presentNotifications.push(notificationId);

//                     // Create a new HTML notification
//                     createNotification(notification);
//                 }else{
//                     console.log("No new notifications found");
//                 }

//             });

//         },

//     });
    
// }

// // Define a function to handle the received notifications
// function handleNotifications(notifications) {
//     // Iterate over the received notifications


//     notifications.forEach((notification) => {
//     // Check if the notification already exists in the unreadNotifications array
//     var existingNotification = unreadNotifications.find((n) => n.id === notification.id);
//     if (!existingNotification) {
//         // console.log(notification);
//         // unreadNotifications.push(notification);

//         // // Create a new notification item element
//         // var notificationItem = document.createElement('a');
//         // notificationItem.classList.add('dropdown-item', 'notification-link');
//         // // Set the href or any other attributes if needed
//         // // notificationItem.href = ...

//         // // Add the notification content to the item
//         // notificationItem.innerHTML = `
//         //     <i class="fa-solid fa-boxes-stacked"></i> ${notification.message}<br>
//         //     <span class="float-right text-muted text-sm">${notification.formattedDuration}</span><br>
//         // `;

//         // // Append the notification item to the notification list container
//         // var notificationItemsContainer = document.getElementById('notificationItems');
//         // notificationItemsContainer.appendChild(notificationItem);
//         // notificationItemsContainer.appendChild(document.createElement('div')).classList.add('dropdown-divider');

//         // // Update the notification count
//         // var notificationCount = unreadNotifications.length;
//         // var rowCountSpan = document.getElementById('rowCountSpan');
//         // rowCountSpan.textContent = notificationCount;
//         // var notificationCountElement = document.getElementById('notificationCount');
//         // notificationCountElement.textContent = `${notificationCount} Notifications`;
//     }

//     });

//   }



