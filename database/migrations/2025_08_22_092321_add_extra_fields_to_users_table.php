<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->string('gelar')->nullable();
			$table->string('fullname')->nullable();
			$table->enum('access_level', ['0','1','2'])->default('0');
			$table->string('position')->default('Karyawan');
			$table->integer('technician')->default(0);
			$table->text('signature')->nullable();
			$table->string('country')->nullable();
			$table->string('phone_number', 15)->nullable();
			$table->string('address')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->integer('zip_code')->nullable();
			$table->date('joined')->nullable();
		});
	}

    /**
     * Reverse the migrations.
     */
    public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$columnsToDropIfExist = [
				'gelar','access_level','position','technician',
				'signature','country','phone_number','address','city','state','zip_code','joined','fullname'
			];
			
			foreach ($columnsToDropIfExist as $column) {
				if (Schema::hasColumn('users', $column)) {
					$table->dropColumn($column);
				}
			}
		});
	}
};
