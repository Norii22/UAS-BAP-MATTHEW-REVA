<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Validator;
use Hash;
use Carbon\Carbon;
use DB;
use App\Models\Assets;
use App\Models\Transaction;

class HomeController extends Controller
{

    public function dashboardPage(){
        $user = Auth::user();
        $title = 'Dashboard';
        $list_asset = Assets::where('user_id',$user->id)->get();
        $list_tsc = Transaction::where('user_id',$user->id)->get();
        $total_income = Transaction::where('tsc_category','Income')->sum('tsc_amount');
        return view('dashboard',compact('title','list_asset','list_tsc','total_income'));
    }
    public function adminDashboardPage(){
        $user = Auth::user();
        $title = 'Admin Dashboard';
        $list_user = User::where('role','user')->get();
        $list_tsc = Transaction::all();
        return view('admin_dashboard',compact('title','list_user','list_tsc'));
    }
    public function transactionPage(){
        $user = Auth::user();
        $title = 'Transactions';
        $list_asset = Assets::where('user_id',$user->id)->get();
        $list_tsc = Transaction::where('user_id',$user->id)->get();
        return view('transaction',compact('title','list_tsc','list_asset'));
    }
    public function journalPage(){
        $user = Auth::user();
        $title = 'Journals';
        $list_tsc = Transaction::where('user_id',$user->id)->get();
        foreach ($list_tsc as $tsc) {
            if(strpos($tsc->tsc_detail,"Pembelian properti") !== false){
                $city = str_replace("Pembelian properti di ","",$tsc->tsc_detail);
                $asset = Assets::where('user_id',$user->id)->where('asset_loc',$city)->first();
                // dd($asset);
                // $tsc->info_land = "Land - ".$city;
                // $tsc->info_building = "Building - ".$city;
                $tsc->city = $city;
                $tsc->land_price = $asset->asset_land_price;
                $tsc->building_price = $asset->asset_building_price;
            }
        }
        $total_tsc = $list_tsc->sum('tsc_amount');
        // dd($list_tsc);
        return view('journal',compact('title','list_tsc','total_tsc'));
    }
    public function generalLedgerPage(){
        $user = Auth::user();
        $title = 'General Ledger';
        $list_tsc = Transaction::where('user_id',$user->id)->get();
        $list_asset = Assets::where('user_id',$user->id)->get();
        $list_liability = Transaction::where('user_id',$user->id)->where('tsc_category','Liability')->get();
        $total_liability = $list_liability->sum('tsc_amount');
        $list_income = Transaction::where('user_id',$user->id)->where('tsc_category','Income')->get();
        $total_income = $list_income->sum('tsc_amount');
        $list_expenses = Transaction::where('user_id',$user->id)->where('tsc_category','Expenses')->get();
        $total_expenses = $list_expenses->sum('tsc_amount');
        // $list_equity = Transaction::where('user_id',$user->id)->where('tsc_category','Equity')->get();
        // $total_equity = $list_equity->sum('tsc_amount');
        return view('general_ledger',compact('title','list_tsc','list_asset','list_liability',
                    'total_liability','list_income','total_income','list_expenses','total_expenses'
                    // ,'total_equity','list_equity'
                ));
    }
    public function trialBalancePage(){
        $user = Auth::user();
        $title = 'Trial Balance';
        $list_tsc = Transaction::where('user_id',$user->id)->get();
        $total_land = Assets::where('user_id',$user->id)->sum('asset_land_price');
        $total_building = Assets::where('user_id',$user->id)->sum('asset_building_price');
        $total_banksloan = Transaction::where('user_id',$user->id)->where('tsc_category','Liability')->sum('tsc_amount');
        $total_income = Transaction::where('user_id',$user->id)->where('tsc_category','Income')->sum('tsc_amount');
        $total_expenses = Transaction::where('user_id',$user->id)->where('tsc_category','Expenses')->sum('tsc_amount');
        return view('trial_balance',compact('title','list_tsc','total_land','total_building','total_banksloan','total_income','total_expenses'));
    }
    public function balancePage(){
        $user = Auth::user();
        $title = 'Balance';
        $list_tsc = Transaction::where('user_id',$user->id)->get();
        $total_land = Assets::where('user_id',$user->id)->sum('asset_land_price');
        $total_building = Assets::where('user_id',$user->id)->sum('asset_building_price');
        $total_banksloan = Transaction::where('user_id',$user->id)->where('tsc_category','Liability')->sum('tsc_amount');
        $total_income = Transaction::where('user_id',$user->id)->where('tsc_category','Income')->sum('tsc_amount');
        $total_expenses = Transaction::where('user_id',$user->id)->where('tsc_category','Expenses')->sum('tsc_amount');
        $net_loss = abs($total_income - $total_expenses);
        $end_balance = $list_tsc[0]->balance - abs($net_loss);
        $total_endequity = $end_balance + abs($total_banksloan);
        return view('balance',compact('title','list_tsc','total_land','total_building','total_banksloan','total_income','total_expenses','total_endequity'));
    }
    public function financialStatementPage(){
        $user = Auth::user();
        $title = 'Financial Statement';
        $list_tsc = Transaction::where('user_id',$user->id)->get();
        $total_income = Transaction::where('user_id',$user->id)->where('tsc_category','Income')->sum('tsc_amount');
        $total_expenses = Transaction::where('user_id',$user->id)->where('tsc_category','Expenses')->sum('tsc_amount');
        $net_loss = abs($total_income - $total_expenses);
        $end_balance = $list_tsc[0]->balance - abs($net_loss);
        $cash = $list_tsc[count($list_tsc) - 1]->balance - $list_tsc[count($list_tsc) - 1]->tsc_amount;
        $total_land = Assets::where('user_id',$user->id)->sum('asset_land_price');
        $total_building = Assets::where('user_id',$user->id)->sum('asset_building_price');
        $total_asset = $cash + $total_land + $total_building;
        $list_liability = Transaction::where('user_id',$user->id)->where('tsc_category','Liability')->get();
        $total_banksloan = Transaction::where('user_id',$user->id)->where('tsc_category','Liability')->sum('tsc_amount');
        $total_endequity = $end_balance + abs($total_banksloan);
        return view('finance_statement',compact('title','list_tsc','total_income','total_expenses','net_loss','end_balance',
                    'cash','total_land','total_building','list_liability','total_asset','total_banksloan','total_endequity'));
    }
    public function getTransactionInfo($tsc_id){
        $tsc_info = Transaction::where('id',$tsc_id)->first();
        return $tsc_info;
    }
    public function usersPage(){
        $title = 'Users List Page';
        $list_users = User::where('role','user')->get();
        return view('users',compact('list_users','title'));
    }
    public function transactionAdmin(){
        $title = 'Transaction List';
        $list_tsc = Transaction::all();
        foreach($list_tsc as $tsc){
            $tsc->name = User::where('id',$tsc->user_id)->first()->name;
        }
        return view('transaction_admin',compact('title','list_tsc'));
    }
    public function printLayout(){
        $title = 'Transaction List Layout';
        $list_tsc = Transaction::all();
        foreach($list_tsc as $tsc){
            $tsc->name = User::where('id',$tsc->user_id)->first()->name;
        }
        return view('print_layout',compact('title','list_tsc'));
    }

    public function updateEquity(Request $request){
        $user = Auth::user();
        $data = $request->all();
        DB::beginTransaction();
        try{
            User::where('id',$user->id)->update([
                'equity' => $data['equity'],
            ]);
            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
        return redirect()->back()->with('success','Update Equity Value Success');
    }
    public function addAsset(Request $request){
        $user = Auth::user();
        $data = $request->all();
        DB::beginTransaction();
        try{
            if($user->equity < $data['building_price'] + $data['land_price']){
                return redirect()->back()->with('error','Set your Equity Balance first');
            }
            Transaction::create([
                'user_id' => $user->id,
                'tsc_type' => 'out',
                'tsc_amount' => $data['building_price'] + $data['land_price'],
                'tsc_category' => 'Land;Building',
                'tsc_detail' => 'Pembelian properti di '.$data['city'],
                'tsc_detail2' => 'Cash',
                'tsc_target' => 'Bank',
                'balance' => $user->equity,
            ]);
            Assets::create([
                'user_id' => $user->id,
                'asset_type' => 'own',
                'asset_name' => $data['name'],
                'asset_loc' => $data['city'],
                'asset_building_price' => $data['building_price'],
                'asset_land_price' => $data['land_price']
            ]);
            User::where('id',$user->id)->update([
                'equity' => $user->equity - ($data['building_price'] + $data['land_price'])
            ]);
            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
        return redirect()->back()->with('success','Success add new asset');
    }
    public function addTransaction(Request $request){
        $user = Auth::user();
        $data = $request->all();
        DB::beginTransaction();
        try {
            if($user->equity < $data['tsc_amount']){
                return redirect()->back()->with('error','Set your Equity Balance first');
            }
            Transaction::create([
                'user_id' => $user->id,
                'tsc_type' => $data['tsc_type'],
                'tsc_amount' => $data['tsc_amount'],
                'tsc_category' => $data['tsc_category'],
                'tsc_title' => $data['tsc_title'],
                'tsc_detail' => $data['tsc_detail1'],
                'tsc_detail2' => $data['tsc_detail2'],
                'tsc_target' => $data['tsc_target'],
                'balance' => $user->equity,
            ]);
            if($data['tsc_type'] == 'out'){
                $amount = -1 * $data['tsc_amount'];
            }
            else {
                $amount = $data['tsc_amount'];
            }
            User::where('id',$user->id)->update([
                'equity' => $user->equity + $amount
            ]);
            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
        return redirect()->back()->with('success','Success add new transaction');
    }
    public function clearTransaction(){
        $user = Auth::user();
        DB::beginTransaction();
        try{
            Transaction::where('user_id',$user->id)->delete();
            Assets::where('user_id',$user->id)->delete();
            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
        return redirect()->back()->with('success','Your transaction list has been cleared');
    }
    public function submitEdit(Request $request){
        $user = Auth::user();
        $data = $request->all();
        DB::beginTransaction();
        try {
            if($user->equity < $data['tsc_amount_ed']){
                return redirect()->back()->with('error','Set your Equity Balance first');
            }
            $tsc_id = $data['tsc_id'];
            $tsc_info = Transaction::where('id',$tsc_id)->first();
            Transaction::where('id',$tsc_id)->update([
                'tsc_type' => $data['tsc_type_ed'],
                'tsc_amount' => $data['tsc_amount_ed'],
                'tsc_category' => $data['tsc_category_ed'],
                'tsc_detail3' => $data['tsc_title_ed'],
                'tsc_detail' => $data['tsc_detail1_ed'],
                'tsc_detail2' => $data['tsc_detail2_ed'],
                'tsc_target' => $data['tsc_target_ed'],
            ]);
            $amount = $data['tsc_amount_ed'];
            if($tsc_info->tsc_type == 'in' && $data['tsc_type_ed'] == 'out'){
                User::where('id',$user->id)->update([
                    'equity' => $user->equity - $amount
                ]);
            }
            else if($tsc_info->tsc_type == 'out' && $data['tsc_type_ed'] == 'in') {
                User::where('id',$user->id)->update([
                    'equity' => $user->equity + $amount
                ]);
            }
            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
        return redirect()->back()->with('success','Success edit transaction');

    }
    public function deleteTransaction($id){
        DB::beginTransaction();
        try{
            Transaction::where('id',$id)->delete();
            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
        return redirect()->back()->with('success',"Success delete transaction");
    }
}
