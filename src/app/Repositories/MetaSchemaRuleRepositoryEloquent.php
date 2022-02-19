<?php

namespace VCComponent\Laravel\Meta\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\Meta\Entities\MetaSchemaRule;
use VCComponent\Laravel\Meta\Repositories\MetaSchemaRuleRepository;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

/**
 * Class AccountantRepositoryEloquent.
 */
class MetaSchemaRuleRepositoryEloquent extends BaseRepository implements MetaSchemaRuleRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        if (isset(config('meta.models')['meta-schema-rule'])) {
            return config('meta.models.meta-schema-rule');
        } else {
            return MetaSchemaRule::class;
        }
    }

    public function getEntity()
    {
        return $this->model;
    }
    public function findById($id)
    {
        $meta_schema_rule = $this->find($id);
        if (!$meta_schema_rule) {
            throw new NotFoundException('Meta schema rules');
        }
        return $meta_schema_rule;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
