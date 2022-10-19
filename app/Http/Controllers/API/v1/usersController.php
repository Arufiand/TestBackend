<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class usersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $role = users::->get();
        $users = User::orderBy('User.created_at', 'DESC')
                    ->get();
        $response = [
            'Status_Request' => True,
            'Message' => 'List users',
            'Data' => $users
        ];

        return response()->json($response, Response::HTTP_OK);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($pallet_name)
    {
        $users = User::findOrFail($pallet_name);
        try {
            $response = [
                'Message' => 'Detail users',
                'Data' => $users
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json('id Not Found', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // $idRole = $request->idRole;
         $users = User::findOrFail($id);

         $validator = Validator::make(
             $request->all(),
             [
                //  'nama_users' => ['required'],
                 'status' => ['required', 'boolean']
                 // 'type' => ['required', 'in:expense, revenue'] untuk menentapkan nilai yang boleh masuk
                 // 'Numeric' => ['required', 'numeric']
             ]
         );
         if ($validator->fails()) {
             return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
         }
 
         //Simpan Variabel satu per satu
         // $request->nama_users;
         // $request->status;
         // Atau
         //simpan semua variabel dengan
         // $request->all()
 
         try {
             $users->update($request->all());
             $response = [
                 'Status_Request' => true,
                 'Message' => 'users Updated',
                 'Data' => $users
             ];
             return response()->json($response, Response::HTTP_OK);
         } catch (QueryException $e) {
             return response()->json(['Message' => "Failed", "Status_Request" => false . $e->errorInfo], Response::HTTP_BAD_REQUEST);
         }
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
