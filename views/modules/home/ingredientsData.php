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
                p.size AS product_size,
                i.quantity AS ingredient_quantity, 
                p.idCategory AS category_id, 
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
            
            // Concatenate product name and size
            $productNameWithSize = $row['product_name'] . " (" . $row['product_size'] . ")";

            // Create product entry if it doesn't exist for the ingredient
            if (!isset($productData[$ingredient_id])) {
                $productData[$ingredient_id] = [
                    'ingredient_name' => $row['ingredient'],
                    'products' => []
                ];
            }

            // Store how many products can be created using that ingredient
            $productData[$ingredient_id]['products'][$product_id] = [
                'product_name' => $productNameWithSize,
                'can_create' => $productsPerIngredient,
                'size' => $row['product_size'], // Add size to the product data
                'category_id' => $row['category_id'] // Add category_id for filtering
            ];
        }

        return $productData;
    }
}

// Function to get categories for the dropdown
function getCategories() {
    $pdo = Connection::connect();
    $query = "SELECT id, category FROM categories";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get the data for the chart
$productCreationData = getProductCreationData();
$categories = getCategories(); // Get categories for the dropdown

// Prepare data for the chart
$chartData = [];
foreach ($productCreationData as $ingredient) {
    foreach ($ingredient['products'] as $product) {
        $chartData[] = [
            'product' => $product['product_name'],
            'ingredient' => $ingredient['ingredient_name'],
            'can_create' => $product['can_create'],
            'size' => $product['size'], // Include size in chart data
            'category_id' => $product['category_id'] // Add category_id for filtering
        ];
    }
}

// Convert the chart data to JSON format for use in JavaScript
$chartDataJson = json_encode($chartData);
?>

<!-- HTML for the dropdown filters -->
<div>
    <label for="sizeFilter">Filter by Size:</label>
    <select id="sizeFilter">
        <option value="">All Sizes</option>
        <option value="Regular">Regular</option>
        <option value="Large">Large</option>
        <!-- Add other sizes as needed -->
    </select>

    <label for="categoryFilter">Filter by Category:</label>
    <select id="categoryFilter">
        <option value="">All Categories</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
        <?php endforeach; ?>
    </select>
</div>

<!-- HTML for the chart -->
<div class="box box-solid">
    <div class="box-header">
        <i class="fa fa-th"></i>
        <h3 class="box-title">Product Creation Chart</h3>
    </div>
    <div class="box-body border-radius-none newSalesGraph">
        <canvas id="bar-chart-product-creation" width="400" height="100"></canvas>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the chart data from PHP
    var chartData = <?php echo json_encode($chartData); ?>;

    // Prepare the dropdown filter elements
    var sizeFilter = document.getElementById('sizeFilter');
    var categoryFilter = document.getElementById('categoryFilter');

    // Initialize chart with all data
    var chart = initChart(chartData);

    // Event listeners for filters
    sizeFilter.addEventListener('change', updateChart);
    categoryFilter.addEventListener('change', updateChart);

    function updateChart() {
        var selectedSize = sizeFilter.value;
        var selectedCategory = categoryFilter.value;

        // Filter chart data based on selected size and category
        var filteredData = chartData.filter(function(item) {
            var sizeMatch = !selectedSize || item.size === selectedSize;
            var categoryMatch = !selectedCategory || item.category_id == selectedCategory; // Match category_id
            return sizeMatch && categoryMatch;
        });

        // Reinitialize chart with filtered data
        chart.destroy();
        chart = initChart(filteredData);
    }

    function initChart(data) {
        var productMap = {};
        var ingredientNames = [];
        var productNames = new Set(); // To store unique product names with size for x-axis labels

        data.forEach(function(item) {
            var ingredient = item.ingredient;

            if (!productMap[ingredient]) {
                productMap[ingredient] = {};
                ingredientNames.push(ingredient);
            }

            // Collect product names with size
            var productNameWithSize = item.product;
            var canCreate = item.can_create;

            productNames.add(productNameWithSize);
            productMap[ingredient][productNameWithSize] = canCreate;
        });

        var labels = Array.from(productNames);

        // Prepare datasets for Chart.js with pastel colors
        var datasets = ingredientNames.map(function(ingredient, index) {
            return {
                label: ingredient,
                data: labels.map(function(product) {
                    return productMap[ingredient][product] || 0;
                }),
                backgroundColor: getPastelColor(index)  // Use pastel color based on index
            };
        });

        // Initialize the chart
        var ctx = document.getElementById('bar-chart-product-creation').getContext('2d');
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
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
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                return context[0].dataset.label;  // Ingredient as tooltip header
                            },
                            label: function(context) {
                                var product = context.label;
                                var canCreate = context.raw;
                                return product + ': ' + canCreate;
                            }
                        }
                    }
                }
            }
        });
    }

    function getPastelColor(index) {
        const pastelColors = [
               '#FF5733', '#FFBD33', '#33FF57', '#33FFBD', '#5733FF', 
        '#FF33A1', '#A133FF', '#FF8C33', '#33FF8C', '#8C33FF',
        '#FF33FF', '#33A1FF', '#FF5733', '#33FF57', '#A1FF33'
        ];
        return pastelColors[index % pastelColors.length];
    }
});
</script>
