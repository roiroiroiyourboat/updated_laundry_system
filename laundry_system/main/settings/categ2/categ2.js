document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const hamburger = document.querySelector("#toggle-btn");
    hamburger.addEventListener("click", function() {
        document.querySelector("#sidebar").classList.toggle("expand");
    });

    // update price button
    const updatePriceButton = document.getElementById('updatePriceButton');
    const updatePriceForm = document.getElementById('updatePriceForm');

    updatePriceButton.addEventListener('click', function() {
        updatePriceButton.submit();
    });
});