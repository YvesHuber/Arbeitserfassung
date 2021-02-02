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
        $this->save = "D:/Dokumente/010_Zli/002_Projekte/Arbeitszeitberechnung/Data/Data.json";
    }

    /**
     * insert_values
     *
     * @return void
     */
    public function insert_values()
    {

        $firstname = readline("geben Sie ihren Namen ein ");
        $name = readline("geben Sie ihren Nachnamen ein ");
        $this->firstname = $firstname;
        $this->name = $name;
        
        $json_already = file_get_contents($this->save);
        $json = json_decode($json_already);

        $array[] = array(

        'firstname' => $this->firstname,
        'name' => $this->name

        );

        $json[] = $array;
        $json_decoded = json_encode($json);

        file_put_contents($this->save, $json_decoded);
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
        
        $lastnametry = readline("\nLastname ");

        $json = file_get_contents($this->save);
        $decoded = json_decode($json,true);

        for($i = 0; $i < count($decoded); $i++)
        {
            if($firstnametry == ($decoded)[$i][0]['firstname'] && $lastnametry == ($decoded)[$i][0]['name'])
            {
                echo "Logged in \n";
                return true;
            }
        }
    }
}
