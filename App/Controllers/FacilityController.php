<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Facility;
use App\Entities\Tag;
use App\Plugins\Di\Factory;
use DbService;
use Exception;

class FacilityController extends BaseController
{

    public DbService $dbService;

    private string $tableName = "Facility";
    public function __construct()
    {
        $this->dbService = Factory::getDi()->getShared('DbService');
    }

    public function getallFacility()
    {
        $result = $this->dbService->dbGetAll();
        print_r($result);
    }
    public function getsingleFacility($id)
    {
        $result = $this->dbService->dbGetByIdAndTableName($id, $this->tableName);
        print_r($result);
    }
    public function getallextendedFacility()
    {
        $facilityName = isset($_GET['facilityName']) ? $_GET['facilityName'] : null;
        $tagName = isset($_GET['tagName']) ? $_GET['tagName'] : null;
        $city = isset($_GET['city']) ? $_GET['city'] : null;
        $query = "SELECT * FROM facility f  join `location` l on f.LocationId=l.Id left JOIN facilitytags ft on f.Id= ft.FacilityId WHERE 1=1";
        if ($facilityName !== null) {
            $query .= " AND f.Name LIKE '%$facilityName%'";
        }
        if ($tagName !== null) {
            $query .= " AND ft.TagName LIKE '%$tagName%'";
        }
        if ($city !== null) {
            $query .= " AND l.City LIKE '%$city%'";
        }
        $this->dbService->p($query);
        $result = $this->dbService->dbGetByQuery($query);
        print_r($result);
    }
    public function getsingleextendedFacility($id)
    {
        $query = "SELECT * FROM facility f  join location l on f.LocationId=l.Id left JOIN facilitytags ft on f.Id= ft.FacilityId where f.Id=$id";
        $result = $this->dbService->dbGetByQuery($query);
        print_r($result);
    }
    public function deletesingleFacility($id)
    {
        $query = "DELETE FROM facilitytags where FacilityId=$id";
        $this->dbService->dbGetByQuery($query);

        $query = "DELETE FROM facility where Id=$id";
        $this->dbService->dbGetByQuery($query);
    }
    public function postFacility()
    {
        $this->dbService->dbCreate();
    }
    public function putFacility()
    {
        $this->dbService->dbUpdate();
    }
}
