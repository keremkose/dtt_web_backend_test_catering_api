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

    public function getFacility()
    {
        $this->dbService->dbGetAll();
    }

    public function getsingleFacility($id)
    {
        $query = "SELECT * FROM facility f  join location l on f.LocationId=l.Id left JOIN facilitytags ft on f.Id= ft.FacilityId where f.Id=$id";
        $result = $this->dbService->dbGetSingleByQueryGeneral($query);
        print_r($result);
    }

    public function deleteFacility()
    {
        $obj = $this->dbService->objectCreatorFromBodyData();
        if (is_array($obj)) {
            $this->dbService->p("Please input one object on each process.");
            throw new Exception();
        }
        $this->dbService->dbDeleteById($obj->Id);
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
