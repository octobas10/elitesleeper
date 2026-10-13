<?php

##################################################
###    File name: dbclass						###
###    Auther Name: Rohit Kumar					###
###    Copy Right : Braintechnosys				###
###    Date: 22/06/2010							###
###    @version 1.0								###
##################################################

/**
 * @class to handle data base connection
 */
class DbConnection
{

    private $dbhost;
    private $dbuser;
    private $dbpass;
    private $dbname;

    public function DbConnection($site = 'local')
    {
        switch ($site) {
            case 'biz':
                $this->dbhost = "localhost";
                $this->dbuser = "root";
                $this->dbpass = "bt123";
                $this->dbname = "elitesleeper_db";
                break;
            case 'live':
                $this->dbhost = "localhost";            // Change here elitelitesleeper database Host according to your database.
                $this->dbuser = "axiom";                    // Change here elitelitesleeper database Username according to your database.
                $this->dbpass = "7anAZewE";                // Change here elitelitesleeper database Password according to your database.
                $this->dbname = "db_elitesleeper";            // Change here elitelitesleeper database Name according to your database.
                break;
            default:
                $this->dbhost = "localhost";
                $this->dbuser = "root";
                $this->dbpass = "";
                $this->dbname = "db_elitesleeper";
                break;
        }
    }

    public function connect()
    {
        try {
            $GLOBALS['conn'] = mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
            //mysql_select_db($this->dbname, $conn);
            return true;
        } catch (Exception $e) {

            return false;
        }
    }
}

?>
