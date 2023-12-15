<?php
namespace App\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Illuminate\Http\Request;

class SendMailController extends Controller
{
    public function sendmail(String $NewPwd){

        try{
    $mail = new PHPMailer(true);
           //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'kyjarticulosonline@gmail.com';                     //SMTP username
    $mail->Password   = 'slkg woyy smwe xkvg';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('kyjarticulosonline@gmail.com', 'KyJ Articulos Online');
    $mail->addAddress('ga22001@itcha.edu.sv');     //Add a recipient
    //Content
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = "Recuperacion de contraseña";
    $mail->Body    = "Se ha solicitado un cambio de contraseña para tu cuenta, por lo tanto se te
    dara una nueva contraseña provicional con la cual podras acceder a tu cuenta para cambiar la contraseña.
    Tu contraseña provicional: <b>$NewPwd</b>";

    $mail->send();
        }catch(Exception $e){
            echo "Message could not be sent. Mailer Error:{$mail->ErrorInfo}";
        }
    }


    public function sendFactura(string $email, string $nombre){ //Datos que necesitaras en el correo, en este caso el email al que se enviara el correo y la ruta del archivo, en este caso es un pdf

        try{
    $mail = new PHPMailer(true);
           //Server settings
    $mail->isSMTP();                                            
    $mail->Host       = 'smtp.gmail.com';                    
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'kyjarticulosonline@gmail.com';                     //Correo que actuara como emisor, tenemos que configurar este correo
    $mail->Password   = 'slkg woyy smwe xkvg';                               //clave de aplicacion del correo
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           
    $mail->Port       = 465;                                

    $pdfContent = file_get_contents(public_path("pdf/$nombre"));
    $pdfBase64 = base64_encode($pdfContent);

    //Recipients
    $mail->setFrom('kyjarticulosonline@gmail.com', 'KyJ Articulos Online'); //correo emisor y nombre del correo del emisor, es el que sale en el correo de "enviado por:"
    $mail->addAddress($email); 
    //Content
    $mail->CharSet = 'UTF-8'; // cambiamos el charset a UTF-8 para poder escribir tildes y ñ en el cuerpo del correo
    $mail->isHTML(true);                           
    $mail->Subject = "Factura de su compra:"; //Titulo del correo a enviar
    $mail->Body = 'Se le adjunta una factura electrónica de su compra, ¡gracias por preferirnos!:'; //cuarpo del correo a enviar
    $mail->AddAttachment(public_path("pdf/$nombre"), 'factura.pdf'); // se adjunta el archivo que queremos enviar, puede ser una imagen o un pdf, el primer parametro es la ruta del archivo (esta guardado localmente)
                                                                    //el segundo parametro es el nombre que tendra el archivo adjuntado en el email
    $mail->send();
        }catch(Exception $e){
            echo "Message could not be sent. Mailer Error:{$mail->ErrorInfo}"; // error por si no es posible enviar el correo (trabajar segun la logica de la empresa y borrar el echo pues eso dara un error)
        }
    }
}
