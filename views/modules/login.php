<div id="back"></div>
<style type="text/css">



</style>
<div class="login-box">

  <div class="login-logo">

    <img class="img-responsive" src="views/img/template/logo.png" style="padding: 30px 100px ">

  </div>

  <div class="login-box-body">

    <p class="login-box-msg">Please log in to start your session</p>

    <form method="post">

      <div class="form-group has-feedback">

        <input type="email" class="form-control" placeholder="Email" name="loginUser" required>

        <span class="glyphicon glyphicon-user form-control-feedback"></span>

      </div>

      <div class="form-group has-feedback">

        <input type="password" class="form-control" placeholder="Password" name="loginPass" required>

        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

      </div>

      <div class="row">

        <div class="col-xs-4">

          <button type="submit" class="btn btn-success btn-block btn-flat">Log In</button>

        </div>
       
      </div>

      <?php

        $login = new ControllerUsers();
        $login -> ctrUserLogin();

      ?>

    </form>

  </div>

</div>
<!-- Log on to codeastro.com for more projects! -->
