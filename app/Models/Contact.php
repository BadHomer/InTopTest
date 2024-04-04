<?php

namespace App\Models;

/**
 * @method Contact fill(array $data)
 * @method static Contact create(array $data)
 */
class Contact extends Model
{
    public string $name;
    public string $telephone;
}