<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseLoggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('stat_db')->create('base_logger', function (Blueprint $table) {
            $table->engine = 'MyISAM';

            $table->bigIncrements('log_id')->comment('主键');
            $table->string('target_id', 20)->comment('目标ID')->default('');
            $table->string('entity_id', 20)->comment('实体对象ID')->default('');
            $table->tinyInteger('type_id')->comment('类型 0 普通用户 1 后台管理员')->default(0)->unsigned();
            $table->text('raw_data')->comment('附加信息');
            $table->dateTime('created_at')->comment('创建时间')->nullable($value = true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_loggers');
    }
}
