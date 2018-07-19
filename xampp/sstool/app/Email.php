<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Post;

//Load Composer's autoloader
require 'C:\xampp\htdocs\sstool\vendor\autoload.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                   // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'ssportal2018@gmail.com';                 // SMTP username
    $mail->Password = 'admin123!@#';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = '465';
    $mail->isHTML();                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('ssportal2018@gmail.com', 'Admin');
    $mail->addAddress('ssportal2018@gmail.com', 'Admin');     // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment('C:\xampp\htdocs\sstool\dns.csv');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    $body = '<p>This is the test auto email for the SSPortal</p>'. '<br> Hello! ['.
            auth()->user()->type.']</br>'.auth()->user()->name.'
            <br>Scaned IP ranges -- </br>
            <br>Denied IP ranges -- </br>
            <br>Finished Report Attachments--</br>';

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}