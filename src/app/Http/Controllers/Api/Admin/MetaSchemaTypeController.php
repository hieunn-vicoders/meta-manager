<?php

namespace VCComponent\Laravel\Meta\Http\Controllers\Api\Admin;

use Exception;
use Illuminate\Http\Request;
use VCComponent\Laravel\Meta\Repositories\MetaSchemaTypeRepository;
use VCComponent\Laravel\Meta\Transformers\MetaSchemaTypeTransformer;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class MetaSchemaTypeController extends ApiController
{
    protected $repository;
    protected $entity;
    protected $transformer;

    public function __construct(MetaSchemaTypeRepository $repository)
    {
        $this->repository = $repository;
        $this->entity = $repository->getEntity();

        if (config('meta.auth_middleware.admin.middleware') !== '') {
            $this->middleware(
                config('meta.auth_middleware.admin.middleware'),
                ['except' => config('meta.auth_middleware.admin.except')]
            );
        } else {
            throw new Exception("Admin middleware configuration is required");
        }

        if (isset(config('meta.transformers')['meta'])) {
            $this->transformer = config('meta.transformers.meta');
        } else {
            $this->transformer = MetaSchemaTypeTransformer::class;
        }
    }

    public function index(Request $request)
    {
        $query = $this->entity;
        $query = $this->getStatus($request, $query);
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        if ($request->has('page')) 
        {
            $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;
    
            $meta_schema_types = $query->paginate($per_page);
    
            return $this->response->paginator($meta_schema_types, new $this->transformer());
        }

        $meta_schema_types = $query->get();

        return $this->response->collection($meta_schema_types, new $this->transformer());
    }
}
