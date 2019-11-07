<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\CarbonInterval;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $jsonCountry = file_get_contents(public_path('country.json'));
        $listCountry = json_decode($jsonCountry, true);
        $user = User::orderBy('id', 'DESC')->paginate(10);
        return view('admin',compact('user','listCountry'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('login');
    }


    public function login(Request $request)
    {
        $login = [
            'email' => $request['email'],
            'password' => $request['password'],
        ];
        if(!Auth::attempt($login)){
            return back()->with('message', 'IT WORKS!');
          //  return back()->with('success', ['Email or Password not exits']);   
        }
        // $user = $request->user();
        // $token =  $user->createToken('MyApp')->accessToken; 
        return redirect()->route('users.index');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $postAttributes = $request->all();
        $time = date('Y-m-d',  time());
        $postAttributes['password'] = Hash::make($postAttributes['password']);
        $carbonTime = Carbon::createFromFormat('Y-m-d', $time);
        $phoneCountry = substr($postAttributes['phone'],0,2);
        if($phoneCountry == "84") // 3 tháng
        {
            $postAttributes['time'] = $carbonTime->add(CarbonInterval::days(90));
        }
        else // 1 tháng
        {
            $postAttributes['time'] = $carbonTime->add(CarbonInterval::days(30));
        }
       // dd($postAttributes);
        $user = User::query()->create($postAttributes);
        //create thành công thì trả về 1token hợp lệ
        return redirect()->route('users.index')->with('message','Created Success!');
        
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jsonCountry = file_get_contents(public_path('country.json'));
        $listCountry = json_decode($jsonCountry, true);
        $user = User::findOrFail($id);
        return view('user.form',compact('user','listCountry'));
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
       $user = User::query()->find($id)->update($request->all());
        //create thành công thì trả về 1token hợp lệ
        return redirect()->route('users.index')->with('message','update success!');
    }
    public function updateLicense(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        $user = User::find($id);

        $carbonTime = Carbon::createFromFormat('Y-m-d', $user['time']);
        //dd($carbonTime);
        if($type == 1) // 1 tháng
        {
            $newTime = $carbonTime->add(CarbonInterval::days(30));
        }
        else if($type == 2) // 6 tháng
        { 
            $newTime = $carbonTime->add(CarbonInterval::days(180));
        }
        else if($type == 3) // 12 months
        { 
            $newTime = $carbonTime->add(CarbonInterval::days(365));
        }
      
        $user->update(['time' => $newTime->format('Y-m-d')]);
        return back();
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::destroy($id);
        return back()->with('message','Delete Success!');
    }

    public function getCountry(Request $request)
    {  
        $countries = $request['country'];
        $jsonProvinces = file_get_contents(public_path('provinces.json'));
        $provinces = json_decode($jsonProvinces, true);
            // so sánh value country với file json
            foreach ($provinces as $item => $province) {
                if($province['iso2'] == $countries){
                   $phone = $province['phone_code'];
                   $states = $province['states'];
                }
            }
       
         //}
   
         return response()->json(['states' => $states, 'phone' => $phone]);
    }
}
