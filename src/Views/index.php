<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
    <script
            src="https://code.jquery.com/jquery-3.7.0.min.js"
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"
            integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
</head>
<body class="h-auto ">
<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="form-container w-25">
        <div id="messageBox" class="text-center"></div>
        <form id="login-form" novalidate>
            <div class="form-group mb-3">
                <label for="firstName" class="mb-1">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name"
                       required>
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group mb-3">
                <label for="lastName" class="mb-1">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name"
                       required>
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group mb-3">
                <label for="email" class="mb-1">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group mb-3">
                <label for="password" class="mb-1">Password</label>
                <input type="password" class="form-control" id="password-main" name="password"
                       placeholder="Password (min 5 characters)" required minlength="5">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group mb-3">
                <label for="repeatPassword" class="mb-1">Repeat Password</label>
                <input type="password" class="form-control" id="repeatPassword-second" name="repeatPassword"
                       placeholder="Repeat password" required minlength="5">
                <div class="invalid-feedback"></div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        var messageBox = $("#messageBox");
        var form = $("#login-form");

        $.validator.addMethod("validate_email", function (value) {
            if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
                return true;
            } else {
                return false;
            }
        });

        form.validate({
            rules: {
                firstName: {
                    required: true,
                },
                lastName: {
                    required: true,
                },
                email: {
                    required: true,
                    validate_email: true
                },
                password: {
                    required: true,
                    minlength: 5
                },
                repeatPassword: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password-main"
                },
            },
            messages: {
                firstName: {
                    required: "Enter your name!"
                },
                lastName: {
                    required: "Enter your last name!"
                },
                email: {
                    required: "Enter your email!",
                    validate_email: "Please enter a valid email address."
                },
                password: {
                    required: "Enter password!",
                    minlength: "Min 5 characters"
                },
                repeatPassword: {
                    required: "Repeat your password!",
                    minlength: "Min 5 characters",
                    equalTo: "Passwords don't match"
                },
            },
            errorPlacement: function (error, element) {
                console.log('here')
                element[0].setCustomValidity('')
                var invalidFeedback = element.parents('.form-group').find('.invalid-feedback');
                form.addClass('was-validated');
                invalidFeedback.html(error.text());
                element[0].setCustomValidity('invalid')
            },
            success: function (label) {
                console.log('here')
                var inputName = label.attr('for');
                let inputElement;
                if (inputName.includes('password-main')) {
                    inputElement = $('input[name="password"]');
                } else if (inputName.includes('repeatPassword-second')) {
                    inputElement = $('input[name="repeatPassword"]');
                } else {
                    inputElement = $('input[name="' + inputName + '"]');
                }
                inputElement[0].setCustomValidity('')
            },
            submitHandler: function (element) {
                $.ajax({
                    type: "POST",
                    url: '/controller/ajax',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        console.log(response)
                        console.log(response.session)
                        if (response.success) {
                            messageBox.stop(true, true);
                            messageBox.empty().removeClass().addClass("text-center text-success").append(response.success).show();
                            $(form).hide();
                        } else if (response.error) {
                            messageBox.stop(true, true);
                            messageBox.empty().addClass("text-danger").append(response.error).show().fadeOut(3000);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        console.error("AJAX request error:", status, error);
                    }
                });
            }
        });
    });
</script>
</body>
</html>
