<?php
require_once('person.class.php');
require_once('timestamp.class.php');
$user = new Person("", "");
if (!file_exists('Data/')) {
    mkdir('Data/', 0777, true);
}
if (!file_exists('Data/Data.json'))
{
    echo "
You have no existing User
Please create a new one
        ";
        $user->insert_values();
}
echo "
Welcome \n
Please Log in\n
";
$firstnametry = readline("Please enter your Firstname to log in ");
$check = $user->compare_values($firstnametry);

if ($check == true) {

    echo "
|--------------------|
|1: Register User    |
|2: Register time    |
|3: Check Time       |
|--------------------|
    ";
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
