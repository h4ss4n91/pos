@extends('layout.main')
@section('content')

@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div> 
@endif

      <!-- Counts Section -->
      <section class="dashboard-counts">
        <div class="container-fluid">
          <div class="row">
            
             <div class="container-fluid">
          <div class="row">
                <div class="col-md-3">
                      <div style="overflow-y: scroll;  height:600px;" class="card">
                            <div style="background-color:purple;" class="card-header d-flex align-items-center">
                              <h4 style="color:#fff;">Customer Ledger</h4>
                            </div>
                            <div class="card-body">
                              <table class="table" style="font-size:18px; width:100%">
                              	<thead class="thead-light">
                                	<tr>
                                      <th> Acc # </th>
                                      <th> Customer </th>
                                      <th> Balance </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                	$customers = DB::table('customers')->get();
                                @endphp
                                	@foreach($customers as $row_customers)
                                    <tr>
                                        <td> {{$row_customers->id}} </td>
                                        <td>
                                        <a style="font-weight:bold; text-decoration:underline" href="{{url('home_customer_ledger',$row_customers->id)}}">
                                        {{$row_customers->name}}
                                        </a>
                                         </td>
                                        <td> 
                                            @php
                                            $account_id = DB::table('accounts')->where('accountTypeID','=',$row_customers->id)->value('id');
                                                        $credit_customer = DB::table('payments')
                                                            ->where('account_id', '=', $account_id)
                                                            ->where('sale_id', '!=', NULL)
                                                            ->where('type', '=', 'c')
                                                            ->sum('credit');

                                                        $debit_customer = DB::table('payments')
                                                            ->where('account_id', '=', $account_id)
                                                            ->where('sale_id', '!=', NULL)
                                                            ->where('type', '=', 'd')
                                                            ->sum('debit');
                                            @endphp
                                            {{$debit_customer - $credit_customer}}
                                    
                                        </td>
                                    </tr>

                                    @endforeach
                                
                                </tbody>
                                
                              </table>
                            </div>
                      </div>
                </div>
                
                <div class="col-md-3">
                      <div style="overflow-y: scroll;  height:600px;" class="card">
                            <div style="background-color:green;" class="card-header d-flex align-items-center">
                              <h4 style="color:#fff;">Product Ledger</h4>
                            </div>
                            <div class="card-body">
                            						
                              <table class="table" style="font-size:18px; width:100%">
                              	<thead class="thead-light">
                                	<tr>
                                      <th> Product </th>
                                      <th> Purchase </th>
                                      <th> Sale </th>
                                      <th> Stock </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                	$products = DB::table('products')->get();
                                @endphp
                                	@foreach($products as $row_products)
                                    
                                    <tr>
                                        <td>
                                        <a style="font-weight:bold; text-decoration:underline" href="{{url('home_product_ledger',$row_products->id)}}">
                                        {{$row_products->name}}
                                        </a>
                                            
                                         </td>
                                         <td> 
                                         		@php
                                                	$purchase = DB::table('product_purchases')->where('product_id', '=', $row_products->id)->sum('qty');
                                                    echo $purchase;
                                                @endphp
                                         </td>
                                         <td> 
                                         		@php
                                                	$sale = DB::table('product_sales')->where('product_id', '=', $row_products->id)->sum('qty');
                                                    echo $sale;
                                                @endphp
                                         </td>
                                        <td> 
                                        @php
                                            $stock = DB::table('product_warehouse')->where('product_id','=', $row_products->id)->where('warehouse_id','=', 1)->get();
                                        @endphp
                                        @if(!$stock->isEmpty())
                                        {{$stock[0]->qty}}
                                        @endif
                                        </td>
                                    </tr>

                                    @endforeach
                                
                                </tbody>
                               
                              </table>
                            </div>
                      </div>
                </div>
                
                <div class="col-md-6">
                       <!-- The Modal -->
                                            <div class="card">
                                              <div class="">
                                                <div class="card-content">

                                                  <!-- Modal Header -->
                                                  <div class="card-header">
                                                    <div style="background-color:purple;" class="card-header">
                                                    <h4 style="color:#fff;" class="card-title">
                                                    @php
                                                        $customer_name = DB::table('customers')->where('id','=',last(request()->segments()))->first();
                                                    @endphp
                                                    
                                                    Customer Ledger of <span style="font-size:31px;"> {{$customer_name->name}} </span> </h4>
                                                  </div>

                                                  <!-- Modal body -->
                                                  <div class="modal-body">
                                                    @php
                                                    $accounts = DB::table('accounts')->where('accountTypeID','=',last(request()->segments()))->get();
                                                    $payments = DB::table('payments')->where('account_id','=',$accounts[0]->id)->get();
                                                    @endphp
                                                    <table style="border:1px solid #000;" id="" class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>{{trans('file.date')}}</th>
                                                                <th>Age</th>
                                                                <th>Description</th>
                                                                <th>Category</th>
                                                                <th>{{trans('file.Debit')}}</th>
                                                                <th>{{trans('file.Credit')}}</th>
                                                                <th>{{trans('file.Balance')}}</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $debitBalance = 0;
                                                                $creditBalance = 0;
                                                                $balance = 0;
                                                            @endphp
                                                            
                                                            @foreach($debit_list_two as $key=>$debit)

                                                            <tr @if($debit->status == 1) style="background:#00FF00" @endif>
                                                                <td>{{date($general_setting->date_format, strtotime($debit->date))}}</td>
                                                                <td>
                                                                    @if($debit->bill_no != NULL)
                                                                            @php
                                                                                $first_date = new DateTime(date($general_setting->date_format, strtotime($debit->date)));
                                                                                $second_date = new DateTime(date('d-m-Y'));
                                                                                $interval = $first_date->diff($second_date);
                                                                                echo $interval->format('%a');
                                                                            @endphp
                                                                    @endif 
                                                                </td>
                                                                <td>
                                                                <table style="width:100%;">
                                                                <tr style="background-color:#000;">
                                                                     	<td style="color:#fff;">Name</td>
                                                                        <td style="color:#fff;">Qty</td>
                                                                        <td style="color:#fff;">Rate</td>
                                                                     </tr>
                                                                </table>
                                                                    {{$debit->payment_note}}
                                                                    @if($debit->status == 1) 
                                                                        Manually : Rs. {{$debit->manual_amount}}, Note: {{$debit->updated_note}}
                                                                     @endif
                                                                     @if($debit->sale_id == 'Receiving') 
                                                                        Receiving
                                                                     @else
                                                                     <table style="width:100%;">
                                                                     
                                                                            @php
                                                                            	$product_sales = DB::table('product_sales')->where('sale_id','=',$debit->sale_id)->get();
                                                                            @endphp
                                                                            @foreach($product_sales as $row_product_sales)
                                                                            	<tr>
                                                                            	<td>
                                                                                @php
                                                                                    $product_name = DB::table('products')->where('id','=',$row_product_sales->product_id)->first();
                                                                                @endphp
                                                                                {{$product_name->name}}</td>
                                                                                <td>{{$row_product_sales->qty}}</td>
                                                                                <td>{{$row_product_sales->net_unit_price}}</td>
                                                                            </tr>
                                                                            @endforeach
                                                                            
                                                                        </table>
                                                                     @endif
                                                                     @if($debit->sale_id != 'Opening Balance')
                                                                    	
                                                                    @else
                                                                    	Opening Balance
                                                                    @endif
                                                                    
                                                                </td>
                                                                <td>
                                                                    @if($debit->sale_id == 'Opening Balance')
                                                                    {{$debit->sale_id}}
                                                                    @endif

                                                                    @if($debit->bill_no != 'Opening Balance')
                                                                         @if($debit->bill_no != NULL)
                                                                            @php 
                                                                                $sales = DB::table('sales')->where('bill_no', '=', $debit->bill_no)->get(); 
                                                                            @endphp
                                                                                @if(!$sales->isEmpty() )
                                                                                     <a style="font-weight:bold; color:blue; text-decoration:underline" target="_blank" href="{{ url('sales/'.$debit->bill_no.'/viewinvoice')}}"> Bill # {{$debit->bill_no}} </a>
                                                                                @endif
                                                                            @endif
                                                                        @endif 
                                                                </td>

                                                                @if($debit->debit != NULL)
                                                                @php 
                                                                    $balance = $debit->debit + $balance; 
                                                                    $debitBalance += $debit->debit;
                                                                @endphp

 																
                                                                <td>{{number_format((float)$debit->debit, 2, '.', '')}}</td>
                                                                <td>0.00</td>
                                                                <td>{{number_format((float)$balance, 2, '.', '')}}</td>
                                                                @elseif($debit->credit != NULL)
                                                                @php
                                                                    $balance = $balance - $debit->credit;
                                                                    $creditBalance += $debit->credit;
                                                                @endphp

                                                                <td>0.00</td>
                                                                <td>{{number_format((float)$debit->credit, 2, '.', '')}}</td>
                                                                <td>{{number_format((float)$balance, 2, '.', '')}}</td>
                                                                @else
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                @endif
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><strong>{{$debitBalance}}.00 </strong></td>
                                                                <td><strong>{{$creditBalance}}.00 </strong></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </tfoot>
                                                        {{$debit_list_two->links()}}
                                                    </table>
                                                  </div>

                                                  <!-- Modal footer -->
                                                  <div class="modal-footer">
                                                    
                                                  </div>

                                                </div>
                                              </div>
                                            </div>
            </div>
          </div>
            
          </div>
        </div>
        
        
       
          
          
        
      </section>
      
<script type="text/javascript">
$(document).ready(function() {
    $('#table').DataTable();
} );
    // Show and hide color-switcher
    $(".color-switcher .switcher-button").on('click', function() {
        $(".color-switcher").toggleClass("show-color-switcher", "hide-color-switcher", 300);
    });

    // Color Skins
    $('a.color').on('click', function() {
        /*var title = $(this).attr('title');
        $('#style-colors').attr('href', 'css/skin-' + title + '.css');
        return false;*/
        $.get('setting/general_setting/change-theme/' + $(this).data('color'), function(data) {
        });
        var style_link= $('#custom-style').attr('href').replace(/([^-]*)$/, $(this).data('color') );
        $('#custom-style').attr('href', style_link);
    });

    $(".date-btn").on("click", function() {
        $(".date-btn").removeClass("active");
        $(this).addClass("active");
        var start_date = $(this).data('start_date');
        var end_date = $(this).data('end_date');
        $.get('dashboard-filter/' + start_date + '/' + end_date, function(data) {
            dashboardFilter(data);
        });
    });

    function dashboardFilter(data){
        $('.revenue-data').hide();
        $('.revenue-data').html(parseFloat(data[0]).toFixed(2));
        $('.revenue-data').show(500);

        $('.return-data').hide();
        $('.return-data').html(parseFloat(data[1]).toFixed(2));
        $('.return-data').show(500);

        $('.profit-data').hide();
        $('.profit-data').html(parseFloat(data[2]).toFixed(2));
        $('.profit-data').show(500);

        $('.purchase_return-data').hide();
        $('.purchase_return-data').html(parseFloat(data[3]).toFixed(2));
        $('.purchase_return-data').show(500);
    }
</script>
@endsection

