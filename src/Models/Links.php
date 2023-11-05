<?php

namespace Projects\Intensa\Models;

use Projects\Intensa\Database\Db;
use Projects\Intensa\Models\Model;

class Links extends Model
{
    protected $fillable = ['origin_uri', 'short_url', 'short_url_hash'];
}