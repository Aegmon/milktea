<?php

$item = null;
$value = null;
$order = "sales";

$products = ControllerProducts::ctrShowProducts($item, $value, $order);
$colours = array(
    "#0000FF", // Blue
    "#1E90FF", // Dodger Blue
    "#00BFFF", // Deep Sky Blue
    "#87CEFA", // Light Sky Blue
    "#4682B4", // Steel Blue
    "#ADD8E6", // Light Blue
    "#B0E0E6", // Powder Blue
    "#5F9EA0", // Cadet Blue
    "#00CED1", // Dark Turquoise
    "#008080"  // Teal
);

$salesTotal = ControllerProducts::ctrShowAddingOfTheSales();


?>

<!--=====================================
products MÁS VENDIDOS
======================================-->
<!-- Log on to codeastro.com for more projects! -->
<div class="box box-default">
	
	<div class="box-header with-bvalue">
  
      <h3 class="box-title">Best Seller Products</h3>

    </div>

	<div class="box-body">
    
      	<div class="row">

	        <div class="col-md-12">

	 			<div class="chart-responsive">
	            
	            	<canvas id="pieChart" height="250"></canvas>
	          
	          	</div>

	        </div>

		    <div class="col-md-5">
		      	
	

		    </div>

		</div>

    </div>
	<!-- Log on to codeastro.com for more projects! -->
    <div class="box-footer no-padding">
    	
		<ul class="nav nav-pills nav-stacked">
			
			 <?php

          	for($i = 0; $i <5; $i++){
			
          		echo '<li>
						 
						 <a>

						 <img src="'.$products[$i]["image"].'" class="img-thumbnail" width="60px" style="margin-right:10px"> 
						 '.$products[$i]["description"].'

						 <span class="pull-right text-'.$colours[$i].'">   
						 '.ceil($products[$i]["sales"]*100/$salesTotal["total"]).'%
						 </span>
							
						 </a>

      				</li>';

			}

			?>


		</ul>

    </div>

</div>

<script>
    var pieChartCanvas = document.getElementById('pieChart').getContext('2d');
    var pieChart       = new Chart(pieChartCanvas);
    var PieData        = [
        <?php
        for($i = 0; $i < min(count($products), 10); $i++){
            echo "{
                value    : ".$products[$i]["sales"].",
                color    : '".$colours[$i]."',
                highlight: '".$colours[$i]."',
                label    : '".$products[$i]["description"]."'
            },";
        }
        ?>
    ];
    var pieOptions     = {
        segmentShowStroke    : true,
        segmentStrokeColor   : '#fff',
        segmentStrokeWidth   : 1,
        percentageInnerCutout: 50,
        animationSteps       : 100,
        animationEasing      : 'easeOutBounce',
        animateRotate        : true,
        animateScale         : false,
        responsive           : true,
        maintainAspectRatio  : false,
        tooltipTemplate      : '<%=value %> <%=label%>'
    };
    pieChart.Doughnut(PieData, pieOptions);
</script>