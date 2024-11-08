<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/milktea/models/connection.php";

header('Content-Type: application/json');  // Make sure the response is JSON

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

// Fetch top 10 products based on selected date range
$stmt = $pdo->prepare("SELECT * FROM products WHERE $dateQuery ORDER BY sales DESC LIMIT 10");
$stmt->execute();
$products = $stmt->fetchAll();

// Colors for the pie chart
$colours = array(
    "#ADD8E6", // Light Blue
    "#B0E0E6", // Powder Blue
    "#87CEFA", // Light Sky Blue
    "#5F9EA0", // Cadet Blue
    "#4682B4", // Steel Blue
    "#00CED1", // Dark Turquoise
    "#1E90FF", // Dodger Blue
    "#00BFFF", // Deep Sky Blue
    "#008080", // Teal
    "#0000FF"  // Blue
);

// Prepare data to be returned for the chart
$productData = [];
foreach ($products as $i => $product) {
    $productData[] = [
        'sales' => $product['sales'],
        'description' => $product['description'],
        'image' => $product['image'],
    ];
}

// Send the JSON response with the products and colours data
echo json_encode([
    'products' => $productData,
    'colours' => $colours
]);
?>
