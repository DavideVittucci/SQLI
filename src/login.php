<?php
session_start(); 


if (isset($_GET['logout'])) {
    session_destroy(); 
    header("Location: login.php"); 
    exit();
}

$conn = new mysqli("mysql", "root", "my-secret-pw", "sqli_lab");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password';";

    if ($conn->multi_query($sql)) {
        do {
            $result = $conn->store_result();
            if ($result) {
                if ($result->num_rows > 0) {
                    $success_message = "Login avvenuto! Benvenuto, MR.Hacker!";
                    $show_success = true;
                    $show_form = false;
                    break;
                }
                
                $result->free();
            }
        } while ($conn->next_result());
    }


    if (!$show_success) {
        if ($conn->errno) {
            $error_message = "Errore di autenticazione.";
        } else {
            $error_message = "Password errata o l'utente non è presente nella tabella users.";
        }
    }

    $conn->close();
} else {

    $show_form = true;
}

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SQL Injection Lab</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .header {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px 0;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .login-container, .error-container, .success-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"], input[type="password"] {
            padding: 10px;
            margin: 10px 0;
            width: 80%;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"], button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover, button:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 20px;
            color: red;
            font-size: 18px;
        }

        .message.success {
            color: green;
        }

        .error-message {
            color: red;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .back-button, .logout-button {
            background-color: red;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: darkred;
        }

        .logout-button {
            background-color: #e30e0e;
        }

        .logout-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="header">
    SQLI HOMEWORK
</div>
<?php if ($show_form) { ?>
    <div class="login-container">
        <h1>LOGIN SICURO</h1>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Inserisci il tuo username" required>
            <input type="password" name="password" placeholder="Inserisci la tua password">
            <input type="submit" value="Login">
        </form>
    </div>
<?php } ?>

<?php if (isset($error_message)) { ?>
    <div class="error-container">
        <div class="error-message"><?php echo $error_message; ?></div>
        <a href="login.php"><button class="back-button">Indietro</button></a>
    </div>
<?php } ?>

<?php if (isset($show_success)) { ?>
    <div class="success-container">
        <div class="message success"><?php echo $success_message; ?></div>
        <a href="login.php?logout=true"><button class="logout-button">Logout</button></a>
    </div>
<?php } ?>

</body>
</html>
