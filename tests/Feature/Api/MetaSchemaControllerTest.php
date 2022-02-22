<?php

namespace VCComponent\Laravel\Meta\Test\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\Meta\Test\Stubs\Entities\MetaSchema;
use VCComponent\Laravel\Meta\Test\Stubs\Entities\MetaSchemaOption;
use VCComponent\Laravel\Meta\Test\TestCase;

class MetaSchemaControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_get_meta_schemas_list()
    {
        $data = factory(MetaSchema::class, 3)->create()->each(function ($item) {
            factory(MetaSchemaOption::class, 2)->create(['schema_id' => $item->id]);

            unset($item['created_at']);
            unset($item['updated_at']);

            return $item;
        })->toArray();

        $data_id_column = array_column($data, 'id');
        array_multisort($data_id_column, SORT_DESC, $data);

        $response = $this->get("api/admin/meta/schemas");
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $data
        ]);
        $response->assertJsonStructure([
            'data' => [
                [
                    'schemaRule' => [],
                    'schemaType' => [],
                    'schemaOptions' => []
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function can_get_meta_schema_detail()
    {
        $data = factory(MetaSchema::class)->create()->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);

        $response = $this->get("api/admin/meta/schemas/" . $data['id']);
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $data
        ]);
    }

    /**
     * @test
     */
    public function can_create_meta_schema()
    {
        $data = factory(MetaSchema::class)->make()->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);

        $response = $this->post("api/admin/meta/schemas", $data);
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $data
        ]);

        $this->assertDatabaseHas('meta_schemas', $data);
    }

    /**
     * @test
     */
    public function can_update_meta_schema()
    {
        $data = factory(MetaSchema::class)->create()->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);

        $update_data = factory(MetaSchema::class)->make()->toArray();

        $response = $this->put("api/admin/meta/schemas/" . $data['id'], $update_data);
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $update_data
        ]);

        $this->assertDatabaseHas('meta_schemas', $update_data);
    }

    /**
     * @test
     */
    public function can_delete_meta_schema()
    {
        $data = factory(MetaSchema::class)->create()->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);

        $response = $this->delete("api/admin/meta/schemas/" . $data['id']);
        $response->assertSuccessful();

        $this->assertDatabaseMissing('meta_schemas', $data);
    }
}
