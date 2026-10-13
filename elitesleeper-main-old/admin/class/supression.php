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
class Supression
{
    private $dbUserTable = 'emate_supression';

    public function trash($id)
    {
        if (!$id) {
            echo "id is missing";
            return;
        }

        $query = "delete from $this->dbUserTable where id=$id";
        return mysqli_query($GLOBALS['conn'], $query);
    }


    public function load($id)
    {
        if (!$id) {
            echo "id is missing";
            return;
        }
        $result = mysqli_query($GLOBALS['conn'], "SELECT * FROM $this->dbUserTable where id='$id'") or die("Wrong sql");
        if (mysqli_num_rows($result))
            return mysqli_fetch_array($result);
        else
            return array();
    }


    public function removeSupression()
    {

        $ids = implode(",", $_POST['users']);

        $query = "DELETE FROM $this->dbUserTable WHERE id in ($ids)";
        return mysqli_query($GLOBALS['conn'], $query);

    }


    public function updateSupression($data, $primarykey, $id)
    {

        if (count($data) == 0) {
            echo "Sorry no data to update";
            return;
        }
        $query = "update $this->dbUserTable set ";
        $temp = array();
        foreach ($data as $key => $value) {
            if ($key != $primarykey) {
                $temp[] = "$key = '$value'";
            }
        }
        $query .= implode(",", $temp);
        $query .= " where $primarykey='$id'";
        return mysqli_query($GLOBALS['conn'], $query) or die("sql error");
    }


    public function fetchRow($condition = "")
    {
        $query = "SELECT * FROM $this->dbUserTable ";
        if ($condition != "") {
            $query .= " $condition";
        }
        //return $query;
        return mysqli_query($GLOBALS['conn'], $query);
    }


    public function fetchAll($condition = "", $order = "", $limit = "")
    {
        $query = "SELECT * FROM $this->dbUserTable";
        if ($condition != "") {
            $query .= " $condition";
        }
        if ($order != "") {
            $query .= " $order";
        }
        if ($limit != "") {
            $query .= " $limit";
        }
        return mysqli_query($GLOBALS['conn'], $query);
    }


    public function totalNumberOfRows($condition = "", $order = "", $limit = "")
    {
        $result = $this->fetchAll($condition, $order, $limit);
        return mysqli_num_rows($result);
    }


    ################ function for check exists mail id ##################

    public function email_exists($id, $email)
    {
        $query = "select * from $this->dbUserTable where id!='" . $id . "' and email='" . $email . "'";
        $data = mysqli_query($GLOBALS['conn'], $query);
        $res = mysqli_num_rows($data);
        if ($res > 0) {
            $result = "1";
        } else {
            $result = "0";
        }
        return $result;
    }

    public $elitesleeper_coreg_users = "elitesleeper_coreg_users";

    public function email_exists2($email)
    {
        //$query = "select * from $this->dbUserTable where  email='" . $email . "'";
        $query = "select * from $this->elitesleeper_coreg_users where  email='" . $email . "'";
        $data = mysqli_query($GLOBALS['conn'], $query);
        $res = mysqli_num_rows($data);
        if ($res > 0) {
            $result = "1";
        } else {
            $result = "0";
        }
        return $result;
    }


    public function insertSupression($data)
    {
        $query = "insert into $this->dbUserTable set ";
        $temp = array();
        foreach ($data as $key => $value) {

            $temp[] = "$key = '$value'";
        }
        $query .= implode(",", $temp);
        //$query.=" , create_date=NOW() ";
        //echo $query;
        mysqli_query($GLOBALS['conn'], $query);
        return mysqli_insert_id();
    }


    public function expiryDate($id)
    {
        $query = "SELECT * FROM $this->dbPaymentTable as p INNER JOIN $this->dbUserTable as u ON p.user_id = u.id where p.user_id = $id order by p.id desc limit 1";
        $data = mysqli_query($GLOBALS['conn'], $query) or die('not ruuning');
        while ($res = mysqli_fetch_array($data)) {
            $date = $res['payment_date'];
        }
        return $date;
    }


    public function subscriptionValidDate($id)
    {
        $query = "select DATE_FORMAT(DATE_ADD(max(payment_date), INTERVAL 30 day), '%Y-%m-%d') as validity_upto from tbl_payments where user_id=$id";
        $data = mysqli_query($GLOBALS['conn'], $query) or die('Sorry some error');
        while ($res = mysqli_fetch_array($data)) {
            $date = $res['validity_upto'];
        }
        return $date;
    }

    function suppressedusers($datauser)
    {
        $email = $datauser['email'];
        $date = date("Y-m-d");
        $suprission_status = 1;
        $query = mysqli_query($GLOBALS['conn'], "UPDATE $this->elitesleeper_coreg_users SET suprission_status='1',date='$date' WHERE email='$email'") or die;
        return $query;

    }
}

?>
