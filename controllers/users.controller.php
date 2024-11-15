<?php

class ControllerUsers{

	/*=============================================
	USER LOGIN
	=============================================*/
	
public static function ctrUserLogin() {
    if (isset($_POST["loginUser"])) {
        
 if (preg_match('/^[a-zA-Z0-9@._-]+$/', $_POST["loginUser"]) && 
    preg_match('/^[a-zA-Z0-9]+$/', $_POST["loginPass"])) {

            $encryptpass = crypt($_POST["loginPass"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
            
            $table = 'users';
            $item = 'user';
            $value = $_POST["loginUser"];

            $answer = UsersModel::MdlShowUsers($table, $item, $value);

            if ($answer["user"] == $_POST["loginUser"] && $answer["password"] == $encryptpass) {

                if ($answer["status"] == 1) {
                    $_SESSION["loggedIn"] = "ok";
                    $_SESSION["otp_verified"] = "no"; // Default OTP status is not verified
                    $_SESSION["id"] = $answer["id"];
                    $_SESSION["name"] = $answer["name"];
                    $_SESSION["user"] = $answer["user"];
                    $_SESSION["photo"] = $answer["photo"];
                    $_SESSION["profile"] = $answer["profile"];

                    /*=============================================
                    Register date to know last_login
                    =============================================*/  
                    date_default_timezone_set("Asia/Manila");

                    $date = date('Y-m-d');
                    $hour = date('H:i:s');
                    $actualDate = $date . ' ' . $hour;

                    $item1 = "lastLogin";
                    $value1 = $actualDate;

                    $item2 = "id";
                    $value2 = $answer["id"];

                    // Update last login date
                    $lastLogin = UsersModel::mdlUpdateUser($table, $item1, $value1, $item2, $value2);

                    if ($lastLogin == "ok") {
                        // Generate OTP
                        $otp = rand(100000, 999999);  // 6-digit OTP
                        $otpExpiry = date('Y-m-d H:i:s', strtotime('+5 minutes')); // OTP expiry time (10 minutes from now)

                        // Update OTP and expiry in database
                        $updateQuery = "UPDATE users SET otp = :otp, otpexpiry = :otpexpiry WHERE id = :id";
                        $stmt = Connection::connect()->prepare($updateQuery);

                        // Bind parameters for OTP and OTP expiry
                        $stmt->bindParam(":otp", $otp, PDO::PARAM_INT);
                        $stmt->bindParam(":otpexpiry", $otpExpiry, PDO::PARAM_STR);
                        $stmt->bindParam(":id", $answer["id"], PDO::PARAM_INT);

                        if ($stmt->execute()) {
                            // Send OTP to user's email
                            // $email = $answer["user"];
                            // $subject = "Your OTP for Login";
                            // $message = "
                            //     <html>
                            //         <head>
                            //             <title>Your OTP for Login</title>
                            //         </head>
                            //         <body>
                            //             <p>Hello " . $answer["name"] . ",</p>
                            //             <p>Your One-Time Password (OTP) is <b>" . $otp . "</b></p>
                            //             <p>This OTP will expire in 5 minutes.</p>
                            //         </body>
                            //     </html>
                            // ";

                         
                            // $from = "noreply@taipeiroyaltea.com";
                            // $headers = "MIME-Version: 1.0" . "\r\n";
                            // $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                            // $headers .= "From: " . $from . "\r\n";

                            // // Send the email
                            // if (mail($email, $subject, $message, $headers)) {
                            //     // Redirect to OTP page
                                echo '<script>
                                      window.location = "home";
                                  </script>';
                            // } else {
                            //     echo '<br><div class="alert alert-danger">Failed to send OTP email.</div>';
                            // }

                        } else {
                            echo '<br><div class="alert alert-danger">Failed to update OTP</div>';
                        }
                    }

                } else {
                    echo '<br><div class="alert alert-danger">User is deactivated</div>';
                }

            } else {
                echo '<br><div class="alert alert-danger">User or password incorrect</div>';
            }
        }
    }
}




	/*=============================================
	CREATE USER
	=============================================*/
	
static public function ctrCreateUser(){

    if (isset($_POST["newUser"])) {
        
    if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ .,_-]+$/', $_POST["newName"]) && 
    preg_match('/^[a-zA-Z0-9@._-]+$/', $_POST["newUser"])) {

            /*=============================================
            VALIDATE IMAGE
            =============================================*/

            // Set default image path
            $photo = "views/img/users/default/prfplaceholder.png"; 
            
            // Check if a file was uploaded and not empty
            if (isset($_FILES["newPhoto"]["tmp_name"]) && !empty($_FILES["newPhoto"]["tmp_name"])){

                list($width, $height) = getimagesize($_FILES["newPhoto"]["tmp_name"]);
                
                $newWidth = 500;
                $newHeight = 500;

                /*=============================================
                CREATE FOLDER FOR EACH USER IF NOT EXISTS
                =============================================*/

                $folder = "views/img/users/".$_POST["newUser"];

                if (!file_exists($folder)) {
                    mkdir($folder, 0755);
                }

                /*=============================================
                PROCESS IMAGE BASED ON TYPE
                =============================================*/

                if ($_FILES["newPhoto"]["type"] == "image/jpeg") {

                    $randomNumber = mt_rand(100,999);
                    
                    $photo = "views/img/users/".$_POST["newUser"]."/".$randomNumber.".jpg";
                    
                    $srcImage = imagecreatefromjpeg($_FILES["newPhoto"]["tmp_name"]);
                    
                    $destination = imagecreatetruecolor($newWidth, $newHeight);

                    imagecopyresized($destination, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                    imagejpeg($destination, $photo);

                } elseif ($_FILES["newPhoto"]["type"] == "image/png") {

                    $randomNumber = mt_rand(100,999);
                    
                    $photo = "views/img/users/".$_POST["newUser"]."/".$randomNumber.".png";
                    
                    $srcImage = imagecreatefrompng($_FILES["newPhoto"]["tmp_name"]);
                    
                    $destination = imagecreatetruecolor($newWidth, $newHeight);

                    imagecopyresized($destination, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                    imagepng($destination, $photo);
                }
            }

            $table = 'users';

            $encryptpass = crypt($_POST["newPasswd"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

            $data = array('name' => $_POST["newName"],
                          'user' => $_POST["newUser"],
                          'password' => $encryptpass,
                          'profile' => $_POST["newProfile"],
                          'photo' => $photo);

            $answer = UsersModel::mdlAddUser($table, $data);

            if ($answer == 'ok') {

                echo '<script>
                
                swal({
                    type: "success",
                    title: "User added successfully!",
                    showConfirmButton: true,
                    confirmButtonText: "Close"

                }).then(function(result){

                    if(result.value){

                        window.location = "users";
                    }

                });
                
                </script>';

            }
        
        } else {

            echo '<script>
                
                swal({
                    type: "error",
                    title: "No special characters or blank fields",
                    showConfirmButton: true,
                    confirmButtonText: "Close"
        
                }).then(function(result){

                    if(result.value){

                        window.location = "users";
                    }

                });
                
            </script>';
        }
        
    }
}


	/*=============================================
	SHOW USER
	=============================================*/

	static public function ctrShowUsers($item, $value){

		$table = "users";

		$answer = UsersModel::MdlShowUsers($table, $item, $value);

		return $answer;
	}

	/*=============================================
	EDIT USER
	=============================================*/

	static public function ctrEditUser(){

		if (isset($_POST["EditUser"])) {
			
			if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["EditName"])){

				/*=============================================
				VALIDATE IMAGE
				=============================================*/

				$photo = $_POST["currentPicture"];

				if(isset($_FILES["editPhoto"]["tmp_name"]) && !empty($_FILES["editPhoto"]["tmp_name"])){

					list($width, $height) = getimagesize($_FILES["editPhoto"]["tmp_name"]);
					
					$newWidth = 500;
					$newHeight = 500;

					/*=============================================
					Let's create the folder for each user
					=============================================*/

					$folder = "views/img/users/".$_POST["EditUser"];

					/*=============================================
					we ask first if there's an existing image in the database
					=============================================*/

					if (!empty($_POST["currentPicture"])){
						
						unlink($_POST["currentPicture"]);

					}else{

						mkdir($folder, 0755);

					}

					/*=============================================
					PHP functions depending on the image
					=============================================*/

					if($_FILES["editPhoto"]["type"] == "image/jpeg"){

						/*We save the image in the folder*/

						$randomNumber = mt_rand(100,999);
						
						$photo = "views/img/users/".$_POST["EditUser"]."/".$randomNumber.".jpg";
						
						$srcImage = imagecreatefromjpeg($_FILES["editPhoto"]["tmp_name"]);
						
						$destination = imagecreatetruecolor($newWidth, $newHeight);

						imagecopyresized($destination, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

						imagejpeg($destination, $photo);

					}
					
					if ($_FILES["editPhoto"]["type"] == "image/png") {

						/*We save the image in the folder*/

						$randomNumber = mt_rand(100,999);
						
						$photo = "views/img/users/".$_POST["EditUser"]."/".$randomNumber.".png";
						
						$srcImage = imagecreatefrompng($_FILES["editPhoto"]["tmp_name"]);
						
						$destination = imagecreatetruecolor($newWidth, $newHeight);

						imagecopyresized($destination, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

						imagepng($destination, $photo);
					}

				}

				
				$table = 'users';

				if($_POST["EditPasswd"] != ""){

					if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["EditPasswd"])){

						$encryptpass = crypt($_POST["EditPasswd"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

					}

					else{

						echo '<script>
					
							swal({
								type: "error",
								title: "No special characters in the password or blank fields",
								showConfirmButton: true,
								confirmButtonText: "Close"

								}).then(function(result){
										
									if (result.value) {
						
										window.location = "users";

									}
								});
							
						</script>';
					}
				
				}else{

					$encryptpass = $_POST["currentPasswd"];
					
				}

				$data = array('name' => $_POST["EditName"],
								'user' => $_POST["EditUser"],
								'password' => $encryptpass,
								'profile' => $_POST["EditProfile"],
								'photo' => $photo);

				$answer = UsersModel::mdlEditUser($table, $data);

				if ($answer == 'ok') {
					
					echo '<script>
					
						swal({
							type: "success",
							title: "User edited succesfully!",
							showConfirmButton: true,
							confirmButtonText: "Close"

						 }).then(function(result){
							
							if (result.value) {

								window.location = "users";
							}

						});
					
					</script>';
				}
				else{
					echo '<script>
						
						swal({
							type: "error",
							title: "No special characters in the name or blank field",
							showConfirmButton: true,
							confirmButtonText: "Close"
							 }).then(function(result){
									
								if (result.value) {

									window.location = "users";
								
								}

							});
						
					</script>';
				}
			
			}	
		
		}
	
	}

	/*=============================================
	DELETE USER
	=============================================*/

	static public function ctrDeleteUser(){

		if(isset($_GET["userId"])){

			$table ="users";
			$data = $_GET["userId"];

			if($_GET["userPhoto"] != ""){

				unlink($_GET["userPhoto"]);				
				rmdir('views/img/users/'.$_GET["username"]);

			}

			$answer = UsersModel::mdlDeleteUser($table, $data);

			if($answer == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "The user has been succesfully deleted",
					  showConfirmButton: true,
					  confirmButtonText: "Close"

					  }).then(function(result){
					  	
						if (result.value) {

						window.location = "users";

						}
					})

				</script>';

			}		

		}

	}
	
}

