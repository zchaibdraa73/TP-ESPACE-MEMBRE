<?php

$bdd = new PDO('mysql:host=localhost;dbname=snwkwced_site1','snwkwced_zydoo','zinedineCHAIBDRAA1234?',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

if(isset($_POST['forminscription'])){

    // permet d'éviter les injonctions de code - sécurise nos variables
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $mail = htmlspecialchars($_POST['mail']);
    $mail2 = htmlspecialchars($_POST['mail2']);
    // permet de sécuriser la base de données en "cachant" le mot de passe
    $mdp = sha1($_POST['mdp']);
    $mdp2 = sha1($_POST['mdp2']);

    // Si les champs ne sont pas vides
    if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2'])){

        // Voir si le pseudo est plus petit que 255 caractères
        $pseudolength = strlen($pseudo);
        if($pseudolength <= 255){

            // On vérifie confirmation mail
            if($mail == $mail2){
                // On vérifie format mail
                if(filter_var($mail, FILTER_VALIDATE_EMAIL)){

                    // On vérifie existence mail avec requête
                    $reqmail = $bdd->prepare("SELECT * FROM abonnes WHERE mail = ?");
                    $reqmail->execute(array($mail));
                    // Compte le nombre de colonnes pour ce qu'on a rentré avant ici les mails
                    $mailexist = $reqmail->rowCount();

                    // On fait la condition pour compter le nombre de fois que l'on a le mail en question
                    if($mailexist == 0){

                        // On vérifie les mots de passes
                        if($mdp == $mdp2){

                            // La condition est vérifié que les abonnes sont ajoutés grâce à une fonction sql
                            $insertmbr = $bdd->prepare("INSERT INTO abonnes(pseudo, mail, motdepasse) VALUES (?, ?, ?)");
                            $insertmbr->execute(array($pseudo, $mail, $mdp));
                            $valide = "Votre compte à bien été créer ! <a href=\"connexion.php\">Me connecter</a>";
                        }
                        // Erreur si les mots de passes ne correspondent
                        else {
                            $erreur = "Vos mots de passes ne correspondent pas !";
                        }
                    }
                    // Erreur si l'adresse mail est déjà connue
                    else {
                        $erreur = "Adressse mail déjà connue, veuillez saisir une autre adresse mail !";
                    }
                }
                // Erreur si l'adresse mail n'est pas au bon format
                else {
                    $erreur = "Votre adresse mail n'est pas valide !";
                }
            }
            else {
                // Eerreur si les mails ne correspondent pas
                $erreur = "Vos mails ne correspond pas !";
            }
        }
        else {
            // Erreur si le pseudo dépasse 255 caractères
            $erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
        }
    }
    else {
        // Erreur qui nous demande de remplir tous les champs
        $erreur = "Tous les champs doivent être complétés !";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Page inscription</title>
</head>
<body>
    <div align="center">
        <h2> Inscription </h2>
        <br/><br/>
        <form action="" method="POST">
            <table>
                <tr>
                    <td align="right"><label for="pseudo"> Pseudo : </label></td>
                    <td><input type="text" name="pseudo" placeholder="Votre pseudo" id="pseudo" value="<?php if(isset($pseudo)){echo $pseudo;}?>"></td>
                </tr>
                <tr>
                    <td align="right"><label for="mail"> Mail : </label></td>
                    <td><input type="email" name="mail" placeholder="Votre mail" id="mail" value="<?php if(isset($mail)){echo $mail;}?>"></td>
                </tr>
                <tr>
                    <td align="right"><label for="mail2"> Confirmation du mail : </label></td>
                    <td><input type="email" name="mail2" placeholder="Confirmez votre mail" id="mail2" value="<?php if(isset($mail2)){echo $mail2;}?>"></td>
                </tr>
                <tr>
                    <td align="right"><label for="mdp"> Mot de passe : </label></td>
                    <td><input type="password" name="mdp" placeholder="Votre mot de passe" id="mdp"></td>
                </tr>
                <tr>
                    <td align="right"><label for="mdp2"> Confirmation du mot de passe : </label></td>
                    <td><input type="password" name="mdp2" placeholder="Confirmez mot de passe" id="mdp2"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <br/>
                        <input type="submit" name="forminscription" value="Je m'inscris">
                    </td>
                </tr>
            </table>
        </form>
        <?php
            // Pour afficher l'erreur ou la validité de l'inscription
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