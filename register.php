<?php
include 'header.php';
include 'db.php';  
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $favorite_game = $_POST["favorite_game"];

    if (strlen($username) < 3 || strlen($username) > 15) {
        $error = "Gebruikersnaam moet tussen de 3 en 15 tekens zijn.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Voer een geldig e-mailadres in.";
    } elseif (empty($favorite_game)) {
        $error = "Kies een favoriete game.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, favorite_game) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $favorite_game); 

        if ($stmt->execute()) {
            $success = "Registratie geslaagd!";
        } else {
            $error = "Er is een fout opgetreden bij het registreren: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lid worden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Word Lid</h1>
    <form action="register.php" method="POST">
        <label>Gebruikersnaam:</label>
        <input type="text" name="username" required>
        <label>E-mail:</label>
        <input type="email" name="email" required>
        <label>Favoriete game:</label>
        <select name="favorite_game">
            <option value="">-- Kies een game --</option>
            <option value="The Witcher 3">The Witcher 3</option>
            <option value="Cyberpunk 2077">Cyberpunk 2077</option>
            <option value="Red Dead Redemption 2">Red Dead Redemption 2</option>
            <option value="Elden Ring">Elden Ring</option>
        </select>
        <button type="submit">Registreren</button>
    </form>

    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <?php if ($success) echo "<p class='success'>$success</p>"; ?>
</body>
</html>
