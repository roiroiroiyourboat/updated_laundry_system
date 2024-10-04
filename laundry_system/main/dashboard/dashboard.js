const hamburger = document.querySelector("#toggle-btn");

hamburger.addEventListener("click", function(){
    document.querySelector("#sidebar").classList.toggle("expand");
})

//CHARTS
document.addEventListener("DOMContentLoaded", function() {
  //DAILY CHART
  fetch('/laundry_system/main/dashboard/configs_db/daily.php')
  .then(response => response.json())
  .then(data => {
      const ctx = document.getElementById('daychart').getContext('2d');
      const daychart = new Chart(ctx, {
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
                      text: 'Daily Requests by Service and Category',
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
                  x: {
                      ticks: {
                          callback: function(value, index, values) {
                              // truncate or shorten labels if they are longer than 15 characters
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
  })
  .catch(error => console.error('Error fetching chart data:', error));

  //WEEKLY CHART
  fetch('/laundry_system/main/dashboard/configs_db/weekly.php')
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('weekchart').getContext('2d');
        const weekchart = new Chart(ctx, {
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
    })
    .catch(error => console.error('Error fetching chart data:', error));

    //MONTHLY CHART
    fetch('/laundry_system/main/dashboard/configs_db/monthly.php')
    .then(response => response.json())
    .then(data => {
        console.log('Chart Data:', data); 
        const ctx = document.getElementById('monthchart').getContext('2d');
        const monthchart = new Chart(ctx, {
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
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Monthly Requests by Service and Category',
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
    })
    .catch(error => console.error('Error fetching chart data:', error));

    //YEARLY CHART
    fetch('/laundry_system/main/dashboard/configs_db/yearly.php')
    .then(response => response.json())
    .then(data => {
        const labels = data.labels;
        const yearData = {
            labels: labels,
            datasets: [{
                label: 'Requests in Year',
                data: data.data,
                backgroundColor: data.backgroundColors,
                borderColor: data.borderColors,
                borderWidth: 5,
                fill: true, 
            }]
        };

        const config = {
            type: 'line',
            data: yearData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Yearly Requests by Service and Category',
                        font: {
                            size: 14
                        }
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            callback: function(value, index, values) {                                
                                //shorten labels if they are longer than 15 characters
                                return value.length > 15 ? value.substr(0, 15) + '...' : value;
                            }
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        var yearchart = new Chart(
            document.getElementById('yearchart'),
            config
        );

    })
    .catch(error => console.error('Error fetching chart data:', error));
  
});


//CALENDAR
