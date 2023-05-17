 <?php 
 session_start();  
 $connect = mysqli_connect("localhost", "root", "", "test");  

 if (isset($_POST['submit'])) {
     $username = $_POST['username'];
     $password = $_POST['password'];
 
     if ($username == 'admin' && $password == '123456') {
         $_SESSION['logged_in'] = true;
         header('Location: admindashboard.php');
         exit;
     } else if (isset($error)) {
         echo '<p style="color: red;">' . $error . '</p>';
         unset($error);
     }
 }
 
 if (isset($_POST['back'])) {
     header('Location: index.php');
     exit;
 }

 if(isset($_POST["add_to_cart"]))  
 {  
      if(isset($_SESSION["shopping_cart"]))  
      {  
           $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");  
           if(!in_array($_GET["id"], $item_array_id))  
           {  
                $count = count($_SESSION["shopping_cart"]);  
                $item_array = array(  
                     'item_id'               =>     $_GET["id"],  
                     'item_name'               =>     $_POST["hidden_name"],  
                     'item_price'          =>     $_POST["hidden_price"],  
                     'item_quantity'          =>     $_POST["quantity"]  
                );  
                $_SESSION["shopping_cart"][$count] = $item_array;  
           }  
           else  
           {  
                echo '<script>alert("Stavka je već dodana")</script>';  
                echo '<script>window.location="index.php"</script>';  
           }  
      }  
      else  
      {  
           $item_array = array(  
                'item_id'               =>     $_GET["id"],  
                'item_name'               =>     $_POST["hidden_name"],  
                'item_price'          =>     $_POST["hidden_price"],  
                'item_quantity'          =>     $_POST["quantity"]  
           );  
           $_SESSION["shopping_cart"][0] = $item_array;  
      }  
 }  
 if(isset($_GET["action"]))  
 {  
      if($_GET["action"] == "delete")  
      {  
           foreach($_SESSION["shopping_cart"] as $keys => $values)  
           {  
                if($values["item_id"] == $_GET["id"])  
                {  
                     unset($_SESSION["shopping_cart"][$keys]);  
                     echo '<script>alert("Stavka je obrisana")</script>';  
                     echo '<script>window.location="index.php"</script>';  
                }  
           }  
      }  
 }  
 if(isset($_GET["action"]))  
 {  
      if($_GET["action"] == "delete_all")  
      {  
           unset($_SESSION["shopping_cart"]);  
           echo '<script>alert("Kupovina uspješno obavljena!")</script>';  
           echo '<script>window.location="index.php"</script>';  
      }  
 } 
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>Automobili</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <link rel="stylesheet" href="stil.css">
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
      </head>  
      <body> 
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
                    <a class="navbar-brand" href="#">Automobili</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Naslovna</a></li>
                        <li><a href="#">Automobili</a></li>
                        <li><a href="#">O nama</a></li>
                        <li><a href="#">Kontakt</a></li>
                        
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
          </nav> 
          <h2 align="center">Registracija za administratora</h2><br />
          <hr>
           <br />  
           <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
               <label for="username">Korisničko ime:</label>
               <input type="text" name="username" id="username">
               <br>
               <label for="password">Lozinka:</label>
               <input type="password" name="password" id="password">
               <br>
               <input type="submit" name="submit" value="Prijava">
          </form>
          <br/>
          <hr>
           <br /> 
           <div class="container">  
           
                <h2 align="center">Kupovna karta automobila</h2><br />  
                <?php  
                $query = "SELECT * FROM tbl_product ORDER BY id ASC";  
                $result = mysqli_query($connect, $query);  
                if(mysqli_num_rows($result) > 0)  
                {  
                     while($row = mysqli_fetch_array($result))  
                     {  
                ?> 
                
                <div class="row">  
                     <form method="post" action="index.php?action=add&id=<?php echo $row["id"]; ?>">  
                          <div class="cards">  
                               <img src="<?php echo $row["image"]; ?>" class="img-responsive" style="border-radius:25px;"/><br />  
                               <h4 class="text-info"><?php echo $row["name"]; ?></h4>  
                               <h4 class="text-danger">$ <?php echo $row["price"]; ?></h4>  
                               <input type="text" name="quantity" class="form-control" value="1" />  
                               <input type="hidden" name="hidden_name" value="<?php echo $row["name"]; ?>" />  
                               <input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>" />  
                               <input type="submit" name="add_to_cart" style="margin:5px;" class="btn btn-success" value="Dodaj u kartu" />  
                          </div>  
                     </form>  
                </div> 
               
                <?php  
                     }  
                }  
                ?>  
                 
                <br />  
                <hr>
                <br />  
                <h3>Detalji narudžbe</h3>  
                <div class="table-responsive">  
                     <table class="table table-bordered">  
                          <tr>  
                               <th width="40%">Naziv automobila</th>  
                               <th width="10%">Količina</th>  
                               <th width="20%">Cijena</th>  
                               <th width="15%">Ukupno</th>  
                               <th width="5%">Akcija</th>  
                          </tr>  
                          <?php   
                          if(!empty($_SESSION["shopping_cart"]))  
                          {  
                               $total = 0;  
                               foreach($_SESSION["shopping_cart"] as $keys => $values)  
                               {  
                          ?>  
                          <tr>  
                               <td><?php echo $values["item_name"]; ?></td>  
                               <td><?php echo $values["item_quantity"]; ?></td>  
                               <td>$ <?php echo $values["item_price"]; ?></td>  
                               <td>$ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>  
                               <td><a href="index.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Ukloni</span></a></td>  
                          </tr>  
                          <?php  
                                    $total = $total + ($values["item_quantity"] * $values["item_price"]);  
                               }  
                          ?>  
                          <tr>  
                               <td colspan="3" align="right">Total</td>  
                               <td align="right">$ <?php echo number_format($total, 2); ?></td>  
                               <td><a href="index.php?action=delete_all&id=<?php echo $values["item_id"]; ?>"><span class="text-success">Kupi</span></a><td>
                          </tr>  
                          <?php  
                          }  
                          ?>  
                     </table>  
                </div>  
           </div>  
           <br />  
      </body>  
 </html>


 
   