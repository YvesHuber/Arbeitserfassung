<?php
require_once('person.class.php');
class Timestamp
{
    public $start;
    public $end;
    public $project;


    /**
     * __construct
     *
     * @param  mixed $start
     * @param  mixed $end
     * @param  mixed $project
     * @return void
     */
    public function __construct($username)
    {
        /*
        $this->start = $start;
        $this->end = $end;
        $this->project = $project;*/
        $this->user = $username;
        $this->save = "D:/Dokumente/010_Zli/002_Projekte/Arbeitszeitberechnung/Data/$this->user.Time.json";
    }

    /**
     * register_end
     *
     * @return void
     */
    public function register_end()
    {
        echo "end";
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
     *
     * @return void
     */
    function Check()
    {
        $jsondecode = file_get_contents($this->save);
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
     *
     * @return void
     */
    public function register_start()
    {

        echo "start";
        $project = readline("On what are you working on? ");
        $this->project = $project;
        date_default_timezone_set('Europe/Berlin');
        //Array that keeps the localtime 
        $arrayt = localtime();
        //calculates seconds rn for further use
        $timeRN = $arrayt[0] + ($arrayt[1] * 60) + ($arrayt[2] * 60 * 60); //in seconds

        $dateRN = date('Y-m-d');
        $json_already = file_get_contents($this->save);
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
     *
     * @return void
     */
    public function calculate()
    {
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
                echo "You messed up logging out\n";
                echo "You now have to enter your time manualy\n";
                $manual = readline("Your Endtime in hh:mm ");

                sscanf($manual, "%d:%d:%d", $hours, $minutes, $seconds);

                $manuals = isset($hours) ? $hours * 3600 + $minutes * 60 + $seconds : $minutes * 60 + $seconds;

                $jsondecode = file_get_contents($this->save);
                $decoded = json_decode($jsondecode, true);
                $decoded[$i][0]['Worked'] = $manuals;
                $encoded = json_encode($decoded);
                file_put_contents($this->save, $encoded);
            }
            $jsondecode = file_get_contents($this->save);
            $decoded = json_decode($jsondecode, true);
            $Zeit = gmdate("H:i:s", $result);

            $decoded[$i][0]['Worked'] = $Zeit;
            $encoded = json_encode($decoded);
            file_put_contents($this->save, $encoded);

            echo "Time Worked on project " . ($decoded)[$i][0]['Project'] . " " . $Zeit . "\n";
        }
    }
}
