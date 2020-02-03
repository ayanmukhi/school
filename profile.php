<?php
      session_start();
      if(isset($_SESSION['user'])) {
        include "php/conn.php";
      }
      else {
        header("location: index.php");
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
    <link rel="stylesheet" href="css/style.css? <?php echo time(); ?>">
    <script src="js/jquery-3.4.1.min.js"></script>
    
    <title>Hello NaKaMa!</title>
  </head>
  <body >
    <div class="container" >
        
        <div class="header">
            <a href="index.php">
                <img src="images/shanks.png" class="headerImage">
            </a>
            <span class="headerTitle">
                HIGH SCHOOL OF ONE PIECE
            </span> 
        </div>
        
        <div class="web-background container" style="padding:5px 15px;height:fit-content">
          <div class="col-md-4 profile leftpanel" style="margin-left: 5px;margin-right:5px;">
              <div class="welcome">
              WELCOME : <?php echo "$_SESSION[user]"; ?>
              </div>
              <div class="profile-btn">
                <input type="button" id="editData" class="btn btn-secondary" value="EDIT PROFILE DATA" />
                <input type="button" id="del_btn" class="btn btn-danger" value="DELETE ACCOUNT" />
                <input type="button" id="logout" class="btn btn-info" value="LOGOUT"/>
              </div>
          </div>
          
          <div class="col-md-8 profile right-panel" style="margin-right:5px;">
              <div class="personalDate">
                <div class="form-group form-row">
                  <div class="col-md-3 labels">
                    <label>SIC</label><br>
                    <label>NAME</label><br>
                    <label>GENDER</label><br>
                    <label>FATHER NAME</label><br>
                    <label>MOTHER NAME</label><br>
                    <label>DATE OF BIRTH</label><br>
                    <label>MATRICULATION</label><br><br><br><br>
                    <label>STATE</label><br>
                    <label>DISTRICT</label><br>
                    <label>STREET ADDRESS</label><br>
                    <label>PHONE</label><br>
                    <label>EMAIL</label><br>
                    <label>PASSWORD</label><br>
                    <label>HOBBY</label><br>
                  </div>
                  <div class="col">
                    <label id="sic"></label><br>
                    <label id="nameFirst"></label><br>
                    <label id="gender"></label><br>
                    <label id="fatherName"></label><br>
                    <label id="motherName"></label><br>
                    <label id="dob"></label><br>
                    <label style="padding-right:10px">BOARD : </label><label id="XBoard"></label><br>
                    <label style="padding-right:10px">ROLL : </label><label id="XRoll"></label><br>
                    <label style="padding-right:10px;padding-bottom:10px">PERCENTAGE : </label><label id="Xmarks"></label><br>
                    <label id="state"></label><br>
                    <label id="subcategory"></label><br>
                    <label id="address"></label><br>
                    <label id="phoneNumber"></label><br>
                    <label id="email"></label><br>
                    <label id="password"></label><br>
                    <label id="hobbies"></label><br>
                  </div>
                    
                </div>
              </div>
          </div>   

        </div>
    </div>
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/profile.js?v=<?php echo time(); ?>"></script>
    <script>
        $(document).ready( function(){
            // alert(sic);
            // // urlstr = "php/slim/profile/index.php/api/v1/customer/" + sic;
            $.ajax({
              method:"get",
              url: "php/slim/profile/index.php/api/v1/customer/<?php echo $_SESSION['sic'] ?>",
              success: function(data) {
                if( data.status == 200 ) {
                  profile_module.exposed_filldata(data.result, data.date);
                  if(data.hasOwnProperty('hobby')) {
                    profile_module.exposed_hobbyfill(data.hobby);
                  }
                }
                if( data.status == 404) {
                  
                  alert("server problem");
                  //location= "../index.php";
                }
              }
            });
            profile_module.exposed_init();
        });
    </script>
    <!-- <script src="js/custum.js"></script>-->
  </body>
</html>