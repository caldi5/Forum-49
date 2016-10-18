<?php
$to      = 'Enari@minit.nu';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: noreply@srv247.se' . "\r\n" .
    'Reply-To: noreply@srv247.se' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
?> 