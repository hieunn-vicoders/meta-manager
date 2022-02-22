<?php

namespace VCComponent\Laravel\Meta\Http\Controllers\Api\Admin;

use Exception;
use Illuminate\Http\Request;
use VCComponent\Laravel\Meta\Repositories\MetaSchemaRepository;
use VCComponent\Laravel\Meta\Transformers\MetaSchemaTransformer;
use VCComponent\Laravel\Meta\Validators\MetaSchemaValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class MetaSchemaController extends ApiController
{
    protected $repository;
    protected $entity;
    protected $validator;
    protected $transformer;

    public function __construct(MetaSchemaRepository $repository, MetaSchemaValidator $validator)
    {
        $this->repository  = $repository;
        $this->entity      = $repository->getEntity();
        $this->validator   = $validator;

        if (config('meta.auth_middleware.admin.middleware') !== '') {
            $this->middleware(
                config('meta.auth_middleware.admin.middleware'),
                ['except' => config('meta.auth_middleware.admin.except')]
            );
        } else {
            throw new Exception("Admin middleware configuration is required");
        }

        if (isset(config('meta.transformers')['meta-schema'])) {
            $this->transformer = config('meta.transformers.meta-schema');
        } else {
            $this->transformer = MetaSchemaTransformer::class;
        }
    }

    public function index(Request $request)
    {
        $query = $this->entity;

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['key', 'value'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);
        $query = $this->applyTypeFromRequest($query, $request);

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer(['schemaRule', 'schemaType', 'schemaOptions']);
        }

        if ($request->has('page')) {
            $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;

            $meta_schemas = $query->paginate($per_page);

            return $this->response->paginator($meta_schemas, $transformer);
        }

        $meta_schemas = $query->get();

        return $this->response->collection($meta_schemas, $transformer);
    }

    public function show($id, Request $request)
    {
        $meta_schema = $this->repository->findById($id);

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer(['schemaRule', 'schemaType', 'schemaOptions']);
        }

        return $this->response->item($meta_schema, $transformer);
    }

    public function store(Request $request)
    {
        $this->validator->isValid($request, 'RULE_ADMIN_CREATE');

        $data = $request->all();

        $meta_schema = $this->repository->create($data);

        return $this->response->item($meta_schema, new $this->transformer);
    }

    public function update(Request $request, $id)
    {
        $this->validator->isValid($request, 'RULE_ADMIN_UPDATE');

        $meta_schema = $this->repository->findById($id);

        $meta_schema->update($request->all());

        return $this->response->item($meta_schema, new $this->transformer);
    }

    public function destroy($id)
    {
        $meta_schema = $this->repository->findById($id);

        $meta_schema->delete();

        return $this->success();
    }

    protected function applyTypeFromRequest($query, Request $request)
    {
        if ($request->has('type')) {
            $query = $query->where('type', $request->get('type'));
        }
        return $query;
    }
}
