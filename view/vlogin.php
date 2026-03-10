<?php
session_start();
$error = $_SESSION["error"] ?? null;
unset($_SESSION["error"]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Knockout Zone</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" href="../resources/images/logolightbig.png" sizes="256x256">
</head>

<body>
    <div class="form-container">
        <?php if ($error): ?>
            <p style="color: red; text-align: center; font-weight: bold; margin-top: 10px;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>
        <h1>LOG IN</h1>
        <form action="../controller/UserController.php?action=login" method="POST">
            <hr>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="knockout_user" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="123ABC.." required>
            <input type="submit" value="Log in">
            <hr>
            Don't have an account? <a href="vregister.php">Create one!</a>
        </form>
</body>

</html>