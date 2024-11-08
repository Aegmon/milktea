<?php

function getIngredientStockData() {
    $pdo = Connection::connect();

    $query = "
        SELECT 
            i.ingredient, 
            i.stockalert, 
            i.quantity AS ingredient_quantity
        FROM ingredients i
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $data = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ingredient = $row['ingredient'];
        $stockAlert = $row['stockalert'];
        $quantity = $row['ingredient_quantity'];

        // Initialize counts for each ingredient stock level category
        $data[$ingredient] = [
            'high' => 0,   // 51-100%
            'medium' => 0, // 35-50%
            'low' => 0     // 0-35%
        ];

        if ($stockAlert > 0) {
            $stockPercentage = ($quantity / $stockAlert) * 100;

            // Calculate the size of each segment based on stock percentage
            if ($stockPercentage > 50) {
                $data[$ingredient]['high'] = min($stockPercentage - 50, 50); // Max segment is 50% for high
                $data[$ingredient]['medium'] = min($stockPercentage - 35, 15); // Max segment is 15% for medium
                $data[$ingredient]['low'] = min($stockPercentage, 35); // Max segment is 35% for low
            } elseif ($stockPercentage >= 35) {
                $data[$ingredient]['medium'] = min($stockPercentage - 35, 15); 
                $data[$ingredient]['low'] = min($stockPercentage, 35);
            } else {
                $data[$ingredient]['low'] = min($stockPercentage, 35);
            }
        } else {
            // If stock alert is zero, classify as low stock by default
            $data[$ingredient]['low'] = 100; // Assume 100% low since there's no alert level
        }
    }

    return $data;
}

// Fetch categorized data to pass to the chart
$ingredientStockData = getIngredientStockData();
?>

<!-- Display stacked bar chart -->
<div class="box box-solid">
    <div class="box-header">
        <i class="fa fa-th"></i>
        <h3 class="box-title">Ingredient Stock Alert Chart</h3>
    </div>
    <div class="box-body border-radius-none newSalesGraph">
        <canvas id="ingredient-stock-alert-chart" height="100"></canvas>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var ingredientData = <?php echo json_encode($ingredientStockData); ?>;

    var labels = Object.keys(ingredientData); // Ingredient names
    var highData = [];
    var mediumData = [];
    var lowData = [];

    // Populate data arrays for each stock level
    labels.forEach(function(ingredient) {
        highData.push(parseFloat(ingredientData[ingredient].high) || 0);
        mediumData.push(parseFloat(ingredientData[ingredient].medium) || 0);
        lowData.push(parseFloat(ingredientData[ingredient].low) || 0);
    });

    var ctx = document.getElementById('ingredient-stock-alert-chart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels, // Ingredient names
            datasets: [
                {
                    label: '0-35% Stock Level',
                    data: lowData,
                    backgroundColor: 'red'
                },
                {
                    label: '35-50% Stock Level',
                    data: mediumData,
                    backgroundColor: 'yellow'
                },
                {
                    label: '51-100% Stock Level',
                    data: highData,
                    backgroundColor: 'green'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                    title: { display: true, text: 'Ingredients' }
                },
                y: {
                    beginAtZero: true,
                    max: 100, // Full bar is 100%
                    stacked: true,
                    title: { display: true, text: 'Stock Level Percentage' }
                }
            }
        }
    });
});

</script>
