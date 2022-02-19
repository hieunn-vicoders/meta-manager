<?php

namespace VCComponent\Laravel\Meta\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\Meta\Entities\MetaSchema;
use VCComponent\Laravel\Meta\Repositories\MetaSchemaRepository;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

/**
 * Class AccountantRepositoryEloquent.
 */
class MetaSchemaRepositoryEloquent extends BaseRepository implements MetaSchemaRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        if (isset(config('meta.models')['meta-schema'])) {
            return config('meta.models.meta-schema');
        } else {
            return MetaSchema::class;
        }
    }

    public function getEntity()
    {
        return $this->model;
    }
    public function findById($id)
    {
        $meta_schema = $this->find($id);
        if (!$meta_schema) {
            throw new NotFoundException('Meta schemas');
        }
        return $meta_schema;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
