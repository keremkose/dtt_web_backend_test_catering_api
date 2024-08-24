<?php

namespace App\Entities;

use App\Entities\BaseEntity;

class Location extends BaseEntity
{

    public string $City = "";
    public string $Adress = "";
    public string $ZipCode = "";
    public string $CountryCode = "";
    public int $PhoneNumber = 0;
}
