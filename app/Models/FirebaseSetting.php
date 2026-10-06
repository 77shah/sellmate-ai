<?php
// app/Models/FirebaseSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirebaseSetting extends Model
{
    use HasFactory;

    protected $table = 'firebase_settings';

    protected $fillable = [
        'project_name',
        'project_id',
        'project_number',
        'app_id',
        'package_name',
        'sender_id',
        'server_key',
        'key_pair',
        'json_file',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function isConfigured()
    {
        return !empty($this->project_id) && !empty($this->server_key);
    }

    public function getConfig()
    {
        return [
            'project_name' => $this->project_name,
            'project_id' => $this->project_id,
            'project_number' => $this->project_number,
            'app_id' => $this->app_id,
            'package_name' => $this->package_name,
            'sender_id' => $this->sender_id,
            'server_key' => $this->server_key,
            'key_pair' => $this->key_pair,
            'json_file' => $this->json_file,
            'status' => $this->status
        ];
    }
}