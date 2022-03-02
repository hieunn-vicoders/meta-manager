<?php

namespace VCComponent\Laravel\Meta\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Meta extends Model
{
    protected $fillable = [
        'schema_id',
        'key',
        'value',
        'metable_id',
        'metable_type',
    ];

    public function schema()
    {
        return $this->belongsTo(MetaSchema::class);
    }

    public function metable()
    {
        return $this->morphTo();
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            return static::formatMetaData($model);
        });

        self::updating(function ($model) {
            return static::formatMetaData($model);
        });
    }

    protected static function formatMetaData($model)
    {
        if ($model->key && !$model->schema_id) {
            try {
                $schema = MetaSchema::firstOrCreate([
                    'key' => $model->key,
                    'type' => $model->metable_type,
                ], [
                    'schema_type_id' => 1,
                ]);

                $model->schema_id = $schema->id;
            } catch (\Exception $e) {
                throw $e;
            }
        }

        unset($model['key']);

        return $model;
    }
}
