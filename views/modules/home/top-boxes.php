<?php

$item = null;
$value = null;
$order = "id";

$controllerSales = new ControllerSales();
$sales = $controllerSales->ctrAddingTotalSales();
$categories = ControllerCategories::ctrShowCategories($item, $value);
$totalCategories = count($categories);

$customers = ControllerCustomers::ctrShowCustomers($item, $value);
$totalCustomers = count($customers);

$products = ControllerProducts::ctrShowProducts($item, $value, $order);
$totalProducts = count($products);

?>



<div class="col-lg-4 col-xs-6">
	 
  <div class="small-box bg-primary">
    
    <div class="inner">
      
      <h3>₱<?php echo number_format($sales["total"],2); ?></h3>

      <p>Sales</p>
    
    </div>
    
    <div class="icon">
      
      <i class="fa fa-money"></i>
    
    </div>
    
    <a href="sales" class="small-box-footer">
      
      More info <i class="fa fa-arrow-circle-right"></i>
    
    </a>

  </div>

</div>
 
<div class="col-lg-4 col-xs-6">

  <div class="small-box bg-primary">
    
    <div class="inner">
    
      <h3><?php echo number_format($totalCategories); ?></h3>

      <p>Categories</p>
    
    </div>
    
    <div class="icon">
    
      <i class="ion ion-clipboard"></i>
    
    </div>
    
    <a href="categories" class="small-box-footer">
      
      More info <i class="fa fa-arrow-circle-right"></i>
    
    </a>

  </div>

</div>
 

 
<div class="col-lg-4 col-xs-6">

  <div class="small-box bg-primary">
  
    <div class="inner">
    
      <h3><?php echo number_format($totalProducts); ?></h3>

      <p>products</p>
    
    </div>
    
    <div class="icon">
      
      <i class="ion ion-ios-cart"></i>
    
    </div>
    
    <a href="products" class="small-box-footer">
      
      More info <i class="fa fa-arrow-circle-right"></i>
    
    </a>

  </div>
	 
</div>