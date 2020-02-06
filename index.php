<?php
  session_start();
  if(isset($_SESSION['user'])){
    header("location: profile.php");
  }
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="boostrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css? <?php echo date('l jS \of F Y h:i:s A'); ?>">
    <script src="js/jquery-3.4.1.min.js"></script>
    <title>Hello NaKaMa!</title>
  </head>
  <body class="body">
    <div class="container" >
        
        <div class="header">
            <a href="index.php">
                <img src="images/shanks.png" class="headerImage">
            </a>
            <span class="headerTitle">
                HIGH SCHOOL OF ONE PIECE
            </span> 
        </div>
        
        <div class="web-background container">
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="login">
                    <div class="login-header" style="border-bottom: 1px solid white;padding-bottom: 15px;">
                        LOGIN
                        
                    </div>
                    <div class="login-body">
                        <form id="form">
                            <div class="form-group">
                              <label for="exampleInputEmail1">EMAIL ID</label>
                              <input type="text" class="form-control" id="username" placeholder="abc@example.com" name="username" >
                            </div>
                            <div class="form-group">
                                <label >PASSWORD</label>
                                <input type="password" id="password" name="password" class="form-control" />
                            </div>
                            
                            <div style="border-top: 1px solid white;padding-top: 20px;">
                              <button type="button" id="login_button" class="btn btn-primary">LOGIN</button>
                              <button type="button" class="register btn btn-secondary" id="register" value="REGISTER" >REGISTER</button>
                              <small id="credentials" class="invalidError" style="padding-left:30px"></small>
                            </div>
                        </form>
                    </div>
                </div>
                <div>
                    <div class="contact-details">
                        MAIL &emsp;&emsp;&emsp;: ayan.mukhi@hotmail.com<br/>
                        mobile no &nbsp;&nbsp;&nbsp;: 7364899994<br/>
                        address &emsp;&nbsp;&nbsp;&nbsp;: Shimotsuki Village (former)<br>
                                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Kuraigana Island (former, temporary)
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12 col-xs-12 image-slide">
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                      <div class="carousel-item active">
                        <img src="images/carasoul_images/1.jfif" class="d-block w-100" alt="...">
                      </div>
                      <div class="carousel-item">
                        <img src="images/carasoul_images/2.jpeg" class="d-block w-100" alt="...">
                      </div>
                      <div class="carousel-item">
                        <img src="images/carasoul_images/3.jpg" class="d-block w-100" alt="...">
                      </div>
                      <div class="carousel-item">
                        <img src="images/carasoul_images/4.png" class="d-block w-100" alt="...">
                      </div>
                      <div class="carousel-item">
                        <img src="images/carasoul_images/5.jpg" class="d-block w-100" alt="...">
                      </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  </div>
            </div>

            
        </div>
    </div>
    <script>
        $(document).ready( function(){
            login_js.exposed_init();
        });
    </script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/login.js?v=<?php echo time(); ?>"></script>
    <script src="boostrap/js/bootstrap.min.js"></script>

  </body>
</html>