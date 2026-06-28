<!-- Ferrah Nihal Yasmine G1 242431423103
     Ziane Damia Feriel G1 232431847516
	 Ouldedine Chaima 232331595914
	 Djahel Yousra 232331406306 -->
	 <?php
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = mysqli_connect("localhost", "root", "", "miniproject");

    if (!$conn) {
        die("Erreur connexion : " . mysqli_connect_error());
    }

    $email    = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $role     = $_POST["role"];

    // ================= ADMIN =================
    if ($role == "Admin") {

        $sql = "SELECT * FROM administrateur 
                WHERE EMAIL='$email' 
                AND MOT_DE_PASSE='$password'";

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Erreur SQL Admin: " . mysqli_error($conn));
        }

        $user = mysqli_fetch_assoc($result);

        if ($user) {

            $_SESSION["user"] = $user;
            $_SESSION["role"] = "Admin";
            $_SESSION["nom"]  = $user["PRENOM"] . " " . $user["NOM"];
            $_SESSION["id"]   = $user["id-administrateurs"];

            header("Location: ../index.php/admi.php");
            exit();

        } else {
            $error = "Identifiants incorrects";
        }
    }

    // ================= ENSEIGNANT =================
    elseif ($role == "Enseignant") {

    $sql = "SELECT * FROM enseignant 
            WHERE email='$email' 
            AND `mot de passe`='$password'";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Erreur SQL Enseignant: " . mysqli_error($conn));
    }

    $user = mysqli_fetch_assoc($result);

    if ($user) {

        $_SESSION["user"] = $user;
        $_SESSION["role"] = "Enseignant";
        $_SESSION["nom"]  = $user["Prenom"] . " " . $user["Nom"];
        $_SESSION["id"]   = $user["id_Enseinant"];
        $_SESSION["module_id"] = $user["ID-MODULE"];

        $id = $user["id_Enseinant"];

        $id = $user["id_Enseinant"];

$id = $user["id_Enseinant"];

if (!empty($id)) {

    $sql = "UPDATE enseignant 
            SET derniere_connexion = NOW() 
            WHERE id_Enseinant = '$id'";

    if (!empty($user["id_Enseinant"])) {

    $id = $user["id_Enseinant"];

    $sql = "UPDATE enseignant 
            SET derniere_connexion = NOW() 
            WHERE id_Enseinant = '$id'";

   
    if (!$result) {
        die("Erreur UPDATE : " . mysqli_error($conn));
    }

} else {
    die("ID enseignant introuvable");
}

    if (!$result) {
        die("Erreur UPDATE : " . mysqli_error($conn));
    }

} else {
    die("ID enseignant introuvable");
}
        header("Location:../index.php/enseignant.php");
        exit();

    } else {
        $error = "Identifiants incorrects";
    }
}

    // ================= ETUDIANT =================
    elseif ($role == "Etudiant") {

        $sql = "SELECT * FROM etudiant 
                WHERE EMAIL='$email' 
                AND `MOT DE PASSE`='$password'";

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Erreur SQL Etudiant: " . mysqli_error($conn));
        }

        $user = mysqli_fetch_assoc($result);

        if ($user) {

            $_SESSION["user"] = $user;
            $_SESSION["role"] = "Etudiant";
            $_SESSION["nom"]  = $user["PRENOM"] . " " . $user["NOM"];
            $_SESSION["matricule"] = $user["Matricule"];

            mysqli_query($conn, "
                UPDATE etudiant 
                SET derniere_connexion = NOW() 
                WHERE Matricule = '" . $user["Matricule"] . "'
            ");

            header("Location: ../index.php/etudiant.php");
            exit();

        } else {
            $error = "Identifiants incorrects";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Connexion</title>
<link rel="stylesheet" href="../fichier.css/acceuil2.css">

</head>

<body>

<div class="container">

<img src="../image/usthb1.webp" class="logo">
<h2>Connexion USTHB</h2>

<?php if ($error != "") echo "<div class='error'>$error</div>"; ?>

<form method="POST">

<input type="hidden" name="role" id="role" value="Admin">

<div>
    <span class="role-btn active" onclick="setRole(this,'Admin')">Admin</span>
    <span class="role-btn" onclick="setRole(this,'Enseignant')">Enseignant</span>
    <span class="role-btn" onclick="setRole(this,'Etudiant')">Etudiant</span>
</div>

<input type="text" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Mot de passe" required>

<button type="submit">Connexion</button>

<div class="annees"><small>2025/2026</small></div>

</form>

</div>

<script src="../fichier.js/acceuil2.js"></script>


</body>
</html>