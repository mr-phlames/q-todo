<?php 
    namespace App\Database\Migrations;
    
    use Leaf\Database;
    
    class CreateUsers extends Database {
        public function __construct() {
            parent::__construct();
        }
        
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()  {
            if(!$this->capsule::schema()->hasTable("users")):
                $this->capsule::schema()->create("users", function ($table) {
                    $table->increments('id');
                    $table->string('username')->unique();
                    $table->string('email')->unique();
                    $table->string('password');
                    $table->timestamps();
                });
            endif;
        }
        
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            $this->capsule::schema()->dropIfExists("users");
        }
    }