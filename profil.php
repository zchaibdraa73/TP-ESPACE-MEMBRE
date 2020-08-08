<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=snwkwced_site1','snwkwced_zydoo','zinedineCHAIBDRAA1234?',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// Requête pour trouver l'id du abonnes
if(isset($_GET['id']) AND $_GET['id'] > 0){

    // Permet de sécuriser la variable en étant sur qu'il n'y est que des nombres
    $getid = intval($_GET['id']);
    // Requête pour aller chercher l'id
    $requser = $bdd->prepare("SELECT * FROM abonnes WHERE id = ?");
    $requser->execute(array($_GET['id']));
    // On affiche ensuite les données
    $userinfo = $requser->fetch();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Page des abonnes</title>
</head>
<body>
    <div align="center">
        <h2> Profil de <?php echo $userinfo['pseudo']; ?></h2>
            <table>
                <tr>
                    <td>
                        <?php
                            if(!empty($userinfo['avatar'])){
                        ?>
                        <img width="150" src="membres/avatars<?php echo $userinfo['avatar'];?>">
                        <?php
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Pseudo = <?php echo $userinfo['pseudo']; ?></td>
                </tr>
                <tr>
                    <td>Mail = <?php echo $userinfo['mail']; ?></td>
                </tr>
                <tr>
                    <td>
                        <?php
                        // Lien permettant d'editer le profil ou se decconecter
                        if(isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id']){
                            ?>
                            <a href="editionprofil.php?id=<?php echo $userinfo['id'];?>">Editer le profil</a><br/>
                            <a href="deconnexion.php">Se deconnecter</a>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
            </table>
    </div>
</body>
</html>
<?php
}
?>