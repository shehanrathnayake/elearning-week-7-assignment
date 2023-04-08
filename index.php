<?php
session_start();

if(empty($_SESSION['logged_in'])){
    header('location: login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <title>Index</title>
</head>
<body>
    <header>
        <div class="d-flex flex-column align-items-end">
            <a href="logout.php" class="btn btn-primary">Log out</a>
        </div>
        
    </header>
    <h1 class="row justify-content-center mt-3">
        Hello <?=$_SESSION['user']['first_name']?> <?=$_SESSION['user']['last_name']?>!
    </h1>

    <section class="all_users d-flex flex-column align-items-center">
        <h2 class="mt-5">All Users In The List</h2>
        <div class="mt-5">
            <?php
            require_once 'db-connect.php';

            // retrieve user data from the database
            $sql_all = "SELECT * FROM users";
            $result_all = $con->query($sql_all);
        
            // create table headers
            echo "<table class='table pe-25'>";
            echo "<tr><th>Full Name</th><th>Username</th><th>Registered Date</th></tr>";
        
            // loop through user data and create table rows
            if ($result_all->num_rows > 0) {
                while ($row = $result_all->fetch_assoc()) {
        
                    echo "<tr><td>" . $row["first_name"] . " " . $row["last_name"] . "</td><td>" . $row["username"] . "</td><td>" . date('Y-m-d H:i:s',$row['registered_date']) . "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No users found</td></tr>";
            }
        
            // close the table
            echo "</table>";

            ?>


        </div>
        

    </section>
    


</body>
</html>