<php?

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$tel = $_POST['tel'];
$presta = $_POST['presta'];
$description = $_POST['description'];

require "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);

$mail ->isSMTP();