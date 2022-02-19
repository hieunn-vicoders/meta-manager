<?php

namespace VCComponent\Laravel\Meta\Traits;

use VCComponent\Laravel\Meta\Entities\Meta;

trait HasMeta 
{
    public function metas()
    {
        if (isset(config('meta.models')['meta'])) {
            return $this->morphMany(config('meta.models.meta'), 'metas');
        } else {
            return $this->morphMany(Meta::class, 'metas');
        }
    }
}