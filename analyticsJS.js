var currentChart = null;

document.getElementById('mode').addEventListener('change', function () {
    var selectedMode = this.value;
    console.log(selectedMode);

    // Destroy the existing chart if it exists
    if (currentChart) {
        currentChart.destroy();
    }

    // Fetch data based on the selected mode
    if (selectedMode == 'hist_dish' || selectedMode == 'date') {
        fetch(`data.php?mode=${selectedMode}`)
            .then(response => response.json())
            .then(data => {
                // Process data for the respective mode
                let labels, values;

                if (selectedMode === 'hist_dish') {
                    labels = data.map(item => item.dishName);
                    values = data.map(item => item.quantity);
                } else if (selectedMode === 'date') {
                    labels = data.map(item => item.orderDate);
                    values = data.map(item => item.quantity);
                }

                // Create the chart
                const ctx = document.getElementById('myChart').getContext('2d');
                currentChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Quantity',
                            data: values,
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    }
}); 