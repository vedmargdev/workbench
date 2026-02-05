<?php

namespace Workbench\OfflineTest\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class OfflineTestMark extends Model
{
  use HasFactory,SoftDeletes,LogsActivity;

    protected $fillable=['uuid','user_id','created_by','session','student_id','marks_data','deleted_reasons','deleted_by','deleted_at'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = bin2hex(random_bytes(20)) . '-' . date('dmyHis') . '-' . (string) Uuid::generate(4);
        });      
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deleted_by_user()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
