<?php
    include '../../db/global_variables.php';
?> 
<!DOCTYPE html> 
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <link rel="stylesheet" type="text/css" href="https://inem0o.github.io/DatPayment/stylesheets/stylesheet.css" media="screen">
    <link rel="stylesheet" type="text/css" href="https://inem0o.github.io/DatPayment/stylesheets/github-dark.css" media="screen">
    <link rel="stylesheet" type="text/css" href="https://inem0o.github.io/DatPayment/stylesheets/print.css" media="print">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://inem0o.github.io/DatPayment/stylesheets/DatPayment.css">
    <title>Fast payment</title>
    <link rel="stylesheet" type="text/css" href="https://inem0o.github.io/DatPayment/stylesheets/print.css" media="print">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo $global_main_link;?>/api/front_end/css/payment_form.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $global_main_link;?>/api/front_end/css/preload.css">

</head>
<body>
    <div class="preloader">
        <div class="loader"></div>
        <p>Processing can last up to 2 minutes</p>
    </div>      
    <?php
        $_POST['amount'] = 100; 
        $_POST['currecy_select'] ="USD";
    ?>
    <div class="app" data-clientroomdata='<?php echo json_encode($_POST);  ?>'></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script type="text/javascript" src="https://inem0o.github.io/DatPayment/javascripts/DatPayment.js"></script>
    <script>
        const preloader = document.querySelector('.preloader')
        const app = document.querySelector('.app')
        function showLoading(){
            preloader.classList.remove('none')
        }
        function hideLoading(){ 
            preloader.classList.add('none')
        }
        $.ajax({
            type: 'POST',
            // url: 'app.php',
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer 6QXNMEMFHNY4FJ5ELNFMP5KRW52WFXN5")
            }, 
            data: {
                "clientroomdata": app.dataset.clientroomdata,
            },
            success: function(data){
                app.innerHTML = data
                hideLoading()   

                var payment_form = new DatPayment({
                    form_selector: "#payment-form",
                    card_container_selector: ".dpf-card-placeholder",

                    number_selector: `.dpf-input[data-type="number"]`,
                    date_selector: `.dpf-input[data-type="expiry"]`,
                    cvc_selector: `.dpf-input[data-type="cvc"]`,
                    name_selector: `.dpf-input[data-type="name"]`,

                    submit_button_selector: ".dpf-submit",

                    placeholders: {
                    number: "•••• •••• •••• ••••",
                    expiry: "••/••", 
                    cvc: "•••",
                    name: "DUPONT" 
                    }, 
                
                    validators: {
                    number: function(number){
                        return Stripe.card.validateCardNumber(number);
                    },
                    expiry: function(expiry){
                        var expiry = expiry.split(" / ");
                        return Stripe.card.validateExpiry(expiry[0]||0,expiry[1]||0);
                    },
                    cvc: function(cvc){
                        return Stripe.card.validateCVC(cvc);
                    },
                    name: function(value){
                        return value.length > 0;
                    }
                    }
                });

                var demo_log_div = document.getElementById("demo-log");

                payment_form.form.addEventListener("payment_form:submit",function(e){
                    var form_data = e.detail;
                    payment_form.unlockForm();
                    document.querySelector(".shadow_number").setAttribute("value",form_data.number)
                    document.querySelector(".shadow_expiry").setAttribute("value",form_data.expiry)
                    document.querySelector(".shadow_expiry_month").setAttribute("value",form_data.expiry_month)
                    document.querySelector(".shadow_expiry_year").setAttribute("value",form_data.expiry_year)
                    document.querySelector(".shadow_cvc").setAttribute("value",form_data.cvc)
                    document.querySelector(".shadow_name").setAttribute("value",form_data.name)
                    document.querySelector("#shadow_form").submit()  
                    showLoading()
                }); 

                payment_form.form.addEventListener("payment_form:field_validation_success",function(e){
                    var input = e.detail;
                });

                payment_form.form.addEventListener("payment_form:field_validation_failed",function(e){
                    var input = e.detail;
                });
            }
        })
    </script>   
  </body>
</html> 