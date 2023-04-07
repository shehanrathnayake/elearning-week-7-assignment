<?php
    session_start();

    $username = $password = "";
    $username_err = $password_err = "";

    if(!empty($_SESSION['logged_in']) && $_SESSION == true){
        header('location: login.php');
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        require_once 'db-connect.php';

        //username validation
        if(empty(trim($_POST['username']))){
            $username_err = "Please enter username";
        }else{
            $username = trim($_POST['username']);
        }

        //password validation
        if(empty(trim($_POST['password']))){
            $password_err = "Please enter password";
        }else{
            $password = trim($_POST['password']);
        }

        //check if there are no errors and directing to index file

        if (empty($username_err) && empty($password_err)){

            $sql_user = "SELECT * FROM users WHERE username = '".$username."'";
            $result = $con->query($sql_user);

            if ($result->num_rows > 0){
                $password_hash = sha1($password);
                $user = $result->fetch_assoc();

                if ($user['password'] == $password_hash){
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user'] = array(
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'id' => $user['id']
                    );

                    header('location: index.php');
                    exit();

                } else $password_err = "Invalid password";
            }else $username_err = "Invalid username";
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
    <title>Login</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Login</h3>
                        <form action="" class="form" method="post">
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
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div> <!--d-grid-->
                        </form>
                    </div> <!--card-body-->
                </div> <!--card -->
            </div> <!--col-6-->
        </div> <!--row-->
    </div> <!--container -->
</body>
</html> 