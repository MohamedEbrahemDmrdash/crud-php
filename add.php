<?php
session_start();
if (!isset($_SESSION['name'])) {
    die('ACCESS DENIED');
}

if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

require_once "pdo.php";

if ( isset($_POST['make']) && isset($_POST['year'])
     && isset($_POST['mileage']) && isset($_POST['model'])) {
       if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1){
         $_SESSION['error'] = "All fields are required";
         header("Location: add.php");
         return;
       }else {
         if (is_numeric($_POST['mileage'])&&is_numeric($_POST['year'])) {
           error_log("year is a number ".$_POST['year']);
           error_log("Mileage is a number ".$_POST['mileage']);
           $sql = "INSERT INTO autos (make, model , year, mileage)
                     VALUES (:make, :model , :year, :mileage)";
           $stmt = $pdo->prepare($sql);
           $stmt->execute(array(
               ':make' => htmlentities($_POST['make']),
               ':year' => htmlentities($_POST['year']),
               ':model' => htmlentities($_POST['model']),
               ':mileage' => htmlentities($_POST['mileage'])));
               $_SESSION['success'] = "added";
               header("Location: index.php");
               return;
       }else {
         error_log("year or mileage is not a number year=".$_POST['year']);
         error_log("Mileage or year is not a number mileage=".$_POST['mileage']);
         $_SESSION['error'] = "Mileage and Year must be numeric";
         header("Location: add.php");
         return;
       }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Mohamed Ibrahem PDO 1f376f49</title>
</head>
<body>

<div class="container">
<h1>
   <?php
      if ( isset($_SESSION['name']) ) {
          echo "<p>Tracking Autos for ";
          echo htmlentities($_SESSION['name']);
          echo "</p>\n";
      }
      ?>
</h1>
<p>
   <?php
   if (isset($_SESSION['error'])) {
     echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
     unset($_SESSION['error']);
   }
   ?>
</p>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"></p>
<p>Model:
<input type="text" name="model" size="40"/></p>
<p>Year:
<input type="text" name="year"></p>
<p>Mileage:
<input type="text" name="mileage"></p>
<input type="submit" name="add" value="Add">
<input type="submit" name="cancel" value="Cancel">
</form>

</div>
</body>
</html>
