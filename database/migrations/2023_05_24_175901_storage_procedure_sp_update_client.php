<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class StorageProcedureSpUpdateClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        CREATE PROCEDURE `sp_update_client`
            (
                in _id int,
                in _fname varchar(150),
                in _lname varchar(150),
                in _dob date,
                in _phone varchar(15),
                in _address varchar(300),
                in _email varchar(150),
                in arrayPayment json,
                in deletePayment json

           )
            begin
            
              declare i int default 0;
              declare j int default 0;

               update clients
               set
                   first_name = _fname,
                   last_name = _lname,
                   dob = _dob,
                   phone = _phone,
                   address = _address,
                   email = _email

               where id = _id;

                while i<JSON_LENGTH(arrayPayment) DO
                      set @tid = JSON_UNQUOTE(JSON_EXTRACT(arrayPayment,CONCAT('$[', i, '].id')));
                       IF @tid is not null then
                              update
                           payments
                           set
                               transaction_id = JSON_UNQUOTE(JSON_EXTRACT(arrayPayment,CONCAT('$[', i, '].transaction_id'))) ,
                               amount = JSON_UNQUOTE(JSON_EXTRACT(arrayPayment,CONCAT('$[', i, '].amount'))) ,
                               transaction_date = JSON_UNQUOTE(JSON_EXTRACT(arrayPayment,CONCAT('$[', i, '].transaction_date'))) ,
                               updated_at = now()
                           where
                               id = @tid ;


                        else
                            insert into  payments (
                                    client_id,
                                    transaction_id,
                                    amount,
                                    transaction_date,
                                    created_at,
                                    updated_at,
                                    delete_at
                                    )
                                   values (
                                   _id,
                                   JSON_UNQUOTE(JSON_EXTRACT(arrayPayment,CONCAT('$[', i, '].transaction_id'))),
                                   JSON_UNQUOTE(JSON_EXTRACT(arrayPayment,CONCAT('$[', i, '].amount'))) ,
                                   JSON_UNQUOTE(JSON_EXTRACT(arrayPayment,CONCAT('$[', i, '].transaction_date'))) ,
                                   now(),
                                   now(),
                                   null
                                   );


                            end if;
                        set  i = i + 1;

               end while;


            while j<JSON_LENGTH(deletePayment) DO

	            set @id = JSON_UNQUOTE(JSON_EXTRACT(deletePayment,CONCAT('$[',j, '].id')));
	            IF @id is not null then
	            update payments  set delete_at=now() where id=@id;


	            end if;
	            set j = j + 1;
            end while;

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
        $procedure = "DROP PROCEDURE IF EXISTS  sp_update_client";
        DB::unprepared($procedure);
    }
}

