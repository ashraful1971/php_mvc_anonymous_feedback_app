<?php

namespace App\Models;

use App\Core\Model;

class Feedback extends Model {
    protected $table_name = 'feedbacks';
    protected $columns = ['user_id', 'feedback'];
}