<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=snwkwced_site1','snwkwced_zydoo','zinedineCHAIBDRAA1234?',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

if(isset($_GET['id'])){

    $requser = $bdd->prepare("SELECT * FROM abonnes WHERE id = ?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();

    if(isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudo']){

        $newpseudo = htmlspecialchars($_POST['newpseudo']);
        // Requête permettant de mettre à jour les infos des abonnes
        $insertpseudo = $bdd->prepare("UPDATE abonnes SET pseudo = ? WHERE id = ?");
        $insertpseudo->execute(array($newpseudo, $_SESSION['id']));
        // Une fois requête exécutée on redirige vers la page de profil
        header('Location: profil.php?id='.$_SESSION['id']);

    }

    if(isset($_POST['newmail']) AND !empty($_POST['newmail']) AND $_POST['newmail'] != $user['mail']){

        $newmail = htmlspecialchars($_POST['newmail']);
        $insertmail = $bdd->prepare("UPDATE abonnes SET mail = ? WHERE id = ?");
        $insertmail->execute(array($newmail, $_SESSION['id']));
        header('Location: profil.php?id='.$_SESSION['id']);
    }

    if(isset($_POST['newmdp']) AND !empty($_POST['newmdp']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2'])){

        $mdp = sha1($_POST['newmdp']);
        $mdp2 = sha1($_POST['newmdp2']);

        if($mdp == $mdp2){

            $insertmdp = $bdd->prepare("UPDATE abonnes SET motdepasse = ? WHERE id = ?");
            $insertmdp->execute(array($mdp, $_SESSION['id']));
            header('Location: profil.php?id='.$_SESSION['id']);
        }
        else {
            $erreur = "ATTENTION ! Vos mots de passes ne correspondent pas !";
        }

    }

    if(isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name'])){

        $tailleMax = 40000000;
        $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');

        // Si la taille de fichier ne dépasse pas la valeur mise dans $tailleMax
        if($_FILES['avatar']['size'] <= $tailleMax){

            // On vérifie si l'extension du fichier est bonne
            // strtolower permet de mettre tout en miniscule
            // substr permet d'ignorer le premier caractère de la chaine
            // strrchr permet de renvoyer l'extension du fichier mais avec le . (.png)
            $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));

            if(in_array($extensionUpload, $extensionsValides)){

                $chemin = "membres/avatars".$_SESSION['id'].".".$extensionUpload;
                // Permet de déplacer le fichier que la personne upload
                $result = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
                if($result){

                    $updateAvatar = $bdd->prepare('UPDATE abonnes SET avatar = :avatar WHERE id = :id');
                    $updateAvatar->execute(array(
                        'avatar' => $_SESSION['id'].".".$extensionUpload,
                        'id' => $_SESSION['id']
                    ));
                    header('Location: profil.php?id='.$_SESSION['id']);
                }
                else {
                    $erreur = "Erreur durant l'importation de votre photo";
                }
            }
            else {
                $erreur = "Votre photo doit être au format jpg, jpeg, gif ou png";
            }
        }
        // Message pour dépassement de la taille
        else{
            $erreur = "Votre photo ne dois pas dépasser les 4Mo";
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Page édition</title>
</head>
<body>
    <div align="center">
        <h2> Edition de mon profil : </h2>
        <form action="" method ="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <td align="right"><label for="">Avatar :</label></td>
                    <td><input type="file" name="avatar"></td><br/>
                </tr>
                <tr>
                    <td align="right"><label for="newpseudo"> Pseudo : </label></td>
                    <td><input type="text" name="newpseudo" placeholder="Pseudo" value="<?php echo $user['pseudo']; ?>"></td>
                </tr>
                <tr>
                    <td align="right"><label for="mail"> Mail : </label></td>
                    <td><input type="email" name="newmail" placeholder="Mail" value="<?php echo $user['mail'];?>"></td>
                </tr>
                <tr>
                        <td align="right"><label for="password"> Mot de passe : </label></td>
                        <td><input type="password" name="newmdp" placeholder="Mot de passe"></td>
                </tr>
                <tr>
                        <td align="right"><label for="password"> Confirmation mot de passe : </label></td>
                        <td><input type="password" name="newmdp2" placeholder="Confirmation mot de passe"></td>
                </tr>
                    <br/><br/>
                    <td></td>
                    <td>
                        <br/>
                        <input type="submit" value="Mettre à jour profil">
                    </td>
                </tr>
            </table>
            <?php
            if(isset($erreur)){
                echo '<font color="red">'.$erreur."</font>";
            }
            ?>
        </form>
    </div>
</body>
</html>
<?php
}
else {
    header("Location: connexion.php");
}
?>