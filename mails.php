<?php
/**
 * Biblioteca de funciones que gestiona los emails que envía SIGIWEB. Para enviar los correos
 * usamos el servidor SMTP que ofrece Google para Gmail, que permite enviar hasta 2000 correos
 * al día de manera gratuita. Para ello, se necesita autenticación en los servicios de Google.
 * 
 * @author SIGIWEB
 */

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    include("PHPMailer/src/Exception.php");
    include("PHPMailer/src/PHPMailer.php");
    include("PHPMailer/src/SMTP.php");

/**
 * Envía el email de bienvenida al sistema.
 * 
 * Envía un email para dar la bienvenida al usuario a SIGI Azahar. En el correo (en formato HTML)
 * se envían los datos para que el usuario inicie sesión: su número de carné y su contraseña 
 * (generada aleatoriamente).
 * 
 * @param string $email El email al que se envía el correo
 * @param string $name El nombre del usuario
 * @param string $user El número de carné asignado al usuario
 * @param string $passwd La contraseña del usuario
 * @return void
 * @throws Exception $e Si ocurre algún problema al enviar el correo electrónico
 */
    function sendWelcomeMail($email, $name, $user, $passwd) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = '465';
            $mail->isHTML(true);
            $mail->Username = 'sigiazahar@gmail.com';
            $mail->Password = 'iissi22019';

            $mail->CharSet = 'UTF-8';

            $mail->setFrom('sigiazahar@gmail.com');
            $mail->Subject = 'Bienvenido a SIGI Azahar';
            $mail->Body = '<h2>Bienvenido, ' . $name . '.</h2><br>' .
            '<p>Estos son sus datos para entrar en el sistema:<p><br><br>' . 
            '<p>Número de carné: ' . $user . '<p><br>' . 
            '<p>Contraseña: ' . $passwd . '<p><br>' . 
            '<p>También puede entrar en el sistema usando su correo electrónico.<p><br>';
            $mail->AddAddress($email);

            $mail->send();
        } catch(Exception $e) {
            echo '<div class="fallo"><h4>No se ha podido enviar el mail.</h4></div>';
        }


    }

/**
 * Envía el email de recuperación de contraseña.
 * 
 * Se envía un correo electrónico cuando el usuario ha solicitado recuperar su contraseña. En el
 * correo se envían sus nuevos datos para iniciar sesión, y un aviso indicando que si no ha
 * solicitado la recuperación de la contraseña, contacte con el bibliotecario.
 * 
 * @param string $email El email al que se envía el correo
 * @param string $pass La nueva contraseña del usuario
 * @return void
 * @throws Exception $e Si ocurre algún problema al enviar el correo electrónico
 */
    function mailRecPass($email, $passwd) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = '465';
            $mail->isHTML(true);
            $mail->Username = 'sigiazahar@gmail.com';
            $mail->Password = 'iissi22019';

            $mail->CharSet = 'UTF-8';

            $mail->setFrom('sigiazahar@gmail.com');
            $mail->Subject = 'Recuperación de contraseña';

            $mail->Body = '<h2>Nueva contraseña</h2><br>' .
                '<p>Al haber solicitado recuperar la contraseña, se le ha generado una nueva:<p><br>' . 
                '<p>Contraseña: ' . $passwd . '<p><br>' . 
                '<p>Si no ha solicitado cambiar la contraseña, contacte con el bibliotecario.<p><br>';

            $mail->AddAddress($email);

            $mail->send();
        } catch(Exception $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }

    }

?>