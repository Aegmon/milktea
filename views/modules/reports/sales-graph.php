<?php
// Connection class for database connection (make sure this is defined elsewhere in your code)

function getSalesData() {
    $pdo = Connection::connect();
    $query = "
        SELECT DATE_FORMAT(saledate, '%Y-%m') AS sale_date, SUM(totalPrice) AS total_sales
        FROM sales
        GROUP BY sale_date
        ORDER BY sale_date"; // Ensure results are ordered by date

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $salesData = [];
    $time = [];
    $sales = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Populate salesData for current sales
        $yearMonth = explode('-', $row['sale_date']);
        $year = (int)$yearMonth[0];
        $month = (int)$yearMonth[1];

        $salesData[] = [
            'year' => $year,
            'month' => $month,
            'monthlySales' => $row['total_sales']
        ];

        // Prepare time and sales arrays for regression
        $timeValue = $year * 12 + $month;
        $time[] = $timeValue;
        $sales[] = $row['total_sales'];
    }

    // Step 2: Calculate linear regression for predictions
    $n = count($time);
    if ($n === 0) return []; // No data available

    $timeSum = array_sum($time);
    $salesSum = array_sum($sales);
    $timeMean = $timeSum / $n;
    $salesMean = $salesSum / $n;

    // Calculate slope (m) and intercept (b)
    $numerator = 0;
    $denominator = 0;
    for ($i = 0; $i < $n; $i++) {
        $numerator += ($time[$i] - $timeMean) * ($sales[$i] - $salesMean);
        $denominator += ($time[$i] - $timeMean) ** 2;
    }
    $m = $denominator ? $numerator / $denominator : 0;
    $b = $salesMean - ($m * $timeMean);

    // Step 3: Prepare data for output with predictions
    $predictedSalesData = [];
    foreach ($salesData as $data) {
        $timeValue = $data['year'] * 12 + $data['month'];
        $predictedSales = $m * $timeValue + $b;

        $predictedSalesData[] = [
            'y' => $data['year'] . '-' . str_pad($data['month'], 2, '0', STR_PAD_LEFT), // Format as 'YYYY-MM'
            'realSales' => $data['monthlySales'],
            'predictedSales' => round($predictedSales, 2)
        ];
    }

    // Add future predictions for the next 12 months
    for ($i = 1; $i <= 12; $i++) {
        $futureTime = end($time) + $i;
        $predictedSales = $m * $futureTime + $b;
        $year = floor($futureTime / 12);
        $month = $futureTime % 12;
        $month = $month === 0 ? 12 : $month; // Adjust for month 0

        $predictedSalesData[] = [
            'y' => sprintf("%04d-%02d", $year, $month),
            'realSales' => 0, // No actual sales for future months
            'predictedSales' => round($predictedSales, 2)
        ];
    }

    return $predictedSalesData; // Return as an array with actual and predicted sales
}

$salesData = getSalesData(); // Call the function to get sales data

?>

<!-- Sales Graph UI -->
<div class="box box-solid">
    <div class="box-header">
        <i class="fa fa-th"></i>
        <h3 class="box-title">Sales Graph</h3>
    </div>
    <div class="box-body border-radius-none newSalesGraph">
        <!-- Combined Sales Chart -->
        <div class="chart" id="line-chart-Sales" style="height: 250px;"></div>
    </div>
</div>

<script>
    // Render the combined sales chart
    new Morris.Line({
        element: 'line-chart-Sales',
        resize: true,
        data: <?php echo json_encode($salesData); ?>, // Pass the PHP array to JavaScript
        xkey: 'y',
        ykeys: ['realSales', 'predictedSales'],
        labels: ['Real Sales', 'Predicted Sales'],
        lineColors: ['#0d6efd', '#28a745'], // Define colors for the two lines
        lineWidth: 2,
        hideHover: 'auto',
        gridTextColor: '#000000', // Black text color
        pointSize: 4,
        pointStrokeColors: ['#0d6efd', '#28a745'],
        gridTextFamily: 'Open Sans', // Font for grid text
        gridTextSize: 12,
        behaveLikeLine: true,
        xLabelAngle: 60,
    });
</script>
