<?php
namespace App\views\user;
?>
<h1 class="text-center">Register Form</h1>

<form method="post" action="/user/register">
    <br>
    <input type="hidden" name="csrfToken" value=<?= $data['csrfToken'] ?> />
    <div class="input-group mb-4">
        <div class="input-group-prepend">
            <span class="input-group-text" id="username">
                <i class="oi oi-person"></i>
            </span>
        </div>
        <input type="text" class="form-control" name="username" placeholder="Username" aria-label="Username" aria-describedby="username" required >
    </div>
    <div class="input-group mb-4">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="oi oi-lock-locked"></i>
            </span>
        </div>
        <input type="password" id="password" class="form-control" name="password" placeholder="Password" aria-label="Password" aria-describedby="Password" required >
    </div>
    <div class="input-group mb-4">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="oi oi-lock-locked"></i>
            </span>
        </div>
        <input type="password" id="confirm_password" class="form-control" name="confirm_password" placeholder="Confirm Password" aria-label="Confirm Password" aria-describedby="Confirm Password" required >
    </div>
    <div class="input-group mb-4">
        <div class="input-group-prepend">
            <span class="input-group-text" id="email">
                <i class="oi oi-envelope-closed"></i>
            </span>
        </div>
        <input type="text" class="form-control" name="email" placeholder="Email" aria-label="Email" aria-describedby="Email" required >
    </div>
    <div class="input-group mb-4">
        <div class="input-group-prepend">
            <span class="input-group-text" id="name">
                <i class="oi oi-person"></i>
            </span>
        </div>
        <input type="text" class="form-control" name="name" placeholder="First Name" aria-label="First Name" aria-describedby="First Name" required >
    </div>
    <div class="input-group mb-4">
        <div class="input-group-prepend">
            <span class="input-group-text" id="family">
                <i class="oi oi-person"></i>
            </span>
        </div>
        <input type="text" class="form-control" name="family" placeholder="Last Name" aria-label="Last Name" aria-describedby="Last Name" required >
    </div>
    <input type="submit" value="Submit" class="btn btn-primary btn-block btn-lg" />
</form>
<br>
<span>If you have an account <a href="/user/login"> Log in</a></span>
<script>
    $(document).ready(function() {
        $('#password, #confirm_password').on('keyup', function () {
            if ($('#confirm_password').val()) {
                if ($('#password').val() == $('#confirm_password').val()) {
                    $('#password').removeClass('border-danger').addClass('border-success').css('border-width', '2px');
                    $('#confirm_password').removeClass('border-danger').addClass('border-success').css('border-width', '2px');
                } else {
                    $('#password').removeClass('border-success').addClass('border-danger').css('border-width', '2px');
                    $('#confirm_password').removeClass('border-success').addClass('border-danger').css('border-width', '2px');
                }
            } else {
                $('#password').removeClass('border-danger').css('border-width', '1px');
                $('#confirm_password').removeClass('border-danger').css('border-width', '1px');
            }
        });
    });
</script>