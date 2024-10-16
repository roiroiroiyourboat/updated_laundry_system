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


    // AJAX polling to update table data every 5 seconds
    setInterval(function() {
        $.ajax({
            url: 'fetch_table.php', // PHP file that fetches updated table data
            method: 'GET',
            success: function(data) {
                $('#table-body').html(data); // Replace table body with updated data
            },
            error: function() {
                console.error('Failed to fetch table data.');
            }
        });
    }, 5000); // Poll every 5 seconds
});