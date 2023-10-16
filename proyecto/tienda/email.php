<?php      
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/OAuth.php';

function pruebaMail($destino_email, $destino_nombre){
//Create a new PHPMailer instance
$mail = new PHPMailer\PHPMailer\PHPMailer();

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Set the hostname of the mail server
$mail->Host = 'smtp.live.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6

//Set the SMTP port number - 587 for authenticated TLS
$mail->Port = 465;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'SSL';

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

$mail->SMTPDebug = 1;

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "guindas_88@hotmail.com";

//Password to use for SMTP authentication
$mail->Password = "Soponcio21!";

//Set who the message is to be sent from
$mail->setFrom('guindas_88@hotmail.com', 'FrutasDelValle');

//Set an alternative reply-to address
$mail->addReplyTo('guindas_88@hotmail.com', 'FrutasDelValle');

//Set who the message is to be sent to
$mail->addAddress($destino_email, $destino_nombre);

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'PRUEBA MAIL ';
$mailContent = '¡¡¡¡ LO HAS CONSEGUIDO '. $destino_nombre. '!!!!';
$mail->Body = $mailContent;

if(!$mail->send()) {
  echo 'Message could not be sent. ';
  echo 'Mailer Error: ' . $mail->ErrorInfo;
  exit;
    }
else{
    echo 'Message has been sent';
    }
}
?>