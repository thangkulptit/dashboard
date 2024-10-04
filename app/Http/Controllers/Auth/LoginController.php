<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\ManagementTool;




class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function getLogin(){
        return view('admin.login');
    }

    public function postLogin(Request $request){
        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required|min:3'
            ] 
        );
        $arr = [
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ];
        if (Auth::attempt($arr)){
            $request->session()->regenerate();
            return redirect()->intended('admin/home');
        } else {
            return back()->withInput()->with('error','Tài khoản hoặc mật khẩu không đúng');
        }
    }

    public function check(Request $request) {
        $machineHash = $request->get('machineHash');
        $key = $request->get('key');
        $gameName = $request->get('game');

        if (empty($machineHash) || empty($key) || empty($gameName)) {
            return response()->json([
                'isAuth' => false,
                'msg' => 'Dữ liệu không hợp lệ!'
            ]);
        }

        if (Game::where('name', $gameName)->count() < 1) {
            return response()->json([
                'isAuth' => false,
                'msg' => 'Game không tồn tại!'
            ]);
        }

        $records = ManagementTool::where('license_key', $key)
            ->join('game', 'game.id', '=', 'management_tool.game_id')
            ->where('active', 1)
            ->where('game.name', $gameName)
            ->get();

        if (count($records) < 1) {
            return response()->json([
                'isAuth' => false,
                'msg' => 'Key không hợp lệ! Liên hệ @LeonVLTN để mua.'
            ]);
        }
        //Nếu chưa có địa chỉ Mac nào thì thêm địa chỉ mac vào.
        if (empty($records[0]->mac_address)) {
            $toolRecord = ManagementTool::find($records[0]->id);
            $toolRecord->mac_address = $machineHash;
            $toolRecord->save();

            return response()->json([
                'isAuth' => true,
                'step' => 1
            ]);
        }

        $macAddressList = explode('|', $records[0]->mac_address);

        // Nếu số Thiết bị hiện tại < Số thiết bị cho phép && Và địa chỉ mới Không có trong List địa chỉ cũ
        if (count($macAddressList) < $records[0]->total_devices && !in_array($machineHash, $macAddressList)) {
            array_push($macAddressList, $machineHash);
            $stringMacAddressList = implode('|', $macAddressList);
            // Save Địa chỉ mới vào DB
            $toolRecord = ManagementTool::find($records[0]->id);
            $toolRecord->mac_address = $stringMacAddressList;
            $toolRecord->save();

            return response()->json([
                'isAuth' => true,
            ]);
        }

        // Nếu số lượng trong DB >= Số thiết bị cho phép
        if (count($macAddressList) >= $records[0]->total_devices) {
            if (!in_array($machineHash, $macAddressList)) {
                return response()->json([
                    'isAuth' => false,
                    'msg' => 'Max '. $records[0]->total_devices .' devices!'
                ]);
            }
        }

        return response()->json([
            'isAuth' => true,
            'step' => 2,
        ]);
    }
}
