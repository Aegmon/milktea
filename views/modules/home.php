<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Dashboard
      
      <small>Control panel</small>

    </h1>

    <ol class="breadcrumb">

      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Dashboard</li>

    </ol>

  </section>

  <section class="content">

    <div class="row">
      
      <?php

       

          include "home/top-boxes.php";

        

      ?>
    
    </div>
        <div class="row p-2">
              <div class="col-lg-12">
      <?php

    if($_SESSION["profile"] =="Administrator"){

          include "home/stockdata.php";

    }

      ?>
       </div>
      <div class="col-lg-12">
      <?php

    if($_SESSION["profile"] =="Administrator"){

          include "home/ingredientsData.php";

    }

      ?>
       </div>
    </div>
    <div class="row">

      <div class="col-lg-12">

      <?php

  

          include "reports/sales-graph.php";



      ?>
      
      </div>

      <div class="col-lg-6">
        
        <?php

  

            include "reports/bestseller-products.php";

       

        ?>

      </div>

   

      <div class="col-lg-12">
           
        <?php

        if($_SESSION["profile"] =="Special" || $_SESSION["profile"] =="Seller"){

           echo '<div class="box box-default">

           <div class="box-header">

           <h1>Welcome ' .$_SESSION["name"].'</h1>

           </div>

           </div>';

        }

        ?>

      </div>

    </div>

  </section>

</div>

