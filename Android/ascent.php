<?php
/**
 * Created by PhpStorm.
 * User: Milind Gour
 * Date: 9/18/15
 * Time: 5:16 PM
 */

require_once 'db.php';

class ASCENT {

    const ERR_MSG_NO_DATABASE = 'Cannot connect to database';
    const ERR_MSG_NO_REGION = 'Cannot fetch any region';
    const ERR_MSG_INSERT_EMPLOYEE = 'Cannot register the given employee details';
    const ERR_MSG_INSERT_FEEDBACK = 'Cannot register the feedback';
    const ERR_MSG_INSERT_FEEDBACK_DATE = 'You cannot give feedback to future dates';
    const ERR_MSG_INSERT_EMPLOYEE_DUPLICATE = 'User has already registered before';
    const ERR_MSG_INSERT_FEEDBACK_DUPLICATE = 'You can give feedback only once per session';
    const ERR_MSG_INSERT_SCHEDULE_STATEMENT = 'Cannot prepare the statement';
    const ERR_MSG_INSERT_SCHEDULE = 'Cannot update the schedules';
    const ERR_MSG_NO_SCHEDULE = 'No schedule for selected date';
    const ERR_MSG_INVALID_RATING = 'Rating can only be between 1 and 5.';
    const ERR_MSG_BLANK_COMMENTS = 'Comments cannot be blank.';
    const MSG_INSERT_EMPLOYEE_SUCCESS = 'Successfully registered';
    const MSG_INSERT_FEEDBACK_SUCCESS = 'Successfully registered the feedback';
    const MSG_INSERT_SCHEDULE_SUCCESS = 'Successfully updated the schedules';
    private function getConnection() {
        $connection = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
        return $connection;
    }

    public function queryToFile($query) {

        $connection = $this->getConnection();
        if ($connection->connect_errno) {
            return false;
        }

        $result = $connection->query($query);
        $data = '"Session Date","Location","Activity","Employee ID","Employee Name","Rating Given","Comments"';

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $newRow = PHP_EOL.'"' .$row['sched_date']. '","' .$row['region_name']. '","' .$row['sched_activity']. '","' .$row['emp_id']. '","' .$row['emp_name']. '","' .$row['feed_rating']. '","' .$row['feed_comment']. '"';
                $data .= $newRow;
            }

        } else {
            die('There are no feedbacks in the selected criteria.') ;
        }

        $connection->close();
        return $data;
    }

/*    public function queryToFile($query) {

        $connection = $this->getConnection();
        if ($connection->connect_errno) {
            return false;
        }

        $tempFile = str_replace("\\", "/", __DIR__).'/tmp_files/'.uniqid('tmpFile');
        $realQuery = $query." INTO OUTFILE '$tempFile' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n'";

        $connection->query($query);

        $fileHandle = fopen($tempFile, 'r');
        fseek($fileHandle, 0, SEEK_END);
        $sizeInBytes = ftell($fileHandle);
        fseek($fileHandle, 0);


        if ($sizeInBytes > 0)
            $fileContents = fread($fileHandle, $sizeInBytes);
        else
            $fileContents = false;


        fclose($fileHandle); // delete the temp file
        unlink($tempFile);
        $connection->close();

        if ($fileContents !== false) {
            $headers = '"Session Date","Location","Activity","Employee ID","Employee Name","Rating Given","Comments"';
            $newContents = $headers.PHP_EOL.$fileContents;
        } else {
            die('No data present to generate a report');
        }

        return $newContents;
    }*/


    public function getSessionList($date, $region) {
        $type = 'SESSION_LIST';
        $connection = $this->getConnection();
        if ($connection->connect_errno) {
            return $this->toJSONString($type, false, ASCENT::ERR_MSG_NO_DATABASE);
        }
        $sqlQuery = "SELECT * FROM ascent_db.schedule WHERE sched_date='$date' AND sched_faculty > ''";
        if ($region != 0)
            $sqlQuery .= " AND sched_region=$region";

        $reply = $connection->query($sqlQuery);

        //$allRows = $reply->fetch_all(MYSQL_ASSOC);
        $result = array();
        while ($row = $reply->fetch_assoc()) {
            array_push($result, $row);
        }

        $connection->close();
        return $this->toJSONString($type, true, $result);
    }
    public function getRegionListArray() {
        $connection = $this->getConnection();
        $result = array();

        $query = "SELECT * FROM ascent_db.region";
        $reply = $connection->query($query);

        //$result = $reply->fetch_all(MYSQL_ASSOC);
        $result = array();
        while ($row = $reply->fetch_assoc()) {
            array_push($result, $row);
        }

        $connection->close();
        return $result;
    }
    // Gets a list of all the available regions in the database
    public function getRegionList() {
        $type = 'REGION_LIST';
        $connection = $this->getConnection();
        if ($connection->connect_errno) {
            return $this->toJSONString($type, false, ASCENT::ERR_MSG_NO_DATABASE);
        }
        $sqlQuery = 'SELECT * FROM ascent_db.region';
        $reply = $connection->query($sqlQuery);
        if ($reply->num_rows == 0) {
            return $this->toJSONString($type, false, ASCENT::ERR_MSG_NO_REGION);
        }

        //$allRows = $reply->fetch_all(MYSQL_ASSOC);
        $result = array();
        while ($row = $reply->fetch_assoc()) {
            array_push($result, $row);
        }

        $connection->close();
        return $this->toJSONString($type, true, $result);
    }

    // Registers an employee with the system
    public function insertEmployee($empId, $empName, $regionId, $emailId) {

        $empName = ucwords($empName);
        $type = 'REGISTER_EMPLOYEE';
        $connection = $this->getConnection();
        if ($connection->connect_errno) {
            return $this->toJSONString($type, false, ASCENT::ERR_MSG_NO_DATABASE);
        }
        $sqlQuery = "INSERT INTO ascent_db.employee values ($empId, '$empName', $regionId, '$emailId');";
        $reply = $connection->query($sqlQuery);

        if (!$reply) {

            if ($connection->errno == 1062 || $connection->errno == 0)
            {
                $sqlQuery = "UPDATE ascent_db.employee set emp_name='$empName', emp_region=$regionId, emp_email='$emailId' WHERE emp_id=$empId";
                $connection->query($sqlQuery);
                return $this->toJSONString($type, true, ASCENT::ERR_MSG_INSERT_EMPLOYEE_DUPLICATE);
            }

            return $this->toJSONString($type, false, ASCENT::ERR_MSG_INSERT_EMPLOYEE);
        }

        $connection->close();
        return $this->toJSONString($type, true, ASCENT::MSG_INSERT_EMPLOYEE_SUCCESS);
    }

    public function insertIntoScheduleByCSVContents($contents, $regionId) {
        $type = "INSERT_BULK";
        $connection = $this->getConnection();
        if ($connection->connect_errno) {
            return $this->toJSONString($type, false, ASCENT::ERR_MSG_NO_DATABASE);
        }

        $date = "sample date";
        $time = "sample time";
        $activity = "sample activity";
        $faculty = "sample faculty";
        $region = $regionId; /* HARDCODED FOR NOW, CHANGE IT IN THE PRODUCTION */

        $stmt = $connection->prepare("INSERT INTO ascent_db.schedule (sched_date, sched_time, sched_activity, sched_faculty, sched_region) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            return false;//$this->toJSONString($type, false, ASCENT::ERR_MSG_INSERT_SCHEDULE_STATEMENT);
        }
        $stmt->bind_param("ssssi", $date, $time, $activity, $faculty, $region);


        $lines = explode(PHP_EOL, $contents);

        $master = array();

        $dateString = "";
        $dayString = "";

        for ($i = 1; $i < sizeof($lines); $i++) {

            $singleExplode = str_getcsv($lines[$i]);

            //$singleExplode = explode(",", $lines[$i]);

            //changes in the array
            if (strlen($lines[$i]) > 5) {

                if ($singleExplode[0] == "") {
                    $singleExplode[0] = $dateString;
                }
                else  {
                    $d = date_parse($singleExplode[0]);
                    $dateString = $d['year'].'-'.$d['month'].'-'.$d['day'];
                    $singleExplode[0] = $dateString;
                }

                array_push($master, $singleExplode);

                //push the contens of the $singleExplode into the database
                $date = $singleExplode[0];
                $time = $singleExplode[2];
                $activity = $singleExplode[3];
                $faculty = $singleExplode[4];

                $resultLast = $stmt->execute();

                if ($resultLast == false) {
                    return false; //$this->toJSONString($type, false, ASCENT::ERR_MSG_INSERT_SCHEDULE);
                }
            }
        }

        return true; //$this->toJSONString($type, true, ASCENT::MSG_INSERT_SCHEDULE_SUCCESS);

    }

    public function getSchedule($date, $regionId) {
        $type = 'SCHEDULE';
        $connection = $this->getConnection();
        if ($connection->connect_errno) {
            return $this->toJSONString($type, false, ASCENT::ERR_MSG_NO_DATABASE);
        }
        $sqlQuery = "SELECT * FROM ascent_db.schedule WHERE sched_date='$date' AND sched_region=$regionId";
        $reply = $connection->query($sqlQuery);
        if ($reply->num_rows == 0) {
            return $this->toJSONString($type, false, ASCENT::ERR_MSG_NO_SCHEDULE);
        }

        //$allRows = $reply->fetch_all(MYSQL_ASSOC);
        $result = array();
        while ($row = $reply->fetch_assoc()) {
            array_push($result, $row);
        }

        $connection->close();
        return $this->toJSONString($type, true, $result);
    }

    public function storeFeedback($schedId, $empId, $rating, $comments) {
        $type = 'FEEDBACK';

        if ($rating > 5 || $rating < 1)
            return $this->toJSONString($type, false, ASCENT::ERR_MSG_INVALID_RATING);
        if ($comments == '') {
            return $this->toJSONString($type, false, ASCENT::ERR_MSG_BLANK_COMMENTS);
        }

        $connection = $this->getConnection();
        if ($connection->connect_errno) {
            return $this->toJSONString($type, false, ASCENT::ERR_MSG_NO_DATABASE);
        }

        //check for previous feedback by same empId and for same feed_sched
        $query = "SELECT COUNT(*) from ascent_db.feedback WHERE feed_emp = $empId AND feed_sched = $schedId";
        $r = $connection->query($query);
        $isThere = $r->fetch_array()[0];

        if ($isThere) {
            return $this->toJSONString($type, false, ASCENT::ERR_MSG_INSERT_FEEDBACK_DUPLICATE);
        }

        //check whether today is less than the sched_date in schedule table
        $query = "SELECT sched_date from ascent_db.schedule WHERE sched_id = $schedId";
        $r = $connection->query($query);

        $today = date('Y-m-d');
        $row = $r->fetch_array();
        $sched_date = $row[0];

        if ($sched_date > $today) {
            return $this->toJSONString($type, false, ASCENT::ERR_MSG_INSERT_FEEDBACK_DATE);
        }

        $sqlQuery = "INSERT INTO `ascent_db`.`feedback`(`feed_date`,`feed_sched`,`feed_emp`,`feed_rating`,`feed_comment`)VALUES(NOW(), $schedId, $empId, $rating, '$comments')";
        $reply = $connection->query($sqlQuery);

        if (!$reply) {
            return $this->toJSONString($type, false, ASCENT::ERR_MSG_INSERT_FEEDBACK);
        }

        $connection->close();
        return $this->toJSONString($type, true, ASCENT::MSG_INSERT_FEEDBACK_SUCCESS);
    }

    public function toJSONString($type, $success, $resp) {
        $response = array();
        $response['response_type'] = $type;
        $response['success'] = $success;

        if ($success == false) {
            $response['error_msg'] = $resp;
        } else {
            $response['response'] = $resp;
        }

        $jsonString = json_encode($response);
        return $jsonString;
    }

}