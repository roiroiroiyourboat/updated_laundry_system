//BURGER MENU
document.addEventListener('DOMContentLoaded', () => {
    const burger = document.querySelector('.burger');
    const navLinks = document.querySelector('.nav-links');
    const links = navLinks.querySelectorAll('a'); // Select all the links inside navLinks

    burger.addEventListener('click', () => {
        navLinks.classList.toggle('nav-active');
        burger.classList.toggle('toggle');
    });

    // Add event listeners to each nav link to close the navigation container when clicked
    links.forEach(link => {
        link.addEventListener('click', () => {
            navLinks.classList.remove('nav-active');
            burger.classList.remove('toggle');
        });
    });
});

document.addEventListener('DOMContentLoaded', (event) => {
    const btnLogin = document.getElementById('form_open');
    const btnLaundryService = document.getElementById('openService');
    const login_form = document.getElementById('form_container');
    const laundry_service_form = document.getElementById('service_form');

    //open the login form
    btnLogin.onclick = function() {
        login_form.style.display = 'block';
        laundry_service_form.style.display = 'none';
    }

    //open the service request
    btnLaundryService.addEventListener('click', () => {
            laundry_service_form.style.display = 'block';
            login_form.style.display = 'none';
    });

});

//scrolling effect
window.addEventListener('scroll', () => {
    const scroll = window.pageYOffset / (document.body.offsetHeight - window.innerHeight);
    const scaleValue = 0.5 + (scroll * 0.5); // Scale from 0.5 to 1 based on scroll position
    document.body.style.setProperty('--scale', scaleValue);
}, false);


//POP-UP LOGIN FORM
document.addEventListener('DOMContentLoaded', (event) => {
    const login_form = document.getElementById("form_container");
    const openLogin = document.getElementById("form_open");
    const closeBtn = document.getElementsByClassName("btnClose")[0];

    //open the service form
    openLogin.onclick = function() {
        login_form.style.display = "block";
    }

    //close the service form
    closeBtn.onclick = function() {
        login_form.style.display = "none";
    }

});

document.addEventListener('DOMContentLoaded', (event) => {
    const service_form = document.getElementById("service_form");
    const login_form = document.getElementById("form_container");
    const openLogin = document.getElementById("form_open");
    const openService = document.getElementById("openService");

    //open the login form
    openLogin.onclick = function() {
        login_form.style.display = "block";
        service_form.style.display = 'none';
    }

    //open the service form
    openService.onclick = function() {
        service_form.style.display = "block";
        login_form.style.display = 'none';
    }

});

//pop up for laundry service form
document.addEventListener('DOMContentLoaded', (event) => {
    const service_form = document.getElementById("service_form");
    const openBtn = document.getElementById("openService");
    const closeBtn = document.getElementsByClassName("btnClose")[1];

    //open the service form
    openBtn.onclick = function() {
        service_form.style.display = "block";
    }

    //close the service form
    closeBtn.onclick = function() {
        service_form.style.display = "none";
    }

});

//OVERVIEW PANEL
document.addEventListener('DOMContentLoaded', (event) => {
    const service_overview = document.getElementById("service_overview");
    const service_form = document.getElementById('service_form');
    const closeBtn = document.getElementsByClassName("btnClose")[2];
    const btnBack = document.getElementById('btnBack');

    //close the service overview
    closeBtn.onclick = function() {
        service_overview.style.display = "none";
    }
});

//LAUNNDRY SERVICE DETAILS
document.addEventListener('DOMContentLoaded', (event) => {
    const service_details = document.getElementById('service_details');
    const closeBtn = document.getElementsByClassName("btnClose")[3];

    //close the service overview
    closeBtn.onclick = function() {
        service_details.style.display = "none";
    }

});


/***************************LAUNDRY SERVICE REQUEST****************************/
//fetch laundry service
function fetchServices() {
    fetch('/laundry-ms/main/homepage/home_configs/fetch_laundry_service.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log(data); // Debugging
            let dropdown = document.getElementById('service');
            dropdown.innerHTML = '<option selected>--Select Service--</option>'; // Clear existing options
            data.forEach(service => {
                let option = document.createElement('option');
                option.value = service.service_id;
                option.textContent = service.laundry_service_option;
                dropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching services:', error));
}
document.addEventListener('DOMContentLoaded', fetchServices);

//fetch laundry category
function fetchCategories() {
    fetch('/laundry-ms/main/homepage/home_configs/fetchLaundryCateg.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log(data); // Debugging
            let dropdown = document.getElementById('category');
            dropdown.innerHTML = '<option selected>--Select Category--</option>'; //clear existing options
            data.forEach(category => {
                let option = document.createElement('option');
                option.value = category.category_id;
                option.textContent = category.laundry_category_option;
                dropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching categories:', error));
}
//fetch categories when the page loads
document.addEventListener('DOMContentLoaded', fetchCategories);

//fetch service option
function fetchServiceOptions() {
    fetch('/laundry-ms/main/homepage/home_configs/fetch_service_option.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log(data); // Debugging
            let dropdown = document.getElementById('service_option');
            dropdown.innerHTML = '<option selected>--Select Option--</option>'; //clear existing options
            data.forEach(service_option => {
                let option = document.createElement('option');
                option.value = service_option.option_id;
                option.textContent = service_option.option_name;
                dropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching service options:', error));
}
//fetch categories when the page loads
document.addEventListener('DOMContentLoaded', fetchServiceOptions);

//collects user input and submit data through database
$(document).ready(function() {
    var orders = []; // Array to store orders

    // Function to update customer details in overview section
    function updateCustomerDetails(customerId, customerName, contactNumber) {
        $('#customer_id_display').text(customerId); // Display customer ID
        $('#customer_name_display').text(customerName);
        $('#contact_number_display').text(contactNumber);
    }

    // Function to add order details to the overview table
    function addOrderToOverview(quantity, service, category, weight, price) {
        var orderDetails = '<tr>' +
            '<td>' + quantity + '</td>' +
            '<td>' + service + '</td>' +
            '<td>' + category + '</td>' +
            '<td>' + weight + '</td>' +
            '<td>' + price + '</td>' +
            '</tr>';
        $('#service_overview tbody').append(orderDetails);
    }

    // Function to show service form and hide overview
    function showServiceForm() {
        $('#service_form').show();
        $('#service_overview').hide();
    }

    // Function to show overview and hide service form
    function showServiceOverview() {
        $('#service_form').hide();
        $('#service_overview').show();
    }

    // Form submission handling
    $('#form_id').submit(function(event) {
        event.preventDefault();
    
        var customerName = $('#customer_name').val();
        var contactNumber = $('#contact_number').val();
        var quantity = $('select[name="quantity"]').val();
        var serviceId = $('select[name="service"]').val();
        var serviceOption = $('select[name="service"] option:selected').text();
        var categoryId = $('select[name="category"]').val();
        var categoryOption = $('select[name="category"] option:selected').text();
        var weight = $('#weight').val();
        var price = $('#price').val();
    
        // Check if required fields are empty
        if (!customerName ||!contactNumber ||!quantity ||!serviceId ||!categoryId ||!weight ||!price) {
            swal.fire({ title: "Oops..", text: "Please fill in all the required fields.", icon: "error" });
            return;
        }
    
        // Validate if the customer name or contact number already exists
        $.ajax({
            type: 'POST',
            url: '/laundry-ms/main/homepage/home_configs/validate_customer.php',
            data: {
                customer_name: customerName,
                contact_number: contactNumber
            },
            success: function(response) {
                if (response.status === 'error') {
                    swal.fire({ title: "Validation Failed!", text: response.message, icon: "error" });
                } else {
                    // if customer name and contact number is available, add order to orders array
                    var order = {
                        customerName: customerName,
                        contactNumber: contactNumber,
                        quantity: quantity,
                        serviceId: serviceId,
                        serviceOption: serviceOption,
                        categoryId: categoryId,
                        categoryOption: categoryOption,
                        weight: weight,
                        price: price
                    };
                    orders.push(order);
    
                    // Update customer details in overview section
                    updateCustomerDetails(response.customer_id, customerName, contactNumber);
    
                    // Add order details to the overview table
                    addOrderToOverview(quantity, serviceOption, categoryOption, weight, price);
    
                    swal.fire({ title: "Order added to list!", text: "You can proceed or add more orders.", icon: "success" })
                       .then((result) => {
                            if (result.isConfirmed) {
                                // Clear form inputs except customer name and contact number
                                $('#form_id').find('input[type="text"], input[type="number"], select').not('#customer_name, #contact_number').val('');
                                $('#service_form').hide();
                                $('#service_overview').show();
                            }
                        });
                }
            },
            error: function(xhr, status, error) {
                console.error("Validation Error: " + error);
                swal.fire({ title: "Validation failed!", text: "An error occurred while validating the customer name. Please try again.", icon: "error" });
            }
        });
    });

    //cancel button in service req form
    document.getElementById('btnCancel').addEventListener('click', function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Cancel Service Request?',
            text: 'Are you sure you want to cancel your service request?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const service_form = document.getElementById('form_id');
                const service_form_con = document.getElementById('service_form');

                service_form.reset();
                service_form_con.style.display = 'none';  
            }
        });
    });

    // "Done" button click on service form
    $('#doneButton').click(function() {
        showServiceOverview();
    });

    // "Back" button click on overview page
    $('#btnBack').click(function() {
        showServiceForm();
    });

    // "Proceed" button click on overview page
    $('#btnProceed').click(function() {
        if (orders.length === 0) {
            swal.fire("No Orders to Proceed!", "Please add at least one order before proceeding.", "warning");
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'laundryService_config.php',
            data: { orders: JSON.stringify(orders) },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    swal.fire("Orders saved successfully!", response.message, "success")
                        .then(() => {
                            // Clear orders array and UI elements
                            orders = [];
                            $('#service_overview tbody').empty();
                            $('#customer_name_display').empty();
                            $('#contact_number_display').empty();

                           // Set customer ID for service details form
                            $('#customer_id_hidden').val(response.customer_id);
                            $('#service_request_id_hidden').val(response.service_request_id);

                            // Show service form after saving
                            $('#service_details').show();
                            $('#service_overview').hide();

                        });
                } else {
                    swal.fire("Orders not saved!", response.message, "error");
                }
            },
            error: function(xhr, status, error) {
                console.error("Save Orders Error: " + error);
                swal.fire("Orders not saved!", "An error occurred while saving your orders. Please try again.", "error");
            }
        });
    });

    $('#btnDone_service').click(function() {
        var customerId = $('#customer_id_hidden').val();
        var serviceId = $('select[name="service_option"]').val();
        var serviceOption = $('select[name="service_option"] option:selected').text();
        var address = $('#address').val();
        var pickupDate = $('#pickup_date').val();
        var totalAmount = $('#total_amount').val();
        var deliveryFee = $('#delivery_fee').val();
        var amountTendered = $('#amount_tendered').val();
        var change = $('#change').val();
    
        if (!serviceOption || !address || !pickupDate) {
            Swal.fire("Oops!", "Please fill in all the required fields.", "error");
            return;
        }
    
        var serviceDetails = {
            customer_id: customerId,
            serviceId: serviceId,
            service_option: serviceOption,
            address: address,
            pickup_date: pickupDate,
            total_amount: totalAmount,
            delivery_fee: deliveryFee,
            amount_tendered: amountTendered,
            change: change
        };
    
        $.ajax({
            type: 'POST',
            url: 'saveServiceDetails.php',
            data: serviceDetails,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: "Great! Service details saved successfully.",
                        text: response.message,
                        icon: "success"
                    }).then(() => {
                        $('#form_id')[0].reset();
                        $('#form-service input, #form-service select, #form-service textarea').val('');
                        $('#service_details').hide();
                        $('#service_form').show();
                    });
                } else {
                    Swal.fire("Service details not saved!", response.message, "error");
                }
            },
            error: function(xhr, status, error) {
                console.error("Save Service Details Error: " + error);
                Swal.fire("Service details not saved!", "An error occurred while saving the service details. Please try again.", "error");
            }
        });
    });

    //cancel service in service details page
    $('#btnCancel_service_details').click(function() {
        swal.fire({
            title: 'Cancel Service Request?',
            text: 'Are you sure you want to cancel your service request?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var serviceRequestId = $('#service_request_id_hidden').val();
                console.log('Service Request ID:', serviceRequestId);
    
                $.ajax({
                    type: 'POST',
                    url: 'cancel_service_details.php',
                    data: { service_request_id: serviceRequestId },
                    dataType: 'json'
                })
               .done((response) => {
                    console.log('Response:', response);
                    if (response.status === 'success') {
                        swal.fire("Service request canceled successfully!", response.message, "success")
                       .then((result) => {
                            if (result.isConfirmed) { 
                                $('#form-service input, #form-service select, #form-service textarea').val('');
                                $('#form_id')[0].reset();
                                $('#service_details').hide();
                                $('#service_form').show();
                            }
                        });
                    } else {
                        swal.fire("Service request cancellation failed!", response.message, "error");
                    }
                })
               .fail((xhr, status, error) => {
                    console.error("Cancel Service Request Error:", error);
                    swal.fire("Service request cancellation failed!", "An error occurred while canceling your service request. Please try again.", "error");
                });
            }
        });
    });

    //Cancel service request button click on overview page
    $('#btnCancel_service').click(function() {
        swal.fire({
            title: 'Cancel Service Request?',
            text: 'Are you sure you want to cancel your service request?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#customer_id_display').text('');
                $('#service_overview tbody').empty();
                $('#customer_name_display').empty();
                $('#contact_number_display').empty();
                $('#form_id')[0].reset();
                showServiceForm();
            }
        });
    });
});
