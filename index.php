<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$firstname = $name = $email = $phone = $message = "";
$firstnameError = $nameError = $emailError = $phoneError = $messageError = "";
$isSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = verifyInput($_POST["firstname"]);
    $name = verifyInput($_POST["name"]);
    $email = verifyInput($_POST["email"]);
    $phone = verifyInput($_POST["phone"]);
    $message = verifyInput($_POST["message"]);
    $isSuccess = true;

    if (empty($firstname)) {
        $firstnameError = "Entrez un prénom correct svp!";
        $isSuccess = false;
    }

    if (empty($name)) {
        $nameError = "Entrez un nom correct svp!";
        $isSuccess = false;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Entrez un email valide svp!";
        $isSuccess = false;
    }

    if (!preg_match("/^[0-9 ]*$/", $phone)) {
        $phoneError = "Entrez un numéro valide svp!";
        $isSuccess = false;
    }

    if (empty($message)) {
        $messageError = "Entrez un message valide svp!";
        $isSuccess = false;
    }

    if ($isSuccess) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io';  // Serveur SMTP de Gmail
            $mail->SMTPAuth   = true;
            $mail->Username   = '49429722020f29';  // Votre adresse e-mail Gmail
            $mail->Password   = '2f094b8a8eee8c';  // Votre mot de passe Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 2525;

            $mail->setFrom($email, $firstname . ' ' . $name);
            $mail->addAddress('yadou471@gmail.com');

            $mail->isHTML(false);
            $mail->Subject = 'Message de votre site';
            $mail->Body    = "Firstname: $firstname\nName: $name\nEmail: $email\nPhone: $phone\nMessage: $message";

            if ($mail->send()) {
                $isSuccess = true;
            } else {
                $isSuccess = false;
                echo 'Erreur lors de l\'envoi du message. ' . $mail->ErrorInfo;
            }

            // Réinitialise les champs après l'envoi
            $firstname = $name = $email = $phone = $message = "";
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi du message. " . $e->getMessage();
        }
    }
}

function verifyInput($var)
{
    $var = trim($var);
    $var = stripslashes($var);
    $var = htmlspecialchars($var);

    return $var;
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Contactez-Moi !</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/style.css">
        <script src="js/script.js"></script>
    </head>
    
    <body>
        
       <div class="container">
            <div class="divider"></div>
            <div class="heading">
                <h2>Contactez-moi</h2>
            </div>
                
           <div class="row">
               <div class="col-lg-10 col-lg-offset-1">
                    <form id="contact-form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" role="form">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="firstname">Prénom <span class="blue">*</span></label>
                                <input id="firstname" type="text" name="firstname" require class="form-control" placeholder="Votre prénom" value="<?php echo $firstname ?>">
                                <p class="comments"><?php echo $firstnameError ?></p>
                            </div>
                            <div class="col-md-6">
                                <label for="name">Nom <span class="blue">*</span></label>
                                <input id="name" type="text" name="name" class="form-control" placeholder="Votre Nom" value="<?php echo $name ?>">
                                <p class="comments"><?php echo $nameError ?></p>
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email <span class="blue">*</span></label>
                                <input id="email" type="email" name="email" class="form-control" placeholder="Votre Email" value="<?php echo $email ?>">
                                <p class="comments"><?php echo $emailError ?></p>
                            </div>
                            <div class="col-md-6">
                                <label for="phone">Téléphone</label>
                                <input id="phone" type="tel" name="phone" class="form-control" placeholder="Votre Téléphone" value="<?php echo $phone ?>">
                                <p class="comments"><?php echo $phoneError ?></p>
                            </div>
                            <div class="col-md-12">
                                <label for="message">Message <span class="blue">*</span></label>
                                <textarea id="message" name="message" class="form-control" placeholder="Votre Message" rows="4"><?php echo $message ?></textarea>
                                <p class="comments"><?php echo $messageError ?></p>
                            </div>
                            <div class="col-md-12">
                                <p class="blue"><strong>* Ces informations sont requises.</strong></p>
                            </div>
                            <div class="col-md-12">
                                <input type="submit" class="button1" value="Envoyer">
                            </div>    
                        </div>
                        <p class="thank-you" style="display:<?php if ($isSuccess) echo "block"; else echo  "none" ?>">Votre message a bien été envoyé. Merci de m'avoir contacté :)</p>
                    </form>
                </div>
           </div>
        </div>
        
    </body>
</html>
