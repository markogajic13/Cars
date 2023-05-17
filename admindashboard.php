<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// CREATE
if (isset($_POST["create"])) {
    $name = $_POST["name"];
    $image = $_POST["image"];
    $price = $_POST["price"];

    $result = mysqli_query($conn, "SELECT max(id) as max_id FROM tbl_product");
    $row = mysqli_fetch_assoc($result);
    $id = $row['max_id'] + 1;

    $sql = "INSERT INTO tbl_product (id, name, image, price)
    VALUES ('$id', '$name', '$image', '$price')";

    if (mysqli_query($conn, $sql)) {
        echo "Novi zapis uspješno dodan u bazu.";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}


// READ
if (isset($_POST["read"])) {
    $sql = "SELECT id, name, image, price FROM tbl_product";
    $result = mysqli_query($conn, $sql);
}

// UPDATE
if (isset($_POST["update"])) {
    $id = $_POST["id"];
    $new_name = $_POST["new_name"];
    $new_price = $_POST["new_price"];
    $new_image = $_POST["new_image"];
    
    $sql = "SELECT name, image, price FROM tbl_product WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    $name = !empty($new_name) ? $new_name : $row['name'];
    $price = !empty($new_price) ? $new_price : $row['price'];
    $image = !empty($new_image) ? $new_image : $row['image'];
    
    $sql = "UPDATE tbl_product SET name='$name', image='$image', price='$price' WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "Zapis uspješno ažuriran.";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    
}

// DELETE
if (isset($_POST["delete"])) {
    $id = $_POST["id"];
    
    $sql = "DELETE FROM tbl_product WHERE id=$id";
    
    if (mysqli_query($conn, $sql)) {
        echo "Zapis uspješno obrisan<br>";
        
        // Count existing IDs
        $count_sql = "SELECT COUNT(*) as count_id FROM tbl_product";
        $result = mysqli_query($conn, $count_sql);
        $count_data = mysqli_fetch_assoc($result);
        $count_id = $count_data['count_id'];
        
        // Continue in the following order
        if ($count_id > 0) {
            echo "Ima još " . $count_id . " zapisa u bazi podataka";
        }
        else {
            echo "Nema više zapisa u bazi podataka";
        }
    }
    else {
        echo "Greška: " . $sql . "<br>" . mysqli_error($conn);
    }
}
mysqli_close($conn);

if (isset($_POST['back'])) {
    header('Location: index.php');
    exit;
}

?>
 <!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <link rel="stylesheet" href="stil.css">
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
    <title>Administrator</title>
</head>
<body>
<form method="post">
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Administrator</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><input type="submit" name="back" style="margin:5px;" class="btn btn-success" value="Povratak" /></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
</form>
<br>
<hr>
<h2>Kreiraj novi zapis u bazu</h2>
<form action="" method="post">
    <label for="name">Ime automobila:</label>
    <input type="text" id="name" name="name">
    <br><br>
    <label for="image">Putanja slike:</label>
    <input type="text" id="image" name="image">
    <br><br>
    <label for="price">Cijena automobila:</label>
    <input type="text" id="price" name="price">
    <br><br>
    <input type="submit" name="create" value="Dodaj novi">
</form>



<br><hr><br>

<h2>Pročitaj sve zapise iz baze</h2>
<form action="" method="post">
    <input type="submit" name="read" value="Pročitaj">
    <?php
        if (isset($_POST['read'])) {
            echo '<table class="table table-bordered" style="padding:5px; margin:5px;">
            <tr>
                <th>ID</th>
                <th>Ime automobila</th>
                <th>Slika automobila</th>
                <th>Cijena automobila</th>
            </tr>';
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr><td>" . $row["id"]. "</td><td>" . $row["name"]. "</td><td>" . $row["image"]. "</td><td>" . $row["price"]. "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Nema razultata</td></tr>";
            }
            echo '</table><br>
            <form action="" method="post">
                <input type="submit" name="read_done" value="Pročitano">
            </form>';
        }
        if (isset($_POST['read_done'])) {
            echo '<br><br><br><br>';
        }
    ?>
</form>




<br><hr><br>

<h2>Ažuriraj postojeći zapis iz baze</h2>
<form action="" method="post">
    <label for="id">ID automobila:</label>
    <input type="text" id="id" name="id">
    <br><br>
    <label for="new_name">Novi naziv automobila:</label>
    <input type="text" id="new_name" name="new_name">
    <br><br>
    <label for="new_price">Nova cijena automobila:</label>
    <input type="text" id="new_price" name="new_price">
    <br><br>
    <label for="new_image">Nova slika automobila:</label>
    <input type="text" id="new_image" name="new_image">
    <br><br>
    <input type="submit" name="update" value="Ažuriraj">
</form>

<br><hr><br>

<h2>Obriši zapis iz baze</h2>
<form action="" method="post">
    <label for="id">ID automobila:</label>
    <input type="text" id="id" name="id">
    <br><br>
    <input type="submit" name="delete" value="Obriši">
</form> 


</body>
</html>