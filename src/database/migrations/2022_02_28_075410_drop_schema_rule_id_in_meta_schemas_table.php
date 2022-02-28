<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropSchemaRuleIdInMetaSchemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (DB::getDriverName() !== 'sqlite') {DB::getDriverName();
            Schema::table('meta_schemas', function (Blueprint $table) {
                $table->dropForeign('meta_schemas_schema_rule_id_foreign');
            });
        }
        Schema::table('meta_schemas', function (Blueprint $table) {
            $table->dropColumn('schema_rule_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meta_schemas', function (Blueprint $table) {
            $table->unsignedBigInteger('schema_rule_id');
            $table->foreign('schema_rule_id')->references('id')->on('meta_schema_rules')->onDelete('cascade');
        });
    }
}
