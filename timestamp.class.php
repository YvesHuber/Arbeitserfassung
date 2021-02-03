
<?php
require_once('person.class.php');

/**
 * Timestamp
 */
class Timestamp
{
    public $start;
    public $end;
    public $project;



    /**
     * __construct
     *  constructor username has to be given
     * @param  mixed $username
     * @return void
     */
    public function __construct($username)
    {
        /*
        $this->start = $start;
        $this->end = $end;
        $this->project = $project;*/
        $this->user = $username;
        $this->save = "Data/$this->user.Time.json";
    }


    /**
     * register_end
     *  Registers the Endtime of the User that is logged in
     * @return void
     */
    public function register_end()
    {
        echo "Ended the Action ";
        date_default_timezone_set('Europe/Berlin');
        //Array that keeps the localtime 
        $arrayt = localtime();
        //calculates seconds rn for further use
        $timeRN = $arrayt[0] + ($arrayt[1] * 60) + ($arrayt[2] * 60 * 60); //in seconds

        $jsondecode = file_get_contents($this->save);
        $decoded = json_decode($jsondecode, true);
        for ($i = 0; $i < count($decoded); $i++) {
            if ($decoded[$i][0]['Endtime'] == NULL) {
                $decoded[$i][0]['Endtime'] = $timeRN;
                $encoded = json_encode($decoded);
                file_put_contents($this->save, $encoded);
            }
        }
    }


    /**
     * Check
     *  Checks which Function has to be called
     * @return void
     */
    function Check()
    {
        if (file_exists($this->save)) {
            $jsondecode = file_get_contents($this->save);
        }

        $decoded = json_decode($jsondecode, true);
        if ($decoded == NULL) {
            $this->register_start();
        } else {
            for ($i = 0; $i < count($decoded) + 1; $i++) {

                if (($decoded)[$i][0]['Starttime'] == NULL && ($decoded)[$i][0]['Endtime'] == NULL) {
                    $this->register_start();
                }
                if (($decoded)[$i][0]['Endtime'] == "" && ($decoded)[$i][0]['Starttime'] != NULL) {
                    $this->register_end();
                    break;
                }
            }
        }
    }

    /**
     * register_start
     *  Registers the starttime from your localtime
     * 
     * @return void
     */
    public function register_start()
    {

        echo "Started a New Action ";
        $project = readline("On what project are you working on? ");
        $this->project = $project;
        date_default_timezone_set('Europe/Berlin');
        //Array that keeps the localtime 
        $arrayt = localtime();
        //calculates seconds rn for further use
        $timeRN = $arrayt[0] + ($arrayt[1] * 60) + ($arrayt[2] * 60 * 60); //in seconds

        $dateRN = date('Y-m-d');
        if (file_exists($this->save)) {
            $json_already = file_get_contents($this->save);
        }
        $json = json_decode($json_already);

        $array[] = array(

            'Time' => $dateRN,
            'Starttime' => $timeRN,
            'Endtime' => "",
            'Project' => $this->project,
            'Worked' => ""

        );

        $json[] = $array;
        $json_decoded = json_encode($json);

        file_put_contents($this->save, $json_decoded);
    }

    /**
     * calculate
     * calculates the worktime and gives an Error if the difference is too big
     * @return void
     */
    public function calculate()
    {
        if (file_get_contents($this->save)) {
            $result = 0;
            $jsondecode = file_get_contents($this->save);
            $decoded = json_decode($jsondecode, true);
            for ($i = 0; $i < count($decoded); $i++) {


                if (($decoded)[$i][0]['Endtime'] == "") {
                    echo "The Project " . ($decoded)[$i][0]['Project'] . " isnt finished yet";
                    exit();
                }
                $result = ($decoded)[$i][0]['Endtime'] - ($decoded)[$i][0]['Starttime'];
                if ($result > 43200) {
                    echo "You messed up logging out";
                    echo "You now have to enter your time manualy";
                    $manual = readline("Your Endtime in hh:mm ");

                    sscanf($manual, "%d:%d:%d", $hours, $minutes, $seconds);

                    $manuals = isset($hours) ? $hours * 3600 + $minutes * 60 + $seconds : $minutes * 60 + $seconds;
                    echo $manuals;

                    $jsondecode = file_get_contents($this->save);
                    $decoded = json_decode($jsondecode, true);
                    $decoded[$i][0]['Endtime'] = $manuals;
                    $encoded = json_encode($decoded);
                    file_put_contents($this->save, $encoded);
                }
                $jsondecode = file_get_contents($this->save);
                $decoded = json_decode($jsondecode, true);
                $Zeit = gmdate("H:i:s", $result);

                $decoded[$i][0]['Worked'] = $Zeit;
                $encoded = json_encode($decoded);
                file_put_contents($this->save, $encoded);

                if (($decoded)[$i][0]['Endtime'] != "") {
                    $diff =  28415 - $result;
                    $difft = gmdate("H:i:s", $diff);
                    echo "you have to work for " . $difft . " on Project " . ($decoded)[$i][0]['Project'];
                }
            }
        } else {
            echo "There is no Time registerd for this user ";
        }
    }
}
