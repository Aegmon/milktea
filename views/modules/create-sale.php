<?php

if($_SESSION["profile"] == "Special"){

  echo '<script>

    window.location = "home";

  </script>';

  return;

}

?>
 
<div class="content-wrapper">

  <section class="content-header">

    <h1>

     Point Of Sales

    </h1>

    <ol class="breadcrumb">

      <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Create Sale</li>

    </ol>

  </section>

  <section class="content">

    <div class="row">
      
      <!--=============================================
      THE FORM
      =============================================-->
      <div class="col-lg-5 col-xs-12">
        
        <div class="box box-default">

          <div class="box-header with-border"></div>

          <form role="form" method="post" class="saleForm">

            <div class="box-body">
                
                <div class="box">

                    <!--=====================================
                    =            SELLER INPUT           =
                    ======================================-->
                  
                    
                    <div class="form-group">

                      <div class="input-group">
                        
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>

                        <input type="text" class="form-control" name="newSeller" id="newSeller" value="<?php echo $_SESSION["name"]; ?>" readonly>

                        <input type="hidden" name="idSeller" value="<?php echo $_SESSION["id"]; ?>">

                      </div>

                    </div>


                    <!--=====================================
                    CODE INPUT
                    ======================================-->
                  
                    
                    <div class="form-group">

                      <div class="input-group">
                        
                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                        

                        <?php 
                          $item = null;
                          $value = null;

                          $sales = ControllerSales::ctrShowSales($item, $value);

                          if(!$sales){

                            echo '<input type="text" class="form-control" name="newSale" id="newSale" value="10001" readonly>';
                          }
                          else{

                            foreach ($sales as $key => $value) {
                              
                            }

                            $code = $value["code"] +1;

                            echo '<input type="text" class="form-control" name="newSale" id="newSale" value="'.$code.'" readonly>';

                          }

                        ?>

                      </div>


                    </div>


                    <!--=====================================
                    =            CUSTOMER INPUT           =
                    ======================================-->
                  
                     
                    <div class="form-group">

                    </div>
					 
                    <!--=====================================
                    =            PRODUCT INPUT           =
                    ======================================-->
                  
                    
                    <div class="form-group row newProduct">


                    </div>
                  <button type="button" class="btn btn-primary" onclick="showAddonTable()" id="addonButton">Addons <i class="fa fa-plus"></i></button>
       <table id="addonTable" class="table table-bordered table-hover table-striped dt-responsive addonTable" style="display: none;">
    <thead>
        <tr>
            <th>Addons</th>
            <th>Price per Addons</th>
            <th>Actions</th>
        </tr> 
    </thead>
<tbody>
<?php
$item = null; 
$value = null;

$ingredients = ControllerIngredients::ctrShowIngredients($item, $value);

foreach ($ingredients as $key => $value) {

    // Skip ingredients that contain "Powder" in their name
    if (stripos($value['ingredient'], 'Powder') !== false) {
        continue;  // Skip this iteration
    }

    // Main addon row
    echo '<tr>
        <td class="text-uppercase">' . $value['ingredient'] . '</td>
        <td>₱' . $value['addons_price'] . '</td>
        <td>
            <div class="btn-group">
                <button type="button" class="btn btn-primary btnAddons recoverButtonaddons" idIngredient="' . $value["id"] . '" data-price="' . $value['addons_price'] . '" data-addon="' . $value['ingredient'] . '">
                    <i class="fa fa-plus"></i> Add
                </button>
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cog"></i> Options
                </button>
                <div class="dropdown-menu">
                    <button type="button" class="dropdown-item btnAddons" idIngredient="' . $value["id"] . '" data-price="0" data-addon="' . $value['ingredient'] . '">
                        No ' . $value['ingredient'] . '
                    </button>
                </div>
            </div>  
        </td>
    </tr>';
}
?>
</tbody>


</table>



                    <input type="hidden" name="productsList" id="productsList">
                    <input type="hidden" name="addonList" id="addonList">
                    <!--=====================================
                    =            ADD PRODUCT BUTTON          =
                    ======================================-->
                    
                    <button type="button" class="btn btn-default hidden-lg btnAddProduct">Add Product</button>

             

                    <div class="row">

                      <!--=====================================
                        TAXES AND TOTAL INPUT
                      ======================================-->

                      <div class="col-xs-8 pull-right">

                        <table class="table">
                          
                          <thead>
                            
               
                            <th>Total</th>

                          </thead>


                          <tbody>
                            
                            <tr>
                  

                              <td style="width: 50%">

                                <div class="input-group">
                                  
                                  <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                  
                                  <input type="number" class="form-control" name="newSaleTotal" id="newSaleTotal" placeholder="00000" totalSale="" readonly required>

                                  <input type="hidden" name="saleTotal" id="saleTotal" required>

                                </div>

                              </td>

                            </tr>

                          </tbody>
			
                        </table>

                        
                        
                      </div>

                 
                      
                    </div>

           

                    <!--=====================================
                      PAYMENT METHOD
                      ======================================-->

                    <div class="form-group row">
                      
                      <div class="col-xs-6" style="padding-right: 0">

                        <div class="input-group">
                      
                          <select class="form-control" name="newPaymentMethod" id="newPaymentMethod" required>
                            
                              <option value="">-Select Payment Method-</option>
                              <option value="cash">Cash</option>
                                      <option value="Gcash">Gcash</option>
                   
                      


                          </select>

                        </div>

                      </div>

                      <div class="paymentMethodBoxes"></div>

                      <input type="hidden" name="listPaymentMethod" id="listPaymentMethod" required>

                    </div>

                    <br>
                    
                </div>

            </div>

            <div class="box-footer">
              <button type="submit" class="btn btn-success pull-right">Save Sale</button>
            </div>
          </form>

          <?php

            $saveSale = new ControllerSales();
            $saveSale -> ctrCreateSale();
            
          ?>

        </div>


        

      </div>


      <!--=============================================
      =            PRODUCTS TABLE                   =
      =============================================-->

		 
      <div class="col-lg-7 hidden-md hidden-sm hidden-xs">
        
          <div class="box box-default">
            
            <div class="box-header with-border"></div>

            <div class="box-body">
              
              <table class="table table-bordered table-hover table-striped dt-responsive salesTable">
                  
                <thead>

                   <tr>
                     
                     <th style="width:10px">#</th>
                     <th>Image</th>
                     <th>Description</th>
                      <th>Size</th>
                     <th>Actions</th>
                   </tr> 

                </thead>

              </table>

            </div>

          </div>
      </div>
    </div>
  </section>
</div>
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
<script>
function showAddonTable() {
    var table = document.getElementById("addonTable");
    var button = document.getElementById("addonButton");

    if (table.style.display === "none") {
        table.style.display = "table";
        button.innerHTML = 'Close Addons ';
    } else {
        table.style.display = "none";
        button.innerHTML = 'Addons ';
    }
}

    document.querySelectorAll('.btnAddons').forEach(button => {
        button.addEventListener('click', function() {
            const addonRow = this.closest('tr'); // Get the current row
            const zeroPriceButton = addonRow.querySelector('.btnZeroPrice');
            
            // Show the zero price option by triggering the dropdown
            if (zeroPriceButton) {
                zeroPriceButton.click(); // Trigger the dropdown
            }
        });
    });

  
</script>