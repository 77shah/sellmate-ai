<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceSetting extends Model
{
    protected $fillable = [
        'vehicle_model_id', 'fix_hourly', 'delay_charge_per_hour', 'distance_slot', 'status'
    ];

    public function vehicleModel()
    {
        return $this->belongsTo(VehicleModel::class);
    }
}

