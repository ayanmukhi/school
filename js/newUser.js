var stu_module = ( function() {
    var err = [];
    var varTemp;
    var selected_gender;
    var gender_help;
    var first;
    var first_help;
    var second;
    var second_help;
    var third;
    var third_help;
    var records_found;
    var father_name;
    var father_help;
    var motherName;
    var mother_help;
    var dob;
    var dob_help;
    var hobbyStr;
    var hobby_help;
    var x_board;
    var x_board_help;
    var x_roll;
    var x_roll_help;
    var x_perc;
    var x_perc_help;
    var password;
    var password_help;
    var dist;
    var dist_help;
    var state;
    var state_help;
    var address;
    var address_help;
    var email;
    var email_help;
    var total_err;
    var address;
    var email_help;
    var phone;
    var phone_help;
    var incorrect;
    var submit;

    init = function(){

        //initialising varriables
        err = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        selected_gender = "";
        gender_help = $("#genderHelp");
        first = $("#nameFirst");
        first_help = $("#nameFirstHelp");
        second = $("#nameSecond");
        second_help = $("#nameSecondHelp");
        third = $("#nameThird");
        third_help = $("#nameThirdHelp");
        father_name = $("#fatherName");
        father_help = $("#fatherNameHelp");
        mother_name = $("#motherName");
        mother_help = $("#motherNameHelp");
        dob = $("#dob");
        date_help = $("#dateHelp");
        hobbyStr = "";
        hobby_help = $("#hobbyHelp");
        x_board = $("#XBoard");
        x_board_help = $("#classXHelp");
        x_roll = $("#XRoll");
        x_roll_help = $("#XRollHelp");
        x_perc = $("#Xmarks");
        x_perc_help = $("#XPercHelp");
        password = $("#password");
        password_help = $("#passwordHelp");
        dist = $("#subcategory");
        dist_help = $("#districtHelp");
        state = $("#state");
        state_help = $("#stateHelp");
        address = $("#address");
        address_help = $("#addressHelp");
        email = $("#email");
        email_help = $("#emailHelp");
        phone = $("#phoneNumber");
        phone_help = $("#phoneHelp");
        hobbies = $("input[type='checkbox']");
        gender_options = $("input[name = 'customRadioInline1']");
        del = $("#del_btn");
        reset = $("#reset_btn");
        incorrect = $("#invalid_input_data");
        varTemp = 0;
        submit = $("#submit_button");
        

        //invoking event listeners functions
        dob.on("keyup", function(){age_check()});
        dob.on("change", function(){age_check()});
        email.on("keyup", function(){email_val()});
        email.on("change", function(){email_val()});
        phone.on("keyup", function(){phone_val()});
        phone.on("change", function(){phone_val()});
        state.on("change", function(){drop_down_list()});
        
        first.on("keyup", function() {nameFirst()});
        second.on("keyup", function() {nameSecond()});
        third.on("keyup", function() {nameThird()});
        father_name.on("keyup", function() {father()});
        hobbies.on("change", function(){hobby_val()});
        x_board.on("change", function() {x_board_val()});
        x_roll.on("keyup", function() {x_roll_val()});
        x_perc.on("keyup", function() {x_perc_val()});
        x_perc.on("change", function() {x_perc_val()});
        password.on("change", function() {pass_val()});
        password.on("keyup", function() {pass_val()});
        state.on("change", function(){state_val()});
        dist.on("change", function(){dist_val()});
        gender_options.on("change", function(){check_gender()});
        $("#reset_btn").on("click", function(){clear_fields()});
    };

    var pass_val = function() {
        if(( /(?=[a-z])/.test(password.val()) == false) || ( /(?=[0-9])/.test(password.val()) == false) || ( /(?=[A-Z])/.test(password.val()) == false) || ( (password.val()).length < 4 )) {
            err[11] = 1;
            password_help.html("PASSWORD FORMAT IS NOT RIGHT\nIT MUST HAVE :<br/>1. ATLEAST ONE CAPITAL AND ONE SMALL ALPHABET<br/>2. LENGHT OF THE PASSWORD MUST BE GREATER THAN 3<br/>3. SPECIAL CHARACTERS ALLOWED ARE (@,#,$,_)");
        }
        else {
            err[11] = 0;
            password_help.html("");
        }
    };


    

    //function to validate gender selection
    var check_gender = function() {    
        for( i = 0 ; i < gender_options.length ; i++ ) {
            if( gender_options[i].checked){
                err[8] = 0;
                selected_gender = gender_options[i].value;
                break;
            }
        }
        if( err[8] == 1 ) {
            gender_help.html("Select a gender");
        }
        else {
            gender_help.html("");
        }
    };

    //function to count total number of incorrect fields
    var count_error = function() {
        total_err = 0;
        for(i = 0 ; i < 17 ; i++ ) {
            if( err[i] == 1 ){
                total_err += 1;
                // total_err = 0;
            } 
        }
    };

    //function to submit data upon correct validation
    var check_submit =function() {
        nameThird();
        nameFirst();
        nameSecond();
        father();
        pass_val();
        x_board_val();
        x_roll_val();
        x_perc_val();
        phone_val();
        email_val();
        age_check();
        count_error();
        if(total_err == 0){
            var datum = JSON.stringify($("#form").serializeArray());
            console.log(datum);
            $.ajax({
                method : "POST",
                url: "php/slim/profile/index.php/api/v1/customer",
                data: datum,
                async: false,
                success: function(result) {
                    if( result.status == 200) { 
                        clear_fields(); 
                        location = "../index.php"; 
                    }
                }
            });
            return false;
            
        }
        else {
            incorrect.html("REGISTRATION FAILED INAPPROPRIATE DATA");
            incorrect.css("color", "red");
            return false;
            
        }
    };

    //function to clear all fields or reset them
    var clear_fields = function() {
        
        //clearing the fields
        for(i = 0 ; i < 17 ; i++ ) {
            err[i] = 1;
        }
        incorrect.html("");
        err[1] = 0;
        err[4] = 0;
        err[6] = 0;
        err[7] = 0;
        err[13] = 0;
        err[14] = 0;
        err[16] = 0;
        first.val("");
        second.val("");
        third.val("");
        father_name.val("");
        mother_name.val("");
        dob.val("");
        x_roll.val("");
        x_perc.val("");
        phone.val("");
        email.val("");
        address.val("");
        password.val("");
        x_board.val("NONE");
        state.val("NONE");
        dist.val("NONE");
        for( i  = 0 ; i < gender_options.length ; i++ ) {
            if( gender_options[i].checked ) {
                gender_options[i].checked = false;
                break;
            }
        }
        for ( i = 0 ; i < hobbies.length ; i++ ) {
            if( hobbies[i].checked ) {
                hobbies[i].checked = false;
            }
        }
        
        //clearing the error status of each fields
        first_help.html("");
        second_help.html("");
        third_help.html("");
        gender_help.html("");
        father_help.html("");
        mother_help.html("");
        date_help.html("");
        hobby_help.html("");
        x_roll_help.html("");
        x_perc_help.html("");
        x_board_help.html("");
        password_help.html("");
        phone_help.html("");
        email_help.html("");
        address_help.html("");
        state_help.html("");
        dist_help.html("");
    };

    //function to validate address field
    var address_val = function() {
        if(/^[a-zA-Z0-9][a-zA-Z 0-9-,:(\\\n)\/\\]*$/.test(address.val()) == false) {
            err[6] = 1;
            address_help.html("FILL OUT YOUR PRESENT ADDRESS");
        }
        else{
            err[6] = 0;
            address_help.html("");
        }
    };


    //function to validate date field
    var age_check = function() {
        if( age_gen() < 20 ){
            err[5] = 1;
            date_help.html("AGE MUST BE GREATER THAN 18");
        }
        else {
            err[5] = 0;
            date_help.html("");
        }
    };

    //function to calculate age
    var age_gen = function() {
        var today = new Date();
        birthDate = new Date(dob.val());
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if ((m < 0) || (m == 0) && (today.getDate() > birthDate.getDate())) {
            age = age - 1;
        }
        return age;
    };

    

    //function to validate email field
    var email_val = function() {
        duplicacy = false;
        $.ajax({
            url: "php/checkEmail.php",
            method: "POST",
            async:false,
            data: {email:email.val()},
            success: function(data) {
                if( data == 1) {
                    duplicacy = true;
                }
                else {
                    duplicacy = false;
                }
            }
        });
        if( duplicacy) {
            err[12] = 1;
            email_help.html("USER WITH THIS EMAIL IS ALREADY REGISTERED");
        }
        else if(( /\S+@\S+/.test(email.val()) == false ) || (email.val() == "")) {
            err[12] = 1;
            email_help.html("INVALID EMAIL");
        }
        else {
            err[12] = 0;
            email_help.html("correct");
        }
    };



    //function for dependent drop down of state and district
    var drop_down_list = function() {
        dist.html("");
        switch (state.val()) {
            case "NONE" :
                dist.append(new Option("-----","NONE"));
                break;
    
            case "WEST BENGAL" :
                dist.append(new Option("Please select the district","NONE"));
                dist.append(new Option("Alipurduar","Alipurduar"));
                dist.append(new Option("Bankura","Bankura"));
                dist.append(new Option("Birbhum","Birbhum"));
                break;
            
            case "GUJRAT" :
                dist.append(new Option("Please select the district","NONE"));
                dist.append(new Option("Ahmedabad","Ahmedabad"));
                dist.append(new Option("Amreli","Amreli"));
                break;
            case "ODISHA" :
                dist.append(new Option("Please select the district","NONE"));
                dist.append(new Option("Angul","Angul"));
                dist.append(new Option("Balangir","Balangir"));
                dist.append(new Option("Ganjam","Ganjam"));
                dist.append(new Option("Khordha","Khordha"));
                break;
            case "GOA" :
                dist.append(new Option("Please select the district","NONE"));
                dist.append(new Option("North Goa","North Goa"));
                dist.append(new Option("South Goa","South Goa"));
                break;
        }
    };

    
    //function to validate phone field
    var phone_val = function() {
        duplicacy = false;
        $.ajax({
            url: "php/checkPhone.php",
            method: "POST",
            async:false,
            data: {phone:phone.val()},
            success: function(data) {
                if( data == 1) {
                    duplicacy = true;
                }
                else {
                    duplicacy = false;
                }
            }
        });
        console.log(duplicacy);
        if(duplicacy) {
            err[15] = 1;
            phone_help.html("PHONE NUMBER ALREADY EXISTS");
        }
        else if( /^[0-9]\d{9}$/.test(phone.val()) == false ) {
            err[15] = 1;
            phone_help.html("INVALID PHONE NUMBER");
        }
        else {
            err[15] = 0;
            phone_help.html("");
        }
    };

    //function to validate district field
    var dist_val = function() {
        if( dist.val() == "NONE" ) {
            err[14] = 1;
            dist_help.html("SELECT YOUR DISTRICT");
        }
        else {
            err[14] = 0;
            dist_help.html("");
        }
    };


    //function to validate state field
    var state_val = function() {
        if( state.val() == "NONE" ) {
            err[13] = 1;
            state_help.html("SELECT YOUR STATE");
        }
        else {
            err[13] = 0;
            state_help.html("");
        }
    };


    //function to validate X percentage field
    var x_perc_val = function() {
        var Xperct = x_perc.val();
        if((( /^[0-9]([0-9]{0,1})(((.){1}([0-9]+))|())$/.test(Xperct) == false ) || ( Number(x_perc.val()) < 0 ) || (Number(x_perc.val()) > 100 ))){
            err[10] = 1;
            x_perc_help.html("INVALID X PERCENTAGE");
        }
        else {
            err[10] = 0;
            x_perc_help.html("");
        }
    };


    //function to validate X roll field
    var x_roll_val = function() {
        if( /^[a-zA-Z0-9][a-zA-Z0-9]*\/{0,1}[a-zA-Z0-9][a-zA-Z0-9]*$/.test(x_roll.val()) == false) {
            //alert("incorrect");
            err[9] = 1;
            x_roll_help.html("ENTER YOUR X ROLL");
        }
        else {
            err[9] = 0;
            x_roll_help.html("");
        }
    };


    //function to validate X board field
    var x_board_val = function() {
        if( x_board.val() == "NONE") {
            err[8] = 1;
            x_board_help.html("SELECT YOUR CLASS X BOARD");
        }
        else {
            err[8] = 0;
            x_board_help.html("");
        }
    };


    //function to validate hobby selection field
    var hobby_val = function() {
        hobby = 0;
        hobbyStr = "";
        for ( i = 0 ; i < hobbies.length ; i++ ) {
            if( hobbies[i].checked) {
                hobby = 1;
                hobbyStr += hobbies[i].value + " ";
                //alert(hobby_values[i].value);
            }
        }
    };

    //function to validate father name field
    var father = function() {
        //alert("fa");
        if( /^[a-zA-Z][a-zA-Z\s]*$/.test(father_name.val()) == false ) {
            err[3] = 1;
            father_help.html("INVALID FATHER NAME");
        }
        else {
            err[3] = 0;
            father_help.html("");
        }
    };


    //function to validate mother name field
    var mother = function() {
        if( /^[a-zA-Z][a-zA-Z\s]*$/.test(motherName.val()) == false ) {
            err[4] = 1;
            mother_help.html("INVALID MOTHER NAME");
        }
        else {
            err[4] = 0;
            mother_help.html("");
        }
    };

    //function to validate first name field
    var nameFirst = function() {
        if (/^[a-zA-Z][a-zA-Z]*$/.test(first.val()) == false) {
            err[0] = 1;
            first_help.html("INVALID FIRST NAME");
        }
        else {
            err[0] = 0;
            first_help.html("");
        }
    };

    //function to validate second name field
    var nameSecond = function() {
        if (/^[a-zA-Z]*$/.test(second.val()) == false) {
            err[1] = 1;
            second_help.html("INVALID SECOND NAME");
        }
        else {
            err[1] = 0;
            second_help.html("");
        }
    };


    //function to validate third name field
    var nameThird = function() {
        if (/^[a-zA-Z][a-zA-Z]*$/.test(third.val()) == false) {
            err[2] = 1;
            third_help.html("INVALID THIRD NAME");
        }
        else {
            err[2] = 0;
            third_help.html("");
        }
    };
  



    //making variables/objects public
    return {
        exposed_init : init,
        exposed_check_submit : check_submit,
        
    }
})();