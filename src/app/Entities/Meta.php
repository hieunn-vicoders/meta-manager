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
                if (is_subclass_of($model->metable_type, Model::class))
                    $schema = MetaSchema::firstOrCreate([
                        'key' => $model->key,
                        'type' => Str::singular((new $model->metable_type())->getTable()),
                    ], [
                        'schema_type_id' => 1,
                        'schema_rule_id' => 3,
                    ]);
                else
                    $schema = MetaSchema::firstOrCreate([
                        'key' => $model->key,
                        'type' => Str::singular($model->metable_type),
                    ], [
                        'schema_type_id' => 1,
                        'schema_rule_id' => 3,
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
