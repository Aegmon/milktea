<?php


if ($_SESSION["profile"] == "Seller") {
    echo '<script>
        window.location = "home";
    </script>';
    return;
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Ingredient Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <button class="btn btn-success" data-toggle="modal" data-target="#addIngredients"> <i class="fa fa-plus"></i> Add Ingredients</button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-hover table-striped dt-responsive tables" width="100%">
                    <thead>
                        <tr>
                            <th style="width:10px">#</th>
                            <th>Ingredient</th>
                            <th>Measurement</th>
                            <th>Size</th>
                            <th>Price per Addons</th>
                            <th>Measurement per Addons</th>
                            <th>Actions</th>
                        </tr> 
                    </thead>
                    <tbody>
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

                            // Determine badge color based on quantity
                            if ($quantity > ($stockAlert * 1.5)) {
                                $badgeClass = 'btn-success'; // More than 50% higher
                            } elseif ($quantity >= ($stockAlert * 0.5)) {
                                $badgeClass = 'btn-warning'; // Between 50% and 100%
                            } elseif ($quantity >= ($stockAlert * 0.25)) {
                                $badgeClass = 'btn-danger'; // Between 25% and 50%
                            } else {
                                $badgeClass = 'btn-danger'; // Below 25%
                            }

                            // Display alert if stock is low
                            if ($quantity < $stockAlert) {
                                echo '<div class="alert alert-danger" role="alert">
                                          Low Stock for <strong> <a href="#" class="text-white" onclick="editIngredient(' . $value["id"] . ')">' . $value['ingredient'] . '</a></strong>
                                      </div>';
                            }

                            echo '<tr>
                                <td>' . ($key + 1) . '</td>
                                <td class="text-uppercase">' . $value['ingredient'] . '</td>
                                <td><button class="btn ' . $badgeClass . '">' . $quantity . '</button></td>
                                <td>' . $value['size'] . '</td>
                                <td>₱' . $value['addons_price'] . '</td>
                                <td>' . $value['addons_measurement'] . ' ' . $value['size'] . '</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-primary btnEditIngredient" idIngredient="' . $value["id"] . '" data-toggle="modal" data-target="#editIngredients"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-danger btnDeleteIngredient" idIngredient="' . $value["id"] . '"><i class="fa fa-trash"></i></button>
                                    </div>  
                                </td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
</div>

<!--=====================================
=            module add Ingredients            =
======================================-->
<!-- Modal -->
<div id="addIngredients" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form role="form" method="POST">
                <div class="modal-header" style="background: #DD4B39; color: #fff">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Ingredient</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <!-- Input for ingredient name -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                <input class="form-control input-lg" type="text" name="newIngredient" placeholder="Add Ingredient" required>
                            </div>
                        </div>
                        <!-- Input for quantity -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                                <input class="form-control input-lg" type="number" name="newQuantity" placeholder="Add Quantity" required>
                            </div>
                        </div>
                        <!-- Input for size -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-balance-scale"></i></span>
                                <select class="form-control input-lg" name="newSize" required>
                                    <option value="" disabled selected>Select Measurement</option>
                                    <option value="grams">Grams</option>
                                    <option value="mililiters">Milliliters</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                                <input class="form-control input-lg" type="number" name="newPrice" placeholder="Add Price For Addons" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                                <input class="form-control input-lg" type="number" name="newMeasurement" placeholder="Add Measure Per Addons" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                                <input class="form-control input-lg" type="number" name="stockalert" placeholder="Stock Alert" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Ingredient</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$createIngredient = new ControllerIngredients();
$createIngredient->ctrCreateIngredient();
?>

<!--=====================================
=            module edit Ingredients            =
======================================-->
<!-- Modal -->
<div id="editIngredients" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form role="form" method="POST">
                <div class="modal-header" style="background: #DD4B39; color: #fff">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Ingredient</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <!-- Input for ingredient name -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                <input class="form-control input-lg" type="text" id="editIngredient" name="editIngredient" required>
                                <input type="hidden" name="idIngredient" id="idIngredient" required>
                            </div>
                        </div>
                        <!-- Input for quantity -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                                <input class="form-control input-lg" type="number" id="editQuantity" name="editQuantity" required>
                            </div>
                        </div>
                        <!-- Input for size -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-balance-scale"></i></span>
                                <select class="form-control input-lg" id="editSize" name="editSize" required>
                                    <option value="grams">Grams</option>
                                    <option value="mililiters">Milliliters</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                                <input class="form-control input-lg" type="number" id="editPrice" name="editPrice" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                                <input class="form-control input-lg" type="number" id="editMeasurement" name="editMeasurement" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                                <input class="form-control input-lg" type="number" id="editStockAlert" name="editStockAlert" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update Ingredient</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$editIngredient = new ControllerIngredients();
$editIngredient->ctrEditIngredient();
?>

<!--=====================================
=            module edit Ingredients            =
======================================-->
<!-- Modal -->
<div id="editIngredients" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form role="form" method="POST">
                <div class="modal-header" style="background: #DD4B39; color: #fff">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Ingredient</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <!-- Input for ingredient name -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                <input class="form-control input-lg" type="text" id="editIngredient" name="editIngredient" required>
                                <input type="hidden" name="idIngredient" id="idIngredient" required>
                            </div>
                        </div>
                        <!-- Input for quantity -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                                <input class="form-control input-lg" type="number" id="editQuantity" name="editQuantity" required>
                            </div>
                        </div>
                        <!-- Input for size -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-balance-scale"></i></span>
                                <select class="form-control input-lg" id="editSize" name="editSize" required>
                                    <option value="grams">Grams</option>
                                    <option value="kilograms">Kilograms</option>
                                    <option value="liters">Liters</option>
                                    <option value="mililiters">Milliliters</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>

                <?php
                $editIngredient = new ControllerIngredients();
                $editIngredient->ctrEditIngredient();
                ?>
            </form>
        </div>
    </div>
</div>

<?php
$deleteIngredient = new ControllerIngredients();
$deleteIngredient->ctrDeleteIngredient();
?>

<script>
$(".tables").on("click", ".btnEditIngredient", function() {
    var idIngredient = $(this).attr("idIngredient");
    console.log(idIngredient);
    var datum = new FormData();
    datum.append("idIngredient", idIngredient);

    $.ajax({
        url: "ajax/ingredients.ajax.php", // Ensure this is the correct URL for the AJAX request
        method: "POST",
        data: datum,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            // Check if answer is not empty
            if (answer) {
                $("#editIngredient").val(answer["ingredient"]); // Match the field names
                $("#editQuantity").val(answer["quantity"]); 
                $("#editSize").val(answer["size"]); 
                $("#idIngredient").val(answer["id"]); // Ensure this matches your hidden input ID
                $('#editIngredients').modal('show'); // Show the modal
            } else {
                console.error("No ingredient found with ID:", idIngredient);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error fetching ingredient:", textStatus, errorThrown);
        }
    });
});

</script>