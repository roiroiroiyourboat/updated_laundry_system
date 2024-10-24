document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const hamburger = document.querySelector("#toggle-btn");
    hamburger.addEventListener("click", function() {
        document.querySelector("#sidebar").classList.toggle("expand");
    });

    //open update form
    const editButtons = document.querySelectorAll(".edit-btn");

    editButtons.forEach(button => {
        button.addEventListener("click", function() {
            const price = this.getAttribute("data-price");
            const categoryOption = this.getAttribute("data-option");
            const serviceId = this.getAttribute("data-id");

            document.getElementById("price").value = price; //set the price in the input field
            document.getElementById("laundry_category_option").value = categoryOption;
            document.getElementById("service_id").value = serviceId;

            // Show the edit form
            document.getElementById("editForm").style.display = "flex";
            document.body.classList.add('modal-open');
        });

    });

    // close the form
    const closeFormButton = document.querySelector('.form-popup .close');
    closeFormButton.addEventListener('click', closeForm);
    
    // for cancel button
    const cancelButton = document.getElementById('cancelButton');
    cancelButton.addEventListener('click', closeForm);

    function closeForm() {
        document.getElementById("editForm").style.display = 'none';
        document.body.classList.remove('modal-open'); 
        document.getElementById("editForm").reset();
    }

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