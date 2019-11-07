<?php


namespace App\Http\Controllers;


use App\Http\Requests\UserPostRequest;
use App\User;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserController extends Controller
{
    public function post(UserPostRequest $request)
    {
        $postAttributes = $request->all();
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
        $time = date('Y:m:d',  time());
        $postAttributes['time'] = $time;
        $user = User::query()->create($postAttributes);
        if($user != null)
        {
            return response([
                'Code' => 1,
                'Message' => 'Register successfully'
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
        // kiểm tra email và mật khẩu đã tồn tại ?
        $email = $request['email'];
        $password = $request['password'];


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


        // Sai password
        if($user['password'] != $password)
        {
            return response([
                'Code' => 0,
                'Message' => 'Password is not correct'
            ],
                200);
        }

        //Nếu đúng sẽ trả về thời gian server, thời gian hết hạn premium
        $time = date('Y-m-d',  time());
        $time_premium = $user['time'];
        return response([
            'Code' => 1,
            'Message' => 'Login successfully',
            'TimeServer' => $time,
            'TimeUser' => $time_premium
        ],
            200);
    }


    public function get($id)
    {
        $user = User::query()->with(['role'])->find($id);
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

        if ($user == null) {
            return response(['Message' => $user], 204);
        }
        return response(['Message' => 'Delete successfully'], 200);
    }
}