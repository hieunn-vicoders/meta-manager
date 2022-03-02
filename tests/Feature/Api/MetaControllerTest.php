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
            'key' => factory(MetaSchema::class)->create()->key
        ])->each(function ($item) {
            unset($item['created_at']);
            unset($item['updated_at']);
            return $item;
        });

        $metable_id = $data[0]->metable_id;
        $metable_type = $data[0]->metable_type;

        $data = $data->filter(function ($item) use ($metable_id, $metable_type) {
            return $item->metable_id == $metable_id && $item->metable_type == $metable_type;
        })->toArray();

        $data_id_column = array_column($data, 'id');
        array_multisort($data_id_column, SORT_DESC, $data);

        $response = $this->get("api/admin/metas?metable_id=" . $metable_id . "&metable_type=" . $metable_type);
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $data
        ]);

        $response = $this->get("api/admin/metas?metable_id=" . $metable_id . "&metable_type=" . $metable_type
            . "&include=schema.schemaType,schema.schemaRules,schema.schemaOptions");
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                [
                    'schema' => [
                        'data' => [
                            'schemaRules' => [],
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
            'key' => factory(MetaSchema::class)->create()->key
        ])->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);

        $response = $this->get("api/admin/metas/" . $data['id']);
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $data
        ]);
    }

    /**
     * @test
     */
    public function can_get_list_metas_by_route_detail()
    {
        $metable_type = 'fake_metable_type';
        $data = factory(Meta::class, 3)->create([
            'key' => factory(MetaSchema::class)->create([
                'metable_type' => 'fake_metable_type',
            ])->key,
            'metable_type' => 'fake_metable_type',
        ])->each(function ($item) {
            unset($item['created_at']);
            unset($item['updated_at']);
        });

        $metable_id = $data->pluck('id')->implode(',');
        $data = $data->toArray();

        $response = $this->get("api/admin/metas/" . $metable_id . "?metable_type=" . $metable_type);
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $data
        ]);
    }

    /**
     * @test
     */
    public function can_delete_meta()
    {
        $data = factory(Meta::class)->create([
            'key' => factory(MetaSchema::class)->create()->key
        ])->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);

        $response = $this->delete("api/admin/metas/" . $data['id']);
        $response->assertSuccessful();

        $this->assertDatabaseMissing('metas', $data);
    }

    /**
     * @test
     */
    public function can_put_batch_metas()
    {
        $data = factory(Meta::class, 3)->make()->each(function ($item) {
            $metable_type = 'metable_type';
            $meta_schema = factory(MetaSchema::class)->create([
                'metable_type' => $metable_type,
            ]);
            $item['metable_type'] = $metable_type;
            $item['key'] = $meta_schema->key;
            $value['value'] = 'update value';
        })->toArray();

        $response = $this->put("api/admin/metas/batch", $data);
        $response->assertSuccessful();

        foreach ($data as $key => $value) {
            unset($value['key']);
            $this->assertDatabaseHas('metas', $value);
        }
    }
}
