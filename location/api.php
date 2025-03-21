<?php

require_once("dbconfig.php");

class Location extends dbconfig {

    function __construct() {
        parent::__construct();
    }

    // Fetch all countries
    public static function getCountries() {
        try {
            $query = "SELECT id, name FROM countries";
            $stmt = dbconfig::getConnection()->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $res = [];
            while ($row = $result->fetch_assoc()) {
                $res[$row['id']] = $row['name'];
            }

            $data = [
                'status' => 'success',
                'tp' => 1,
                'msg' => "Countries fetched successfully.",
                'result' => $res
            ];
        } catch (Exception $e) {
            $data = [
                'status' => 'error',
                'tp' => 0,
                'msg' => $e->getMessage()
            ];
        }
        echo json_encode($data);
    }

    // Fetch states based on country ID
    public static function getStates($countryId) {
        try {
            $query = "SELECT id, name FROM states WHERE country_id = ?";
            $stmt = dbconfig::getConnection()->prepare($query);
            $stmt->bind_param("i", $countryId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $res = [];
            while ($row = $result->fetch_assoc()) {
                $res[$row['id']] = $row['name'];
            }

            $data = [
                'status' => 'success',
                'tp' => 1,
                'msg' => "States fetched successfully.",
                'result' => $res
            ];
        } catch (Exception $e) {
            $data = [
                'status' => 'error',
                'tp' => 0,
                'msg' => $e->getMessage()
            ];
        }
        echo json_encode($data);
    }

    // Fetch cities based on state ID
    public static function getCities($stateId) {
        try {
            $query = "SELECT id, name FROM cities WHERE state_id = ?";
            $stmt = dbconfig::getConnection()->prepare($query);
            $stmt->bind_param("i", $stateId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $res = [];
            while ($row = $result->fetch_assoc()) {
                $res[$row['id']] = $row['name'];
            }

            $data = [
                'status' => 'success',
                'tp' => 1,
                'msg' => "Cities fetched successfully.",
                'result' => $res
            ];
        } catch (Exception $e) {
            $data = [
                'status' => 'error',
                'tp' => 0,
                'msg' => $e->getMessage()
            ];
        }
        echo json_encode($data);
    }
}

// Handle API Requests
if (isset($_GET['type'])) {
    $location = new Location();
    
    switch ($_GET['type']) {
        case 'getCountries':
            $location->getCountries();
            break;
        case 'getStates':
            if (isset($_GET['countryId']) && is_numeric($_GET['countryId'])) {
                $location->getStates($_GET['countryId']);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Invalid country ID']);
            }
            break;
        case 'getCities':
            if (isset($_GET['stateId']) && is_numeric($_GET['stateId'])) {
                $location->getCities($_GET['stateId']);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Invalid state ID']);
            }
            break;
        default:
            echo json_encode(['status' => 'error', 'msg' => 'Invalid request type']);
    }
} else {
    echo json_encode(['status' => 'error', 'msg' => 'No request type specified']);
}
