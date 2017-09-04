<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentClasses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $queryString = <<<QUERY
            CREATE (document:Class {name:"Document"})
            CREATE (minute:Class {name:"Minute"})
            CREATE (minute)-[:extends]->(document)
            CREATE (article:Class {name:"Article"})
            CREATE (article)-[:extends]->(document)
            CREATE (tag:Class {name: "Tag"})
QUERY;
        DB::statement($queryString);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        throw new Exception('Não é possível dar rollback.');
        // DB::statement(`MATCH (document:Class {name:"Document"}) DETACH DELETE document`);
        // DB::statement(`MATCH (minute:Class {name:"Minute"}) DETACH DELETE minute`);
        // DB::statement(`MATCH (article:Class {name:"Article"}) DETACH DELETE article`);
        // DB::statement(`MATCH (tag:Class {name:"Tag"}) DETACH DELETE tag`);
    }
}
