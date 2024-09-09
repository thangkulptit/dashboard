<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagementTool extends Model
{
    protected $table = 'management_tool';
    protected $primaryKey = 'id';
    protected $fillable = [
        'game', 'mac_address', 'license_key','total_devices', 'active', 'created_at', 'updated_at'
    ];
}
