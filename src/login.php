<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SQL Injection Homework</title>
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

        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
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

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
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
    </style>
</head>
<body>

<div class="login-container">
    <h1>SQL Injection Lab</h1>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new mysqli("mysql", "root", "my-secret-pw", "sqli_lab");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        // Verifica se l'utente esiste
        $sql_user_check = "SELECT * FROM users WHERE username = '$username'";
        $result_user_check = $conn->query($sql_user_check);

        if ($result_user_check->num_rows == 0) {
            // Se l'utente non esiste
            echo "<div class='message'>L'utente '$username' non è presente nella tabella users.</div>";
            $show_form = true;
        } else {
            // Se l'utente esiste, controlla la password
            $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Login avvenuto con successo, non mostrare il form
                echo "<div class='message success'>Login successful! Benvenuto!</div>";
                $show_form = false;
            } else {
                echo "<div class='message'>Password errata per l'utente '$username'.</div>";
                $show_form = true;
            }
        }

        $conn->close();
    } else {
        // Mostra il form se l'utente accede per la prima volta
        $show_form = true;
    }

    // Mostra il form solo se $show_form è true
    if ($show_form) {
    ?>
    <form action="login.php" method="post">
        <input type="text" name="username" placeholder="Inserisci il tuo username" >
        <input type="password" name="password" placeholder="Inserisci la tua password">
        <input type="submit" value="Login">
    </form>
    <?php
    }
    ?>
</div>

</body>
</html>
