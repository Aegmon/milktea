<?php
function getIngredientStockData($ingredientId = null) {
    // Connect to the database
    $pdo = Connection::connect();

    // SQL query to fetch the ingredient stock data
    $query = "
        SELECT *
        FROM ingredients 
";
   
    // Filter by ingredient if an ingredientId is provided
    if ($ingredientId) {
        $query .= " WHERE i.id = :ingredientId";
    }

    $stmt = $pdo->prepare($query);

    // Bind ingredientId if it's set
    if ($ingredientId) {
        $stmt->bindParam(':ingredientId', $ingredientId, PDO::PARAM_INT);
    }

    // Execute the query
    $stmt->execute();

    $data = [];

    // Loop through the results and calculate the stock levels
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ingredient = $row['ingredient'];
        $stockAlert = $row['stockalert'];
        $quantity = $row['quantity'];

        // Initialize counts for each ingredient stock level category
        if (!isset($data[$ingredient])) {
            $data[$ingredient] = [
                'high' => 0,   // 51-100%
                'medium' => 0, // 35-50%
                'low' => 0     // 0-35%
            ];
        }

        // If there's a stock alert, calculate the percentage
        if ($stockAlert > 0) {
            $stockPercentage = ($quantity / $stockAlert) * 100;

            if ($stockPercentage > 50) {
                $data[$ingredient]['high'] = min($stockPercentage, 100); // High segment 51-100%
            } elseif ($stockPercentage >= 35) {
                $data[$ingredient]['medium'] = min($stockPercentage - 35, 15); // Medium segment 35-50%
                $data[$ingredient]['low'] = min($stockPercentage, 35); // Low segment for 0-35%
            } else {
                $data[$ingredient]['low'] = min($stockPercentage, 35); // Low segment for 0-35%
            }
        } else {
            // If stock alert is zero, classify as low stock by default
            $data[$ingredient]['low'] = 100; // Assume 100% low since there's no alert level
        }
    }

    return $data;
}

// Fetch the ingredient stock data with the optional ingredient filter
$ingredientFilter = isset($_GET['ingredient']) ? $_GET['ingredient'] : null; // Get selected ingredient from URL parameter
$ingredientStockData = getIngredientStockData($ingredientFilter);

// Debugging: Output the data for JavaScript
echo '<script>';
echo 'var ingredientData = ' . json_encode($ingredientStockData) . ';';
echo 'console.log(ingredientData);'; // This will log the data in the browser console
echo '</script>';
?>
        <?php
                        // Adjust the controller method to fetch ingredients
                        $item = null; 
                        $value = null;
                        $ingredients = ControllerIngredients::ctrShowIngredients($item, $value);

                        foreach ($ingredients as $key => $value) {
                            // Define stock alert and quantity
                            $stockAlert = $value['stockalert'];
                            $quantity = $value['quantity'];
                            $badgeClass = '';

                          $percentage = ($quantity / $stockAlert) * 100; // Calculate the percentage

                            if ($percentage >= 51) {
                                $badgeClass = 'btn-success'; // 51% and above (Green)
                            } elseif ($percentage >= 35) {
                                $badgeClass = 'btn-warning'; // 35% to 50% (Yellow)
                            } else {
                                $badgeClass = 'btn-danger'; // Below 35% (Red)
                            }

                            // Display alert if stock is low
                            if ($quantity < $stockAlert) {
                                echo '<div class="alert alert-danger" role="alert">
                                          Low Stock for <strong> <a href="#" class="text-white" onclick="editIngredient(' . $value["id"] . ')">' . $value['ingredient'] . '</a></strong>
                                      </div>';
                            }
                        }
?>
<!-- HTML for the dropdown filter (Ingredients) -->
<label for="ingredient">Select Ingredient:</label>
<select name="ingredient" id="ingredient" onchange="updateChart()">
    <option value="">All Ingredients</option>
    <?php
    // Fetch all ingredients from the database for the dropdown
    $pdo = Connection::connect();
    $ingredientQuery = "SELECT ingredient FROM ingredients";
    $stmt = $pdo->prepare($ingredientQuery);
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $selected = ($row['ingredient'] == $ingredientFilter) ? 'selected' : '';
        echo "<option value=\"{$row['ingredient']}\" {$selected}>{$row['ingredient']}</option>";
    }
    ?>
</select>





<!-- HTML for the chart -->
<div class="box box-solid">
    <div class="box-header">
        <i class="fa fa-th"></i>
        <h3 class="box-title">Ingredient Stock Alert Chart</h3>
    </div>
    <div class="box-body border-radius-none newSalesGraph">
        <canvas id="ingredient-stock-alert-chart" height="50"></canvas>
    </div>
</div>

<script>
 // Ensure the ingredientData is available from PHP
var ingredientData = <?php echo json_encode($ingredientStockData); ?>;
console.log(ingredientData);  // Log to check if the data is correct
// Store chart instance globally
var chartInstance = null;

// Function to filter ingredient data based on ingredient name
function filterIngredientData(selectedIngredient) {
    if (!selectedIngredient) {
        return ingredientData;  // Return all data if no ingredient is selected
    }

    // Filter out the data for the selected ingredient name
    var filteredData = {};
    for (var ingredient in ingredientData) {
        if (ingredientData.hasOwnProperty(ingredient)) {
            if (ingredient === selectedIngredient) {
                filteredData[ingredient] = ingredientData[ingredient];
            }
        }
    }

    return filteredData;
}

// Function to update the chart with filtered data
function updateChart() {
    // Get the selected ingredient name value from the dropdown
    var selectedIngredient = document.getElementById('ingredient').value;

    // Filter the ingredient data based on the selection
    var filteredData = filterIngredientData(selectedIngredient);

    // Prepare data for the chart
    var labels = Object.keys(filteredData);
    var highData = [];
    var mediumData = [];
    var lowData = [];

    labels.forEach(function(ingredient) {
        highData.push(filteredData[ingredient].high || 0);
        mediumData.push(filteredData[ingredient].medium || 0);
        lowData.push(filteredData[ingredient].low || 0);
    });

    // Destroy the existing chart if it exists
    if (chartInstance) {
        chartInstance.destroy();
    }

    // Get the canvas element
    var ctx = document.getElementById('ingredient-stock-alert-chart').getContext('2d');

    // Create a new chart instance
    chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: '0-35% Stock Level',
                    data: lowData,
                    backgroundColor: 'red',
                },
                {
                    label: '35-50% Stock Level',
                    data: mediumData,
                    backgroundColor: 'yellow',
                },
                {
                    label: '51-100% Stock Level',
                    data: highData,
                    backgroundColor: 'green',
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Ingredients'
                    }
                },
                y: {
                    beginAtZero: true,
                    max: 100,
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Stock Level Percentage'
                    }
                }
            }
        }
    });
}

// Call the updateChart function on initial load to render the chart with all data
document.addEventListener('DOMContentLoaded', function() {
    updateChart();
});


</script>
