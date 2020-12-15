<?php

namespace GrantHolle\Http\Tests\Fixtures;

use GrantHolle\Http\Resources\Traits\HasResource;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasResource;

    protected $guarded = [];
}