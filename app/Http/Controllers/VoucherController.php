<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

use Auth;
use Hash;
use DB;
use App\Account;
use App\Payment;
use App\Returns;
use App\ReturnPurchase;
use App\Expense;
use App\Payroll;
use App\Customer;
use App\Supplier;
use App\Product;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
     public function add_cash_in_hand(Request $request){
         
         //dd($request);
         //die();
         
         DB::table('cash_registers')->insert([
                    'created_at' => $request['date'],
                    'cash_in_hand' => $request['cashInHand'],
                    'user_id' => 1
                ]);
                
        return redirect()->back();
         
     }
     
     public function customer_payment_voucher_list(){
         
        return view('voucher.customer_payment_voucher_list');
    }
    
   
   public function supplier_receiving_voucher_edit_post(Request $request){
            //dd($request);
            //die();
         DB::table('payment_vouchers')->where('id', $request->id)->update(
             array(
                 'receive_voucher_date' => $request->date,
                 'amount' => $request->amount,
                 'action' => $request->action,
                 'supplier_id' => $request->account_id,
                 )
             );
             
              $account = DB::table('accounts')
                            ->where('supplier_id', '=', $request->account_id)
                            ->get();

             
             if($request->action == 'credit'){
                 
                 DB::table('payments')->where('payment_voucher_id', $request->id)->update(
                 array(
                     'date' => $request->date,
                     'date_2' => $request->date,
                     'amount' => $request->amount,
                     'credit' => $request->amount,
                     'debit' => NULL,
                     'type' => 'c',
                     'account_id' => $request->account_id,
                     )
                 ); 
             
             }elseif($request->action == 'debit'){
                     DB::table('payments')->where('payment_voucher_id', $request->id)->update(
                 array(
                     'date' => $request->date,
                     'date_2' => $request->date,
                     'amount' => $request->amount,
                     'debit' => $request->amount,
                     'credit' => NULL,
                     'type' => 'd',
                     'account_id' => $request->account_id,
                     )
                 ); 
             }
             
             
         return redirect()->back()->with('success',  'Voucher has been updated successfully');
    }
    
    public function customer_receiving_voucher_edit_post(Request $request){
        //dd($request);
        //die();
         DB::table('receive_vouchers')->where('id', $request->id)->update(
             array(
                 'receive_voucher_date' => $request->date,
                 'amount' => $request->amount,
                 'action' => $request->action,
                 'customer_id' => $request->account_id,
                 )
             );
             
                $account = DB::table('accounts')
                            ->where('account_no', '=', $request->account_id)->where('account_type', '=', 'customer')
                            ->get();

             
             if($request->action == 'credit'){
                 
                 DB::table('payments')->where('receive_voucher_id', $request->id)->update(
                 array(
                     'date' => $request->date,
                     'date_2' => $request->date,
                     'amount' => $request->amount,
                     'credit' => $request->amount,
                     'debit' => NULL,
                     'type' => 'c',
                     'account_id' => $account[0]->account_no,
                     )
                 ); 
             
             }elseif($request->action == 'debit'){
                     DB::table('payments')->where('receive_voucher_id', $request->id)->update(
                 array(
                     'date' => $request->date,
                     'date_2' => $request->date,
                     'amount' => $request->amount,
                     'debit' => $request->amount,
                     'credit' => NULL,
                     'type' => 'd',
                     'account_id' => $account[0]->account_no,
                     )
                 ); 
             }
             
             
         return redirect()->back()->with('success',  'Voucher has been updated successfully');
    }
    
    
    
    public function customer_receiving_voucher_list(){
        
        $customer_receive_voucher = DB::table('receive_vouchers')->get();
              
                
        return view('voucher.customer_receiving_voucher_list',compact('customer_receive_voucher'));
    }
    
    public function customer_receiving_voucher_edit($id){
        
        $customer_receive_voucher = DB::table('receive_vouchers')->where('id', '=', $id)->get();
        //dd($customer_receive_voucher);
        //die();        
        //$customer_receive_voucher = DB::table('receive_vouchers')
          //      ->join('customers','receive_vouchers.customer_id','=','customers.id')
            //    ->select('customers.id as cust_id, customers.city, customers.company_name, customers.name, receive_vouchers.id as rv_id, receive_vouchers.amount, receive_vouchers.receive_voucher_date, receive_vouchers.actions')
              //  ->get();
        //dd($customer_receive_voucher);
        //die();        
                
        return view('voucher.customer_receiving_voucher_edit',compact('customer_receive_voucher'));
    }
    
    public function supplier_receiving_voucher_edit($id){
        
        $supplier_receive_voucher = DB::table('payment_vouchers')->where('id', '=', $id)->get();
        //dd($supplier_receive_voucher);
        //die();
        //$customer_receive_voucher = DB::table('receive_vouchers')
          //      ->join('customers','receive_vouchers.customer_id','=','customers.id')
            //    ->select('customers.id as cust_id, customers.city, customers.company_name, customers.name, receive_vouchers.id as rv_id, receive_vouchers.amount, receive_vouchers.receive_voucher_date, receive_vouchers.actions')
              //  ->get();
        //dd($customer_receive_voucher);
        //die();        
        return view('voucher.supplier_receiving_voucher_edit',compact('supplier_receive_voucher'));
    }
    
    
    
    public function supplier_receiving_voucher_list(){
        
        $supplier_receive_voucher = DB::table('payment_vouchers')
                ->join('suppliers','suppliers.id','=','payment_vouchers.supplier_id')
                ->select('*')
                ->get();
        //dd($customer_receive_voucher);
        //die();        
        return view('voucher.supplier_payment_voucher_list',compact('supplier_receive_voucher'));
    }
    
    
    
    
    
     
     public function cash_in_hand(){
         
         $cash_in_hand = DB::table('cash_registers')->get();
         return view('voucher.cashInHand', compact('cash_in_hand'));
     }
    public function customer_ledger_delete($id){
              //dd($id);
              //die();
              $payments = DB::table('payments')
                ->where('id', '=', $id)
                ->get();
            
            if($payments[0]->receive_voucher_id != NULL){
                DB::table('receive_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
            }elseif($payments[0]->payment_voucher_id != NULL){
                DB::table('payment_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
            }elseif($payments[0]->expense_voucher_id != NULL){
                DB::table('expense_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
            }elseif($payments[0]->bank_voucher_id != NULL){
                DB::table('bank_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
            }
            
         DB::table('payments')->where('id', '=', $id)->delete();
         return redirect()->back();
     }
     
    public function supplier_ledger_delete($id){
              //dd($id);
              //die();
              $payments = DB::table('payments')
                ->where('id', '=', $id)
                ->get();
            
            if($payments[0]->receive_voucher_id != NULL){
                DB::table('receive_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
            }elseif($payments[0]->payment_voucher_id != NULL){
                DB::table('payment_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
            }elseif($payments[0]->expense_voucher_id != NULL){
                DB::table('expense_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
            }elseif($payments[0]->bank_voucher_id != NULL){
                DB::table('bank_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
            }
            
         DB::table('payments')->where('id', '=', $id)->delete();
         return redirect()->back();
     }
     
    public function voucher_deleted($id){
        echo $id;
        
        
    }
        public function add_recieve_voucher(){
        
        //echo "Testing";
        //die();
        //
            $lims_account_list = \App\Account::where('is_active', true)->where('supplier_id', NULL)->get();
            $lims_user_list = \App\User::where('is_active', true)->get();
            //$payment = Payment::orderBy('created_at', 'desc')->get();
            
            $payment = Payment::all();

            //$debit_list = Payment::whereNotNull('debit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            $receive_voucher_payment_list = Payment::all();
            //$credit_list = Payment::whereNotNull('credit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            //$credit_list = Payment::whereNotNull('credit')->get();
            $balance = 0;
            $lims_products_list = Customer::where('is_active', true)->orderBy('name', 'ASC')->get();
            $list_payment = DB::table('payments')->orderBy('created_at', 'desc')->first();
            
            //$product_id =  $request->input('product_id');
            
            $today = date('23-03-2021');
            //$sale_date = $request->input('sale_date');
            $newDate = date("d-m-Y", strtotime($today));
            
            //$today = $newDate;
            
            $receive_vouchers = DB::table('receive_vouchers')
                ->orderBy('id', 'ASC')
                ->get();
            
            //$testing = DB::table('receive_vouchers')->orderBy('id', 'ASC')->first();
            
            return view('voucher.add_receive_voucher', compact('lims_account_list','receive_voucher_payment_list', 'receive_vouchers', 'lims_products_list', 'list_payment',  'lims_user_list','payment','balance'));
        
    }
    
    public function add_payment_voucher(){
        
        //echo "Testing";
        //die();
        //
            $lims_account_list = \App\Account::where('is_active', true)->where('supplier_id', NULL)->get();
            $lims_user_list = \App\User::where('is_active', true)->get();
            //$payment = Payment::orderBy('created_at', 'desc')->get();
            
            $payment = Payment::all();

            //$debit_list = Payment::whereNotNull('debit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            $receive_voucher_payment_list = Payment::all();
            //$credit_list = Payment::whereNotNull('credit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            //$credit_list = Payment::whereNotNull('credit')->get();
            $balance = 0;
            $lims_products_list = Customer::where('is_active', true)->orderBy('name', 'ASC')->get();
            $list_payment = DB::table('payments')->orderBy('created_at', 'desc')->first();
            
            //$product_id =  $request->input('product_id');
            
            $today = date('23-03-2021');
            //$sale_date = $request->input('sale_date');
            $newDate = date("d-m-Y", strtotime($today));
            
            //$today = $newDate;
            
            $receive_vouchers = DB::table('receive_vouchers')
                ->orderBy('id', 'ASC')
                ->get();
            //$testing = DB::table('receive_vouchers')->orderBy('id', 'ASC')->first();
            return view('voucher.add_purchase_voucher', compact('lims_account_list','receive_voucher_payment_list', 'receive_vouchers', 'lims_products_list', 'list_payment',  'lims_user_list','payment','balance'));
    }
    public function customer_receiving_voucher(){
        //echo "Customer Receiving Voucher";
        //die();
        $lims_products_list = Customer::orderBy('name', 'ASC')->get();
        
        return view('voucher.customer_receive_voucher', compact('lims_products_list'));
    }
    public function customer_payment_voucher(){
        $lims_products_list = Customer::where('is_active', true)->orderBy('name', 'ASC')->get();
        
        return view('voucher.customer_payment_voucher', compact('lims_products_list'));
        
    }
    public function supplier_receiving_voucher(){
        
        $lims_products_list = Supplier::where('is_active', true)->orderBy('name', 'ASC')->get();
        
        return view('voucher.supplier_receive_voucher', compact('lims_products_list'));
        
        
    }
    public function supplier_payment_voucher(){
        
        
        $lims_products_list = Supplier::where('is_active', true)->orderBy('name', 'ASC')->get();
        
        return view('voucher.supplier_payment_voucher', compact('lims_products_list'));
        
    }
    
    public function expense_receiving_voucher(){
         $lims_account_list = \App\Account::where('is_active', true)->where('customer_id', NULL)->get();
            $lims_user_list = \App\User::where('is_active', true)->get();
            //$payment = Payment::orderBy('created_at', 'desc')->get();
            
            $payment = Payment::all();

            //$debit_list = Payment::whereNotNull('debit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            $receive_voucher_payment_list = Payment::all();
            //$credit_list = Payment::whereNotNull('credit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            //$credit_list = Payment::whereNotNull('credit')->get();
            $lims_products_list = Account::where('type', 'Expense')->orderBy('name', 'ASC')->get();
            $expense_vouchers = DB::table('expense_vouchers')
                ->orderBy('id', 'ASC')
                ->get();
            $balance = 0;
            return view('voucher.expense_receiving_voucher', compact('lims_account_list','receive_voucher_payment_list', 'expense_vouchers', 'lims_products_list', 'payment','balance'));
        
    }
    public function expense_payment_voucher(){
        
        $lims_account_list = \App\Account::where('is_active', true)->where('customer_id', NULL)->get();
        $lims_user_list = \App\User::where('is_active', true)->get();
        //$payment = Payment::orderBy('created_at', 'desc')->get();
            
            $payment = Payment::all();

            
            $receive_voucher_payment_list = Payment::all();
            
            $lims_products_list = Account::where('type', 'Expense')->orderBy('name', 'ASC')->get();
            $expense_vouchers = DB::table('expense_vouchers')
                ->orderBy('id', 'ASC')
                ->get();
            $balance = 0;
            return view('voucher.expense_payment_voucher', compact('lims_account_list','receive_voucher_payment_list', 'expense_vouchers', 'lims_products_list', 'lims_user_list','payment','balance'));
        
    }
    
    public function bank_receiving_voucher(){
        $lims_account_list = \App\Account::where('is_active', true)->where('customer_id', NULL)->get();
            $lims_user_list = \App\User::where('is_active', true)->get();
            //$payment = Payment::orderBy('created_at', 'desc')->get();
            
            $payment = Payment::all();

            //$debit_list = Payment::whereNotNull('debit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            $receive_voucher_payment_list = Payment::all();
            //$credit_list = Payment::whereNotNull('credit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            //$credit_list = Payment::whereNotNull('credit')->get();
            $lims_products_list = Account::where('type', 'Bank')->orderBy('name', 'ASC')->get();
            $balance = 0;
            return view('voucher.bank_receive_voucher', compact('lims_account_list','receive_voucher_payment_list', 'lims_products_list', 'lims_user_list','payment','balance'));
        
    }
    public function bank_payment_voucher(){
        $lims_account_list = \App\Account::where('is_active', true)->where('customer_id', NULL)->get();
            $lims_user_list = \App\User::where('is_active', true)->get();
            //$payment = Payment::orderBy('created_at', 'desc')->get();
            
            $payment = Payment::all();

            //$debit_list = Payment::whereNotNull('debit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            $receive_voucher_payment_list = Payment::all();
            //$credit_list = Payment::whereNotNull('credit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            //$credit_list = Payment::whereNotNull('credit')->get();
            $lims_products_list = Account::where('type', 'Bank')->orderBy('name', 'ASC')->get();
            $balance = 0;
            return view('voucher.bank_payment_voucher', compact('lims_account_list','receive_voucher_payment_list', 'lims_products_list', 'lims_user_list','payment','balance'));
        
    }
    
    public function index()
    {
        //echo "Testing";
        //die();
        
            $lims_account_list = \App\Account::where('is_active', true)->where('supplier_id', NULL)->get();
            $lims_user_list = \App\User::where('is_active', true)->get();
            //$payment = Payment::orderBy('created_at', 'desc')->get();
            
            $payment = Payment::all();

            //$debit_list = Payment::whereNotNull('debit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            $receive_voucher_payment_list = Payment::all();
            //$credit_list = Payment::whereNotNull('credit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            //$credit_list = Payment::whereNotNull('credit')->get();
            $balance = 0;
            $lims_products_list = Customer::where('is_active', true)->orderBy('name', 'ASC')->get();
            $list_payment = DB::table('payments')->orderBy('created_at', 'desc')->first();
            
            //$product_id =  $request->input('product_id');
            
            $today = date('23-03-2021');
            //$sale_date = $request->input('sale_date');
            $newDate = date("d-m-Y", strtotime($today));
            
            //$today = $newDate;
            
            $receive_vouchers = DB::table('receive_vouchers')
                ->orderBy('id', 'ASC')
                ->get();
            
            //$testing = DB::table('receive_vouchers')->orderBy('id', 'ASC')->first();
            
            return view('voucher.receive', compact('lims_account_list','receive_voucher_payment_list', 'receive_vouchers', 'lims_products_list', 'list_payment',  'lims_user_list','payment','balance'));
    }
    
    
        public function salesman()
            {
                //
                $lims_account_list = \App\Account::where('is_active', true)->where('supplier_id', NULL)->get();
                $lims_user_list = \App\User::where('is_active', true)->get();
                //$payment = Payment::orderBy('created_at', 'desc')->get();
                
                $payment = Payment::where('user_id', Auth::id())->get();
                
                $latest_payment = Payment::latest()->first();
                
                //$debit_list = Payment::whereNotNull('debit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
                $receive_voucher_payment_list = Payment::where('user_id', Auth::id())->get();
                //$credit_list = Payment::whereNotNull('credit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
                //$credit_list = Payment::whereNotNull('credit')->get();
                $balance = 0;
                
                $list_payment = DB::table('payments')->orderBy('created_at', 'desc')->where('user_id', Auth::id())->get();
                return view('voucher.salesman-receive', compact('lims_account_list','receive_voucher_payment_list', 'latest_payment', 'list_payment',  'lims_user_list','payment','balance'));
                
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
        //dd($request);
        //die();

       $user = User::find(Auth::id());

       $data = $request->all();

       //dd($data);
       //die();

       $payment_data['user_id'] = $data['user_id'];
       
       $payment_data['change'] = 0;

        $debit = DB::table('payments')
                    ->where('account_id', '=', $data['account_id'])
                    ->where('user_id', '=', Auth::id())
                    ->sum('debit');

        $credit = DB::table('payments')
                    ->where('account_id', '=', $data['account_id'])
                    ->where('user_id', '=', Auth::id())
                    ->sum('credit');
        
        $total_debit = DB::table('payments')
                    ->where('account_id', '=', $data['account_id'])
                    ->sum('debit');

        $total_credit = DB::table('payments')
                    ->where('account_id', '=', $data['account_id'])
                    ->sum('credit');
                    
                    

       if($data['type'] == "d"){
        $payment_data['type'] = "d";
        $payment_data['debit'] = $data['amount'];
        $payment_data['amount'] = $data['amount'];
        $payment_data['years'] = 2021;
        $payment_data['total_balance'] = $total_debit - $total_credit + $data['amount'];
        $payment_data['balance'] = $debit - $credit + $data['amount'];
        
       }

       if($data['type'] == "c"){
        $payment_data['type'] = "c";
        $payment_data['credit'] = $data['amount'];
        $payment_data['amount'] = $data['amount'];
        $payment_data['years'] = 2021;
        $payment_data['date'] = $data['date'];
        $payment_data['date_2'] = $data['date'];
        
        $payment_data['total_balance'] = $total_debit - $total_credit - $data['amount'];
        
        $payment_data['balance'] = $debit - $credit - $data['amount'];
        
        
       }
       
       $payment_data['paying_method'] = 'Cash';
       $payment_data['account_id'] = $data['account_id'];
       $payment_data['payment_reference'] = $user->name. '-' . date("d-m-Y - H:i:a");
        
       $payment = Payment::create($payment_data);
       return redirect()->back();
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
        //
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

    public function purchase_store(){

          //
        //dd($request);
        //die();

       $user = User::find(Auth::id());

       $data = $request->all();

       //dd($data);
       //die();


       $payment_data['user_id'] = $data['user_id'];
       $payment_data['change'] = 0;
       if($data['type'] == "d"){
        $payment_data['type'] = "d";
        $payment_data['years'] = 2021;
        $payment_data['debit'] = $data['amount'];
       }
       if($data['type'] == "c"){
        $payment_data['type'] = "c";
        $payment_data['years'] = 2021;
        $payment_data['credit'] = $data['amount'];
       }
       $payment_data['paying_method'] = 'Cash';
       $payment_data['account_id'] = $data['account_id'];
       $payment_data['payment_reference'] = $user->name. '-' . date("d-m-Y - H:i:a");
       $payment = Payment::create($payment_data);
       return redirect()->back();

    }

    public function purchase()
    {
            
            $lims_account_list = \App\Account::where('is_active', true)->where('customer_id', NULL)->get();
            $lims_user_list = \App\User::where('is_active', true)->get();
            //$payment = Payment::orderBy('created_at', 'desc')->get();
            
            $payment = Payment::all();

            //$debit_list = Payment::whereNotNull('debit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            $receive_voucher_payment_list = Payment::all();
            //$credit_list = Payment::whereNotNull('credit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            //$credit_list = Payment::whereNotNull('credit')->get();
            $lims_products_list = Supplier::where('is_active', true)->orderBy('name', 'ASC')->get();
             $payment_vouchers = DB::table('payment_vouchers')
                ->orderBy('id', 'ASC')
                ->get();
            $balance = 0;
            return view('voucher.purchase', compact('lims_account_list','receive_voucher_payment_list', 'payment_vouchers', 'lims_products_list', 'lims_user_list','payment','balance'));
    
    }
    
    
    
    
    
    public function general_journal_edit(Request $request){
        
        //dd($request);
        //die();
        $user = User::find(Auth::id());
        
        DB::table('receive_vouchers')
        ->where('id', $request['id'])
        ->update([
            'receive_voucher_date' => $request['date'],
            'customer_id' => $request['customer_id'],
            'amount' => $request['amount'],
            'note' => $request['note'],
            'action' => $request['action']
             ]);
             
        if($request['action'] == 'credit'){
            
            DB::table('payments')
                ->where('receive_voucher_id', $request['id'])
                ->update([
                    'sale_id' => 'Receiving',
                    'payment_reference' => $user->name. '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'type' => 'c',
                    'receive_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'credit' => $request['amount'],
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        if($request['action'] == 'debit'){
            
           DB::table('payments')
                ->where('receive_voucher_id', $request['id'])
                ->update([
                    'sale_id' => 'Receiving',
                    'payment_reference' => $user->name. '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'type' => 'd',
                    'receive_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'debit' => $request['amount'],
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        $date = $request['date'];
        $message = "Receive Voucher has been updated successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);
        
        
    }
    
    public function general_journal_edit_two($date){
     
     
        //$data = $request->all();
        //$date = $data['sale_date'];
        
        $payment_vouchers = DB::table('payment_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'credit')
                ->orderBy('id', 'ASC')
                ->sum('amount');
        
        $payment_vouchers_debit = DB::table('payment_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'debit')
                ->orderBy('id', 'ASC')
                ->sum('amount');
                
        
        $receive_vouchers = DB::table('receive_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'credit')
                ->orderBy('id', 'asc')
                ->sum('amount');
                
        $receive_vouchers_debit = DB::table('receive_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'debit')
                ->orderBy('id', 'asc')
                ->sum('amount');
                
                
        $expense_vouchers = DB::table('expense_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'credit')
                ->orderBy('id', 'ASC')
                ->sum('amount');
        
        $expense_vouchers_debit = DB::table('expense_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'debit')
                ->orderBy('id', 'ASC')
                ->sum('amount');
                
        $bank_vouchers = DB::table('bank_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'credit')
                ->orderBy('id', 'ASC')
                ->sum('amount');
                
        $bank_vouchers_debit = DB::table('bank_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'debit')
                ->orderBy('id', 'ASC')
                ->sum('amount');
                
        
        $payment_vouchers_list = DB::table('payment_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->orderBy('id', 'ASC')
                ->get();
                
        
        //$receive_vouchers_list = [];
        $receive_vouchers_list = DB::table('receive_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->orderBy('id', 'ASC')
                ->get();
        //if($receive_vouchers_list[0]->customer_id = "25371"){
          //  echo $receive_vouchers_list[0]->customer_id;
            //$customer_name = DB::table('customers')->where('id', $receive_vouchers_list[0]->customer_id)->get(); 
            //echo $customer_name[0]->name.' ('.$customer_name[0]->city.') ';
        //}
        //die();
        $expense_vouchers_list = DB::table('expense_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->orderBy('id', 'ASC')
                ->get();
                
        $bank_vouchers_list = DB::table('bank_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->orderBy('id', 'ASC')
                ->get();
        
        $cash_in_hand = DB::table('cash_registers')
                ->where('created_at', '=', $date)
                ->orderBy('created_at', 'desc')
                ->get();
        
        if($cash_in_hand->isEmpty()){
           $cashInHand = 0;
        }else{
            $cashInHand = $cash_in_hand[0]->cash_in_hand;
        }
            $newDate = '';
        return view('voucher.general_journal_two', compact('cashInHand', 'newDate', 'payment_vouchers', 'receive_vouchers', 'expense_vouchers', 'bank_vouchers', 'payment_vouchers_debit', 'receive_vouchers_debit', 'expense_vouchers_debit', 'bank_vouchers_debit', 'payment_vouchers_list', 'receive_vouchers_list', 'bank_vouchers_list', 'date', 'expense_vouchers_list'));
        
        
    }
    
    public function general_journal_edit_bank_voucher(Request $request){
        
         //dd($request);
        //die();
        $user = User::find(Auth::id());
        
        DB::table('bank_vouchers')
        ->where('id', $request['id'])
        ->update([
            'receive_voucher_date' => $request['date'],
            'bank_id' => $request['customer_id'],
            'amount' => $request['amount'],
            'note' => $request['note'],
            'action' => $request['action']
             ]);
             
        if($request['action'] == 'credit'){
            
            DB::table('payments')
                ->where('bank_voucher_id', $request['id'])
                ->update([
                    'payment_reference' => $user->name. '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'bank_id' => $request['customer_id'],
                    'type' => 'c',
                    'bank_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'credit' => $request['amount'],
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        if($request['action'] == 'debit'){
            
           DB::table('payments')
                ->where('bank_voucher_id', $request['id'])
                ->update([
                    'payment_reference' => $user->name. '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'bank_id' => $request['customer_id'],
                    'type' => 'd',
                    'bank_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'debit' => $request['amount'],
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        
        
        $date = $request['date'];
        $message = "Bank Voucher has been updated successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);



        //return redirect('general-journal')->with('success', 'Record has been Updated Successfully!');
        
    }
    
    public function general_journal_edit_expense_voucher(Request $request){
          //dd($request);
        //die();
        $user = User::find(Auth::id());
        
        DB::table('expense_vouchers')
        ->where('id', $request['id'])
        ->update([
            'receive_voucher_date' => date("d-m-Y", strtotime($request['date'])),
            'expense_id' => $request['customer_id'],
            'amount' => $request['amount'],
            'note' => $request['note'],
            'action' => $request['action']
             ]);
             
        if($request['action'] == 'credit'){
            
            DB::table('payments')
                ->where('expense_voucher_id', $request['id'])
                ->update([
                    'payment_reference' => $user->name. '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'expense_id' => $request['customer_id'],
                    'type' => 'c',
                    'expense_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'credit' => $request['amount'],
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        if($request['action'] == 'debit'){
            
           DB::table('payments')
                ->where('expense_voucher_id', $request['id'])
                ->update([
                    'payment_reference' => $user->name. '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'expense_id' => $request['customer_id'],
                    'type' => 'd',
                    'expense_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'debit' => $request['amount'],
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        
        
        
        $date = $request['date'];
        $message = "Expense Voucher has been updated successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);


        //return redirect('general-journal')->with('success', 'Record has been Updated Successfully!');
    }
    
    public function general_journal_edit_payment_voucher(Request $request){
        
        //dd($request['id']);
        //die();
        
        $user = User::find(Auth::id());
        
        DB::table('payment_vouchers')
        ->where('id', $request['id'])
        ->update([
            'receive_voucher_date' => date("d-m-Y", strtotime($request['date'])),
            'supplier_id' => $request['customer_id'],
            'amount' => $request['amount'],
            'note' => $request['note'],
            'action' => $request['action']
             ]);
             
        if($request['action'] == 'credit'){
            
            DB::table('payments')
                ->where('payment_voucher_id', $request['id'])
                ->update([
                    'payment_reference' => $user->name. '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'purchase_id' => $request['customer_id'],
                    'type' => 'c',
                    'payment_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),$request['date'],
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'credit' => $request['amount'],
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        if($request['action'] == 'debit'){
            
           DB::table('payments')
                ->where('receive_voucher_id', $request['id'])
                ->update([
                    'payment_reference' => $user->name. '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'purchase_id' => $request['customer_id'],
                    'type' => 'd',
                    'payment_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'debit' => $request['amount'],
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        
        $date = $request['date'];
        $message = "Payment Voucher has been updated successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);
    }
    
    public function receive_voucher_delete($id){
        
        $receive_vouchers = DB::table('receive_vouchers')->where('id', $id)->get();
        $payment = DB::table('payments')->where('receive_voucher_id', $receive_vouchers[0]->id)->delete();
        DB::table('receive_vouchers')->where('id', '=', $id)->delete();

        $date = date("Y-m-d", strtotime($receive_vouchers[0]->receive_voucher_date));
        $message = "Receive Voucher has been Deleted successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);

        //return redirect('general-journal')->with('success', 'Record has been deleted Successfully!');
    }
    
     public function payment_voucher_delete($id){
        
        $receive_vouchers = DB::table('payment_vouchers')->where('id', $id)->get();
        $payment = DB::table('payments')->where('payment_voucher_id', $receive_vouchers[0]->id)->delete();
        DB::table('payment_vouchers')->where('id', '=', $id)->delete();
        
        $date = date("Y-m-d", strtotime($receive_vouchers[0]->receive_voucher_date));
        $message = "Receive Voucher has been Deleted successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);
        
        //return redirect('general-journal')->with('success', 'Record has been deleted Successfully!');
        
    }
    
    public function expense_voucher_delete($id){
        
        $receive_vouchers = DB::table('expense_vouchers')->where('id', $id)->get();
        $payment = DB::table('payments')->where('expense_voucher_id', $receive_vouchers[0]->id)->delete();
        DB::table('expense_vouchers')->where('id', '=', $id)->delete();
        
        $date = date("Y-m-d", strtotime($receive_vouchers[0]->receive_voucher_date));
        $message = "Expense Voucher has been Deleted successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);
        
        //return redirect('general-journal')->with('success', 'Record has been deleted Successfully!');

    }
    
    public function bank_voucher_delete($id){
        
        $receive_vouchers = DB::table('bank_vouchers')->where('id', $id)->get();
        $payment = DB::table('payments')->where('bank_voucher_id', $receive_vouchers[0]->id)->delete();
        DB::table('bank_vouchers')->where('id', '=', $id)->delete();
        
        $date = date("Y-m-d", strtotime($receive_vouchers[0]->receive_voucher_date));
        $message = "Bank Voucher has been Deleted successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);
        
        //return redirect('general-journal')->with('success', 'Record has been deleted Successfully!');
        
    }
    
    public function general_journal23_two($date){
        
        $receive_vouchers_debit = "";
        $receive_vouchers = DB::table('receive_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'credit')
                ->orderBy('id', 'asc')
                ->sum('amount');
        $payment_vouchers_debit = "";
        $payment_vouchers = "";
        $expense_vouchers_debit = "";
        $expense_vouchers = "";
        $bank_vouchers_debit = "";
        $bank_vouchers = "";
        $receive_vouchers_list = [];
        $payment_vouchers_list = [];
        $expense_vouchers_list = [];
        $bank_vouchers_list = [];
        $cash_in_hand = DB::table('cash_registers')
                ->where('created_at', '=', $date)
                ->orderBy('created_at', 'desc')
                ->get();
        
           if($cash_in_hand->isEmpty()){
           $cashInHand = 0;
            }else{
                $cashInHand = $cash_in_hand[0]->cash_in_hand;
            }
            $newDate = '';
        return view('voucher.general_journal_two', compact('cashInHand', 'newDate', 'payment_vouchers', 'receive_vouchers', 'expense_vouchers', 'bank_vouchers', 'payment_vouchers_debit', 'receive_vouchers_debit', 'expense_vouchers_debit', 'bank_vouchers_debit', 'payment_vouchers_list', 'receive_vouchers_list', 'bank_vouchers_list', 'date', 'expense_vouchers_list'));
    }
    
    public function general_journal23(Request $request){
        $data = $request->all();
        $date = $data['sale_date'];
        
        $payment_vouchers = DB::table('payment_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'credit')
                ->orderBy('id', 'ASC')
                ->sum('amount');
        
        $payment_vouchers_debit = DB::table('payment_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'debit')
                ->orderBy('id', 'ASC')
                ->sum('amount');
                
        
        $receive_vouchers = DB::table('receive_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'credit')
                ->orderBy('id', 'asc')
                ->sum('amount');
                
        $receive_vouchers_debit = DB::table('receive_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'debit')
                ->orderBy('id', 'asc')
                ->sum('amount');
                
                
        $expense_vouchers = DB::table('expense_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'credit')
                ->orderBy('id', 'ASC')
                ->sum('amount');
        
        $expense_vouchers_debit = DB::table('expense_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'debit')
                ->orderBy('id', 'ASC')
                ->sum('amount');
                
        $bank_vouchers = DB::table('bank_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'credit')
                ->orderBy('id', 'ASC')
                ->sum('amount');
                
        $bank_vouchers_debit = DB::table('bank_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->where('action', '=', 'debit')
                ->orderBy('id', 'ASC')
                ->sum('amount');
                
        
        $payment_vouchers_list = DB::table('payment_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->orderBy('id', 'ASC')
                ->get();
                
        
        //$receive_vouchers_list = [];
        $receive_vouchers_list = DB::table('receive_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->orderBy('id', 'ASC')
                ->get();
        //if($receive_vouchers_list[0]->customer_id = "25371"){
          //  echo $receive_vouchers_list[0]->customer_id;
            //$customer_name = DB::table('customers')->where('id', $receive_vouchers_list[0]->customer_id)->get(); 
            //echo $customer_name[0]->name.' ('.$customer_name[0]->city.') ';
        //}
        //die();
        $expense_vouchers_list = DB::table('expense_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->orderBy('id', 'ASC')
                ->get();
                
        $bank_vouchers_list = DB::table('bank_vouchers')
                ->where('receive_voucher_date', '=', $date)
                ->orderBy('id', 'ASC')
                ->get();
        
        $cash_in_hand = DB::table('cash_registers')
                ->where('created_at', '=', $date)
                ->orderBy('created_at', 'desc')
                ->get();
        
        if($cash_in_hand->isEmpty()){
           $cashInHand = 0;
        }else{
            $cashInHand = $cash_in_hand[0]->cash_in_hand;
        }
            $newDate = '';
        return view('voucher.general_journal_two', compact('cashInHand', 'newDate', 'payment_vouchers', 'receive_vouchers', 'expense_vouchers', 'bank_vouchers', 'payment_vouchers_debit', 'receive_vouchers_debit', 'expense_vouchers_debit', 'bank_vouchers_debit', 'payment_vouchers_list', 'receive_vouchers_list', 'bank_vouchers_list', 'date', 'expense_vouchers_list'));
    }
    public function general_journal(){
            
        //$payment_vouchers = DB::table('payment_vouchers')
          //      ->where('receive_voucher_date', '=', '2021-03-22 00:00:00')
            //    ->orderBy('id', 'ASC')
              //  ->sum('amount');
        //dd($payment_vouchers);
        //die();
        //$receive_vouchers = DB::table('receive_vouchers')
          //      ->orderBy('id', 'ASC')
            //    ->get();
        //$expense_vouchers = DB::table('expense_vouchers')
           ///     ->orderBy('id', 'ASC')
              //  ->get();
            $balance = 0;
            
            //$date = date('Y-m-d H:i:s');
            //$newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y ');
            //dd($newDate);

        $date = '';
        $cash_in_hand = '';
        $payment_vouchers = '';
        $receive_vouchers = '';
        $expense_vouchers = '';         
        $bank_vouchers = '';         
        $payment_vouchers_debit = '';
        $receive_vouchers_debit = '';
        $expense_vouchers_debit = '';         
        $bank_vouchers_debit = '';         
        $payment_vouchers_list = [];
        $receive_vouchers_list = [];
        $expense_vouchers_list = [];
        $bank_vouchers_list = [];
            return view('voucher.general_journal', compact('cash_in_hand', 'payment_vouchers', 'bank_vouchers_list', 'payment_vouchers_list', 'date', 'receive_vouchers', 'receive_vouchers_debit', 'payment_vouchers_debit',  'bank_vouchers', 'expense_vouchers_debit',  'bank_vouchers_debit', 'expense_vouchers', 'receive_vouchers_list', 'expense_vouchers_list'));
    }
     public function expense_voucher()
    {
            $lims_account_list = \App\Account::where('is_active', true)->where('customer_id', NULL)->get();
            $lims_user_list = \App\User::where('is_active', true)->get();
            //$payment = Payment::orderBy('created_at', 'desc')->get();
            
            $payment = Payment::all();

            //$debit_list = Payment::whereNotNull('debit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            $receive_voucher_payment_list = Payment::all();
            //$credit_list = Payment::whereNotNull('credit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            //$credit_list = Payment::whereNotNull('credit')->get();
            $lims_products_list = Account::where('type', 'Expense')->orderBy('name', 'ASC')->get();
            $expense_vouchers = DB::table('expense_vouchers')
                ->orderBy('id', 'ASC')
                ->get();
            $balance = 0;
            return view('voucher.expense_voucher', compact('lims_account_list','receive_voucher_payment_list', 'expense_vouchers', 'lims_products_list', 'lims_user_list','payment','balance'));
    
    }
     public function bank_voucher()
    {
            $lims_account_list = \App\Account::where('is_active', true)->where('customer_id', NULL)->get();
            $lims_user_list = \App\User::where('is_active', true)->get();
            //$payment = Payment::orderBy('created_at', 'desc')->get();
            
            $payment = Payment::all();

            //$debit_list = Payment::whereNotNull('debit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            $receive_voucher_payment_list = Payment::all();
            //$credit_list = Payment::whereNotNull('credit')->where('account_id', $data['account_id'])->whereDate('created_at', '>=' , $data['start_date'])->whereDate('created_at', '<=' , $data['end_date'])->get();
            //$credit_list = Payment::whereNotNull('credit')->get();
            $lims_products_list = Account::where('type', 'Bank')->orderBy('name', 'ASC')->get();
            $balance = 0;
            return view('voucher.bank_voucher', compact('lims_account_list','receive_voucher_payment_list', 'lims_products_list', 'lims_user_list','payment','balance'));
    
    }
    
    public function recieve_vouchers(){
        
        $receive_vouchers = DB::table('payment_vouchers')->where('receive_voucher_date', '=', '2021-10-06 00:00:00')->where('status', '=', 0)->get();
        return view('voucher.rv', compact('receive_vouchers'));
    }
    
    public function vouchereditForLedger($id){
        
        $rv = DB::table('payment_vouchers')->where('id', '=', $id)->where('status', '=', 0)->get();
        $receive_voucher_id = $rv[0]->id;
        $receive_voucher_date = $rv[0]->receive_voucher_date;
        $customer_id = $rv[0]->supplier_id;
        $amount = $rv[0]->amount;
        $action = $rv[0]->action;
        
        
        if($action == 'credit'){

            $user = User::find(Auth::id());
            //echo $user->name;
              //die();     
                    DB::table('payments')->insert([
                            'credit' => $amount,
                            'payment_reference' => $user->name. '-' . date("d-m-Y - H:i:a"),
                            'account_id' => $customer_id,
                            'change' => 0,
                            'payment_voucher_id' => $receive_voucher_id,
                            'amount' => $amount,
                            'purchase_id' => 'Payments',
                            'date' => date('Y-m-d', strtotime($receive_voucher_date)),
                            'date_2' => date('Y-m-d', strtotime($receive_voucher_date)),
                            'type' => "c",
                            'paying_method' => 0,
                            'payment_note' => '',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                        
            $receive_vouchers_updated = DB::table('payment_vouchers')->where('id', $id)->update(['status' => 1]);
            return redirect()->back()->with('message', 'Record has been Updated Successfully! ');
            
        }elseif($action == 'debit'){
            
            $user = User::find(Auth::id());
            //dd($user);
              //die();     
              
                    DB::table('payments')->insert([
                            'debit' => $amount,
                            'payment_reference' => $user->name. '-' . date("d-m-Y - H:i:a"),
                            'account_id' => $customer_id,
                            'change' => 0,
                            'payment_voucher_id' => $receive_voucher_id,
                            'amount' => $amount,
                            'purchase_id' => 'Payments',
                            'date' => date('Y-m-d', strtotime($receive_voucher_date)),
                            'date_2' => date('Y-m-d', strtotime($receive_voucher_date)),
                            'type' => "d",
                            'paying_method' => 0,
                            'payment_note' => '',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
            $receive_vouchers_updated = DB::table('payment_vouchers')->where('id', $id)->update(['status' => 1]);
            return redirect()->back()->with('message', 'Record has been Updated Successfully! ');
        }  
                 
        
    }
    
    
}
