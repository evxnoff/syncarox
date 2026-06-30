<?php
session_start();
$config = require __dir__ . '/../host.php';
$db = new PDO(
    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
    $config['user'],
    $config['password']
);

if (isset($_POST['submit'])) {
    if(!empty($_POST['username']) AND !empty($_POST['password'])) {
        $name = htmlspecialchars($_POST['username']);
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $insertUser = $db->prepare('INSERT INTO users(username, pass) VALUES (?, ?)');
        $insertUser->execute(array($name, $pass));

        $getUser = $db->prepare('SELECT * FROM users WHERE username = ? AND pass = ?');
        $getUser->execute(array($name, $pass));
        if ($getUser->rowCount() > 0) {
            $_SESSION['name'] = $name;
            $_SESSION['pass'] = $pass;
            $_SESSION['id'] = $getUser->fetch()['id'];
        }
        echo $_SESSION['id'];
    } else {
        echo 'Veuillez remplir tous les champs!';
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
