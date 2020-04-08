<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\User;
use Yajra\DataTables\Facades\DataTables; 
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;
use Auth;


class ClientController extends Controller
{
    use RegistersUsers;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $clients =Client::with('user')->get();
            return Datatables::of($clients)
                    ->addColumn('national_id', function($clients) {
                        return $clients->user->national_id;
                    })
                    ->addColumn('name', function($clients) {
                        return $clients->user->name;
                    })
                    ->addColumn('email', function($clients) {
                        return $clients->user->email;
                    })
                    ->addColumn('gender', function($clients) {
                        return $clients->gender;
                    })
                    ->addColumn('mobile', function($clients) {
                        return $clients->mobile;
                    })
                    ->addColumn('birthdate', function($clients) {
                        return $clients->birthdate;
                    })
                    ->addColumn('is_insured', function($clients) {
                        if($clients->is_insured){
                            return '<p style="color:green;">Insured</p>';
                        }else{
                            return '<p style="color:red;">Not insured</p>';
                        }
                    })
                    ->addColumn('last_login', function($clients) {
                        return $clients->last_login_at;
                    })
                    ->addColumn('role_id', function($clients) {
                        return $clients->user->role_id;
                    })
                    ->addColumn('image', function($clients){   
                        $url = asset('storage/'.$clients->user->image);
                        return '<img src='.$url.' border="0" width="100" height="90" class="img-rounded" align="center" />'; 
                    })
                    ->addColumn('action', function($clients){
                        $btn = '<a href="'.route("clients.edit",["client" => $clients->id]).'" class="edit btn btn-primary btn-sm">Edit</a>';
                        $btn .= '<button type="button" data-id="'.$clients->id.'" data-toggle="modal" data-target="#DeleteProductModal" class="btn btn-danger btn-sm" id="getDeleteId">Delete</button>';

                        return $btn;
                    })
                    ->rawColumns(['is_insured','image','action'])
                    ->make(true);
            }
            return view('clients.index');
    }

    public function create(){
        return view('clients.create');
    }

    public function store(StoreClientRequest $request){
        //get the request data
        //store the request data in the database
        //redirect to show page
       
        //dd($insured);
        $validate = $request->validated();
        if($validate){
            if($request->has('is_insured')){
                //Checkbox checked
                $insured = 1;
            }else{
                //Checkbox not checked
                $insured = 0;
            }
            if ($request->hasfile('image')){
                // $path = $request->file('image')->store('avatarss');
                // dd($path);
                $path = Storage::disk('public')->put('clients_avatars', $request->file('image'));
            }
            // else if($request->gender == "female"){
            //     $path = 'female.png';
               
            // }
            // else if($request->gender == "male"){
            //     $path = 'male.png';
            // }
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'national_id' => $request->national_id,
                'image' => $path
            ]);
    
            $client = Client::create([
                'gender' => $request->gender,
                'birthdate'=> $request->birthdate,
                'is_insured' =>$insured,
                'mobile' => $request->mobile,
                'user_id' => $user->id
            ]);
            
        }
       
        //Auth::login($user);

        return redirect()->route('clients.index');
    }


    public function edit(Request $request){
        
        $clientID = $request->client;
        $client = Client::with('user')->find($clientID);
        if(!$client){
            return redirect()->route('clients.index')->with('error', 'client is not found');
        }
        //dd($client);
        return view('clients.edit',[
            'client' => $client
        ]);

    }

    public function update(UpdateClientRequest $request){

        $client= Client::find($request->client);
 
        $validate = $request->validated();
 
        if($validate){
            $client= Client::with('user')->find($request->client);
            if($request->has('is_insured')){
                //Checkbox checked
                $insured = 1;
            }else{
                //Checkbox not checked
                $insured = 0;
            }
            $path = $client->user->image;
            if ($request->hasfile('image')){
                Storage::disk('public')->delete($path);
                $path = Storage::disk('public')->put('clients_avatars', $request->file('image'));

              //  $path = Storage::putFile('clients_avatars', $request->file('image'));
            }
            
            $client -> update([
                'gender' => $request->gender,
                'birthdate'=> $request->birthdate,
                'is_insured' =>$insured,
                'mobile' => $request->mobile,
                'user_id' => $request->user_id
            ]); 

            $user= User::find($request->user_id);

            $user -> update([
                'name' => $request->name,
                'email' => $request->email,
                'national_id' => $request->national_id,
                'image' => $path
            ]);

              
        }

        return redirect()->route('clients.index');
    }



        /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $client = Client::with('user')->find($request->client);
        $client->user()->delete();
        $client->delete();      
        return redirect()->back();
    }


    public function trashed(Request $request)
    {
        $clients =Client::with('user')->onlyTrashed()->get();
        
        if ($request->ajax()) {
            
            return Datatables::of($clients)
                    ->addColumn('national_id', function($clients) {
                        return $clients->user->national_id;
                    })
                    ->addColumn('name', function($clients) {
                        return $clients->user->name;
                    })
                    ->addColumn('email', function($clients) {
                        return $clients->user->email;
                    })
                    ->addColumn('gender', function($clients) {
                        return $clients->gender;
                    })
                    ->addColumn('mobile', function($clients) {
                        return $clients->mobile;
                    })
                    ->addColumn('birthdate', function($clients) {
                        return $clients->birthdate;
                    })
                    ->addColumn('is_insured', function($clients) {
                        if($clients->is_insured){
                            return '<p style="color:green;">Insured</p>';
                        }else{
                            return '<p style="color:red;">Not insured</p>';
                        }
                    })
                    ->addColumn('last_login', function($clients) {
                        return $clients->last_login_at;
                    })
                    ->addColumn('role_id', function($clients) {
                        return $clients->user->role_id;
                    })
                    ->addColumn('image', function($clients){   
                        $url = asset('storage/'.$clients->user->image);
                        return '<img src='.$url.' border="0" width="100" height="90" class="img-rounded" align="center" />'; 
                    })
                    ->addColumn('action', function($clients){
                        $btn = '<button type="button" data-id="'.$clients->id.'" data-toggle="modal" data-target="#restoreModal" class="btn btn-danger btn-sm" id="getRestoreId">restore</button>';
                        return $btn;
                    })
                    ->rawColumns(['is_insured','image','action'])
                    ->make(true);
            }
            return view('clients.trashed');
    }


    public function restoreClient($id)
    {
        Client::withTrashed()->find($id)->restore();
        return redirect()->route('clients.index');
    }
}

