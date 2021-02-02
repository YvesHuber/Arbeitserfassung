<?php
require_once('person.class.php');
require_once('timestamp.class.php');

echo "Melden sie sich an\n";
$user = new Person("", "");
$firstnametry = readline("\nFirstname ");
$check = $user->compare_values($firstnametry);

if ($check == true) {

    echo "1: Register User \n2: Register time \n3: Check Time ";
    $time = new Timestamp($firstnametry);
    $answer = readline("\nWhat action do you want to do? ");
    switch ($answer) {
        case '1':
            $user->insert_values();
            break;
        case '2':

            $time->Check();
            break;
        case '3':
            $time->calculate();
            break;
    }
}
