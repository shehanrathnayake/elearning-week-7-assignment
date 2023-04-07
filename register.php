<?php
$firstname = $lastname = $username = $password = '';
$firstname_err = $lastname_err = $username_err = $password_err = $con_password_err = '';
$validation_failed = false;

if(!empty($_SESSION['logged_in']) && $_SESSION == true){
    header('location: login.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    require_once 'db-connect.php';

    //first name validation
    if(empty(trim($_POST['firstname']))){
        $firstname_err = "Please enter first name";
        $validation_failed = True;
    }else{
        $firstname = trim($_POST['firstname']);
    }

    //last name validation
    if(empty(trim($_POST['lastname']))){
        $lastname_err = "Please enter last name";
        $validation_failed = True;
    }else{
        $lastname = trim($_POST['lastname']);
    }

    //username validation
    if(empty(trim($_POST['username']))){
        $username_err = "Please enter username";
        $validation_failed = True;
    }else{

        //checking duplicate usernames
        $username = trim($_POST['username']);
        $sql_username = "SELECT * FROM users WHERE username = '".$username."'";
        $result_user = $con->query($sql_username);

        if ($result_user->num_rows > 0) {
            $validation_failed = true;
            $username_err = "Username is already taken";
        }
        
    }

    //password validation
    if(empty(trim($_POST['password']))){
        $password_err = "Please enter password";
        $validation_failed = True;
    }else{
        $password = trim($_POST['password']);
    }

    //confirm password validation
    if(empty(trim($_POST['con_password']))){
        $con_password_err = "Please confirm password";
        $validation_failed = True;

    }else{
        $con_password = trim($_POST['con_password']);

        if($password != $con_password){
            $con_password_err = "Password should be matched";
            $validation_failed = True;
        }
    }

    //Adding user to the database

    if (!$validation_failed){

        $stm = $con->prepare("INSERT INTO users (first_name, last_name, username, password, registered_date, status) VALUES (?, ?, ?, ?, ?, ?)");
        $status = 'active';
        $registered_date = time();
        $hashed_password = sha1($password);
        $stm->bind_param('ssssis', $firstname, $lastname, $username, $hashed_password, $registered_date, $status);
        $result = $stm->execute();

        if ($result){
            header ('location: login.php');
        }else{
            $error = 'Something went wrong';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <title>Register</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Register</h3>

                        <form action="" class="form" method="post">

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger" role="alert">
                                <?=$error?>
                                </div>
                            <?php endif; ?>

                            <div class="form-floating mb-3">
                                <input type="text" name="firstname" class="form-control <?=empty($firstname_err) ?'': 'is-invalid'?> " id="firstname" value="<?=$firstname?>">
                                <label for="firstname">First name</label>
                                <div class="invalid-feedback"><?=$firstname_err?></div>
                            </div> <!--form-floating-->

                            <div class="form-floating mb-3">
                                <input type="text" name="lastname" class="form-control <?=empty($lastname_err) ?'': 'is-invalid'?> " id="lastname" value="<?=$lastname?>">
                                <label for="lastname">Last Name</label>
                                <div class="invalid-feedback"><?=$lastname_err?></div>
                            </div> <!--form-floating-->

                            <div class="form-floating mb-3">
                                <input type="text" name="username" class="form-control <?=empty($username_err) ?'': 'is-invalid'?> " id="username" value="<?=$username?>">
                                <label for="username">Username</label>
                                <div class="invalid-feedback"><?=$username_err?></div>
                            </div> <!--form-floating-->

                            <div class="form-floating mb-3">
                                <input type="password" name="password" class="form-control <?=empty($password_err)?'':'is-invalid'?> " id="password">
                                <label for="password">Password</label>
                                <div class="invalid-feedback"><?=$password_err?></div>
                            </div> <!--form-floating-->

                            <div class="form-floating mb-3">
                                <input type="password" name="con_password" class="form-control <?=empty($con_password_err)?'':'is-invalid'?> " id="con_password">
                                <label for="con_password">Confirm Password</label>
                                <div class="invalid-feedback"><?=$con_password_err?></div>
                            </div> <!--form-floating-->

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div> <!--d-grid-->

                        </form>

                    </div> <!--card-body-->
                </div> <!--card -->
            </div> <!--col-6-->
        </div> <!--row-->
    </div> <!--container -->
</body>
</html> 