<?php

namespace VCComponent\Laravel\Meta\Http\Controllers\Api\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use VCComponent\Laravel\Meta\Entities\Meta;
use VCComponent\Laravel\Meta\Entities\MetaSchema;
use VCComponent\Laravel\Meta\Repositories\MetaRepository;
use VCComponent\Laravel\Meta\Transformers\MetaTransformer;
use VCComponent\Laravel\Meta\Validators\MetaValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class MetaController extends ApiController
{
    protected $repository;
    protected $entity;
    protected $validator;
    protected $transformer;

    public function __construct(MetaRepository $repository, MetaValidator $validator)
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

        if (isset(config('meta.transformers')['meta'])) {
            $this->transformer = config('meta.transformers.meta');
        } else {
            $this->transformer = MetaTransformer::class;
        }
    }

    public function index(Request $request)
    {
        $this->validator->isValid($request, 'CREATE_META');

        $query = $this->entity;

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['key', 'value'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);
        $query = $this->applyMetableId($query, $request);
        $query = $this->applyMetableType($query, $request);

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer(['schema']);
        }

        if ($request->has('page')) {
            $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;

            $metas = $query->paginate($per_page);

            return $this->response->paginator($metas, $transformer);
        }

        $metas = $query->get();

        return $this->response->collection($metas, $transformer);
    }

    public function show($id, Request $request)
    {
        $meta = $this->repository->findById($id);

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer(['schema']);
        }

        return $this->response->item($meta, $transformer);
    }

    public function store(Request $request)
    {
        $this->validator->isValid($request, 'RULE_ADMIN_CREATE');

        if ($request->has('meta')) {
            return $this->storeManyData($request);
        }

        return $this->storeSimpleData($request);
    }

    public function update(Request $request, $id = null)
    {
        $this->validator->isValid($request, 'RULE_ADMIN_UPDATE');

        if ($request->has('meta')) {
            return $this->updateManyData($request);
        }

        return $this->updateSimpleData($request, $id);
    }

    public function destroy($id)
    {
        $meta_schema = $this->repository->findById($id);

        $meta_schema->delete();

        return $this->success();
    }

    protected function storeSimpleData(Request $request)
    {
        $this->validator->isValid($request, 'HAS_VALUE');

        $meta = $this->repository->create($request->all());

        return $this->response->item($meta, new $this->transformer);
    }

    protected function updateSimpleData(Request $request, $id)
    {
        $this->validator->isValid($request, 'HAS_VALUE');

        $meta_schema = $this->repository->findById($id);

        $meta_schema->update($request->all());

        return $this->response->item($meta_schema, new $this->transformer);
    }

    protected function updateManyData(Request $request)
    {
        $this->entity->where('metable_id', $request->get('metable_id'))->where('metable_type', $request->get('metable_type'))->delete();

        return $this->storeManyData($request);
    }

    protected function storeManyData(Request $request)
    {
        $meta_values = $this->mapRequestData($request);
        $schema_keys = array_keys($meta_values);

        $meta_schemas = MetaSchema::whereIn('key', $schema_keys)
            ->where('type', Str::singular($request->get('metable_type')))
            ->with('schemaRules')->get();

        $this->validator->isSchemaValid($request, $meta_schemas);

        $meta_schemas = $this->mapMetaData($request, $meta_schemas, $meta_values);

        $this->entity->insert($meta_schemas);

        return $this->success();
    }

    protected function mapRequestData($request)
    {
        return collect($request->get('meta'))->mapWithKeys(function ($value, $key) {
            return [$key => $value];
        })->toArray();
    }

    protected function mapMetaData($request, $meta_schemas, $meta_values)
    {
        return $meta_schemas->map(function ($item, $key) use ($request, $meta_values) {
            return [
                'metable_id' => $request['metable_id'],
                'metable_type' => $request['metable_type'],
                'schema_id' => $item->id,
                'value' => $meta_values[$item->key],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();
    }

    public function batch(Request $request)
    {
        $meta = collect($request->all());
        foreach ($meta as $item) {
            $schema = MetaSchema::where('key', $item['key'])->where('type', $item['metable_type'])->firstOrFail();
            Meta::updateOrCreate(
                ['schema_id' => $schema->id, 'metable_id' => $item['metable_id'], 'metable_type' => $item['metable_type']],
                ['schema_id' => $schema->id, 'metable_id' => $item['metable_id'], 'metable_type' => $item['metable_type'], 'value' => $item['value']]
            );
        }

        return $this->success();
    }

    public function applyMetableId($request, $query)
    {
        if ($request->has('metable_id')) {
            $query = $query->where('metable_id', $request->get('metable_id'));
        }
        return $query;
    }

    public function applyMetableType($request, $query)
    {
        if ($request->has('metable_type')) {
            $query = $query->where('metable_type', $request->get('metable_type'));
        }
        return $query;
    }
}
