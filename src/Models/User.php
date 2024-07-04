<?php

namespace App\Models;

use App\Core\Model;

class User extends Model {
    protected $table_name = 'users';
    protected $columns = ['name', 'email', 'password'];
}