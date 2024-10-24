const hamburger = document.querySelector("#toggle-btn");

hamburger.addEventListener("click", function(){
    document.querySelector("#sidebar").classList.toggle("expand");
})

$(document).ready(function(){
    $.ajax({
        url: 'get_user_data.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log(data);  
            if (data && !data.error) {
                // Populate form fields with user data
                $('#fname').val(data.first_name);
                $('#lname').val(data.last_name);
                $('#username').val(data.username);
                $('#password').val(data.password);

                //edit user
                $('#edit_fname').val(data.first_name);
                $('#edit_lname').val(data.last_name);
                $('#edit_username').val(data.username);
            } else {
                console.error('Error fetching user data: ' + (data.error || 'Unknown error'));
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error: ' + error);
        },
        complete: function(xhr, status) {
            console.log('Complete response: ', xhr.responseText);  //log raw response for debugging
        }
    });

    function showEditUserForm () {
        $('#user_container').hide();
        $('#edit_user').show();
    }

    function showUserContainer () {
        $('#edit_user').hide(); 
        $('#user_container').show();
    }

    $('#btn_edit').click(function(){
        showEditUserForm();
    });

    $('#btnBack').click(function(){
        showUserContainer();
    });

    //edit user profile
    document.getElementById('btnSaveChanges').addEventListener('click', async function () {
        const firstName = document.getElementById('edit_fname').value;
        const lastName = document.getElementById('edit_lname').value;
        const username = document.getElementById('edit_username').value;
        const currentPass = document.getElementById('current_pass').value;
        const newPass = document.getElementById('new_pass').value;
        const confirmPass = document.getElementById('confirm_pass').value;
    
        //to check if password fields are filled in
        if (currentPass || newPass || confirmPass) {
            //to validate password fields
            if (newPass !== confirmPass) {
                Swal.fire({
                    icon: 'error',
                    title: 'Passwords do not match',
                    text: 'New password and confirmation password do not match!',
                });
                return; 
            }
    
            //check if new password is long enough
            if (newPass.length < 8) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Password Too Short',
                    text: 'Password must be at least 8 characters long.',
                });
                return; 
            }
        }
    
        //form data to send to server
        const formData = {
            firstName: firstName,
            lastName: lastName,
            username: username,
        };
    
        //add password fields only if changing the password
        if (currentPass && newPass && confirmPass) {
            formData.currentPass = currentPass;
            formData.newPass = newPass;
        }
    
        try {
            const response = await fetch('/laundry_system/main/my_profile/update_user_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });
    
            const result = await response.json();
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated',
                    text: 'Your profile has been updated successfully!',
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: 'Error updating profile: ' + result.message,
                });
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while updating the profile.',
            });
        }
    });
    

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
