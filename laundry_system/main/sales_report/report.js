const hamburger = document.querySelector("#toggle-btn");

hamburger.addEventListener("click", function(){
    document.querySelector("#sidebar").classList.toggle("expand");
})

/**************************************CHARTS**************************************/
document.addEventListener('DOMContentLoaded', function() {
    //daily
    const datePicker = document.getElementById('chooseDate');
    datePicker.value = getCurrentDate();
    updateDayChart();

    datePicker.addEventListener('change', updateDayChart);

    document.getElementById('chooseDate').addEventListener('change', function() {
        updateDayChart(); //update day
    });

    //weekly
    populateYears();
    populateMonths();

    //year changes, update weeks
    document.getElementById('chooseYear').addEventListener('change', function() {
        populateWeeks(); //update weeks based on new year and selected month
    });

    document.getElementById('chooseMonth').addEventListener('change', function() {
        populateWeeks(); 
    });

    document.getElementById('chooseWeek').addEventListener('change', function() {
        loadWeekData(this.value); 
    });

    //month
    populateMonthOptions();
    fetchMonthData();

    document.getElementById('monthSelect').addEventListener('change', function() {
        fetchMonthData();
    });

    /************DAILY CHART*************/
    function getCurrentDate() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0'); 
        const day = String(today.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function updateDayChart() {
        const selectedDate = document.getElementById('chooseDate').value;
        
        fetch(`/laundry_system/main/sales_report/sales_config/dailyChart.php?date=${selectedDate}`)
        .then(response => {
            if (!response.ok) {  //check if the response status is OK
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();  // If response is OK, parse the JSON
        })
        .then(data => {
            const ctx = document.getElementById('daychart').getContext('2d');
            
            // Destroy previous chart instance, if any
            if (window.daychart && typeof window.daychart.destroy === 'function') {
                window.daychart.destroy();
            }

            // Create new chart
            window.daychart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Requests in Day',
                        data: data.data,
                        backgroundColor: data.backgroundColors,
                        borderColor: data.borderColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: `Daily Requests for ${selectedDate}`,
                            font: {
                                size: 14 
                            }
                        },
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 12 
                                }
                            }
                        },
                    },
                    scales: {
                        x: {
                            ticks: {
                                callback: function(value) {
                                    return value.length > 15 ? value.substr(0, 15) + '...' : value;
                                }
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            renderModalChart(data, selectedDate);
        })
        .catch(error => {
            console.error('Error fetching chart data:', error);
            alert('There was an error fetching the data. Please try again later.');
        });
    }

    function renderModalChart(data, selectedDate) {
        const ctx = document.getElementById('dayChartModalCanvas').getContext('2d');
        
        if (window.dayChartModal && typeof window.dayChartModal.destroy === 'function') {
            window.dayChartModal.destroy();
        }

        window.dayChartModal = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Requests in Day',
                    data: data.data,
                    backgroundColor: data.backgroundColors,
                    borderColor: data.borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: `Daily Requests for ${selectedDate}`,
                        font: {
                            size: 14 
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12 
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    function downloadChart() {
        const canvas = document.getElementById('dayChartModalCanvas');
        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/png');  
        link.download = 'daily-chart.png'; 
        link.click();
    }

    $('#dl_daily_chart').click(function(){
        downloadChart();
    });

    /************WEEKLY CHART*************/
    function populateYears() {
        const chooseYear = document.getElementById('chooseYear');
        chooseYear.innerHTML = ''; 
        const currentYear = new Date().getFullYear();
        
        // Add year options
        for (let year = currentYear - 5; year <= currentYear + 5; year++) {
            const option = document.createElement('option');
            option.value = year;
            option.text = year;
            if (year === currentYear) option.selected = true; //set current year as selected
            chooseYear.appendChild(option);
        }
    }

    function populateMonths() {
        const chooseMonth = document.getElementById('chooseMonth');
        chooseMonth.innerHTML = ''; // Clear previous options

        const months = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        const currentMonth = new Date().getMonth();

        // Add month options
        months.forEach((month, index) => {
            const option = document.createElement('option');
            option.value = index + 1; //month value starts at  1 (january = 1, etc.)
            option.text = month;
            if (index === currentMonth) option.selected = true; 
            chooseMonth.appendChild(option);
        });

        populateWeeks(); // Update weeks after months are populated
    }

    function populateWeeks() {
        const chooseYear = document.getElementById('chooseYear').value;
        const chooseMonth = document.getElementById('chooseMonth').value;
        const chooseWeek = document.getElementById('chooseWeek');
        chooseWeek.innerHTML = ''; // Clear previous weeks

        fetch(`/laundry_system/main/sales_report/sales_config/weeks.php?year=${chooseYear}&month=${chooseMonth}`)
            .then(response => response.json())
            .then(weeks => {
                console.log(weeks);
                weeks.forEach(week => {
                    const option = document.createElement('option');
                    option.value = week.value;
                    option.text = week.label;
                    chooseWeek.appendChild(option);
                });

                // Optionally set the current week as selected
                const currentWeekLabel = getCurrentWeekLabel(chooseYear, chooseMonth);
                chooseWeek.value = currentWeekLabel;

                // Load data for the selected week
                loadWeekData(currentWeekLabel);
            })
            .catch(error => {
                console.error('Error fetching weeks:', error);
            });
    }

    function getCurrentWeekLabel(year, month) {
        const today = new Date();
        const currentYear = today.getFullYear();
        const currentMonth = today.getMonth() + 1;

        if (year != currentYear || month != currentMonth) {
            return "Week 1";
        }

        const firstDayOfMonth = new Date(year, month - 1, 1);

        //loop throuh weeks in month then find the cuurent data
        let currentDay = firstDayOfMonth;
        let weekNumber = 1;

        while (currentDay.getMonth() === firstDayOfMonth.getMonth()) {
            const startOfWeek = new Date(currentDay);
            startOfWeek.setDate(currentDay.getDate() - currentDay.getDay()); //set sunday
            const endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 6);

            //current date is between startOfWeek and endOfWeek, it's the current week
            if (today >= startOfWeek && today <= endOfWeek) {
                return `Week ${weekNumber}`;
            }

            //move to next week
            currentDay.setDate(endOfWeek.getDate() + 1);
            weekNumber++;
        }

        //default week
        return "Week 1";
    }

    function loadWeekData(weekLabel) {
        const year = document.getElementById('chooseYear').value;
        const month = document.getElementById('chooseMonth').value;

        //week number from the label
        const weekNumber = weekLabel.split(' ')[1];
        
        // Calculate the start and end dates of the week
        const startDate = new Date(year, month - 1, (weekNumber - 1) * 7 + 1);
        startDate.setDate(startDate.getDate() - startDate.getDay() + 1);
        const endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + 6); 

        // Format dates as YYYY-MM-DD
        const start = startDate.toISOString().split('T')[0];
        const end = endDate.toISOString().split('T')[0];

        console.log(`Loading data for: YearWeek ${year}${weekNumber} from ${start} to ${end}`);

        fetch(`/laundry_system/main/sales_report/sales_config/weeklyChart.php?start=${start}&end=${end}`)
            .then(response => response.json())
            .then(data => {
                console.log('Chart Data:', data); 
                
                // Check if data is valid
                if (!data || !data.labels || !data.data) {
                    console.error('Invalid data format');
                    return;
                }
                
                const ctx = document.getElementById('weekchart').getContext('2d');
                
                //destroy previous chart instance if it exists
                if (window.weekChartInstance && typeof window.weekChartInstance.destroy === 'function') {
                    window.weekChartInstance.destroy();
                }

                //create the chart
                window.weekChartInstance = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Weekly Requests by Service and Category',
                            data: data.data,
                            backgroundColor: data.backgroundColors,
                            borderColor: data.borderColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    color: '#000',
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: 'Weekly Requests by Service and Category',
                                font: {
                                    size: 14
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        let label = tooltipItem.label || '';
                                        let value = tooltipItem.raw || '';
                                        return [
                                                `${label}: ${value}`, 
                                                `Requests Count: ${value}` 
                                        ];
                                    }
                                }
                            }
                        }
                        
                    }
                });
                renderWeekModalChart(data, weekLabel);
            })
            .catch(error => console.error('Error fetching chart data:', error));
    }

    function renderWeekModalChart(data, weekLabel) {
        const ctx = document.getElementById('weekChartModalCanvas').getContext('2d');

        if (window.weekChartModalInstance && typeof window.weekChartModalInstance.destroy === 'function') {
            window.weekChartModalInstance.destroy();
        }

        window.weekChartModalInstance = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Weekly Requests by Service and Category',
                    data: data.data,
                    backgroundColor: data.backgroundColors,
                    borderColor: data.borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: `Weekly Requests for ${weekLabel}`,
                        font: {
                            size: 14 
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12 
                            }
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    }

    function downloadWeekChart() {
        const canvas = document.getElementById('weekChartModalCanvas');
        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/png');  
        link.download = 'weekly-chart.png'; 
        link.click();
    }

    $('#dl_weekly_chart').click(function(){
        downloadWeekChart();
    });

    /************************MONTHLY CHART*************************/
    function populateMonthOptions() {
        const monthSelect = document.getElementById('monthSelect');
        const currentMonth = new Date().getMonth() + 1;

        for (let month = 1; month <= 12; month++) {
            const monthName = new Date(0, month - 1).toLocaleString('en-US', { month: 'long' });
            const option = document.createElement('option');
            option.value = month;
            option.text = monthName;
            if (month === currentMonth) {
                option.selected = true;
            }
            monthSelect.appendChild(option);
        }
    }

    function fetchMonthData() {
        const selectedMonth = document.getElementById('monthSelect').value;

        fetch('/laundry_system/main/sales_report/sales_config/monthlyChart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `selectedMonth=${selectedMonth}`
        })
        .then(response => response.json())
        .then(data => {
            renderMonthChart(data);
            renderModalMonthChart(data);
        })
        .catch(error => console.error('Error fetching month data:', error));
    }

    function renderMonthChart(data) {
        const ctx = document.getElementById('monthchart').getContext('2d');

        if (window.monthchart && typeof window.monthchart.destroy === 'function') {
            window.monthchart.destroy();
        }

        window.monthchart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Orders in the Month',
                    data: data.data,
                    backgroundColor: data.backgroundColors,
                    borderColor: data.borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: true, text: 'Monthly Requests by Service and Category', font: { size: 14 } }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            let label = tooltipItem.label || '';
                            let value = tooltipItem.raw || '';
                            return [
                                `${label}: ${value}`, 
                                `Requests Count: ${value}` 
                            ];
                        }
                    }
                }
            }
        });
    }

    function renderModalMonthChart(data) {
        const ctx = document.getElementById('monthChartModalCanvas').getContext('2d');
        
        if (window.monthChartModal && typeof window.monthChartModal.destroy === 'function') {
            window.monthChartModal.destroy();
        }

        window.monthChartModal = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Orders in the Month',
                    data: data.data,
                    backgroundColor: data.backgroundColors,
                    borderColor: data.borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: true, text: 'Monthly Requests', font: { size: 14 } }
                }
            },
            plugins: [ChartDataLabels]
        });
    }

    function downloadMonthChart() {
        const canvas = document.getElementById('monthChartModalCanvas');
        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/png');
        link.download = 'monthly-chart.png';
        link.click();
    }

    $('#dl_monthly_chart').click(function(){
        downloadMonthChart()
    });
    
    /****************YEARLY CHART****************/
    const yearSelect = document.getElementById('yearSelect');
    const currentYear = new Date().getFullYear();

    //year dropdown for the last 5 years
    for (let year = currentYear; year >= currentYear - 5; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.text = year;
        if (year === currentYear) {
            option.selected = true;
        }
        yearSelect.appendChild(option);
    }

    function fetchYearData(selectedYear) {
        fetch('/laundry_system/main/sales_report/sales_config/yearlyChart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `selectedYear=${selectedYear}`
        })
        .then(response => response.json())
        .then(data => {
            renderChart(data);
            renderModalYearChart(data);
        })
        .catch(error => console.error('Error fetching data:', error));
    }

    //fetch for the current year
    fetchYearData(currentYear);

    //for year selection
    yearSelect.addEventListener('change', function() {
        const selectedYear = yearSelect.value;
        fetchYearData(selectedYear);
    });

    //render the main chart
    function renderChart(data) {
        const ctx = document.getElementById('yearchart').getContext('2d');

        if (window.yearChart && typeof window.yearChart.destroy === 'function') {
            window.yearChart.destroy();
        }

        window.yearChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Requests in Year',
                    data: data.data,
                    backgroundColor: data.backgroundColors,
                    borderColor: data.borderColors,
                    borderWidth: 5,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Yearly Requests by Service and Category' }
                },
                scales: {
                    x: {
                        ticks: {
                            callback: function(value, index, values) {
                                // truncate labels if they are longer than 15 characters
                                return value.length > 15 ? value.substr(0, 15) + '...' : value;
                            }
                        }
                    },
                    y: { beginAtZero: true }
                }
            }
        });
    }

    //modal chart
    function renderModalYearChart(data) {
        const ctxModal = document.getElementById('yearChartModalCanvas').getContext('2d');

        if (window.yearModalChart && typeof window.yearModalChart.destroy === 'function') {
            window.yearModalChart.destroy();
        }

        window.yearModalChart = new Chart(ctxModal, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Orders in Year',
                    data: data.data,
                    backgroundColor: data.backgroundColors,
                    borderColor: data.borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: true, text: 'Yearly Requests' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    //download yearly chart
    function downloadYearChart() {
        const canvas = document.getElementById('yearChartModalCanvas');
        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/png');
        link.download = 'yearly-chart.png';
        link.click();
    }

    $('#dl_yearly_chart').click(function(){
        downloadYearChart();
    });

    /*******************TRANSACTION SUMMARY TABLE***********************/
    window.onload = function() {
        fetchTransactionSummary('all');
    };

    function highlightButton(button) {
        var buttons = document.querySelectorAll('.btn-secondary');
        buttons.forEach(function(btn) {
            btn.classList.remove('clicked');
        });
        button.classList.add('clicked');
    }

    //filter table by day, week, month, and year
    document.querySelectorAll('.btns button').forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            fetchTransactionSummary(filter);
        });
    });

    //print table
    function printTransactionTable() {
        //get the current filtered table data
        const filter = document.querySelector('.btns button.clicked') ? document.querySelector('.btns button.clicked').getAttribute('data-filter') : 'all';
        
        //AJAX request to fetch the filtered table data for printing
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/laundry_system/main/sales_report/sales_config/transaction_summary.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        xhr.onload = function() {
            if (this.status === 200) {
                const response = JSON.parse(this.responseText);
                
                // table content with the returned data
                var tableContent = `
                    <table id="table_summary">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Request Date</th>
                                <th>Customer Order ID</th>
                                <th>Customer Name</th>
                                <th>Laundry Service Option</th>
                                <th>Laundry Category Option</th>
                                <th>Price</th>
                                <th>Weight</th>
                                <th>Service Option</th>
                                <th>Laundry Cycle</th>
                                <th>Service Fee</th>
                                <th>Total Amount</th>
                                <th>Order Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${response.table_data}
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10" style="text-align: right;"><strong>Total Revenue:</strong></td>
                                <td colspan="2"><strong>₱${response.total_revenue}</strong></td>
                            </tr>
                        </tfoot>
                    </table>`;
                
                //open a new window for printing
                var newWindow = window.open('', '', 'height=600,width=800');
                newWindow.document.write('<html><head><title>Transaction_Summary</title>');
                newWindow.document.write('<style>' +
                    'table {' + 
                    'width: 95%;' + 
                    'border-collapse: collapse; margin: 20px auto;}' + 
                    'th, td { border: 1px solid black; padding: 5px; text-align: left; }' + 
                    '@media print { ' +
                    '#table_summary { width: 95%; border-collapse: collapse; margin: 0 auto;}' + 
                    'th, td { font-size: 10px; padding: 5px; }' + 
                    '.table-responsive { margin: 0 auto; padding: 5px 5px; }' +
                    '}' + 
                    '.header { font-size: 16px; font-weight: bold; text-align: center; margin-top: 20px; }' +
                    '</style>');
                newWindow.document.write('</head><body>');
                newWindow.document.write('<h2 class="header">Transaction Summary</h2>');
                newWindow.document.write(tableContent);
                newWindow.document.write('</body></html>');
                newWindow.document.close();
                newWindow.focus();
                
                // Trigger the print
                newWindow.print();
            }
        };
        
        //send the filter to get the table data for the selected filter
        xhr.send('filter=' + filter + '&print_all=true');
    }

     //dynamic search bar
     $(document).ready(function(){
        $("#filter_transac").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#transaction-table-body tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    //funtions for buttons
    $('#btnDaily, #btnWeekly, #btnMonthly, #btnYearly').click(function(){
        highlightButton(this);
    });    

    $('#btnPrint').click(function(){
        printTransactionTable();
    });
});

function fetchTransactionSummary(filter, page = 1) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/laundry_system/main/sales_report/sales_config/transaction_summary.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status === 200) {
            const response = JSON.parse(this.responseText);

            // Update transaction table and total revenue
            document.getElementById('transaction-table-body').innerHTML = response.table_data;
            document.getElementById('total-revenue').innerText = response.total_revenue;

            // Update pagination
            let pagination = '';
            if (response.page > 1) {
                pagination += `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="fetchTransactionSummary('${filter}', ${response.page - 1})">&laquo;</a></li>`;
            }

            for (let i = 1; i <= response.total_pages; i++) {
                pagination += `<li class="page-item ${i === response.page ? 'active' : ''}"><a class="page-link" href="javascript:void(0)" onclick="fetchTransactionSummary('${filter}', ${i})">${i}</a></li>`;
            }

            if (response.page < response.total_pages) {
                pagination += `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="fetchTransactionSummary('${filter}', ${response.page + 1})">&raquo;</a></li>`;
            }

            document.getElementById('pagination').innerHTML = pagination;
        }
    };

    // Send filter and page data
    xhr.send('filter=' + filter + '&page=' + page);
}

