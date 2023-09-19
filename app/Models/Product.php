<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Product::class, 'CategoryID', 'CategoryID');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'SupplierID', 'SupplierID');
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'ProductID', 'ProductID');
    }

}
