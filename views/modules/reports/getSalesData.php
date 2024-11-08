<?php
function getSalesData($viewType) {
    $pdo = Connection::connect();
    
    if ($viewType == "day") {
        $query = "
            SELECT DATE(saledate) AS sale_date, SUM(totalPrice) AS total_sales
            FROM sales
            WHERE MONTH(saledate) = MONTH(CURRENT_DATE()) AND YEAR(saledate) = YEAR(CURRENT_DATE())
            GROUP BY sale_date";
    } elseif ($viewType == "month") {
        $query = "
            SELECT DATE_FORMAT(saledate, '%Y-%m') AS sale_date, SUM(totalPrice) AS total_sales
            FROM sales
            GROUP BY sale_date";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $salesData = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $salesData[] = ['y' => $row['sale_date'], 'Sales' => $row['total_sales']];
    }

    return json_encode($salesData);
}

$viewType = $_POST['viewType'] ?? 'day'; // Default to daily view
$salesData = getSalesData($viewType);
?>