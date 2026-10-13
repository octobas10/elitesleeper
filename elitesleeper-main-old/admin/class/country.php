<?php

##################################################
###    File name: dbclass						###
###    Auther Name: Amar  Gupta					###
###    Copy Right : Braintechnosys				###
###    Date: 22/06/2010							###
###    @version 1.1								###
##################################################

/**
 * @class to handle functions related to user table
 */
class country
{
    private $dbUserTable = 'elitesleeper_state';
    private $dbUserTable2 = 'elitesleeper_country';

    public function load($id)
    {
        if ($id == "") {
            echo "Id is missing";
            return;
        }
        $query = "SELECT * FROM $this->dbUserTable WHERE id='$id' ";
        $result = mysqli_query($GLOBALS['conn'], $query) or die("sorry some sql error");
        if (mysqli_num_rows($result)) {
            return mysqli_fetch_array($result);
        }
        return array();
    }

    public function fetchState($id)
    {
        $query = "SELECT * FROM $this->dbUserTable where id='$id'";
        $result = mysqli_query($GLOBALS['conn'], $query) or die("Sorry some sql error");

        $row2 = mysqli_fetch_array($result);
        return $row2['name'];
    }

    public function selectState($id)
    {
        $query = "SELECT * FROM $this->dbUserTable where id='$id'";
        $result = mysqli_query($GLOBALS['conn'], $query) or die("Sorry some sql error");

        $row2 = mysqli_fetch_array($result);
        return $row2;
    }

    public function fetchAll($cid)
    {
        $query = "SELECT * FROM $this->dbUserTable where country_id='$cid'";
        $result = mysqli_query($GLOBALS['conn'], $query) or die("Sorry some sql error");
        if (mysqli_num_rows($result)) {
            $RecordSet = array();
            while ($row = mysqli_fetch_array($result)) {
                $RecordSet[] = $row;
            }
            return $RecordSet;
        }
        return array();
    }

    public function fetchRow($id)
    {
        $query = "SELECT * FROM $this->dbUserTable2 WHERE id='$id' ";
        $result = mysqli_query($GLOBALS['conn'], $query) or die("Sorry some sql error");


        $row = mysqli_fetch_array($result);

        $RecordSet = $row['shipping_charge'];

        return $RecordSet;


    }

    public function checkState($state, $cid)
    {
        $query = "SELECT * FROM $this->dbUserTable WHERE id='$state' and country_id='$cid' ";
        $result = mysqli_query($GLOBALS['conn'], $query) or die("Sorry some sql error");


        $row = @mysqli_num_rows($result);

        return $row;


    }

    public function fetchCountry()
    {
        $query = "SELECT * FROM $this->dbUserTable2 WHERE status = 'Active'";
        $result = mysqli_query($GLOBALS['conn'], $query) or die("Sorry some sql error");
        return $result;
    }

    public function fetchUsState()
    {
        $query = "SELECT * FROM $this->dbUserTable where country_id='1'";
        $result = mysqli_query($GLOBALS['conn'], $query) or die("Sorry some sql error");
        //echo $query;
        return $result;
    }

    public function getCountryNameById($country_id)
    {
        $query = "SELECT * FROM $this->dbUserTable2 where id='$country_id'";
        $result = mysqli_query($GLOBALS['conn'], $query) or die("Sorry some sql error");
        $rec = mysqli_fetch_assoc($result);
        return $rec['name'];
    }
}


?>