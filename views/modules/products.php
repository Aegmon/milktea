<?php

if($_SESSION["profile"] == "Seller"){

  echo '<script>

    window.location = "home";

  </script>';

  return;

}
$categoryID = $_GET["category_id"] ?? '';
?>
<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Products

    </h1>

    <ol class="breadcrumb">
	 
      <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Dashboard</li>

    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">

        <button class="btn btn-success" data-toggle="modal" data-target="#addProduct"> <i class="fa fa-plus"></i> Add Product</button>

      </div>

      <div class="box-body">

        <table class="table table-bordered table-hover table-striped dt-responsive productsTable" width="100%">
       
          <thead>
		 
           <tr>
             
             <th style="width:10px">#</th>
             <th>Image</th>
             <th>Description</th>
             <th>Category</th>
             <th>Selling Price</th>
             <th>Date added</th>
             <th>Actions</th>

           </tr> 

          </thead>

        </table>

        <input type="hidden" value="<?php echo $_SESSION['profile']; ?>" id="hiddenProfile">
    <input type="hidden" value="<?php echo $categoryID; ?>" id="hiddencategoryID">
      </div>
    
    </div>

  </section>

</div>

<!--=====================================
=            module add Product            =
======================================-->

<!-- Modal -->
<div id="addProduct" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

      <form role="form" method="POST" enctype="multipart/form-data">

        <!-- HEADER -->
        <div class="modal-header" style="background: #DD4B39; color: #fff">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Product</h4>
        </div>

        <!-- BODY -->
        <div class="modal-body">
          <div class="box-body">
   <!-- Description Input -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>
                <input class="form-control input-lg" type="text" id="newDescription" name="newDescription" placeholder="Add Description/Product Name" required>
              </div>
            </div>
            <!-- Category Input -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span>
                <select class="form-control input-lg" id="newCategory" name="newCategory">
                  <option value="">Select Category</option>
                  <?php
                    $item = null;
                    $value1 = null;
                    $categories = controllerCategories::ctrShowCategories($item, $value1);
                    foreach ($categories as $key => $value) {
                      echo '<option value="'.$value["id"].'">'.$value["Category"].'</option>';
                    }
                  ?>
                </select>
              </div>
            </div>
      <!-- Ingredients Input -->
<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-leaf"></i></span>
        <select class="form-control input-lg" name="ingredients[]" id="ingredientSelect" required onchange="updateSize(this, 'sizeDisplay')">
            <option value="">Select Ingredients</option>
            <?php
                $item = null;
                $value1 = null;
                $ingredients = controllerIngredients::ctrShowIngredients($item, $value1);
                foreach ($ingredients as $key => $value) {
                    echo '<option value="'.$value["id"].'" data-size="'.$value["size"].'"><b>'.$value["ingredient"].'</b></option>'; 
                }
            ?>
        </select>
    </div>
    <div class="input-group" style="margin-top:20px">
        <span class="input-group-addon"><i class="fa fa-arrows-alt"></i></span>
        <input class="form-control input-lg" type="number" name="sizes[]" placeholder="Measurement" min="1" required>
        <span class="input-group-addon size-display" id="sizeDisplay"><strong>Select an ingredient</strong></span>
    </div>
</div>
      <div class="form-group" id="ingredientsContainer">
      </div>
      <!-- Button to Add More Ingredients and Sizes -->
      <button type="button" class="btn btn-primary" id="addIngredientBtn" style="margin-bottom:20px">Add Ingredient</button>

        
   <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span>
                <select class="form-control input-lg" id="newSize" name="newSize">
                  <option value="">Select Size</option>
               <option value="Large">Large</option>
                      <option value="Regular">Regular</option>
                </select>
              </div>
            </div>
         

  
            <!-- Selling Price Input -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>
                <input type="number" class="form-control input-lg" id="newSellingPrice" name="newSellingPrice" step="any" min="0" placeholder="Selling Price" required>
              </div>
            </div>

            <!-- Image Upload -->
            <div class="form-group">
              <div class="panel">Upload Image</div>
              <input id="newProdPhoto" type="file" class="newImage" name="newProdPhoto">
              <p class="help-block">Maximum size 2Mb</p>
              <img src="views/img/products/default/anonymous.png" class="img-thumbnail preview" alt="" width="100px">
            </div>

          </div>
        </div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save Product</button>
        </div>

      </form>

      <?php
        $createProduct = new ControllerProducts();
        $createProduct->ctrCreateIngredientProduct();
      ?> 

    </div>
  </div>
</div>

<!--====  End of module add Product  ====-->

<!--=====================================
EDIT PRODUCT
======================================-->
<div id="modalEditProduct" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post" enctype="multipart/form-data">

        <!-- HEADER -->
        <div class="modal-header" style="background:#DD4B39; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit product</h4>
        </div>

        <!-- BODY -->
        <div class="modal-body">
          <div class="box-body">

            <!-- Category Input -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span>
                <select class="form-control input-lg" name="editCategory" readonly required>
                  <option id="editCategory"></option>
                </select>
              </div>
            </div>

     

            <!-- Description Input -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>
                <input type="text" class="form-control input-lg" id="editDescription" name="editDescription" required>
              </div>
            </div>

            <!-- Stock Input -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                <input type="number" class="form-control input-lg" id="editStock" name="editStock" min="0" required>
              </div>
            </div>

            <!-- Selling Price Input -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>
                <input type="number" class="form-control input-lg" id="editSellingPrice" name="editSellingPrice" step="any" min="0" required>
              </div>
            </div>

            <!-- Image Upload -->
            <div class="form-group">
              <div class="panel">Upload Image</div>
              <input type="file" class="newImage" name="editImage">
              <p class="help-block">2MB max</p>
              <img src="views/img/products/default/anonymous.png" class="img-thumbnail preview" width="100px">
              <input type="hidden" name="currentImage" id="currentImage">
            </div>

          </div>
        </div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save Changes</button>
        </div>

      </form>

      <?php
        $editProduct = new controllerProducts();
        $editProduct->ctrEditProduct();
      ?> 

    </div>
  </div>
</div>

<!--=====================================
VIEW PRODUCT
======================================-->
<div id="modalViewProduct" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <!-- HEADER -->
      <div class="modal-header" style="background:#DD4B39; color:white">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">View Ingredients</h4>
      </div>

      <!-- BODY -->
      <div class="modal-body">
        <div class="box-body">

          <!-- Ingredients List -->
          <div class="form-group">
            <ul id="ingredientsList" class="list-group">
            
            </ul>
          </div>

        </div>
      </div>

      <!-- FOOTER -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
      </div>
 

    </div>
  </div>
</div>


<?php

  $deleteProduct = new controllerProducts();
  $deleteProduct -> ctrDeleteProduct();

?>
<script>
function updateSize(selectElement, displayId) {
    var sizeDisplay = document.getElementById(displayId); // Get the specific size display element
    var selectedOption = selectElement.options[selectElement.selectedIndex];

    if (selectedOption.value) {
        var size = selectedOption.getAttribute("data-size");
        sizeDisplay.innerHTML = "<strong>" + size + "</strong>"; // Update size display for the selected ingredient
    } else {
        sizeDisplay.innerHTML = "<strong>Select an ingredient</strong>"; // Default message
    }
}

var ingredientOptions = `<?php
    $item = null;
    $value1 = null;
    $ingredients = controllerIngredients::ctrShowIngredients($item, $value1);
    $options = "";
    foreach ($ingredients as $key => $value) {
        $options .= '<option value="'.$value["id"].'" data-size="'.$value["size"].'">'.$value["ingredient"].'</option>'; 
    }
    echo $options;
?>`;

$('#addIngredientBtn').on('click', function () {
    var uniqueId = Date.now(); // Unique ID for each new ingredient field group
    var newIngredientField = `
        <div class="form-group" id="ingredient-${uniqueId}">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-leaf"></i></span>
                <select class="form-control input-lg" name="ingredients[]" required onchange="updateSize(this, 'sizeDisplay-${uniqueId}')">
                    <option value="">Select Ingredients</option>
                    ${ingredientOptions}
                </select>
            </div>
            <div class="input-group" style="margin-top:20px">
                <span class="input-group-addon"><i class="fa fa-arrows-alt"></i></span>
                <input class="form-control input-lg" type="number" name="sizes[]" placeholder="Measurement (e.g., 10ml, 10grams)" min="1" required>
                <span class="input-group-addon size-display" id="sizeDisplay-${uniqueId}"><strong>Select an ingredient</strong></span>
            </div>
            <div class="input-group">
                <button type="button" class="btn btn-danger btn-remove-ingredient" data-id="${uniqueId}" style="margin-top:20px; margin-left:10px;">Remove</button>
            </div>
        </div>
    `;
    $('#ingredientsContainer').append(newIngredientField);
});

// Event delegation for dynamically added buttons
$('#ingredientsContainer').on('click', '.btn-remove-ingredient', function() {
    var id = $(this).data('id'); // Get the unique ID of the ingredient field
    $('#ingredient-' + id).remove(); // Remove the specific ingredient field group
});
</script>