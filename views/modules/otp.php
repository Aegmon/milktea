<div id="back"></div>
<style type="text/css">
    /* You can add custom styles here */
</style>
<div class="login-box">

  <div class="login-logo">
    <img class="img-responsive" src="views/img/template/logo.png" style="padding: 30px 100px">
  </div>

  <div class="login-box-body">
    <p class="login-box-msg">Enter Your OTP</p>

    <form method="post">

      <div class="form-group has-feedback">
        <input type="number" class="form-control" placeholder="Enter OTP" name="otp" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-4">
          <button type="submit" class="btn btn-success btn-block btn-flat">Verify</button>
        </div>
      </div>

      <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['otp'])) {
            $user_id = $_SESSION['id'];  // User ID stored in session
            $entered_otp = $_POST['otp'];

            // Fetch user details from the database to get stored OTP and expiration time
            $table = 'users';
            $item = 'id';
            $value = $user_id;
            $answer = UsersModel::MdlShowUsers($table, $item, $value);

            // Check if OTP matches and is not expired
            if ($answer['otp'] == $entered_otp && strtotime($answer['otpexpiry']) > time()) {
                // OTP is valid, proceed with login
                $_SESSION['otp_verified'] = true;
                echo '<script>
                    window.location = "home"; 
                </script>';
            } else {
                // OTP is invalid or expired
                echo '<br><div class="alert alert-danger">Invalid or expired OTP. Please try again.</div>';
            }
        }

        // Resend OTP logic
        if (isset($_POST['resendOtp'])) {
            $user_id = $_SESSION['id']; // User ID stored in session

            // Fetch user details from the database
            $table = 'users';
            $item = 'id';
            $value = $user_id;
            $answer = UsersModel::MdlShowUsers($table, $item, $value);

            // Generate a new OTP and expiry time
            $otp = rand(100000, 999999);  // 6-digit OTP
            $otpExpiry = date('Y-m-d H:i:s', strtotime('+10 minutes')); // OTP expiry time (10 minutes from now)

            // Update OTP and expiry in the database
            $updateQuery = "UPDATE users SET otp = :otp, otpexpiry = :otpexpiry WHERE id = :id";
            $stmt = Connection::connect()->prepare($updateQuery);

            // Bind parameters for OTP and OTP expiry
            $stmt->bindParam(":otp", $otp, PDO::PARAM_INT);
            $stmt->bindParam(":otpexpiry", $otpExpiry, PDO::PARAM_STR);
            $stmt->bindParam(":id", $answer["id"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Send new OTP to the user's email
                $email = $answer["user"];
                $subject = "Your New OTP for Login";
                $message = "
                    <html>
                        <head>
                            <title>Your New OTP for Login</title>
                        </head>
                        <body>
                            <p>Hello " . $answer["name"] . ",</p>
                            <p>Your new One-Time Password (OTP) is <b>" . $otp . "</b></p>
                            <p>This OTP will expire in 10 minutes.</p>
                        </body>
                    </html>
                ";

                $from = "noreply@taipeiroyaltea.com"; // Updated sender email address
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: " . $from . "\r\n";

                // Send the email
                if (mail($email, $subject, $message, $headers)) {
                    echo '<br><div class="alert alert-success">A new OTP has been sent to your email.</div>';
                } else {
                    echo '<br><div class="alert alert-danger">Failed to resend OTP email.</div>';
                }
            } else {
                echo '<br><div class="alert alert-danger">Failed to update OTP</div>';
            }
        }
      ?>

      <!-- Resend OTP button -->
      <div class="row mt-4">
        <div class="col-xs-4">
          <form method="post">
            <button type="submit" name="resendOtp" class="btn btn-warning btn-block btn-flat">Resend OTP</button>
          </form>
        </div>
      </div>

    </form>
  </div>
</div>
