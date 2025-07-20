<?php
session_start();

require '../../../vendor/autoload.php';




use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    $email = filter_var(trim($_POST['gmail']), FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "El correo electrónico no es válido.";
        exit;
    }

    require_once '../../helpers/conexion_bd.php';

    $stmt = $conexion_metadocs->prepare("SELECT * FROM `usuarios` WHERE correo = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id_usuario'];

        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Guardar en la sesión el correo y valdiar que el acceso fue correcto
        $_SESSION['email'] = $email;
        $_SESSION['recuperacion_iniciada'] = true;


        $stmt = $conexion_metadocs->prepare("INSERT INTO contraseña_resets (id_usuario, token, expira_en) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $token, $expires);

        if ($stmt->execute()) {
            $link = "http://localhost/metadocs_V2/app/vistas/log/form_cambio_contraseña.php?token=$token";

            // Configurar PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->CharSet = 'UTF-8';
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; 
                $mail->SMTPAuth = true;
                $mail->Username = 'metadocs7@gmail.com'; 
                $mail->Password = 'ajut qrux zvaa dqls'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Configuración del correo
                $mail->setFrom('no-reply@metadocs.com', 'Metadocs');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Recuperación de contraseña Metadocs';
                $mail->Body = "Haz clic en este enlace para restablecer tu contraseña: <a href='$link'>$link</a><br><br>Este enlace expirará en una hora.";
                $mail->AltBody = "Haz clic en este enlace para restablecer tu contraseña: $link\n\nEste enlace expirará en una hora.";

                // Enviar el correo
                $mail->send();
                header('Location: ../../vistas/log/correo_enviado.php');
                exit;

            } catch (Exception $e) {
                echo "Hubo un problema al enviar el correo. Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Hubo un problema al generar el enlace. Por favor, inténtalo más tarde.";
        }
    } else {
        
        $_SESSION['no_existe'] = 'El correo electrónico que ingresaste no se encuentra en el sistema';
        header('Location: ../../vistas/log/recuperacion.php ');
        exit();
    }
}
?>
