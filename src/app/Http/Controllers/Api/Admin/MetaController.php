<?php

namespace VCComponent\Laravel\Meta\Http\Controllers\Api\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use VCComponent\Laravel\Meta\Entities\MetaSchema;
use VCComponent\Laravel\Meta\Repositories\MetaRepository;
use VCComponent\Laravel\Meta\Transformers\MetaTransformer;
use VCComponent\Laravel\Meta\Validators\MetaValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

class MetaController extends ApiController
{
    protected $repository;
    protected $entity;
    protected $validator;
    protected $transformer;
    protected $meta_schema;

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

        if (isset(config('meta.model')['meta-schema'])) {
            $this->meta_schema = config('meta.model.meta-schema');
        } else {
            $this->meta_schema = MetaSchema::class;
        }
    }

    public function index(Request $request)
    {
        $this->validator->isValid($request, 'GET_META');

        $query = $this->entity;
        
        $metable_id = $request->get('metable_id');
        
        $metable_type = $request->get('metable_type');
        
        $result = $query->where('metable_id', $metable_id)->where('metable_type', $metable_type)->get();
        
        $transformer = new $this->transformer(['schema']);
        
        return $this->response->collection($result, $transformer);
    }

    public function show($id, Request $request)                                                         
    {          
        $this->validator->isValid($request, 'SHOW_META');   

        $meta_ids = explode(',', $id);                                                                  
        $result = $this->entity                                                                         
            ->where('metable_type', $request->get('metable_type'))                                      
            ->whereIn('metable_id', $meta_ids)                                                          
            ->get();   
                                                                                                                 
        return $this->response->collection($result, new $this->transformer(['schema']));                
    }

    public function destroy($id)
    {
        $meta_schema = $this->repository->findById($id);

        $meta_schema->delete();

        return $this->success();
    }

    public function batch(Request $request)
    {
        $meta = collect($request->all());

        DB::transaction(function () use ($meta) {
            foreach ($meta as $item) {
                $schema = $this->meta_schema::where('key', $item['key'])->where('metable_type', $item['metable_type'])->firstOrFail();
                
                $this->validator->isValidRule(
                    [$schema->key => $item['value']],
                    $schema->schemaRules->pluck('name')->toArray()
                );

                $this->entity->updateOrCreate(
                    ['key' => $item['key'], 'metable_id' => $item['metable_id'], 'metable_type' => $item['metable_type']],
                    ['value' => $item['value']]
                );
            }
        });

        return $this->success();
    }
}
