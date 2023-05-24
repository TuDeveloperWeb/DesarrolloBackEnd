<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listClient = DB::select('CALL sp_list_clients');
        return response()->json($listClient);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isJson()) {
            $data= $request->all();
            $first_name = $data["first_name"];
            $last_name = $data["last_name"];
            $dob = $data["dob"];
            $phone = $data["phone"];
            $email = $data["email"];
            $address = $data["address"];
            $arrPayments = json_encode($data["payments"])  ;

            DB::select('CALL sp_insert_clients(?,?,?,?,?,?,?,?)', [null,$first_name,$last_name,$dob,$phone,$email,$address,$arrPayments]);

            return response()->json(['message'=>'Exito']);
        } else{
            return response()->json(['error'=>'Not acceptable']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function delete($id)
    {

        DB::select('CALL sp_delete_client(?)', [$id]);
        return response()->json(['message'=>'Exito']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $resultado=DB::select('CALL sp_editClient(?)', [$id]);
        // return response()->json(['message'=>'Exito']);
        return response()->json($resultado);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data= $request->all();
        $id = $data["id"];
        $first_name = $data["first_name"];
        $last_name = $data["last_name"];
        $dob = $data["dob"];
        $phone = $data["phone"];
        $email = $data["email"];
        $address = $data["address"];
        $arrPayments = json_encode($data["payments"])  ;
        $deletePayments = json_encode($data["deletePayment"]) ;

        DB::select('CALL sp_update_client(?,?,?,?,?,?,?,?,?)', [$id,$first_name,$last_name,$dob,$phone,$address,$email,$arrPayments,$deletePayments]);

        return response()->json(['message'=>'Exito']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
