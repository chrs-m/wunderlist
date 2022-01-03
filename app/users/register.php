<?php
// In this file we register a new user.

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

// trim and hash values from form input

if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
}

// check if email exist in db

//prepare the statement

$statement = $database->prepare('SELECT * FROM Users WHERE email = :email');
$statement->bindParam(':email', $email, PDO::PARAM_STR);
//execute the statement

$statement->execute();
$checkEmail = $statement->fetch(PDO::FETCH_ASSOC);
if ($checkEmail !== false) {
    echo "you already have an account!";
} elseif ($checkEmail === false) {


    // insert query

    $statement = $database->prepare('INSERT INTO Users
    (username, email, password)
    VALUES
    (:username, :email, :password)');

    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->bindParam(':username', $username, PDO::PARAM_STR);
    $statement->bindParam(':password', $hashed_password, PDO::PARAM_STR);

    $statement->execute();


    redirect('/');
}
