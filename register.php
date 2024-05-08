<?php
if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    $conn = new PDO('mysql:host=localhost;dbname=mobisense', "root", "");

    // Controleer of het e-mailadres al in de database bestaat
    $query_check_email = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $query_check_email->bindValue(":email", $email);
    $query_check_email->execute();

    if ($query_check_email->rowCount() > 0) {
        // E-mail bestaat al, verwerk de foutmelding of stuur terug naar het registratieformulier
        // Voor eenvoud stuur ik gewoon terug naar het registratieformulier met een foutmelding
        header("Location: register.php?error=email_exists");
        exit;
    }

    // Hash het wachtwoord
    $options = [
        'cost' => 12,
    ];
    $hashed_password = password_hash($password . "SDF030303", PASSWORD_DEFAULT, $options);

    // Voeg gebruikersgegevens toe aan de database
    $query_insert_user = $conn->prepare("INSERT INTO users (email, password, firstname, lastname) VALUES (:email, :password, :firstname, :lastname)");
    $query_insert_user->bindValue(":email", $email);
    $query_insert_user->bindValue(":password", $hashed_password);
    $query_insert_user->bindValue(":firstname", $firstname);
    $query_insert_user->bindValue(":lastname", $lastname);
    $query_insert_user->execute();

    // Stuur door naar een succespagina of ergens anders na een succesvolle registratie
    header("Location: registration_success.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styleregister.css">
</head>
<body>

<div id="app">
    <form action="register.php" method="post">
        <h1>Sign up</h1>

        <?php
        // Toon foutmelding als deze is opgegeven in URL-parameter
        if (isset($_GET['error']) && $_GET['error'] === 'email_exists') {
            echo '<div class="alert">This email address is already registered. Please use a different one.</div>';
        }
        ?>

        <div class="form form--register">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="firstname">First Name</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="lastname">Last Name</label>
            <input type="text" id="lastname" name="lastname" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <input type="submit" value="Sign Up" class="btn" id="btnSubmit">
    </form>
</div>

</body>
</html>

