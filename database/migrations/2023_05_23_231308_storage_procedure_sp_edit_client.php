<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class StorageProcedureSpEditClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        CREATE PROCEDURE `sp_editClient`(
            in _idClient int
        )
        begin

            select
            c.id,
            c.first_name  ,
            c.last_name ,
            c.dob,
            c.phone,
            c.address,
            c.email,
            (
            select
                JSON_ARRAYAGG(JSON_OBJECT('transaction_id',
                p.transaction_id,
                'amount',
                p.amount ,
                'transaction_date',
                p.transaction_date,
                'id',
                p.id))
            from
                payments p
            where
                p.client_id  = c.id
                and p.delete_at  is null) payments
        from
            clients  c

        where
            c.id = _idClient;
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
        $procedure = "DROP PROCEDURE IF EXISTS  sp_editClient";
        DB::unprepared($procedure);
    }
}
