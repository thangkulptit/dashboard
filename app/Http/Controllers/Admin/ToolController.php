<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Game;
use App\Models\ManagementTool;
use Carbon\Carbon;


class ToolController extends Controller
{
    public function getView(Request $request){
        $name_id = $request->game;
        $data['games'] = Game::get();

        $statement = DB::table('management_tool')
            ->select(
                'management_tool.id as mt_id',
                'game.id as game_id',
                'game.name as name',
                'management_tool.mac_address as mac_address',
                'management_tool.customer as customer',
                'management_tool.license_key as license_key',
                'management_tool.total_devices as total_devices',
                'management_tool.active as active',
                'management_tool.created_at as created_at'
                )
            ->join('game', 'game.id', '=', 'management_tool.game_id')
            ->orderBy('management_tool.created_at', 'DESC');
        
        if ($name_id) {
            $statement = $statement->where('game.id', '=', $name_id);
        }

        $data['keyTodayRecords'] = $statement->paginate(20);
        $data['searchGame'] = $name_id;
        
        return view('admin.tool', $data);
    }

    public function getViewGame(){
        $data['games'] = Game::orderBy('id', 'DESC')->paginate(100);
        return view('admin.game', $data);
    }

    // CONTROLLER GAME================
    public function addGame(Request $request){
        $game = new Game();
        $game->name = $request->name;
        $game->other = $request->other;
        $game->save();
        return redirect()->back()->withInput()->with('success','Thêm Game thành công');
    }

    public function removeGame(Request $request) {
        $id = $request->route('id');
        $record = Game::find($id);
        $result = $record->delete();
        $status = $result == true ? 'success' : 'error';
        return redirect()->back()->withInput()->with($status, $status);
    }
    // CONTROLLER GAME================

    public function update(Request $request){
        if($request->ajax()) {
            $type = $request->get('type');
            
            $active = $type == 'active' ? 1 : 0;

            $id = $request->route('id');
            $toolRecord = ManagementTool::find($id);
            $toolRecord->active = $active;
            $toolRecord->save();

            return response()->json([
                'success' => true,
                'msg' => 'Update thành công!'
            ]);
        }
    }

    public function remove(Request $request){
        $id = $request->route('id');
        $toolRecord = ManagementTool::find($id);
        $result = $toolRecord->delete();

        $status = $result == true ? 'success' : 'error';
        return redirect()->back()->withInput()->with($status, $status);
    }

    public function createKey(Request $request){
        $tool = new ManagementTool();

        if (empty($request->game_id)) {
            return '';
        }

        $tool->game_id = $request->game_id;
        $tool->mac_address = null;
        $tool->license_key = $this->generateLicenseKey();
        $tool->total_devices = $request->total_devices;
        $tool->customer = $request->customer;
        $tool->active = 1;
        $tool->save();
        return redirect()->back()->withInput()->with('success','Tạo key thành công');
    }

    private function generateLicenseKey($length = 5) {
        $randomBytes = random_bytes($length).''.time();
        return hash('sha256', $randomBytes);
    }
    
}
