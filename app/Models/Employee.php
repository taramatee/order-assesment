<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    public function Order()
    {
        return this->hasMany(Order::class, 'EmployeeID', 'EmployeeID');
    }
}
