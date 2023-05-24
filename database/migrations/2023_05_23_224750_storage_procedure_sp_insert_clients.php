<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class StorageProcedureSpInsertClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        CREATE PROCEDURE `sp_insert_clients`(
            in _id bigint,
            in _fname varchar(150),
            in _lname varchar(150),
            in _dob date,
            in _phone varchar(15),
            in _email varchar(150),
            in _address varchar(300),
            in arrayPayment json )
            BEGIN
                declare i int default 0;
            -- declare j int default 0;
            -- start transaction;
                if _id is null then
	                insert into clients (
	                id,
	                first_name ,
	                last_name ,
	                dob,
	                phone,
	                email,
	                address,
	                created_at,
	                updated_at,
	                delete_at)
		            values(
		            null,
		            _fname,
		            _lname,
		            _dob,
		            _phone,
		            _email,
		            _address,
		            now(),
		            now(),
		            null
		           );

	  set   @clientid = @@identity;

            while i<JSON_LENGTH(arrayPayment) DO
                insert into
                payments (
                id,
                client_id ,
                transaction_id  ,
                amount,
                transaction_date ,
                created_at,
                updated_at,
                delete_at
                )
            values(
            null,
            @clientid,
            JSON_UNQUOTE(JSON_EXTRACT(arrayPayment,CONCAT('$[', i, '].transaction_id'))),
            JSON_UNQUOTE(JSON_EXTRACT(arrayPayment,CONCAT('$[', i, '].amount'))),
            JSON_UNQUOTE(JSON_EXTRACT(arrayPayment,CONCAT('$[', i, '].transaction_date'))),
            now(),
            now(),
            null
            );

            set i = i + 1;
            end while;

            end if;

           END
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
        $procedure = "DROP PROCEDURE IF EXISTS  sp_insert_clients";
        DB::unprepared($procedure);
    }
}
