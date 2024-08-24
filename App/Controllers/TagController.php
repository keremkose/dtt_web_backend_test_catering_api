<?php

namespace App\Controllers;



use App\Controllers\BaseController;
use App\Plugins\Db\Db;
use App\Plugins\Di\Factory;
use DbService;
use Exception;

class TagController extends BaseController
{
    public DbService $dbService;

    public function __construct()
    {
        $this->dbService = Factory::getDi()->getShared('DbService');
    }
    public function getTag()
    {
        $this->dbService->dbGetAll();
    }
    public function deleteTag()
    {
        $obj = $this->dbService->objectCreatorFromBodyData();
        if (is_array($obj)) {
            $this->dbService->p("Please input one object on each process.");
            throw new Exception();
        }
        $this->dbService->dbDeleteById($obj->Id);
    }
    public function postTag()
    {
        $this->dbService->dbCreate();
    }
    public function putTag()
    {
         $this->dbService->dbUpdate();
    }
}
