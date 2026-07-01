<?php
session_start();

$config = require __DIR__ . '/../host.php';

$db = new PDO(
    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
    $config['user'],
    $config['password']
);

if (isset($_POST['submit'])) {

    if (!empty($_POST['username']) && !empty($_POST['password'])) {

        $name = trim($_POST['username']);
        $password = $_POST['password'];

        $check = $db->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$name]);

        if ($check->rowCount() > 0) {

            echo "Ce nom d'utilisateur existe déjà.";

        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $insert = $db->prepare("INSERT INTO users(username, pass) VALUES(?, ?)");
            $insert->execute([$name, $hash]);

            $_SESSION['id'] = $db->lastInsertId();
            $_SESSION['name'] = $name;

            header("Location: index.php");
            exit;
        }

    } else {

        echo "Veuillez remplir tous les champs !";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syncarox: Register</title>
</head>
<body>
    <form action="" method="post">
        <p>Nom d'Utilisateur</p>
        <input name="username" id="name" autocomplete="off">
        <br>
        <p>Mot de Passe</p>
        <input type="password" name="password" id="pass" autocomplete="off">
        <br><br>
        <input type="submit" name="submit">
    </form>
</body>
</html>
