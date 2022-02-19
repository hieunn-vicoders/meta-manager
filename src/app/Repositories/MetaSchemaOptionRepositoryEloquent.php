<?php

namespace VCComponent\Laravel\Meta\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\Meta\Entities\MetaSchemaOption;
use VCComponent\Laravel\Meta\Repositories\MetaSchemaOptionRepository;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

/**
 * Class AccountantRepositoryEloquent.
 */
class MetaSchemaOptionRepositoryEloquent extends BaseRepository implements MetaSchemaOptionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        if (isset(config('meta.models')['meta-schema-option'])) {
            return config('meta.models.meta-schema-option');
        } else {
            return MetaSchemaOption::class;
        }
    }

    public function getEntity()
    {
        return $this->model;
    }
    public function findById($id)
    {
        $meta_schema_option = $this->find($id);
        if (!$meta_schema_option) {
            throw new NotFoundException('Meta schema options');
        }
        return $meta_schema_option;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
