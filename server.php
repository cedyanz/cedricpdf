<?php


// initializing variables
$username = "";
$email    = "";
$fullname = "";

$errors = array(); 


// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'xpo');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // if (strlen($username) >=15 ) { array_push($errors, "USER LENGTH INCORRECT"); }

  // if (!preg_match("/^[a-zA-Z-' ]*$/",$username)) {
  //   // array_push($name_errors, "Only letters and white space allowed"); 
  //   echo "<script type = \"text/javascript\">
  //   alert(\"username: Only letters and white space allowed!\");
  //   window.location = (\"register.php\")
  //   </script>";
  // } 
  





  

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) 
     {
      // echo "<script type = \"text/javascript\">
      // alert(\"username/email already exist\");
      // window.location = (\"register.php\")
      // </script>";
      array_push($errors, "username already exists");
    }

    if ($user['email'] === $email); {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username, email, password) 
  			  VALUES('$username', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['user_name'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: login.php');
  }
}

// ... 
// ... 

// LOGIN USER
if (isset($_POST['login_user'])) {
  $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    // $username = mysqli_real_escape_string($db, $_POST['username']);
  
  
    if (empty($email)) {
        array_push($errors, "email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
  
    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $results = mysqli_query($db, $query);
        $rws = $results->fetch_assoc();
        if (mysqli_num_rows($results) == 1) {
    session_start();

          $_SESSION['email'] = $email;
          $_SESSION['user_name'] = $rws['username'];
          $_SESSION['success'] = "";
          header('location: index.php');
        }else {
          echo "<script type = \"text/javascript\">
          alert(\"Wrong email/password\");
          window.location = (\"login.php\")
          </script>";
        }
    }
  }


  if (isset($_POST['apply'])) {
    // receive all input values from the form
    $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
    $parent_name = mysqli_real_escape_string($db, $_POST['parent_name']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $last_degree = mysqli_real_escape_string($db, $_POST['last_degree']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $country = mysqli_real_escape_string($db, $_POST['country']);
    $phoneno = mysqli_real_escape_string($db, $_POST['phoneno']);
    $course = mysqli_real_escape_string($db, $_POST['course']);
    $university = mysqli_real_escape_string($db, $_POST['university']);
    
    $pdf_files =[];

// Count total files 
 $countfiles = count($_FILES['pdf_file']['name']); 

// Looping all files  
  for($i=0;$i<$countfiles;$i++){   
    $filename = $_FILES['pdf_file']['name'][$i];     
    // Upload file 
      move_uploaded_file($_FILES['pdf_file']['tmp_name'][$i], './admin/uploads/'.$filename);  
      array_push($pdf_files, $filename);
  }  

  $pdf = implode (", ", $pdf_files);
    // $allowedExts = array("pdf");
    
    //$upload_pdf = $_POST['pdf_file'];
    // $temp = explode(".", $_FILES['pdf_file']['name']);
    // $extension = end($temp);
    // $upload_pdf=$_FILES['pdf_file']['name'];
    // move_uploaded_file($_FILES['pdf_file']['tmp_name'],"uploads/" .$_FILES['pdf_file']['name']);
    // $pdf_file = $_FILES['pdf_file']['name'];
    if (empty($fullname) AND empty($parent_name) AND empty($address) AND empty($last_degree) AND empty($email) AND empty($country) AND empty($phoneno) AND empty($ $pdf_files)) 
    {
      //array_push($errors, "email is required");
      echo "<script type = \"text/javascript\">
            alert(\"Please fill properly the form\");
            window.location = (\"application.php\")
            </script>";
    }
    $user_check_query = "SELECT * FROM applications WHERE fullname='$fullname' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);
    
    if ($user) { // if user exists
      if ($user['fullname'] === $fullname) {array_push($errors, "fullname already exist"); } {
        echo "<script type = \"text/javascript\">
        alert(\"fullname/email already exist\");
        window.location = (\"application.php\")
        </script>";
      }
  
      if ($user['email'] === $email); {
        array_push($errors, "email already exists");
      }
    }
  
    
    // if (!preg_match("/^[a-zA-Z-' ]*$/",$fullname)) {
    //   array_push($errors, "Only letters and white space allowed"); 
    //    //array_push($errors, "email is required");
    //    echo "<script type = \"text/javascript\">
    //    alert(\"fullname: Only letters and white space allowed!\");
    //    window.location = (\"application.php\")
    //    </script>";
    // } 
    // if (!preg_match("/^[a-zA-Z-' ]*$/",$parent_name)) {
    //   // array_push($errors, "Only letters and white space allowed");
    //   echo "<script type = \"text/javascript\">
    //   alert(\"parent name: Only letters and white space allowed!\");
    //   window.location = (\"application.php\")
    //   </script>"; 
    // } 
    // if (!preg_match("/^[a-zA-Z-' ]*$/",$country)) {
    //   array_push($errors, "Only letters and white space allowed"); 
    //   echo "<script type = \"text/javascript\">
    //   alert(\"Only letters and white space allowed!\");
    //   window.location = (\"application.php\")
    //   </script>";
    // } 
    // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //   // array_push($errors, "invalid email format");                   ^([\w\.]*) 
    //   echo "<script type = \"text/javascript\">
    //   alert(\"invalid email format\");
    //   window.location = (\"application.php\")
    //   </script>";
    // }
    // if (!preg_match('/^[\d] {3}-[\d] {3}-[\d] {4} $/',$phoneno )) {
    //   // array_push($ph_errors, "phone format invalid"); 
    //   echo "<script type = \"text/javascript\">
    //   alert(\"invalid phoneno format\");
    //   window.location = (\"application.php\")
    //   </script>";
    // } 
    // else{
    //   echo "<script type = \"text/javascript\">
    //   alert(\"Phoneno format incorrect\");
    //   window.location = (\"application.php\")
    //   </script>";
    // }
   

    // first check the database to make sure 
    // a user does not already exist with the same username and/or email
    $user_check_query = "SELECT * FROM applications WHERE fullname='$fullname' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    mysqli_fetch_assoc($result);
    
    if (isset($user)) { // if fullname exists
      if ($user['fullname'] === $fullname) {
        array_push($errors, "application already exists");
      }
  
      if ($user['email'] === $email) {
        echo "<script type = \"text/javascript\">
        alert(\"email already exist\");
        window.location = (\"application.php\")
        </script>";
      }
    }
  
    // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
      $query ="INSERT INTO applications(fullname,parent_name,address,last_degree,email,country,phoneno,course,university,pdf_file,status) VALUES('$fullname','$parent_name','$address','$last_degree','$email','$country','$phoneno','$course','$university','$pdf','pending')";
     
      if(mysqli_query($db, $query)){
        // header("location: index.php?uploadssuccessfull");
          //array_push($errors, "email is required");
      echo "<script type = \"text/javascript\">
      alert(\"Upload Successful\");
      window.location = (\"index.php\")
      </script>";
      }
      else{
        echo "<script type = \"text/javascript\">
        alert(\"Please try again\");
        window.location = (\"application.php\")
        </script>";
      }
    }
    
  }

  
  if(isset($_POST['save'])){
    // receive all input values from the form
    $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
    $parent_name = mysqli_real_escape_string($db, $_POST['parent_name']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $last_degree = mysqli_real_escape_string($db, $_POST['last_degree']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $country = mysqli_real_escape_string($db, $_POST['country']);
    $phoneno = mysqli_real_escape_string($db, $_POST['phoneno']);
    $course = mysqli_real_escape_string($db, $_POST['course']);
    $university = mysqli_real_escape_string($db, $_POST['university']);
   
      
    $pdf_files =[];

// Count total files 
 $countfiles = count($_FILES['pdf_file']['name']); 

      
  for($i=0;$i<$countfiles;$i++){   
    $filename = $_FILES['pdf_file']['name'][$i];     
    // Upload file 
      move_uploaded_file($_FILES['pdf_file']['tmp_name'][$i], './admin/uploads/'.$filename);  
      array_push($pdf_files, $filename);
  }  

  $pdf = implode (", ", $pdf_files);
  if (empty($fullname) AND empty($email) ) 
  {
      //array_push($errors, "email is required");
      echo "<script type = \"text/javascript\">
            alert(\"Please fill properly the form\");
            window.location = (\"application.php\")
            </script>";
    }
    
    if ($user) { // if user exists
      if ($user['fullname'] === $fullname) {array_push($errors, "fullname already exist"); } {
        echo "<script type = \"text/javascript\">
        alert(\"fullname/email already exist\");
        window.location = (\"application.php\")
        </script>";
      }
  
      if ($user['email'] === $email); {
        array_push($errors, "email already exists");
      }
    }
     
    if (!preg_match("/^[a-zA-Z-' ]*$/",$fullname)) {
      array_push($errors, "Only letters and white space allowed"); 
       //array_push($errors, "email is required");
       echo "<script type = \"text/javascript\">
       alert(\"fullname: Only letters and white space allowed!\");
       window.location = (\"application.php\")
       </script>";
    } 
    if (!preg_match("/^[a-zA-Z-' ]*$/",$parent_name)) {
      array_push($errors, "Only letters and white space allowed");
      echo "<script type = \"text/javascript\">
      alert(\"parent name: Only letters and white space allowed!\");
      window.location = (\"application.php\")
      </script>"; 
    } 
    if (!preg_match("/^[a-zA-Z-' ]*$/",$country)) {
      array_push($errors, "Only letters and white space allowed"); 
      echo "<script type = \"text/javascript\">
      alert(\"Only letters and white space allowed!\");
      window.location = (\"application.php\")
      </script>";
    } 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      array_push($errors, "invalid email format"); 
      echo "<script type = \"text/javascript\">
      alert(\"invalid email format\");
      window.location = (\"application.php\")
      </script>";
    }
    
    
   
    // first check the database to make sure 
    // a user does not already exist with the same username and/or email
    $user_check_query = "SELECT * FROM applications_draft WHERE fullname='$fullname' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    mysqli_fetch_assoc($result);
    
    if ($user) { // if fullname exists
      if ($user['fullname'] === $fullname) {
        array_push($errors, "application already exists");
      }
  
      if ($user['email'] === $email) {
        array_push($errors, "email already exists");
      }
    }
  
    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {
      $query ="INSERT INTO applications_draft(fullname,parent_name,address,last_degree,email,country,phoneno,course,university,pdf_file,status) VALUES('$fullname','$parent_name','$address','$last_degree','$email','$country','$phoneno','$course','$university','$pdf_file','pending')";
     
      if(mysqli_query($db, $query)){
        header("location: index.php?uploadssuccessfull");
      }
      else{
        echo "<script type = \"text/javascript\">
        alert(\"Please try again\");
        window.location = (\"application.php\")
        </script>";
      }
     
     
    }
   

    if(isset($_POST['pay']))
    {
      session_start();
      
      $db=mysqli_connect('localhost','root','','xpo');
      $date=date("Y-m-d");
      $session=$_SESSION['email'];
      $mysql="SELECT * FROM applications WHERE email='$session'";
      $quer=mysqli_query($db,$mysql);
      $mh = curl_multi_init();
      $fullname = $_POST['fullname'];
      $email = $_POST['email'];
      $amt = mysqli_real_escape_string($db, $_POST['amt']);
      $cardno = $_POST['cardno'];
      $cardnojson = json_encode($cardno);
      $cvc = $_POST['cvc'];
      $expm = $_POST['expm'];
      $expy = $_POST['expy'];
            //array of data to POST
      $request_contents = array();
      //array of URLs
      $urls = array();
      //array of cURL handles
      $chs = array();
      $query = "SELECT * FROM applications WHERE email='$email'";
      $res = mysqli_query($db,$query);
      $row = mysqli_fetch_assoc($res);
      $custId =$row['id'];
    
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://secure.fusebill.com/v1/paymentMethods/creditCard');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "{customerId:'$custId',cardNumber:$cardnojson,firstName:'$fullname',lastName:'',expirationMonth:$expm,expirationYear:$expy,cvv:$cvc,address1:'232 Herzberg Road',address2:'Suite 203',countryId:124,stateId:9,city:'Kanata',postalZip:'K2K 2A1',isDefault:true}");
      curl_setopt($ch, CURLOPT_POST, 1);
      $header = array();
      $header[] = 'Content-Type: application/json';
      $header[] = 'Authorization: Basic MDppM1VlUmJyWXI2dzNPWFU1Y1ZhaXVhR2hxeWJKNjlJQmJaaU9KdDFKSzdoYW9FQXg2YllXbURCd2VDeUtJQVZq';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      $result = curl_exec($ch);
      if (curl_errno($ch)) {
        echo 'eror:' .curl_error($ch);
      }
      else{
      $output = curl_exec($ch); 
      $data = json_decode($output,true);
      echo $data;
      $paymentMethodId = $data['id'];
      }
      curl_close ($ch);
    // if($data){
    //   header("location: login.php?uploadssuccessfull");
    // }
    while($data = mysqli_fetch_array($quer))
    {
      
    }
  
    }

    
   

  }
  
  ?>