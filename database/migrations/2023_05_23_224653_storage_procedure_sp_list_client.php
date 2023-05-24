<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class StorageProcedureSpListClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        create procedure `sp_list_clients`()
            begin
            select c.id, concat(c.first_name,' ',c.last_name) as name, c.dob, c.phone ,c.email ,c.address ,count(p.amount) as t_count,sum(p.amount) t_total  from payments p
            inner join clients c on p.client_id = c.id
            where c.delete_at is null and p.delete_at is null
            group by c.id ,concat(c.first_name,'',c.last_name) ,c.dob, c.phone ,c.email ,c.address;
            end
        ";
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $procedure = "DROP PROCEDURE IF EXISTS  sp_list_clients";
        DB::unprepared($procedure);
    }
}
