const hamburger = document.querySelector("#toggle-btn");

hamburger.addEventListener("click", function(){
    document.querySelector("#sidebar").classList.toggle("expand");
})

document.addEventListener('DOMContentLoaded', function() {

    // Add User Modal functionality
    const addUserModal = document.getElementById('addUserModal');
    const addUserButton = document.getElementById('addUserButton');
    const closeAddUserButton = addUserModal.querySelector('.close');
    const clearButton = addUserModal.querySelector('.btn-info');

    addUserButton.addEventListener('click', () => {
        addUserModal.style.display = 'flex';
    });

    closeAddUserButton.addEventListener('click', () => {
        addUserModal.style.display = 'none';
    });

    clearButton.addEventListener('click', () => {
        document.getElementById('userForm').reset();
        document.getElementById('passwordHelp').style.display = 'none';
    })

    window.addEventListener('click', (event) => {
        if (event.target === addUserModal) {
            addUserModal.style.display = 'none';
        }
    });

    //add user
    document.getElementById('userForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting in the default way
    
        const formData = new FormData(this); // Get the form data
        
        fetch('add_users.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'User added successfully!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    //redirect after showing the success message
                    window.location.href = 'users.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Unexpected error',
                text: 'Something went wrong!'
            });
        });
    });

    // Edit User Modal functionality
    const editModal = document.getElementById('editModal');
    const closeEditButton = editModal.querySelector('.close-btn');
    const editForm = document.getElementById('editForm');

    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const userId = btn.dataset.id;
            const username = btn.dataset.username;
            const firstName = btn.dataset.firstname;
            const lastName = btn.dataset.lastname;
            const userRole = btn.dataset.userrole;

            document.getElementById('editUserId').value = userId;
            document.getElementById('editUsername').value = username;
            document.getElementById('editFirstName').value = firstName;
            document.getElementById('editLastName').value = lastName;
            document.getElementById('editUserRole').value = userRole;

            editModal.style.display = 'flex';
        });
    });

    //update info
    document.getElementById('editForm').addEventListener('submit', function(event) {
        event.preventDefault();
    
        const formData = new FormData(this);
        
        fetch('update_user.php', { 
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                //success message
                Swal.fire({
                    icon: 'success',
                    title: 'User Updated!',
                    text: 'The user details have been successfully updated.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = 'users.php';
                });
            } else {
                //error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.error || 'There was a problem updating the user.',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            //handle error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong! Please try again.',
            });
            console.error('Error:', error);
        });
    });

    closeEditButton.addEventListener('click', () => {
        editModal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === editModal) {
            editModal.style.display = 'none';
        }
    });


    // Password Validation and Form Submission Handling
    const passwordField = document.getElementById('password');
    const passwordHelpText = document.getElementById('passwordHelp');
    const userForm = document.getElementById('userForm');

    if (passwordField && passwordHelpText && userForm) {
        // Password validation criteria
        const passwordRequirements = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;

         // Validate password on input
         passwordField.addEventListener('input', function () {
            const passwordValue = passwordField.value;
            const lengthCheck = passwordValue.length >= 8;
            const uppercaseCheck = /[A-Z]/.test(passwordValue);
            const lowercaseCheck = /[a-z]/.test(passwordValue);
            const numberCheck = /\d/.test(passwordValue);

            // Update the color and text for each requirement
            document.getElementById('length').style.color = lengthCheck ? 'green' : 'red';
            document.getElementById('uppercase').style.color = uppercaseCheck ? 'green' : 'red';
            document.getElementById('lowercase').style.color = lowercaseCheck ? 'green' : 'red';
            document.getElementById('number').style.color = numberCheck ? 'green' : 'red';

            if (lengthCheck && uppercaseCheck && lowercaseCheck && numberCheck) {
                passwordHelpText.style.display = 'none';
            } else {
                passwordHelpText.style.display = 'block';
            }
        });

        // Validate form on submit
        userForm.addEventListener('submit', function (event) {
            const passwordValue = passwordField.value;
            const isValid = passwordRequirements.test(passwordValue);

            if (!isValid) {
                event.preventDefault();
                alert('Password does not meet the required criteria.');
                passwordHelpText.style.display = 'block';
                passwordHelpText.style.color = 'red';
                passwordField.scrollIntoView({ behavior: 'smooth' });
            }
        });
    } else {
        console.error("Password field or user form not found in the DOM.");
    }

    // for archive
    const archiveModal = document.getElementById('archiveModal');
    const confirmArchiveButton = document.getElementById('confirmArchiveButton');
    const cancelArchiveButton = document.getElementById('cancelArchiveButton');
    const closeArchiveModal = document.getElementById('closeArchiveModal');

    // success
    const successModal = document.getElementById('successModal');
    const closeSuccessModal = document.getElementById('closeSuccessModal');
    const closeSuccessButton = document.getElementById('closeSuccessButton');

    let userIdToArchive = null;

    document.querySelectorAll('.archive-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            userIdToArchive = btn.dataset.id;
            archiveModal.style.display = 'block';
        });
    });

    closeArchiveModal.addEventListener('click', () => {
        archiveModal.style.display = 'none';
    });

    cancelArchiveButton.addEventListener('click', () => {
        archiveModal.style.display = 'none';
    });

    confirmArchiveButton.addEventListener('click', () => {
        fetch('/laundry_system/archived/archive_users_db.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: userIdToArchive })  
        })

        .then(response => response.text())  
        .then(data => {
            console.log('Raw response:', data);  
    
            // Check if response is JSON or an HTML error page
            if (data.trim().startsWith('<')) {
                console.error('Received HTML instead of JSON:', data);
                return;  
            }
    
            try {
                const jsonData = JSON.parse(data);

                if (jsonData.success) {
                    archiveModal.style.display = 'none';
                    successModal.style.display = 'block';
                } else {
                    alert('Error archiving user: ' + jsonData.error);
                }
            } catch (error) {
                console.error('Error parsing JSON:', error, data);
            }
        })
        
        .catch(error => {
            console.error('Fetch error:', error);
        });
    });

    closeSuccessButton.addEventListener('click', () => {
        successModal.style.display = 'none';
        location.reload();
    });

    /* search */
    $("#filter_user").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#user_table tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    /* pagination */
    const rowsPerPage = 15;
    const tableBody = document.querySelector('table tbody');
    const rows = tableBody.querySelectorAll('tr');
    const paginationContainer  = document.getElementById('pagination');

    let totalRows = rows.length;
    let totalPages = Math.ceil(totalRows / rowsPerPage);
    let currentPage = 0;

    function displayRows(startIndex) {
      rows.forEach(row => row.style.display = 'none');

      let endIndex = startIndex + rowsPerPage;
      for (let i = startIndex; i < endIndex && i < totalRows; i++) {
          rows[i].style.display = '';
      }
    }

    function setupPagination() {
      paginationContainer.innerHTML = '';

      // previous arrow
      let prevPageLink = document.createElement('li');
      prevPageLink.classList.add('page-item');
      prevPageLink.innerHTML = `<a class="page-link" href="#"><<</a>`;
      prevPageLink.addEventListener('click', function (e) {
          e.preventDefault();
          if (currentPage > 0) {
              currentPage--;
              displayRows(currentPage * rowsPerPage);
              setActivePage(currentPage);
          }
      });

      paginationContainer.appendChild(prevPageLink);

      // numbered page links
      for (let i = 0; i < totalPages; i++) {
            let pageLink = document.createElement('li');
            pageLink.classList.add('page-item');
            pageLink.innerHTML = `<a class="page-link" href="#">${i + 1}</a>`;

            pageLink.addEventListener('click', function (e) {
            e.preventDefault();
            displayRows(i * rowsPerPage);
            setActivePage(i);
        });

        paginationContainer.appendChild(pageLink);
      }

      // next arrow
      let nextPageLink = document.createElement('li');
      nextPageLink.classList.add('page-item');
      nextPageLink.innerHTML = `<a class="page-link" href="#">>></a>`;
      nextPageLink.addEventListener('click', function (e) {
          e.preventDefault();
          if (currentPage < totalPages - 1) {
              currentPage++;
              displayRows(currentPage * rowsPerPage);
              setActivePage(currentPage);
          }
      });
      paginationContainer.appendChild(nextPageLink);
  }

    function setActivePage(pageIndex) {
        const pageLinks = paginationContainer.querySelectorAll('.page-item');
        pageLinks.forEach(link => link.classList.remove('active'));
        pageLinks[pageIndex].classList.add('active');
    }

    // Initialization of table with first page and pagination links
    displayRows(0);
    setupPagination();
    setActivePage(0);

    //Show Password
    $('.toggle-password').click(function() {
        var target = $(this).data('target');
        var input = $(target);
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).removeClass('bx-show').addClass('bx-hide');
        } else {
            input.attr('type', 'password');
            $(this).removeClass('bx-hide').addClass('bx-show');
        }
    });

    // for logout
    const logoutModal = document.getElementById("logoutModal");
    const closeBtn = logoutModal.querySelector(".close");
    const noBtn = logoutModal.querySelector(".btn-no");

    $("#btn_logout").click(function(){
        $('#logoutModal').show();
    });

    // Close the modal when clicking the close button (×)
    if (closeBtn) {
        closeBtn.addEventListener("click", function() {
            logoutModal.style.display = "none"; // Hide the modal
        });
    }

    // Close the modal when clicking the 'No' button
    if (noBtn) {
        noBtn.addEventListener("click", function() {
            logoutModal.style.display = "none"; // Hide the modal
        });
    }

    // Close the modal when clicking outside the modal content
    window.addEventListener("click", function(event) {
        if (event.target === logoutModal) {
            logoutModal.style.display = "none"; // Hide the modal
        }
    });
    
});