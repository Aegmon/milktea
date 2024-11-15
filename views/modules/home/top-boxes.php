<?php

$item = null;
$value = null;
$order = "id";

$controllerSales = new ControllerSales();
$todaySales = $controllerSales->ctrAddingSalesByDate('today'); // New method for today's sales
$weekSales = $controllerSales->ctrAddingSalesByDate('week'); // New method for weekly sales
$totalSales = $controllerSales->ctrAddingTotalSales(); // Original method for total sales

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
      <h3>₱<?php echo number_format($todaySales["total"], 2); ?></h3>
      <p>Today's Sales</p>
    </div>
    <div class="icon">
      <i class="fa fa-calendar-day"></i>
    </div>
    <a href="sales?timeRange=today" class="small-box-footer">
      More info <i class="fa fa-arrow-circle-right"></i>
    </a>
  </div>
</div>

<div class="col-lg-4 col-xs-6">
  <div class="small-box bg-primary">
    <div class="inner">
      <h3>₱<?php echo number_format($weekSales["total"], 2); ?></h3>
      <p>Weekly Sales</p>
    </div>
    <div class="icon">
      <i class="fa fa-calendar-week"></i>
    </div>
    <a href="sales?timeRange=week" class="small-box-footer">
      More info <i class="fa fa-arrow-circle-right"></i>
    </a>
  </div>
</div>

<div class="col-lg-4 col-xs-6">
  <div class="small-box bg-primary">
    <div class="inner">
      <h3>₱<?php echo number_format($totalSales["total"], 2); ?></h3>
      <p>Total Sales</p>
    </div>
    <div class="icon">
      <i class="fa fa-money"></i>
    </div>
    <a href="sales" class="small-box-footer">
      More info <i class="fa fa-arrow-circle-right"></i>
    </a>
  </div>
</div>
