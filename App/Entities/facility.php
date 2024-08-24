<?php


namespace App\Entities;

use App\Entities\BaseEntity;
use DateTime;

class Facility extends BaseEntity
{
    public ?string $CreationDate;
    public string $Name = "";
    public int $LocationId = 0;
    // public ?int  $tagId;
}
