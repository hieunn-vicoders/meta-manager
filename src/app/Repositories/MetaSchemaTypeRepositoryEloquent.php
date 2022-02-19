<?php

namespace VCComponent\Laravel\Meta\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\Meta\Entities\MetaSchemaType;
use VCComponent\Laravel\Meta\Repositories\MetaSchemaTypeRepository;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

/**
 * Class AccountantRepositoryEloquent.
 */
class MetaSchemaTypeRepositoryEloquent extends BaseRepository implements MetaSchemaTypeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        if (isset(config('meta.models')['meta-schema-type'])) {
            return config('meta.models.meta-schema-type');
        } else {
            return MetaSchemaType::class;
        }
    }

    public function getEntity()
    {
        return $this->model;
    }
    public function findById($id)
    {
        $meta_schema_type = $this->find($id);
        if (!$meta_schema_type) {
            throw new NotFoundException('Meta schema types');
        }
        return $meta_schema_type;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
