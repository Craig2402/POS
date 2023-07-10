// mark as read ajax
function MarkReadNotification(type, session) {

    var datum = new FormData();
    datum.append("session", session);
    datum.append("type", type);
    
    $.ajax({
        url: "ajax/notifications.ajax.php",
        method: "POST",
        data: datum,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            // Handle the AJAX success response here
        }

    });
}

//  mark as read ajax rejected
function MarkReadNotificationRejected(type, session, status, user, answer) {
    
    var datum = new FormData();
    datum.append("session", session);
    datum.append("type", type);
    datum.append("status", status);
    datum.append("user", user);
    datum.append("name", answer);
    
    $.ajax({
        url: "ajax/notifications.ajax.php",
        method: "POST",
        data: datum,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            // Handle the AJAX success response here
        }

    });

}



processedNotifications = []
// validates the new notifications
function fetchNotification() {
    var datum = new FormData();
    
    var notificationCounter = 0; // Counter variable to track the number of created notifications

    $.ajax({
        url: "ajax/notifications.ajax.php",
        method: "POST",
        data: datum,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(notifications) {

            notifications.forEach((notification) => {
                var notificationId = notification.id;
                var viewedBy = notification.viewed_by;

                getSessionInfo(function(data) {
                    if (data !== null) {
                        // Data retrieval was successful
                        var sessionId = data.sessionId;
                        // Check if the notification has been viewed by the current logged in user
                        if (!viewedBy.includes(sessionId)) {

                            if (!processedNotifications.includes(notificationId)) {                  
            
                                // add the notification ID to the processedNotifications array
                                processedNotifications.push(notificationId);
            
                                // Create a new HTML notification
                                createNotification(notification);

    
                                // Increment the notification counter
                                notificationCounter++;

                                // Update the notification count display
                                var notificationCount = notificationCounter;
                                var rowCountSpan = document.getElementById('rowCountSpan');
                                rowCountSpan.textContent = notificationCount;
                            
                                var rowCountSpanHeader = document.getElementById('rowCountSpanHeader');
                                rowCountSpanHeader.textContent = notificationCount + " Notifications";
            
                            }

                        }

                    } else {
                        // Error occurred during data retrieval
                        Swal.fire({
                            title: 'Error',
                            text: 'Error fetching session information.',
                            icon: 'error',
                            timer: 2000,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                        
                    }

                });

            });

        }

    });
}
// fetchNotification()
setInterval(fetchNotification, 1000);
// Function to create a new HTML notification
function createNotification(notification) {
    // Extract the required properties from the notification object
    var message = notification.message;
    var notificationTypeData = notification.notification_type;
    var createDate = new Date(notification.create_date);
    var name = notification.name;
    var notificationType = notificationTypeData.split(',')[0];
    var notificationTypeValue = notificationTypeData.split(',')[1];
    
    getSessionInfo(function(data) {
        if (data !== null) {
            // Data retrieval was successful
            var sessionId = data.sessionId;
            var sessionRole = data.sessionRole;
            // Define the allowed notification types for each role
            var allowedNotificationTypes = {
                'Administrator': ['Stock notification', 'Category deletion'],
                'Store': ['Admin feedback', 'Stock notification', 'Acceptions'],
                'Seller': ['Stock', 'Acceptions']
            };
            // Check if the user is eligible to view the notification based on their role
            var eligibleNotificationTypes = allowedNotificationTypes[sessionRole];
            if (eligibleNotificationTypes && eligibleNotificationTypes.includes(notificationType)) {
                // Get the notification datetime from your data with the timezone information
                var currentDate = new Date();
                var timeDifference = Math.abs(currentDate - createDate) / 1000; // in seconds
        
                let timeDifferenceString = '';
        
                if (timeDifference <= 10) {
                    timeDifferenceString = 'just now';
                } else if (timeDifference <= 60) {
                    timeDifferenceString = timeDifference + ' seconds ago';
                } else if (timeDifference <= 60 * 60) {
                    var minutes = Math.floor(timeDifference / 60);
                    timeDifferenceString = minutes + ' minutes ago';
                } else if (timeDifference <= 60 * 60 * 24) {
                    var hours = Math.floor(timeDifference / (60 * 60));
                    timeDifferenceString = hours + ' hours ago';
                } else if (timeDifference <= 60 * 60 * 24 * 7) {
                    var days = Math.floor(timeDifference / (60 * 60 * 24));
                    timeDifferenceString = days + ' days ago';
                } else if (timeDifference <= 60 * 60 * 24 * 7 * 4) {
                    var weeks = Math.floor(timeDifference / (60 * 60 * 24 * 7));
                    timeDifferenceString = weeks + ' weeks ago';
                } else if (timeDifference <= 60 * 60 * 24 * 7 * 4 * 12) {
                    var months = Math.floor(timeDifference / (60 * 60 * 24 * 7 * 4));
                    timeDifferenceString = months + ' months ago';
                } else {
                    var years = Math.floor(timeDifference / (60 * 60 * 24 * 7 * 4 * 12));
                    timeDifferenceString = years + ' years ago';
                }
                // console.log(name + ' ' + message + ' ' + createDate + ' ' + timeDifferenceString + ' ' + notificationType + ' ' + viewedBy);
                
                var notificationItemsContainer = document.querySelector('.notificationItems');

                var notificationItem = document.createElement('a');
                notificationItem.classList.add('dropdown-item', 'notification-link');

                if (notificationType === 'Stock notification') {
                notificationItem.classList.add('stock-notification');
                notificationItem.setAttribute('data-value1', notificationTypeValue);
                notificationItem.setAttribute('data-value2', sessionId);
                notificationItem.innerHTML = `
                    <i class="fa-solid fa-boxes-stacked"></i> ${message}<br>
                    <span class="float-right text-muted text-sm">${timeDifferenceString}</span><br>
                `;
                } else if (notificationType === 'Category deletion') {
                    
                    var item = 'id';
                    var value = notificationTypeValue;
                    
                    var datum = new FormData();
                    datum.append("item", item);
                    datum.append("value", value);
                    
                    $.ajax({
                        url: "ajax/categories.ajax.php",
                        method: "POST",
                        data: datum,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType:"json",
                        success: function(answer){
                            
                            notificationItem.classList.add('category-deletion');
                            notificationItem.setAttribute('data-value1', notificationTypeValue);
                            notificationItem.setAttribute('data-value2', sessionId);
                            notificationItem.setAttribute('data-value3', name);
                            notificationItem.setAttribute('data-value4', message);
                            notificationItem.innerHTML = `
                                <i class="fa-solid fa-sitemap"></i> ${name} wants to delete the ${answer.Category} category<br>
                                <span class="float-right text-muted text-sm">${timeDifferenceString}</span><br>
                            `;

                        }
                    });
                } else if (notificationType === 'Admin feedback' && notification.viewUser === sessionStorage.username) {
                notificationItem.classList.add('admin-feedback');
                notificationItem.setAttribute('data-value1', notification.notificationType);
                notificationItem.setAttribute('data-value2', sessionId);
                notificationItem.innerHTML = `
                    <i class="fa-solid fa-xmark"></i> ${message}<br>
                    <span class="float-right text-muted text-sm">${timeDifferenceString}</span><br>
                `;
                }
                

                // Add a click event listener to the notification item
                notificationItem.addEventListener('click', function () {
                    // Handle different notification types based on clicked values
                    if (notificationType === 'Stock notification') {
                        // Stock notification item clicked
                        var barcode = this.getAttribute('data-value1');
                        var session = this.getAttribute('data-value2');
                        var type = "Stock notification," + barcode;
                        
                        window.location = "index.php?route=viewproduct&barcode=" + barcode;
                        MarkReadNotification(type, session);
                    } else if (notificationType === 'Category deletion') {
                        // Category deletion notification item clicked
                        var idCategory = this.getAttribute('data-value1');
                        var session = this.getAttribute('data-value2');
                        var user = this.getAttribute('data-value3');
                        var message = this.getAttribute('data-value4');
                        var type = "Category deletion,"+idCategory;
                        var status = "Rejected";

                        // Handle category deletion
                        var datum = new FormData();
                        datum.append("idCategory", idCategory);

                        $.ajax({
                            url: "ajax/categories.ajax.php",
                            method: "POST",
                            data: datum,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType:"json",
                            success: function(answer){
                                var answer = answer['Category']
                                // Show specific SweetAlert for category deletion
                                Swal.fire({
                                    title: 'Category Deletion',
                                    html: `Reason: ${message}<br>Are you sure you want to delete the ${answer} category?<br>All the products in this category will also be deleted.<br>Changes made can not be reverted.`,
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Confirm',
                                    cancelButtonText: 'Cancel',
                                }).then((result) => {
                                    // If the user clicks on 'Confirm', handle the category deletion
                                    if (result.isConfirmed) {
                                        window.location = "index.php?route=category&idCategory="+idCategory;
                                        MarkReadNotification(type, session);
                                    }else if (result.dismiss === Swal.DismissReason.cancel) {
                                        MarkReadNotificationRejected(type, session, status, user, answer);
                                        MarkReadNotification(type, session);
                                    }

                                });
                            }

                        });
                    } else if (notificationType === 'Admin feedback') {
                        // Admin feedback notification item clicked
                        var type = this.getAttribute('data-value1');
                        var session = this.getAttribute('data-value2');
                        
                        MarkReadNotification(type, session);
                    }
                });

                notificationItemsContainer.appendChild(notificationItem);
                notificationItemsContainer.appendChild(document.createElement('div')).classList.add('dropdown-divider');
            }

        } else {
            // Error occurred during data retrieval
            Swal.fire({
                title: 'Error',
                text: 'Error fetching session information.',
                icon: 'error',
                timer: 2000,
                showConfirmButton: false,
                allowOutsideClick: false
            });
            
        }

    });

}

// Function to get session information
function getSessionInfo(callback) {
    $.ajax({
        url: "ajax/session.ajax.php",
        method: "GET",
        dataType: "json",
        success: function(data) {
            callback(data); // Invoke the callback with the retrieved data
        },
        error: function() {
            callback(null); // Invoke the callback with null if an error occurs
        }
    });
}





