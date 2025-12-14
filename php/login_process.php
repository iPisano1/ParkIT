<?php
    session_start();

    $adminUser = "admin";
    $adminPass = "admin";

    $username = $_POST['username'] ?? "";
    $password = $_POST['password'] ?? "";

    if ($username === $adminUser && $password === $adminPass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: ../admin.php");
        exit;
    }

    $_SESSION['login_error'] = "❌ Invalid username or password.";
    header("Location: ../login.php");
    exit;
