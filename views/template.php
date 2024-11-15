<?php
  session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Taipei Milk Tea</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="icon" href="views/img/template/icono-negro.png">

  <!-- Plugin CSS -->
  <link rel="stylesheet" href="views/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="views/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="views/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="views/dist/css/AdminLTE.css">
  <link rel="stylesheet" href="views/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet" href="views/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="views/bower_components/datatables.net-bs/css/responsive.bootstrap.min.css">
  <link rel="stylesheet" href="views/plugins/iCheck/all.css">
  <link rel="stylesheet" href="views/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="views/bower_components/morris.js/morris.css">

  <!-- Plugin JS -->
  <script src="views/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="views/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="views/bower_components/fastclick/lib/fastclick.js"></script>
  <script src="views/dist/js/adminlte.min.js"></script>
  <script src="views/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="views/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="views/bower_components/datatables.net-bs/js/dataTables.responsive.min.js"></script>
  <script src="views/bower_components/datatables.net-bs/js/responsive.bootstrap.min.js"></script>
  <script src="views/plugins/sweetalert2/sweetalert2.all.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <script src="views/plugins/iCheck/icheck.min.js"></script>
  <script src="views/plugins/input-mask/jquery.inputmask.js"></script>
  <script src="views/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
  <script src="views/plugins/input-mask/jquery.inputmask.extensions.js"></script>
  <script src="views/plugins/jqueryNumber/jquerynumber.min.js"></script>
  <script src="views/bower_components/moment/min/moment.min.js"></script>
  <script src="views/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script src="views/bower_components/raphael/raphael.min.js"></script>
  <script src="views/bower_components/morris.js/morris.min.js"></script>
  <script src="views/bower_components/Chart.js/Chart.js"></script>

</head>

<body class="hold-transition skin-black-light sidebar-mini login-page" style="background-image: url('views/img/template/bg.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

<!-- Site wrapper -->

<?php

  if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == "ok"){
 if ($_GET["route"] == 'otp') {
        include "modules/otp.php";
    }
    // Check if OTP is required (e.g., the user is not yet verified)
    if (isset($_SESSION["otp_verified"]) && $_SESSION["otp_verified"] != "yes") {
        // Redirect to OTP page if OTP is not verified
        include "modules/otp.php";
    } else {

        echo '<div class="wrapper">';

        /*=============================================
        =            header          =
        =============================================*/  
        include "modules/header.php";

        /*=============================================
        =            sidebar          =
        =============================================*/ 
        include "modules/sidebar.php";

        /*=============================================
        =            Content          =
        =============================================*/ 
        if(isset($_GET["route"])){

            if ($_GET["route"] == 'home' || 
                $_GET["route"] == 'users' ||
                $_GET["route"] == 'categories' ||
                $_GET["route"] == 'products' ||
                $_GET["route"] == 'addproducts' ||
                $_GET["route"] == 'customers' ||
                $_GET["route"] == 'sales' ||
                $_GET["route"] == 'create-sale' ||
                $_GET["route"] == 'edit-sale' ||
                $_GET["route"] == 'reports' ||
                $_GET["route"] == 'ingredients' ||
                $_GET["route"] == 'attributes' ||
                
                $_GET["route"] == 'logout'){

            include "modules/".$_GET["route"].".php";

            }else{

                include "modules/404.php";

            }

        }else{
            include "modules/home.php";
        }

        /*=============================================
        =            Footer          =
        =============================================*/ 
        include "modules/footer.php";

        echo '</div>';

    }

  } else {
      include "modules/login.php";
  }
?>

<!-- ./wrapper -->

<script src="views/js/attribute.js"></script>
<script src="views/js/ingredients.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="views/js/template.js"></script>
<script src="views/js/users.js"></script>
<script src="views/js/categories.js"></script>
<script src="views/js/products.js"></script>
<script src="views/js/customers.js"></script>
<script src="views/js/sales.js"></script>
<script src="views/js/reports.js"></script>

</body>
</html>
