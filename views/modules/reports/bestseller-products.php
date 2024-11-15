<?php
// Database connection
$pdo = Connection::connect(); // Establish a database connection

// Get time range from URL parameters or set default to 'day'
$timeRange = isset($_GET['timeRange']) ? $_GET['timeRange'] : 'day'; // Default to 'day' if not set

// Define date range query based on $timeRange
$dateQuery = "";
switch ($timeRange) {
    case 'day':
        $dateQuery = "DATE(date) = CURDATE()"; // today
        break;
    case 'week':
        $dateQuery = "YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)"; // this week
        break;
    case 'month':
        $dateQuery = "MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())"; // this month
        break;
    case 'year':
        $dateQuery = "YEAR(date) = YEAR(CURDATE())"; // this year
        break;
}

// Fetch products based on selected date range
$stmt = $pdo->prepare("SELECT * FROM products WHERE $dateQuery ORDER BY sales DESC");
$stmt->execute();
$products = $stmt->fetchAll();

// Colors for the pie chart
$colours = array(
    '#FF5733', '#FFBD33', '#33FF57', '#33FFBD', '#FF33A1', 
    '#A133FF', '#FF8C33', '#33FF8C', '#FF33FF', '#FFC300', 
    '#6A4CFF', '#FF6347', '#FF1493', '#00FA9A', '#FFD700', 
    '#DA70D6', '#32CD32', '#DC143C', '#F08080', '#8A2BE2', 
    '#FF4500', '#8B0000', '#7FFF00', '#D2691E', '#FFFF00', 
    '#20B2AA', '#8B008B', '#00BFFF', '#FF69B4', '#C71585',
    '#F4A300', '#7CFC00', '#FF6347', '#3CB371', '#FF1493'
);



// Calculate total sales for the selected time range
$salesTotalStmt = $pdo->prepare("SELECT SUM(sales) AS total FROM products WHERE $dateQuery");
$salesTotalStmt->execute();
$salesTotal = $salesTotalStmt->fetch(PDO::FETCH_ASSOC);
$salesTotalValue = $salesTotal["total"] ?? 0; // Use null coalescing operator to avoid undefined index

// Ensure we do not divide by zero
if ($salesTotalValue == 0) {
    $salesTotalValue = 1; // To avoid division by zero errors
}

// Return the required data in JSON format
$response = [
    'products' => $products,
    'salesTotalValue' => $salesTotalValue,
    'colours' => $colours
];

?>

<div class="box box-default">
    <!-- Dropdown Filter for Time Range -->
    <div class="box-header with-border">
        <label for="timeFilter">Filter by:</label>
        <select id="timeFilter">
            <option value="day">Today</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
            <option value="year">This Year</option>
        </select>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="chart-responsive">
                    <canvas id="pieChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="box-footer no-padding">
        <ul id="productList" class="nav nav-pills nav-stacked">
            <!-- Products list will be populated dynamically by JavaScript -->
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->

<script>
    var timeFilter = document.getElementById('timeFilter');
    var pieChartCanvas = document.getElementById('pieChart').getContext('2d');
    var pieChart = null; // To store the chart instance

    // Function to update both the chart and the product list
    function updateData() {
        var selectedTime = timeFilter.value;

        // Fetch the new data for the selected time range
        fetch('views/modules/reports/chartData.php?timeRange=' + selectedTime)
            .then(response => response.json())
            .then(data => {
                // Destroy the previous chart if it exists
                if (pieChart) {
                    pieChart.destroy();
                }

                // Prepare the pie chart data
          var PieData = data.products.map((product, index) => {
    var salesPercentage = 0;
    if (data.salesTotalValue > 0 && product.sales > 0) {
        salesPercentage = ((product.sales * 100) / data.salesTotalValue).toFixed(1);
    }
    if (isNaN(salesPercentage) || salesPercentage === Infinity || salesPercentage === -Infinity) {
        salesPercentage = 0;
    }

    var color = product.sales === 0 ? '#D3D3D3' : data.colours[index];

    return {
        value: product.sales,
        color: color,
        label: product.description + ' (' + salesPercentage + '%)',
        percentage: salesPercentage
    };
});


                // Create a new chart instance
                pieChart = new Chart(pieChartCanvas, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: PieData.map(item => item.value),
                            backgroundColor: PieData.map(item => item.color),
                            label: 'Sales Data'
                        }],
                        labels: PieData.map(item => item.label)
                        
                    },
                    options: {
                        cutout: '50%', // For inner cutout similar to pie
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        var productData = PieData[tooltipItem.dataIndex];
                                        return productData.label + ' (' + productData.percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
console.log(PieData.map(item => item.color));
                // Update the product list
                var productList = document.getElementById('productList');
                productList.innerHTML = ''; // Clear the current list
                for (let i = 0; i < Math.min(10, data.products.length); i++) {
                    const product = data.products[i];
                    const salesPercentage = (product.sales * 100 / data.salesTotalValue).toFixed(1) + '%';

                    var listItem = document.createElement('li');
                    listItem.innerHTML = `
                        <a>
                            <img src="${product.image}" class="img-thumbnail" width="60px" style="margin-right:10px"> 
                            ${product.description}
                            <span class="pull-right" style="color:${data.colours[i]}">   
                                ${salesPercentage}
                            </span>
                        </a>
                    `;
                    productList.appendChild(listItem);
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    // Initialize chart when page loads
    updateData();

    // Event listener for the time filter change
    timeFilter.addEventListener('change', updateData);
</script>
