<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetaSchemaRuleablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_schema_ruleables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schema_id');
            $table->foreign('schema_id')->references('id')->on('meta_schemas')->onDelete('cascade');
            $table->unsignedBigInteger('schema_rule_id');
            $table->foreign('schema_rule_id')->references('id')->on('meta_schema_rules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_schema_ruleables');
    }
}
