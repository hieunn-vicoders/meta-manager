<?php

namespace VCComponent\Laravel\Meta\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\Meta\Entities\Meta;
use VCComponent\Laravel\Meta\Repositories\MetaRepository;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

/**
 * Class AccountantRepositoryEloquent.
 */
class MetaRepositoryEloquent extends BaseRepository implements MetaRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        if (isset(config('meta.models')['meta'])) {
            return config('meta.models.meta');
        } else {
            return Meta::class;
        }
    }

    public function getEntity()
    {
        return $this->model;
    }
    public function findById($id)
    {
        $meta = $this->find($id);
        if (!$meta) {
            throw new NotFoundException('Metas');
        }
        return $meta;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
