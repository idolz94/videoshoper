<?php


namespace App\Http\Controllers;


use App\Http\Requests\UserPostRequest;
use App\User;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function post(UserPostRequest $request)
    {
        $postAttributes = $request->all();
        $postAttributes['password'] = Hash::make($postAttributes['password']);
        $time = date('Y:m:d',  time());
        $postAttributes['time'] = $time;
        $user = User::query()->create($postAttributes);
        return response(['Message' => $user], 201);
    }

    // Đăng kí
    public function register(UserPostRequest $request)
    {
      
        // Nếu chưa tồn tại sẽ tạo dữ liệu và cho vào bảng user
        $postAttributes = $request->all();
        $postAttributes['password'] = Hash::make($postAttributes['password']);
        $time = date('Y-m-d',  time());
        $carbonTime = Carbon::createFromFormat('Y-m-d', $time);
        $phoneCountry = substr($postAttributes['phone'],0,2);
        // nếu đầu số VN thì free 3 tháng
        if($phoneCountry == "84") // 
        {
            $postAttributes['time'] = $carbonTime->add(CarbonInterval::days(90));
        }   
        else // free 1 tháng
        {
            $postAttributes['time'] = $carbonTime->add(CarbonInterval::days(30));
        }
        
        $user = User::query()->create($postAttributes);
     
        if($user != null)
        { 
            $time = date('Y-m-d',  time());
            $time_premium = $user->time;
           // $token =  $user->createToken('MyApp')->accessToken; 
            return response([
                'Code' => 1,
                'Message' => 'Register successfully',
                'TimeServer' => $time,
                'TimeUser' => $time_premium->format('Y-m-d')
              //  'success'=>$token
            ],
                200);
        }
        else
        {
            return response([
                'Code' => 0,
                'Message' => 'Register error'
            ],
                204);
        }

    }

    // Ra hạn
    //type 1 1 tháng
    //type 2 là 6 tháng
    //type 3 là 12 tháng
    public function updateLicense(Request $request)
    {
        // kiểm tra email và mật khẩu đã tồn tại ?
        $email = $request['email'];
        $type = $request['type'];
        $user = User::query()->where('email', $email)->first();
        // Chưa tồn tại user
        if($user == null)
        {
            return response([
                'Code' => 0,
                'Message' => 'User is not exist'
            ],
                201);
        }
      

        $carbonTime = Carbon::createFromFormat('Y-m-d', $user['time']);
        $newTime = $carbonTime;
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
        $newUser = DB::table('users')
            ->where('email', $email)
            ->update(['time' => $newTime->format('Y-m-d')]);
        if($newUser != 0)
        {
            $time = date('Y-m-d',  time());
            return response([
                'Code' => 1,
                'Message' => 'Update License Successfully',
                'TimeServer' => $time,
                'TimeUser' => $newTime->format('Y-m-d')
            ],
                200);
        }
        else
        {
            return response([
                'Code' => 0,
                'Message' => 'Update time not successfully'
            ],
                201);
        }

    }


    // Đăng nhập
    public function login(Request $request)
    {
         // lấy thông tin từ các request gửi lên
         $login = [
                    'email' => $request['email'],
                    'password' => $request['password'],
        ];
        if($login['email'] == null){
                    return response([
                        'Code' => 0,
                        'Message' => 'Email is not exist'
                    ],
                        201);
                }
        if(!Auth::attempt($login)){
            return response([
                'Code' => 0,
                'Message' => 'Email or Password is not exist',
            ],
                201);
        }

        $user = $request->user();
        //  $expired = $user->tokens;
        //  dd($expired);
        
        //  Nếu đúng sẽ trả về thời gian server, thời gian hết hạn premium
         // xác nhận thành công thì trả về 1 token hợp lệ
         $token =  $user->createToken('MyApp'); 
        $time = date('Y-m-d',  time());
        $time_premium = Auth::user()->time;
        return response([
            'Code' => 1,
            'Message' => 'Login successfully',
            'TimeServer' => $time,
            'TimeUser' => $time_premium,
            'token'=>  [
                'access_token' => $token->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $token->token['expires_at']
                )->toDateTimeString()
                ]
        ],200);
    }
    

    public function get($id)
    {
        $user = User::query()->with(['role'])->find($id);
        //dd($user);
        //$paginatedUser = User::query()->limit(5)->offset($request->get('offset'))->get();
        //$sPaginatedUser = User::query()->paginate(5);
//        $queryUser = DB::table('users')->where('id','=', $id)->get();
        //$queryUser = DB::table('users')->find($id);
        if ($user == null) {
            return response([
                'Code' => 0,
                'Message' => $user

            ], 404);
        }

        return response([
            'Code' => 1,
            'Message' => $user
        ], 200);
        /*return response([
            'Message' => $user,
            'QueryUser' => $queryUser
        ], 200);*/
    }

    public function update(Request $request, $id)
    {
        $user = User::query()->find($id)->update($request->all());
        if ($user == false) {
            return response(['Message' => $user], 204);
        }
        return response(['Message' => $user], 200);
    }

    public function delete($id)
    {
        $user = User::destroy($id);
    // code
        if ($user == null) {
            return response(['Message' => $user], 204);
        }
        return response(['Message' => 'Delete successfully'], 200);
    }


    //logout
    public function logout(Request $request){
        $request->user()->token()->revoke(); //->delete()
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    //danh sách các nước
    public function listCountry()
    {
        $jsonCountry = file_get_contents(public_path('country.json'));
        $listCountry = json_decode($jsonCountry, true);
        return response()->json(['Message' => $listCountry], 200);
    }

    // danh sách thành phố vs mã vùng của các nước 
    public function getCountry(Request $request)
    {  
        $jsonProvinces = file_get_contents(public_path('provinces.json'));
        $provinces = json_decode($jsonProvinces, true);
        $data =   $request['country'];
        $country = [];
        // check mã nước và lấy dữ liệu
            foreach ($provinces as $item => $province) {
                if($province['iso2'] == $data){
                   $country['phone'] = $province['phone_code'];
                   $country['states'] = $province['states'];
                }
            }
        return response()->json(['Message' => $country], 200);
    }
    
}