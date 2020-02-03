
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
        <div class="register-web-background">
            <div class="form-heading">
                REGISTRATION FORM
            </div>
            <form class="form-body" id="form" onsubmit="return stu_module.exposed_check_submit();">
                <div class="form-group form-group-style">
                    <label class="labelDsip">NAME<i style="color: rgba(255, 0, 0, 0.747);"> *</i></label>
                    <div class="form-row">
                        <div class="col">
                            <input type="text" id="nameFirst" name="nameFirst" class="form-control " placeholder="First name" value="">
                            <small id="nameFirstHelp" class="form-text invalidError"></small>
                        </div>
                        <div class="col">
                            <input type="text" id="nameSecond" name="nameSecond" class="form-control" placeholder="Middle name" value="" >
                            <small id="nameSecondHelp" class="form-text invalidError"></small>
                        </div>
                        <div class="col">
                            <input type="text" id="nameThird" name="nameThird" class="form-control" placeholder="Last name" value="" >
                            <small id="nameThirdHelp" class="form-text invalidError"></small>
                        </div>
                    </div>
                    <small id="nameHelp" class="form-text invalidError"></small>
                </div>
                
                <div class="form-row form-group-style">
                    <div class="form-group col">
                        <label for="fatherName"  class="labelDsip">FATHER'S NAME<i style="color: rgba(255, 0, 0, 0.747);"> *</i></label>
                        <input type="text" name="fatherName" class="form-control" id="fatherName" value="" placeholder="ENTER FULL NAME OF FATHER" >
                        <small id="fatherNameHelp" class="form-text invalidError"></small>
                    </div>
                    <div class="form-group col">
                        <label for="motherName"  class="labelDsip">MOTHER'S NAME</label>
                        <input type="text" class="form-control" name="motherName" value="" id="motherName" placeholder="ENTER FULL NAME OF MOTHER" >
                        <small id="motherNameHelp" class="form-text invalidError"></small>
                    </div>
                </div>
                <div class="form-row form-group-style">
                    <div class="col">
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="dob"  class="labelDsip">DATE OF BIRTH<i style="color: rgba(255, 0, 0, 0.747);"> *</i></label>
                                <input type="date" class="form-control" id="dob" name="date" >
                                <small id="dateHelp" class="form-text invalidError"></small>
                            </div>
                        </div>
                        
                            <div class="form-group">
                                <label  class="labelDsip" >GENDER</label>
                                <div class="custom-radio form-control" style="height: auto;">
                                    <div style="display: inline;padding: 0px 25px;" >
                                        <input type="radio" id="radioSexMale" name="customRadioInline1" value="male" class="custom-control-input" style="padding: 20px;">
                                        <label class="custom-control-label" for="radioSexMale">Male</label>
                                    </div >
                                    <div  style="display: inline;padding: 0px 25px;">
                                        <input type="radio" id="radioSexFemale" name="customRadioInline1" value="female" class="custom-control-input">
                                        <label class="custom-control-label" for="radioSexFemale">Female</label>
                                    </div >
                                    <div style="display: inline;padding: 0px 25px;">
                                        <input type="radio" id="radioSexOther" name="customRadioInline1" value="other" class="custom-control-input">
                                        <label class="custom-control-label" for="radioSexOther">Other</label>
                                    </div>
                                </div>
                                <small id="genderHelp" class="form-text invalidError"></small>
                            </div>
                        
                    </div>
                    <div class="form-group col">
                        <label for="address"  class="labelDsip">STREET NAME/HOUSE NO/PIN</label>
                        <textarea class="form-control" id="address" rows="5" name="presentAddress" style="overflow: hidden;" value="" ></textarea>
                        <small id="addressHelp" class="invalidError"></small>
                    </div>
                    
                </div>
                
                <div class="form-group form-group-style">
                    <label  class="labelDsip">CLASS X<i style="color: rgba(255, 0, 0, 0.747);"> *</i></label>
                    <div class="form-row">
                        <div class="col">
                            <select class="browser-default custom-select" name="classX" id="XBoard" >
                                <option selected="selected" value="NONE" name="Xoptions">Select the Board</option>
                                <option value="CBSE" name="Xoptions">CBSE</option>
                                <option value="ICSE" name="Xoptions">ICSE</option>
                                <option value="CHSE" name="Xoptions">CHSE</option>
                            </select>
                            <small id="classXHelp" class="invalidError"></small>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" id="XRoll" value="" placeholder="ENTER CLASS X ROLL NUMBER" name="XRoll" >
                            <small id="XRollHelp" class="invalidError"></small>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" id="Xmarks" value="" placeholder="Percentage(avoid '%' symbol)" name="XPerc" >
                            <small id="XPercHelp" class="invalidError"></small>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                        <div class="col">
                            <label  class="labelDsip">PASSWORD<i style="color: rgba(255, 0, 0, 0.747);"> *</i></label>
                                <input type="password" id="password" name="password" class="form-control" />
                                <small id="passwordHelp" class="invalidError"></small>
                        </div>
                        <div class="col"> 
                            <div class="form-group">
                                <label for="email" class="labelDsip">EMAIL<i style="color: rgba(255, 0, 0, 0.747);"> *</i></label>
                                <input type="text" class="form-control" id="email" placeholder="abc@example.com" name="email" >
                                <small id="emailHelp" class="form-text invalidError"></small>
                            </div>
                        </div>
                </div>
                <div class="form-group form-group-style">
                    <label for="statePlace" class="labelDsip">STATE AND DISTRICT</label>
                    <div class="form-row">
                        <div class="col">
                            <select class="browser-default custom-select" selected="selected" name="state" id="state">
                                <option value="NONE">Select your state</option>
                                <option value="WEST BENGAL">WEST BENGAL</option>
                                <option value="GUJRAT">GUJRAT</option>
                                <option value="ODISHA">ODISHA</option>
                                <option value="GOA">GOA</option>
                            </select>
                            <small id="stateHelp" class="invalidError"></small>
                        </div>
                        <div class="col"  class="labelDsip">
                            <select name="subcategory" id="subcategory" class="browser-default custom-select" >
                                <option value="NONE">-----</option>
                            </select>
                            <small id="districtHelp" class="invalidError"></small>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <label class="labelDsip">PHONE NUMBER<i style="color: rgba(255, 0, 0, 0.747);"> *</i></label>
                        <div class="form-row">
                            <div class="col-md-2">
                                <input type="number" style="padding-left: 0px;" class="form-control" id="phoneCountryCode" placeholder="  +91" disabled="disabled">
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" id="phoneNumber" value="" placeholder="ENTER 10 DIGIT NUMBER" name="phone" >
                            </div>
                        </div>
                        <small id="phoneHelp" class="invalidError"></small>
                    </div>
                    <div class="form-group col">
                        <label  class="labelDsip">HOBBIES</label>
                        <div class="form-row form-control" style="height: auto;" >
                            <div class="custom-control custom-checkbox" style="padding-right: 40px;"> 
                                <input type="checkbox" class="custom-control-input" id="customCheck1" name="hobby" value="football" >
                                <label class="custom-control-label" for="customCheck1">FootBall</label>
                            </div>
                            <div class="custom-control custom-checkbox" style="padding-right: 40px;">
                                <input type="checkbox" class="custom-control-input" id="customCheck2" name="hobby" value="cricket" >
                                <label class="custom-control-label" for="customCheck2">Cricket</label>
                            </div>
                            <div class="custom-control custom-checkbox" style="padding-right: 40px;">
                                <input type="checkbox" class="custom-control-input" id="customCheck3" name="hobby" value="other" >
                                <label class="custom-control-label" for="customCheck3">OTHER</label>
                            </div>
                        </div>
                        <small id="hobbyHelp" class="invalidError"></small>
                    </div>
                    <input type="number" id="sic" class="form-control" name="sic" hidden='hidden' value="" />
                    
                </div>
                <div class="row">
                    <div class="mand-info col-md-6">
                        <i>* marked fields are mandatory</i>
                    </div>
                    <div class="registraiton-info col-md-6" id="invalid_input_data">
                    </div>
                </div>
                <hr class="footer"/> 
                <div class="submit-btn" >
                    <span id="button-replace">
                        <input type="submit" class="btn btn-primary" id="submit_button" value="REGISTER"/>
                    </span>
                    <input type="button" class="btn btn-secondary" id="reset_btn" value="RESET">
                </div>
            </form>  
   
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/newUser.js?v=<?php echo time(); ?>"></script>
    <script>
        $(document).ready( function(){
            stu_module.exposed_init();
        });
    </script>
    <script src="boostrap/js/bootstrap.min.js"></script>
  </body>
</html>