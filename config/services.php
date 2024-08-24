<?php

require_once '../vendor/autoload.php';

use App\Plugins;
use App\Entities\BaseEntity;
use App\Entities\Facility;
use App\Entities\Location;
use App\Entities\Tag;
use App\Plugins\Db\Db;
use App\Plugins\Di\Factory;

$di = Factory::getDi();

$di->setShared('router', function () {
    return new Bramus\Router\Router();
});

$di->setShared('db', function () use ($config) {
    $dbConfig = $config['db'];
    $connectionInterface = new Plugins\Db\Connection\Mysql(
        $dbConfig['host'],
        $dbConfig['database'],
        $dbConfig['username'],
        $dbConfig['password'],
    );
    $db = new Plugins\Db\Db($connectionInterface);
    $dbAdapter = new Plugins\Db\Adapters\MySql();
    $dbAdapter->setDb($db);
    return $db;
});

$di->setShared('DbService', function () {
    return new DbService();
});


class DbService
{
    public Db $_db;
    public function __construct()
    {
        $this->_db = Factory::getDi()->getShared('db');
    }

    private string $_tableName = "";

    function findAndGetFirstAllCapsLetter($inputString)
    {
        for ($i = 0; $i < strlen($inputString); $i++) {
            $char = $inputString[$i];
            if (ctype_upper($char)) {
                return substr($inputString, $i);
            }
        }
        return null;
    }
    function currentTableNameSetter()
    {
        if (empty($this->_tableName))
            $this->_tableName = $this->findAndGetFirstAllCapsLetter(debug_backtrace()[2]['function']);
    }
    function tableNameGetterByClass(BaseEntity $obj): string
    {
        switch (gettype($obj)) {
            case $obj instanceof Facility:
                return "Facility";
                break;
            case $obj instanceof Location:
                return "Location";
                break;
            case  $obj instanceof Tag:
                return "Tag";
                break;
            default:
                throw new Exception("No such a table");
                break;
        }
    }
    function classGetterByTableName(string $tableName): BaseEntity
    {
        switch ($tableName) {
            case "Facility":
                return new Facility;
                break;
            case "Location":
                return new Location;
                break;
            case "Tag":
                return new Tag;
                break;

            default:
                throw new Exception("No such a class");
                break;
        }
    }
    function objectToBaseEntityConverter($object): BaseEntity //TODO
    {
        $oldReflection = new ReflectionObject($object);
        $reflectionArray = [
            new ReflectionClass(Facility::class),
            new ReflectionClass(Location::class),
            new ReflectionClass(Tag::class)
        ];
        foreach ($reflectionArray as $reflection) {
            $classProperties = $reflection->getProperties();
            $match = true;
            foreach ($classProperties as $property) {
                $propertyName = $property->getName();

                if (!$oldReflection->hasProperty($propertyName)) {
                    $match = false;
                }
            }
            if ($match) {
                $matchedClassName = $reflection->getName();
                $newObject = new $matchedClassName();
                foreach ($oldReflection->getProperties() as $property) {
                    $property->setAccessible(true);
                    $value = $property->getValue($object);

                    if ($reflection->hasProperty($property->getName())) {
                        $newProperty = $reflection->getProperty($property->getName());
                        $newProperty->setAccessible(true);
                        $newProperty->setValue($newObject, $value);
                    }
                }

                return $newObject;
            }
        }
        return null;
    }
    function queryFetchSingleObject($query): object | null
    {
        $this->_db->executeQuery($query);
        $object = $this->_db->getStatement()->fetchObject();
        if ($object == false)
            return null;
        return $object;
    }
    function dbGetAll(): array | object
    {
        $this->currentTableNameSetter();
        $tableName = $this->_tableName;
        $query = "SELECT * FROM $tableName";
        $objects = [];
        $this->_db->executeQuery($query);
        while ($object = $this->_db->getStatement()->fetchObject()) {
            $objects[] = $object;
        }
        return $objects;
    }
    function dbGetSingleByQueryGeneral($query)
    {
        $this->_db->executeQuery($query);
        $objects = [];
        while ($object = $this->_db->getStatement()->fetchObject()) {
            $objects[] = $object;
        }
        return $objects;
    }
    // function dbGetSingleByQueryGeneral($query)
    // {
    //     $results = $this->queryFetchSingleObject($query);
    //     return $results;
    //     print_r($results);
    // }
    function dbGetSingleByQueryCurrentTable($condition)
    {
        $this->currentTableNameSetter();
        $tableName = $this->_tableName;
        $query = "SELECT * FROM $tableName WHERE $condition";
        $results = $this->queryFetchSingleObject($query);
        return $results;
        print_r($results);
    }
    function dbGetByIdAndTableName(int $id, string $tableName): BaseEntity | null
    {
        $query = "SELECT * FROM $tableName WHERE Id = $id";
        $object = $this->queryFetchSingleObject($query);
        if (!is_null($object)) {
            return  $this->objectToBaseEntityConverter($object);
        }
        if (is_null($object)) {
            return null;
        }
    }
    function dbObjectGetByObject(BaseEntity $oldObject): BaseEntity | null
    {
        $tableName = $this->tableNameGetterByClass($oldObject);
        $query = "SELECT * FROM $tableName WHERE Id = $oldObject->Id";
        $object = $this->queryFetchSingleObject($query);
        $this->p($object);
        if (!is_null($object)) {
            return  $this->objectToBaseEntityConverter($object);
        }
        if (is_null($object)) {
            return null;
        }
    }
    function dbDeleteById(int $id) //TODO it might need to get table name also.
    {
        $this->currentTableNameSetter();
        $object = $this->dbGetByIdAndTableName($id, $this->_tableName);
        if ($object == null)
            throw new Exception;
        $tableName = $this->tableNameGetterByClass($object);
        $query = "DELETE FROM $tableName WHERE Id = $object->Id";
        $this->_db->executeQuery($query);
    }
    function dbDeleteByObject(BaseEntity $obj)
    {
        $tableName = $this->tableNameGetterByClass($obj);
        $query = "DELETE FROM $this->$tableName WHERE Id = $obj->Id";
        $this->queryFetchSingleObject($query);
    }
    public function objectCreatorFromBodyData(): object | array //TODO
    {
        $this->currentTableNameSetter();
        $jsonData = file_get_contents('php://input');
        $dataArray = json_decode($jsonData, true);
        $tableName = $this->_tableName;
        $class = $this->classGetterByTableName($tableName);
        $className = get_class($class);
        $objects = [];
        $reflection = new ReflectionClass($className);
        if (isset($dataArray[0])) {

            foreach ($dataArray as $data) {
                $obj = new $className();
                foreach ($data as $key => $value) {
                    if ($reflection->hasProperty($key)) {
                        $reflectionProperty = $reflection->getProperty($key);
                        $reflectionProperty->setAccessible(true);
                        $reflectionProperty->setValue($obj, $value);
                    }
                }
                if (!array_key_exists("Id", $data) || $data["Id"] === null) {
                    $reflectionProperty = $reflection->getProperty("Id");
                    $reflectionProperty->setAccessible(true);
                    $randomId = mt_rand(0, 999999999);
                    $reflectionProperty->setValue($obj, $randomId);
                }
                $objects[] = $obj;
            }
            return $objects;
        } else {
            $obj = new $className();
            foreach ($dataArray as $key => $value) {
                $reflectionProperty = $reflection->getProperty($key);
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($obj, $value);
            }
            if (!array_key_exists("Id", $dataArray) || $dataArray["Id"] === null) {
                $reflectionProperty = $reflection->getProperty("Id");
                $reflectionProperty->setAccessible(true);
                $randomId = mt_rand(0, 999999999);
                $reflectionProperty->setValue($obj, $randomId);
            }
            return $obj;
        }
    }
    private function dbCreateQueryPreparer($obj, &$keys, &$values, &$bindArray, &$query, $tableName)
    {
        $keys = "";
        $values = "";
        foreach ($obj as $key => $value) {
            $keys .= "$key, ";
            $values .= ":$key, ";
            $bindArray[":$key"] = $value;
        }
        $keys = substr($keys, 0, -2);
        $values = substr($values, 0, -2);
        $query = "INSERT INTO `$tableName` ($keys) VALUES ($values)";
    }
    private function memberController(BaseEntity $obj): bool
    {
        return $this->dbGetByIdAndTableName($obj->Id, $this->_tableName) ? true : false;
    }
    function dbCreate()
    {
        $this->currentTableNameSetter();
        $tableName = $this->_tableName;
        $objects = $this->objectCreatorFromBodyData();
        $bindArray = [];
        $keys = "";
        $values = "";
        $query = "";
        if (is_array($objects)) {
            for ($i = 0; $i < count($objects); $i++) {
                if ($this->memberController($objects[$i])) {
                    throw new Exception;
                }
                $this->dbCreateQueryPreparer($objects[$i], $keys, $values, $bindArray, $query, $tableName);
                $this->_db->executeQuery($query, $bindArray);
            }
        } else {


            $this->dbCreateQueryPreparer($objects, $keys, $values, $bindArray, $query, $tableName);
            $this->_db->executeQuery($query, $bindArray);
        }
    }
    function dbUpdate()
    {
        $this->currentTableNameSetter();
        $obj = $this->objectCreatorFromBodyData();
        if (is_null($this->dbGetByIdAndTableName($obj->Id, $this->_tableName)))
            throw new Exception;
        $qq = "";
        $bindArray = [];
        foreach ($obj as $key => $value) {
            if ($key == "Id")
                goto a;
            $qq .= "`$key`='$value', ";
            $bindArray[":$key"] = $value;
            a:;
        }

        $qq = substr($qq, 0, -2);
        $query = "UPDATE `$this->_tableName` SET $qq WHERE Id=$obj->Id;";

        $this->_db->executeQuery($query);
    }

    public function p($data)
    {
        print_r("------------------------------------------------------");
        print_r(debug_backtrace()[0]["line"]);
        print_r("------------------------------------------------------\n");
        print_r($data);
        print_r("\n");
    }
}
