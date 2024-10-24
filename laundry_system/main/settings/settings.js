const hamburger = document.querySelector("#toggle-btn");

hamburger.addEventListener("click", function(){
    document.querySelector("#sidebar").classList.toggle("expand");
})

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