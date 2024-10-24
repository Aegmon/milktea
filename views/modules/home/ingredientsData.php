<?php

// Check if the function already exists to avoid redeclaration
if (!function_exists('getProductCreationData')) {
    function getProductCreationData() {
        $pdo = Connection::connect();

        // Query to get ingredients needed for each product and the available stock of ingredients
        $query = "
            SELECT 
                p.id AS product_id, 
                p.description AS product_name, 
                pi.ingredient_id, 
                i.ingredient, 
                i.quantity AS ingredient_quantity, 
                pi.size AS ingredient_needed
            FROM productsingredients pi
            INNER JOIN products p ON pi.product_id = p.id
            INNER JOIN ingredients i ON pi.ingredient_id = i.id";

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $productData = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ingredient_id = $row['ingredient_id'];
            $product_id = $row['product_id'];
            
            // Calculate how many products can be made with the current ingredient
            $productsPerIngredient = floor($row['ingredient_quantity'] / $row['ingredient_needed']);

            // Create product entry if it doesn't exist for the ingredient
            if (!isset($productData[$ingredient_id])) {
                $productData[$ingredient_id] = [
                    'ingredient_name' => $row['ingredient'],
                    'products' => []
                ];
            }

            // Store how many products can be created using that ingredient
            $productData[$ingredient_id]['products'][$product_id] = [
                'product_name' => $row['product_name'],
                'can_create' => $productsPerIngredient
            ];
        }

        return $productData;
    }
}

// Get the data for the chart
$productCreationData = getProductCreationData();

// Prepare data for the chart
$chartData = [];
foreach ($productCreationData as $ingredient) {
    foreach ($ingredient['products'] as $product) {
        $chartData[] = [
            'product' => $product['product_name'],
            'ingredient' => $ingredient['ingredient_name'],
            'can_create' => $product['can_create']
        ];
    }
}

// Convert the chart data to JSON format for use in JavaScript
$chartDataJson = json_encode($chartData);
?>

<!-- HTML for the chart -->
<div class="box box-solid ">
    <div class="box-header">
        <i class="fa fa-th"></i>
        <h3 class="box-title">Product Creation Chart</h3>
    </div>
    <div class="box-body border-radius-none newSalesGraph">
 <canvas id="line-chart-product-creation" width="400" height="100"></canvas>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Your Chart.js code goes here
    var chartData = <?php echo json_encode(array_values($productCreationData)); ?>;

    // Check the structure of the data
    console.log(chartData);

    // Check if chartData is an array
    if (!Array.isArray(chartData)) {
        console.error('chartData is not an array:', chartData);
        return; // Exit if data is not in the correct format
    }

    // Prepare the data for Chart.js
    var productMap = {};
    var ingredientNames = [];
    var productNames = new Set(); // To store unique product names for x-axis labels

    // Populate productMap with the ingredient data
    chartData.forEach(function(item) {
        var ingredient = item.ingredient_name;
        var products = item.products; // Access the products object

        if (!productMap[ingredient]) {
            productMap[ingredient] = {};
            ingredientNames.push(ingredient); // Keep track of ingredient names
        }

        // Check if products is an object and iterate over it
        if (typeof products === 'object' && products !== null) {
            for (var productId in products) {
                if (products.hasOwnProperty(productId)) {
                    var product = products[productId];
                    var productName = product.product_name;
                    var canCreate = product.can_create;

                    // Add product name to the set
                    productNames.add(productName);

                    // Store how many products can be created using that ingredient
                    productMap[ingredient][productName] = canCreate; // Use product name directly
                }
            }
        }
    });

    // Convert Set to Array for labels
    var labels = Array.from(productNames);

    // Prepare datasets for Chart.js
    var datasets = [];
    ingredientNames.forEach(function(ingredient) {
        var dataset = {
            label: ingredient,
            data: [], // Initialize data array
            borderColor: getRandomColor(), // Function to get a random color for each line
            fill: false
        };

        // Fill the dataset with can_create values for each product in labels
        labels.forEach(function(product) {
            dataset.data.push(productMap[ingredient][product] || 0); // Push can_create or 0 if undefined
        });
        datasets.push(dataset);
    });

    // Ensure the canvas element is correctly selected
    var ctx = document.getElementById('line-chart-product-creation');
    if (!ctx) {
        console.error('Canvas element not found');
        return; // Exit if the canvas element is not found
    }

    // Check if ctx is actually a canvas element
    if (!(ctx instanceof HTMLCanvasElement)) {
        console.error('ctx is not a canvas element:', ctx);
        return; // Exit if ctx is not a valid canvas element
    }

    var chartCtx = ctx.getContext('2d');

    new Chart(chartCtx, {
        type: 'line',
        data: {
            labels: labels, // Use product names as x-axis labels
            datasets: datasets
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Products'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Number of Products That Can Be Created'
                    }
                }
            }
        }
    });

    // Function to get a random color for each line
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
});


</script>

