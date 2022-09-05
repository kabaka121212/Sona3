<?php
	// Set the values
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $notes = $_POST['notes'];
    if(!empty($name) && !empty($email) && !empty($phone) && !empty($address) && !empty($notes)){

        // reCAPTCHA validation
        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {

            // Google secret API
            $secretAPIkey = '6LfylFQhAAAAAIACQyrMtCGaAl8ZrFbzqqHr_4Ue';

            // reCAPTCHA response verification
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretAPIkey.'&response='.$_POST['g-recaptcha-response']);

            // Decode JSON data
            $response = json_decode($verifyResponse);
            if($response->success){
                // Database connection
                $conn = new mysqli('localhost','sona3elkhair_admin','allow2sona3elkhair','sona3elkhair_main');
                if($conn->connect_error){
                    echo "$conn->connect_error";
                    die("Connection Failed : ". $conn->connect_error);
                } else {
                    mysqli_set_charset($conn,"utf8");
                    $stmt = $conn->prepare("insert into volunteer(name, email, phone, address, notes) values(?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $name, $email, $phone, $address, $notes);
                    $execval = $stmt->execute();
                    $stmt->close();
                    $conn->close();
                    $response = array(
                        "status" => "alert-success",
                        "message" => "Your message have been sent."
                    );
                }
            }
            else {
                $response = array(
                    "status" => "alert-danger",
                    "message" => "Robot verification failed, please try again."
                );
            }       
        } 
        else{ 
            $response = array(
                "status" => "alert-danger",
                "message" => "Plese check on the reCAPTCHA box."
            );
        } 
    }  
    else{ 
        $response = array(
            "status" => "alert-danger",
            "message" => "All the fields are required."
        );
    }
?>