<?php

use App\Enums\PriorityEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->json('files')->nullable();
            $table->enum('priority',[
                PriorityEnum::CRITICAL_HIGH,PriorityEnum::HIGH,PriorityEnum::MEDIUM,
                PriorityEnum::LOW]);
            $table->enum('status',[
                StatusEnum::AWAITING,StatusEnum::OPEN,StatusEnum::CLOSED])->default(StatusEnum::AWAITING);
            $table->foreignId("agent_id")->constrained('users');
            $table->foreignId("category_id")->constrained('categories');
            $table->foreignId("label_id")->constrained('labels');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
