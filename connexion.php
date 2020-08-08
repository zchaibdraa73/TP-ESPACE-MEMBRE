<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=snwkwced_site1','snwkwced_zydoo','zinedineCHAIBDRAA1234?',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

if(isset($_POST['formconnexion'])){

    // On sécurise la variable
    $mailconnect = htmlspecialchars($_POST['mailconnect']);
    $mdpconnect = sha1($_POST['mdpconnect']);

    if(!empty($mailconnect) AND !empty($mdpconnect)){
        $requser = $bdd->prepare("SELECT * FROM abonnes WHERE mail = ? AND motdepasse = ?");
        $requser->execute(array($mailconnect, $mdpconnect));
        $userexist = $requser->rowCount();

        // On fait une condition pour savoir si l'utilisateur existe
        if($userexist == 1){

            // Condition "se souvenir de moi"
            if(isset($_POST['rememberme'])){
                setcookie('email',$mailconnect,time()+365*24*3600,null,null,false,true);
                setcookie('password',$mdpconnect,time()+365*24*3600,null,null,false,true);
            }
            // Si condition respecté on fait appel aux variables de session
            $userinfo = $requser->fetch();
            $_SESSION['id'] = $userinfo['id'];
            $_SESSION['pseudo'] = $userinfo['pseudo'];
            $_SESSION['mail'] = $userinfo['mail'];
            // On redirige la personne vers son profil
            header("Location: profil.php?id=".$_SESSION['id']);
        }
        else {
            $erreur = "Erreur mail et/ou mot de pase invlide(s)!";
        }
    }
    else {
        $erreur = "Tous les champs doivent être complétés !";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Page connexion</title>
</head>
<body>
    <div align="center">
        <h2> Connexion </h2>
        <br/><br/>
            <table>
                <form action="" method="POST">
                <tr>
                    <td align="right"><label for="mail"> Mail : </label></td>
                    <td><input type="email" name="mailconnect" placeholder="Mail"></td>
                </tr>
                <tr>
                    <td align="right"><label for="mdpconnect"> Mot de passe : </label></td>
                    <td><input type="password" name="mdpconnect" placeholder="Mot de passe"></td>
                </tr>
                <tr>
                <td></td>
                    <td>
                        <br/>
                        <input type="submit" name="formconnexion" value="Je me connecte">
                        <br/>
                        <a href="inscription.php" target="_blank"><input type="button" value="S'inscrire"> </a>
                        <br>
                        <input type="checkbox" name="rememberme" id="remembercheckbox"/><label for="remembercheckbox"> Se souvenir de moi</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p> Toujours pas inscrit ? <a href="inscription.php">Inscrivez-vous</a> maintenant !</p>
                    </td>
                </tr>
                </form>
            </table>
        <?php
            // Pour afficher l'erreur ou la validité de l'envoie
            if(isset($erreur)){
                echo '<font color="red">'.$erreur."</font>";
            }
            elseif(isset($valide)){
                echo '<font color="green">'.$valide."</font>";
            }
        ?>
    </div>
</body>
</html>