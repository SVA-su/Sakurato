<?php
class ClientCommandHandler {
    public function __construct($host, $user, $password, $db) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->db = $db;
        $config = require("config.php");
        /*if($config['work'] == 'true') $this->config = true; else if($config['work'] == false) $this->config = false; else $this->config = $config['work'];
        @$this->WorkHandler($this->config)->work; */
    }
    private function WorkHandler($info){
        if($info == true){
            return 0;
        } else if($info == false) {
            die("Error: CommandHandler is down!");
        } else {
            die(printf("Error: CommandHandler is down with code: ", $info));
        }
    }
    protected $work;
    protected $config;
    protected $host;
    protected $user;
    protected $password;
    protected $db;
    public function UpApp(){
        $mysqli = mysqli_connect($this->host, $this->user, $this->password, $this->db);
        $query = "SELECT down FROM `config`";
        $result = mysqli_query($mysqli,$query);
        if($result == false){
            die("Error: select from database failed: " . mysqli_error($mysqli) . "\n");
        }
            $result = mysqli_query($mysqli, 'SELECT * FROM `config`'); // запрос на выборку
            while($row = mysqli_fetch_assoc($result)){
            if($row['down'] == '0'){
                mysqli_close($mysqli);
                die("Application is already up\n");
            } else if($row['down'] == '1'){
                $querytwo = "UPDATE `config` SET `down` = '0' WHERE `down` = '1'";
                $resulttwo = mysqli_query($mysqli,$querytwo);
                if($resulttwo == false){
                    die("Error: update to database failed: " . mysqli_error($mysqli) . "\n");
                }
                mysqli_close($mysqli);
                echo "Application is up\n";
            }
    }
}
public function DownApp(){
    $mysqli = mysqli_connect($this->host, $this->user, $this->password, $this->db);
    $query = "SELECT down FROM `config`";
    $result = mysqli_query($mysqli,$query);
    if($result == false){
        mysqli_close($mysqli);
        die("Error: select from database failed: " . mysqli_error($mysqli) . "\n");
    }
        $result = mysqli_query($mysqli, 'SELECT * FROM `config`'); // запрос на выборку
        while($row = mysqli_fetch_assoc($result)){
        if($row['down'] == '1'){
            mysqli_close($mysqli);
            die("Application is already down\n");
        } else if($row['down'] == '0'){
            $querytwo = "UPDATE `config` SET `down` = '1' WHERE `down` = '0'";
            $resulttwo = mysqli_query($mysqli,$querytwo);
            if($resulttwo == false){
                mysqli_close($mysqli);
                die("Error: update to database failed: " . mysqli_error($mysqli) . "\n");
            }
            mysqli_close($mysqli);
            echo "Application is down\n";
        }
}
}
public function CheckApp(){
    $mysqli = mysqli_connect($this->host, $this->user, $this->password, $this->db);
    $query = "SELECT down FROM `config`";
    $result = mysqli_query($mysqli,$query);
    if($result == false){
        printf("Error: select from database failed: %s\n", mysqli_error($mysqli));
		mysqli_close($mysqli);
		exit();
    }
        $result = mysqli_query($mysqli, 'SELECT * FROM `config`'); // запрос на выборку
        while($row = mysqli_fetch_assoc($result)){
        mysqli_close($mysqli);
        return $row['down'];
}
}
}
