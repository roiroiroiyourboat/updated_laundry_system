<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset your password</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        .password-wrapper {
        position: relative;
        }

        /* show password */
        .password-wrapper .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 1.2rem;
        }
    </style>

</head>
<body>
    <form>
        <h3>Password</h3>
        <p>Choose a strong password.</p>
        <div class="mb-3">
            <label for="new_password" class="form-label">New password</label>
            <div class="password-wrapper">
                <input type="email" class="form-control" id="new_pass" name="new_pass" aria-describedby="emailHelp">
                <i class='bx bx-show toggle-password' data-target="#new_pass"></i>
                <div id="passwordHelpBlock" class="form-text">
                    Your password must be 8-20 characters long, 
                    contain letters and numbers, and must not contain spaces, special characters, or emoji.
                </div>
                
            </div>     
        </div>
        <div class="mb-3">
            <div class="password-wrapper">
                <label for="confirm_pass" class="form-label">Confirm new password</label>
                <input type="password" class="form-control" id="con_password">
                <i class='bx bx-show toggle-password' data-target="#con_password"></i>
            </div>  
        </div>
        <button type="submit" class="btn btn-primary" id="showPassword">Change Password</button>
    </form>
    

    <script>
         //show password
        function passwordVisibility(event) {
            // Check if the clicked element has the toggle-password class
            if (event.target.classList.contains('toggle-password')) {
                var target = event.target.getAttribute('data-target');
                var input = document.querySelector(target); 
                
                // Toggle the input type between 'password' and 'text'
                if (input.type === 'password') {
                    input.type = 'text';
                    event.target.classList.remove('bx-show');
                    event.target.classList.add('bx-hide');
                } else {
                    input.type = 'password';
                    event.target.classList.remove('bx-hide');
                    event.target.classList.add('bx-show');
                }
            }
        }

        // Add the event listener to the document
        document.addEventListener('click', passwordVisibility);

    </script>
</body>
</html>