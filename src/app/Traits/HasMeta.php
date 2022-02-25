<?php

namespace VCComponent\Laravel\Meta\Traits;

use VCComponent\Laravel\Meta\Entities\Meta;

trait HasMeta 
{
    public function metas()
    {
        if (isset(config('meta.models')['meta'])) {
            return $this->morphMany(config('meta.models.meta'), 'metable');
        } else {
            return $this->morphMany(Meta::class, 'metable');
        }
    }

    /**
     * Get resource meta value
     *
     * @param string|int $key
     * @return string
     */
    public function getMetaField($key)
    {
        if ($this->getMeta($key)) {
            return $this->getMeta($key)->value;
        }
    }

    /**
     * Get resource meta option value
     *
     * @param string|int $key
     * @return string
     */
    public function getMetaOptionValue($key)
    {
        try {
            $meta = $this->getMeta($key);

            if (count($meta->schema->schemaOptions)) {
                return $meta->schema->schemaOptions->first(function ($option) use ($meta) {
                    return $option->key == $meta->value;
                });
            }
        } catch (\Throwable $th) {
            return;
        }
    }

    /**
     * Get resource meta data
     *
     * @param string|int $key
     * @return VCComponent\Laravel\Meta\Entities\Meta
     */
    public function getMeta($key)
    {
        try {
            return $this->metas->first(function ($meta) use ($key) {
                return $meta->schema->key == $key;
            });
        } catch (\Throwable $th) {
            return;
        }
    }
}