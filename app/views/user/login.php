<?php
namespace App\views\user;
?>
<h1 class="text-center">Login Form</h1>

<form method="post" action="/user/login">
    <input type="hidden" name="csrfToken" value=<?= $data['csrfToken'] ?> />
    <div class="input-group mb-4">
        <div class="input-group-prepend">
            <span class="input-group-text" id="username">
                <i class="oi oi-account-login"></i>
            </span>
        </div>
        <input type="text" class="form-control" name="username" placeholder="Username" aria-label="Username" aria-describedby="username" required >
    </div>
    <div class="input-group mb-4">
        <div class="input-group-prepend">
            <span class="input-group-text" id="password">
                <i class="oi oi-lock-locked"></i>
            </span>
        </div>
        <input type="password" class="form-control" name="password" placeholder="Password" aria-label="Password" aria-describedby="Password" required >
    </div>
    <input type="submit" value="Submit" class="btn btn-primary btn-block btn-lg" />
</form>
<br>
<span>Don't have account?<a href="/user/register"> Create one</a></span>