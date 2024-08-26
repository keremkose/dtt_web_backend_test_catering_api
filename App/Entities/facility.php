<?php

namespace App\Entities;

use App\Entities\BaseEntity;

class Facility extends BaseEntity
{
    public ?string $CreationDate;
    public string $Name = "";
    public int $LocationId ;
    public ?array $TagNames;
}
