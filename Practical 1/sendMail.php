<?php

$subject = "Practical work 1";

$firstName = "Oleksandr";
$lastName = "Nahirnyi";
$city = "Kharkiv";

$text1 = "First name: $firstName\n";
$text2 = "Last name: $lastName\n";
$text3 = "City: $city\n";

$message = $text1 . $text2 . $text3;

echo "Message:\n";
echo $message;

$headers = "From: nagirnyjsasha@gmail.com";

mail("o.v.nahirnyi@student.khai.edu", $subject, $message, $headers);

echo "\nEmail sent";

?>
