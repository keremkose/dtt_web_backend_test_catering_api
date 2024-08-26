<?php

namespace App\Controllers;




use App\Controllers\BaseController;
use App\Plugins\Di\Factory;
use DbService;
use Exception;

class LocationController extends BaseController
{


    public DbService $dbService;

    public function __construct()
    {
        $this->dbService = Factory::getDi()->getShared('DbService');
    }

    public function getLocation()
    {
        $this->dbService->dbGetAll();
    }
    public function deleteLocation()
    {
        $obj = $this->dbService->objectCreatorFromBodyData();
        if (is_array($obj)) {
            throw new Exception("Please input one object on each process.");
        }
        $this->dbService->dbDeleteById($obj->Id);
    }
    public function postLocation()
    {
        $this->dbService->dbCreate();
    }
    public function putLocation()
    {
        // $this->dbService->dbUpdate();
    }
}
