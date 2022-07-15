<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Payment;
use App\Returns;
use App\ReturnPurchase;
use App\Expense;
use App\Payroll;
use App\MoneyTransfer;
use DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;


class AccountsController extends Controller
{
    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('account-index')){
            $lims_account_all = Account::where('is_active', true)->get();
            return view('account.index', compact('lims_account_all'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function create()
    {
        //
    }

    public function show($id)
    {
        //
        if($id == "customer_statement"){
                $role = Role::find(Auth::user()->role_id);
                if($role->hasPermissionTo('account-index')){
                    $lims_account_all = Account::where('is_active', true)->get();
                    
                    return view('account.customer_statement', compact('lims_account_all'));
                }
            
        }elseif($id == "supplier_statement"){
                $role = Role::find(Auth::user()->role_id);
                if($role->hasPermissionTo('account-index')){
                    $lims_account_all = Account::where('is_active', true)->get();
                    return view('account.supplier_statement', compact('lims_account_all'));
                }
        }elseif($id == "expense_statement"){
                $role = Role::find(Auth::user()->role_id);
                if($role->hasPermissionTo('account-index')){
                    $lims_account_all = Account::where('is_active', true)->get();
                    return view('account.expense_statement', compact('lims_account_all'));
                }
        }elseif($id == "bank_statement"){
                $role = Role::find(Auth::user()->role_id);
                if($role->hasPermissionTo('account-index')){
                    $lims_account_all = Account::where('is_active', true)->get();
                    return view('account.bank_statement', compact('lims_account_all'));
                }
        }
    }
    
   

    public function store(Request $request)
    {
        $this->validate($request, [
            'account_no' => [
                'max:255',
                    Rule::unique('accounts')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
        ]);

        $lims_account_data = Account::where('is_active', true)->first();
        $data = $request->all();
        if($data['initial_balance'])
            $data['total_balance'] = $data['initial_balance'];
        else
            $data['total_balance'] = 0;
        if(!$lims_account_data)
            $data['is_default'] = 1;
        $data['is_active'] = true;
        Account::create($data);
        return redirect('accounts')->with('message', 'Account created successfully');
    }

    public function makeDefault($id)
    {
        $lims_account_data = Account::where('is_default', true)->first();
        $lims_account_data->is_default = false;
        $lims_account_data->save();

        $lims_account_data = Account::find($id);
        $lims_account_data->is_default = true;
        $lims_account_data->save();

        return 'Account set as default successfully';
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'account_no' => [
                'max:255',
                    Rule::unique('accounts')->ignore($request->account_id)->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
        ]);

        $data = $request->all();
        $lims_account_data = Account::find($data['account_id']);
        if($data['initial_balance'])
            $data['total_balance'] = $data['initial_balance'];
        else
            $data['total_balance'] = 0;
        $lims_account_data->update($data);
        return redirect('accounts')->with('message', 'Account updated successfully');
    }

    public function balanceSheet()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('balance-sheet')){
            $lims_account_list = Account::where('is_active', true)->get();
            $debit = [];
            $credit = [];
            foreach ($lims_account_list as $account) {
                $payment_recieved = Payment::whereNotNull('sale_id')->where('account_id', $account->id)->sum('amount');
                $payment_sent = Payment::whereNotNull('purchase_id')->where('account_id', $account->id)->sum('amount');
                $returns = DB::table('returns')->where('account_id', $account->id)->sum('grand_total');
                $return_purchase = DB::table('return_purchases')->where('account_id', $account->id)->sum('grand_total');
                $expenses = DB::table('expenses')->where('account_id', $account->id)->sum('amount');
                $payrolls = DB::table('payrolls')->where('account_id', $account->id)->sum('amount');
                $sent_money_via_transfer = MoneyTransfer::where('from_account_id', $account->id)->sum('amount');
                $recieved_money_via_transfer = MoneyTransfer::where('to_account_id', $account->id)->sum('amount');

                $credit[] = $payment_recieved + $return_purchase + $recieved_money_via_transfer + $account->initial_balance;
                $debit[] = $payment_sent + $returns + $expenses + $payrolls + $sent_money_via_transfer;

                /*$credit[] = $payment_recieved + $return_purchase + $account->initial_balance;
                $debit[] = $payment_sent + $returns + $expenses + $payrolls;*/
            }
            return view('account.balance_sheet', compact('lims_account_list', 'debit', 'credit'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function accountStatement(Request $request)
    {
        $data = $request->all();
        $lims_account_data = Account::find($data['account_id']);
        $credit_list = [];
        $debit_list = [];
        $expense_list = [];
        $return_list = [];
        $purchase_return_list = [];
        $payroll_list = [];
        $recieved_money_transfer_list = [];
        $sent_money_transfer_list = [];
        
        if($data['type'] == '0' || $data['type'] == '2') {
            $credit_list = Payment::whereNotNull('sale_id')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();

            $recieved_money_transfer_list = MoneyTransfer::where('to_account_id', $data['account_id'])->get();
        }
        if($data['type'] == '0' || $data['type'] == '1'){
            $debit_list = Payment::whereNotNull('purchase_id')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();

            $expense_list = Expense::where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();

            $return_list = Returns::where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();

            $purchase_return_list = ReturnPurchase::where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();

            $payroll_list = Payroll::where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();

            $sent_money_transfer_list = MoneyTransfer::where('from_account_id', $data['account_id'])->get();
        }
        $balance = 0;
        return view('account.account_statement', compact('lims_account_data', 'credit_list', 'debit_list', 'expense_list', 'return_list', 'purchase_return_list', 'payroll_list', 'recieved_money_transfer_list', 'sent_money_transfer_list', 'balance'));
    }

    public function destroy($id)
    {
        if(!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');
        $lims_account_data = Account::find($id);
        if(!$lims_account_data->is_default){
            $lims_account_data->is_active = false;
            $lims_account_data->save();
            return redirect('accounts')->with('not_permitted', 'Account deleted successfully!');
        }
        else
            return redirect('accounts')->with('not_permitted', 'Please make another account default first!');
    }

    public function getRecords($id){
        
        $payment = str_replace($id, 'payment_', $id);
        $payment_id = str_replace('payment_', '', $id);
        
                $debit_list_two = DB::table('payments as pay')
                     ->where('pay.account_id',$payment_id)
                     ->where('sale_id', '!=', NULL)
                     ->orderBy('date_2','ASC')
                     ->select("pay.*", DB::raw('DATEDIFF(NOW(), date_2) as expires_in, DATE_FORMAT(pay.date_2, "%d-%m-%Y") as formatted_date'))
                     ->get();
        
        $total_receiving = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('sale_id', '!=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');
            
        $total_receiving = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('sale_id', '!=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');
            
            
            
        $current_balance_credit = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('sale_id', '!=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');
            
        $current_balance_debit = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('sale_id', '!=', NULL)
            ->where('type', '=', 'd')
            ->sum('debit');
         
         $userData['total_debit'] = $current_balance_debit;
         $userData['total_credit'] = $current_balance_credit;
         
         // Fetch all records
         $userData['data'] = $debit_list_two;
         $userData['total_receiving'] = $total_receiving;
         
         echo json_encode($userData);
         exit;
         
         // Fetch all records
         $userData['data'] = $debit_list_two;
    
         echo json_encode($userData);
         exit;
         
}

    public function getRecords_tick($id, $current_balance){
            
        $payments = DB::table('payments')
            ->where('id', '=', $id)
            ->get();
            
        //$payments[0]->debit + $payments[0]->credit
            
        DB::table('payments')
        ->where('id', $id)
        ->update(['status' => 1, 'checked_amount' => $current_balance]);
        
        $updated['success'] = 'Updated Successfully.';
        $updated['id'] = $payments[0]->account_id;

        
        echo json_encode($updated);
            
        
    }

    public function getSupplierRecords($id){


        $payment = str_replace($id, 'payment_', $id);
        $payment_id = str_replace('payment_', '', $id);
        
        
                $debit_list_two = DB::table('payments as pay')
                     ->where('pay.account_id',$payment_id)
                     ->where('purchase_id', '!=', NULL)
                     ->orderBy('date_2','ASC')
                     ->select("pay.*", DB::raw('DATEDIFF(NOW(), date_2) as expires_in, DATE_FORMAT(pay.date_2, "%d-%m-%Y") as formatted_date'))
                     ->get();
        
        $total_receiving = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('purchase_id', '!=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');
            
        $total_receiving = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('purchase_id', '!=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');
            
            
            
        $current_balance_credit = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('purchase_id', '!=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');
            
        $current_balance_debit = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('purchase_id', '!=', NULL)
            ->where('type', '=', 'd')
            ->sum('debit');
         
         $userData['total_debit'] = $current_balance_debit;
         $userData['total_credit'] = $current_balance_credit;
         
         // Fetch all records
         $userData['data'] = $debit_list_two;
         $userData['total_receiving'] = $total_receiving;
         
         echo json_encode($userData);


          
        //  $payment = str_replace($id, 'payment_', $id);
        //  $payment_id = str_replace('payment_', '', $id);
         
        //      $debit_list_two_previous = DB::table('payments as pay')
        //               ->where('pay.account_id',$payment_id)
        //               ->where('purchase_id', '!=', NULL)
        //               ->orderBy('date_2','ASC')
        //               ->select("pay.*", DB::raw('DATE_FORMAT(pay.date_2, "%d-%m-%Y") as formatted_date'))
        //               ->get();
         
        //  $total_receiving_previous = DB::table('payments')
        //      ->where('account_id', '=', $payment_id)
        //      ->where('purchase_id', '!=', NULL)
        //      ->where('type', '=', 'c')
        //      ->sum('credit');
             
             
        //  $debit_list_two = DB::table('payments as pay')
        //               ->where('pay.account_id',$payment_id)
        //               ->where('purchase_id', '!=', NULL)
        //               ->orderBy('date_2','ASC')
        //               ->select("pay.*", DB::raw('DATE_FORMAT(pay.date_2, "%d-%m-%Y") as formatted_date'))
        //               ->get();
         
        //  $total_receiving = DB::table('payments')
        //      ->where('account_id', '=', $payment_id)
        //      ->where('purchase_id', '!=', NULL)
        //      ->where('type', '=', 'c')
        //      ->sum('credit');
             
        //   // Fetch all records
        //   $userData['data'] = $debit_list_two;
        //   $userData['total_receiving'] = $total_receiving;
          
        //   $userData['data_previous'] = $debit_list_two_previous;
        //   $userData['total_receiving_previous'] = $total_receiving_previous;
     
        //   echo json_encode($userData);
          exit;
          
          
         
          // Fetch all records
          $userData['data'] = $debit_list_two;
     
          echo json_encode($userData);
          exit;
          
 }

 public function checkRecords($id){
        
    $payment = str_replace($id, 'payment_', $id);
    $payment_id = str_replace('payment_', '', $id);
    
    
     $current_balance_credit = DB::table('payments')
        ->where('account_id', '=', $payment_id)
        ->where('sale_id', '!=', NULL)
        ->where('type', '=', 'c')
        ->sum('credit');
        
    $current_balance_debit = DB::table('payments')
        ->where('account_id', '=', $payment_id)
        ->where('sale_id', '!=', NULL)
        ->where('type', '=', 'd')
        ->sum('debit');
        
    return $current_balance_debit - $current_balance_credit;
        
}


 public function getEXPRecords($id){
        
    $payment = str_replace($id, 'payment_', $id);
    $payment_id = str_replace('payment_', '', $id);
    
    
    
        $debit_list_two = DB::table('payments as pay')
                 ->where('pay.account_id',$payment_id)
    
                 ->orderBy('date_2','ASC')
                 ->select("pay.*", DB::raw('DATEDIFF(NOW(), date_2) as expires_in, DATE_FORMAT(pay.date_2, "%d-%m-%Y") as formatted_date'))
                 ->get();
    
    $total_receiving = DB::table('payments')
        ->where('account_id', '=', $payment_id)
    
        ->where('type', '=', 'c')
        ->sum('credit');
        
    $total_receiving = DB::table('payments')
        ->where('account_id', '=', $payment_id)
    
        ->where('type', '=', 'c')
        ->sum('credit');
        
        
        
    $current_balance_credit = DB::table('payments')
        ->where('account_id', '=', $payment_id)
    
        ->where('type', '=', 'c')
        ->sum('credit');
        
    $current_balance_debit = DB::table('payments')
        ->where('account_id', '=', $payment_id)
    
        ->where('type', '=', 'd')
        ->sum('debit');
     
     $userData['total_debit'] = $current_balance_debit;
     $userData['total_credit'] = $current_balance_credit;
     
     // Fetch all records
     $userData['data'] = $debit_list_two;
     $userData['total_receiving'] = $total_receiving;
     
     

     echo json_encode($userData);
     exit;
     
     
    
     // Fetch all records
     $userData['data'] = $debit_list_two;

     echo json_encode($userData);
     exit;
     
}

public function getBankRecords_two($id){
        
    $payment = str_replace($id, 'payment_', $id);
    $payment_id = str_replace('payment_', '', $id);
    
    
    
        $debit_list_two = DB::table('payments as pay')
                 ->where('pay.account_id',$payment_id)
    
                 ->orderBy('date_2','ASC')
                 ->select("pay.*", DB::raw('DATEDIFF(NOW(), date_2) as expires_in, DATE_FORMAT(pay.date_2, "%d-%m-%Y") as formatted_date'))
                 ->get();
    
    $total_receiving = DB::table('payments')
        ->where('account_id', '=', $payment_id)
    
        ->where('type', '=', 'c')
        ->sum('credit');
        
    $total_receiving = DB::table('payments')
        ->where('account_id', '=', $payment_id)
    
        ->where('type', '=', 'c')
        ->sum('credit');
        
        
        
    $current_balance_credit = DB::table('payments')
        ->where('account_id', '=', $payment_id)
    
        ->where('type', '=', 'c')
        ->sum('credit');
        
    $current_balance_debit = DB::table('payments')
        ->where('account_id', '=', $payment_id)
    
        ->where('type', '=', 'd')
        ->sum('debit');
     
     $userData['total_debit'] = $current_balance_debit;
     $userData['total_credit'] = $current_balance_credit;
     
     // Fetch all records
     $userData['data'] = $debit_list_two;
     $userData['total_receiving'] = $total_receiving;
     
     

     echo json_encode($userData);
     exit;
     
     
    
     // Fetch all records
     $userData['data'] = $debit_list_two;

     echo json_encode($userData);
     exit;
     
}
 

}
