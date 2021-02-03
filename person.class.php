<?php

class Person
{
    private $firstname;
    private $name;
    
    //public $array;    
    /**
     * __construct
     *
     * @param  mixed $firstname
     * @param  mixed $name
     * @return void
     */
    public function __construct($firstname, $name)
    {
        $this->firstname = $firstname;
        $this->name = $name;
        $this->save = "Data/Data.json";
    }

    /**
     * insert_values
     *
     * @return void
     */
    public function insert_values()
    {

        $firstname = readline("Enter your firstname ");
        $name = readline("Enter your Lastname ");
        $this->firstname = $firstname;
        $this->name = $name;
        if (file_exists($this->save)) {
        $json_already = file_get_contents($this->save);
        }
        $json = json_decode($json_already);

        $array[] = array(

        'firstname' => $this->firstname,
        'name' => $this->name

        );

        $json[] = $array;
        $json_decoded = json_encode($json);

        file_put_contents($this->save, $json_decoded);
        echo "
        Registerd new user
        ";
    }

    /**
     * check_values
     *
     * @return void
     */
    public function check_values()
    {
        $json_data = file_get_contents($this->save);
        $php_values = json_decode($json_data, false);
        print_r($php_values);
    }
    
    /**
     * compare_values
     *
     * @return void
     */
    public function compare_values($firstnametry)
    {
        
        $lastnametry = readline("Please enter your lastname \n");
        if(file_exists($this->save)) {
        $json = file_get_contents($this->save);
        }
        $decoded = json_decode($json,true);

        for($i = 0; $i < count($decoded); $i++)
        {
            if($firstnametry == ($decoded)[$i][0]['firstname'] && $lastnametry == ($decoded)[$i][0]['name'])
            {
                echo "You are now logged in Welcome " . $firstnametry . "\n";
                return true;
            }
        }
    }
}
