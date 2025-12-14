<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Park-IT | Admin</title>
    <link rel="icon" type="image/png" href="css/ParkIT.png">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <main>
        <button class="back-button" onclick="goBack()">Go Back</button>
        <section id="login-section">
            <h1>Admin Login</h1>
            <form id="loginForm" method="POST" action="php/login_process.php">
                <label>Username:</label>
                <input id="username" type="text" name="username" required>

                <label>Password:</label>
                <input id="password" type="password" name="password" required>

                <?php if (!empty($_SESSION['login_error'])): ?>
                    <p class="error-message">
                        <?= $_SESSION['login_error']; ?>
                    </p>
                    <?php unset($_SESSION['login_error']); ?>
                <?php endif; ?>

                <button type="submit" id="loginBtn">Log In</button>
            </form>
        </section>
    </main>
    <script>
        function goBack(){
            window.location.href = "main.php";
        }
    </script>
</body>

</html>