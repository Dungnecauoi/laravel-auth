<?php

namespace Duxbo\LaravelAuth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $table = 'auth_audit_logs';

    protected $fillable = ['user_id', 'event', 'ip_address', 'user_agent', 'meta', 'created_at'];

    protected $casts = [
        'meta' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('laravel-auth.user_model'));
    }
}
