<?php

namespace App\Models;

use App\Models\Shipper;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerID', 'customerID');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeID', 'EmployeeID');
    }

    public function shipper()
    {
        return $this->belongsTo(Shipper::class, 'ShipperID', 'ShipperID');
    }

    public function OrderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'OrderID', 'OrderID');
    }
}
