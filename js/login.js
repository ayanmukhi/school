var login_js = ( function(){
    var user_err;
    var pass_err;
    var username;
    var password;
    var login;
    var credentials;
    var register;
    init = function() {
        user_err = 1;
        pass_err = 1;
        credentials = $("#credentials");
        username = $("#email");
        password = $("#password");
        login = $("#login_button");
        register = $("#register");
        register.on("click", function(){register_user()});
        login.on("click", function(){check_credentials()});
        username.on("change", function(){username_val()});
        username.on("keyup", function(){username_val()});
        password.on("change", function(){password_val()});
        password.on("keyup", function(){password_val()});
    };

    var register_user = function() {
        location = "register.php";
    }
    var check_credentials = function() {
        if(user_err == 0 && pass_err == 0) {
            
            var datum = JSON.stringify($("#form").serializeArray());

            console.log(datum);
            $.ajax({
                method:"POST",
                url:"php/slim/profile/index.php/api/v1/login/students",
                data: datum,
                success: function(result) {

                    console.log(result);
                    if(result.status == 200){
                        location = "profile.php";
                    }
                    else {
                        credentials.html("Incorrect credentials");
                    }
                }
            });
        }
        else {
            credentials.html("Incorrect credentials");
        }
    };

    var username_val = function() {
        if(( /\S+@\S+/.test(username.val()) == false ) || (username.val() == "")) {
            user_err = 1;
        }
        else {
            user_err = 0;
        }
    };
    var password_val = function() {
        //console.log("pass len : " + (password.val()).length);
        if(( /(?=[a-z])/.test(password.val()) == false) || ( /(?=[0-9])/.test(password.val()) == false) || ( /(?=[A-Z])/.test(password.val()) == false) || ( (password.val()).length < 4 )) {
            pass_err = 1;
        }
        else {
            pass_err = 0;
        }
    };
    return {
        exposed_init : init,
    }

})();