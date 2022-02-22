<?php

namespace VCComponent\Laravel\Meta\Test\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\Meta\Test\Stubs\Entities\Meta;
use VCComponent\Laravel\Meta\Test\Stubs\Entities\MetaSchema;
use VCComponent\Laravel\Meta\Test\TestCase;

class MetaControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_get_metas_list()
    {
        $data = factory(Meta::class, 3)->create([
            'schema_id' => factory(MetaSchema::class)->create()->id
        ])->each(function ($item) {
            unset($item['created_at']);
            unset($item['updated_at']);
            return $item;
        })->toArray();
        
        $data_id_column = array_column($data, 'id');
        array_multisort($data_id_column, SORT_DESC, $data);

        $response = $this->get("api/admin/metas");
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $data
        ]);

        $response = $this->get("api/admin/metas?include=schema.schemaType,schema.schemaRule,schema.schemaOptions");
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                [
                    'schema' => [
                        'data' => [
                            'schemaRule' => [],
                            'schemaType' => [],
                            'schemaOptions' => [] 
                        ]
                    ]
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function can_get_meta_detail()
    {
        $data = factory(Meta::class)->create([
            'schema_id' => factory(MetaSchema::class)->create()->id
        ])->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);

        $response = $this->get("api/admin/metas/".$data['id']);
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $data
        ]);
    }

    /**
     * @test
     */
    public function can_create_a_meta()
    {
        $data = factory(Meta::class)->make([
            'schema_id' => factory(MetaSchema::class)->create()->id
        ])->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);

        $response = $this->post("api/admin/metas", $data);
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $data
        ]);

        $this->assertDatabaseHas('metas' ,$data);
    }

    /**
     * @test
     */
    public function can_create_many_metas()
    {
        $metable_type = 'posts';
        $meta_schema = factory(MetaSchema::class,2)->create(['type' => $metable_type, 'schema_rule_id' => 3]);
        $data = factory(Meta::class)->make([
            'metable_type' => $metable_type,
            'meta' => [
                $meta_schema[0]->key => 'value_1',
                $meta_schema[1]->key => 'value_2', 
            ]
        ])->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);

        $response = $this->post("api/admin/metas", $data);
        $response->assertSuccessful();
        $response->assertJson([
            'success' => true
        ]);

        $data = collect($data['meta'])->values()->map(function ($item, $key) use ($data) {
            return [
                'value' => $item,
                'metable_id' => "".$data['metable_id'],
                'metable_type' => $data['metable_type'],
            ]; 
            $this->assertDatabaseHas('metas', [
                'value' => $item,
                'metable_id' => $data['metable_id'],
                'metable_type' => $data['metable_type'],
            ]);
        })->toArray();
    }

    /**
     * @test
     */
    public function can_update_meta()
    {
        $data = factory(Meta::class)->create([
            'schema_id' => factory(MetaSchema::class)->create()->id
        ])->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);
        
        $update_data = factory(Meta::class)->make([
            'schema_id' => factory(MetaSchema::class)->create()->id
        ])->toArray();

        $response = $this->put("api/admin/metas/".$data['id'], $update_data);
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $update_data
        ]);

        $this->assertDatabaseHas('metas' ,$update_data);
    }

    /**
     * @test
     */
    public function can_update_many_metas()
    {
        $metable_type = 'posts';
        $meta_schema = factory(MetaSchema::class,2)->create(['type' => $metable_type, 'schema_rule_id' => 3]);
        $data = factory(Meta::class)->make([
            'metable_type' => $metable_type,
            'meta' => [
                $meta_schema[0]->key => 'value_1',
                $meta_schema[1]->key => 'value_2', 
            ]
        ])->toArray();

        $data = factory(Meta::class)->make([
            'metable_type' => $metable_type,
            'meta' => [
                $meta_schema[0]->key => 'new_value_1',
            ]
        ])->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);

        $response = $this->put("api/admin/metas", $data);
        $response->assertSuccessful();
        $response->assertJson([
            'success' => true
        ]);

        $data = collect($data['meta'])->values()->map(function ($item, $key) use ($data) {
            $this->assertDatabaseHas('metas', [
                'value' => $item,
                'metable_id' => $data['metable_id'],
                'metable_type' => $data['metable_type'],
            ]);
        })->toArray();
    }

    /**
     * @test
     */
    public function can_delete_meta()
    {
        $data = factory(Meta::class)->create([
            'schema_id' => factory(MetaSchema::class)->create()->id
        ])->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);

        $response = $this->delete("api/admin/metas/".$data['id']);
        $response->assertSuccessful();

        $this->assertDatabaseMissing('metas' ,$data);
    }
}