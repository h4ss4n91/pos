<?php $__env->startSection('content'); ?>

<?php if(session()->has('not_permitted')): ?>
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('not_permitted')); ?></div> 
<?php endif; ?>
<?php if(session()->has('message')): ?>
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('message')); ?></div> 
<?php endif; ?>

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
                                <?php
                                	$customers = DB::table('customers')->get();
                                ?>
                                	<?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row_customers): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td> <?php echo e($row_customers->id); ?> </td>
                                        <td>
                                        <a style="font-weight:bold; text-decoration:underline" href="<?php echo e(url('home_customer_ledger',$row_customers->id)); ?>">
                                        <?php echo e($row_customers->name); ?>

                                        </a>
                                         </td>
                                        <td> 
                                            <?php
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
                                            ?>
                                            <?php echo e($debit_customer - $credit_customer); ?>

                                    
                                        </td>
                                    </tr>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
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
                                <?php
                                	$products = DB::table('products')->get();
                                ?>
                                	<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row_products): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <tr>
                                        <td>
                                        <a style="font-weight:bold; text-decoration:underline" href="<?php echo e(url('home_product_ledger',$row_products->id)); ?>">
                                        <?php echo e($row_products->name); ?>

                                        </a>
                                            
                                         </td>
                                         <td> 
                                         		<?php
                                                	$purchase = DB::table('product_purchases')->where('product_id', '=', $row_products->id)->sum('qty');
                                                    echo $purchase;
                                                ?>
                                         </td>
                                         <td> 
                                         		<?php
                                                	$sale = DB::table('product_sales')->where('product_id', '=', $row_products->id)->sum('qty');
                                                    echo $sale;
                                                ?>
                                         </td>
                                        <td> 
                                        <?php
                                            $stock = DB::table('product_warehouse')->where('product_id','=', $row_products->id)->where('warehouse_id','=', 1)->get();
                                        ?>
                                        <?php if(!$stock->isEmpty()): ?>
                                        <?php echo e($stock[0]->qty); ?>

                                        <?php endif; ?>
                                        </td>
                                    </tr>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
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
                                                    <?php
                                                        $customer_name = DB::table('customers')->where('id','=',last(request()->segments()))->first();
                                                    ?>
                                                    
                                                    Customer Ledger of <span style="font-size:31px;"> <?php echo e($customer_name->name); ?> </span> </h4>
                                                  </div>

                                                  <!-- Modal body -->
                                                  <div class="modal-body">
                                                    <?php
                                                    $accounts = DB::table('accounts')->where('accountTypeID','=',last(request()->segments()))->get();
                                                    $payments = DB::table('payments')->where('account_id','=',$accounts[0]->id)->get();
                                                    ?>
                                                    <table style="border:1px solid #000;" id="" class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo e(trans('file.date')); ?></th>
                                                                <th>Age</th>
                                                                <th>Description</th>
                                                                <th>Category</th>
                                                                <th><?php echo e(trans('file.Debit')); ?></th>
                                                                <th><?php echo e(trans('file.Credit')); ?></th>
                                                                <th><?php echo e(trans('file.Balance')); ?></th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $debitBalance = 0;
                                                                $creditBalance = 0;
                                                                $balance = 0;
                                                            ?>
                                                            
                                                            <?php $__currentLoopData = $debit_list_two; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$debit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                            <tr <?php if($debit->status == 1): ?> style="background:#00FF00" <?php endif; ?>>
                                                                <td><?php echo e(date($general_setting->date_format, strtotime($debit->date))); ?></td>
                                                                <td>
                                                                    <?php if($debit->bill_no != NULL): ?>
                                                                            <?php
                                                                                $first_date = new DateTime(date($general_setting->date_format, strtotime($debit->date)));
                                                                                $second_date = new DateTime(date('d-m-Y'));
                                                                                $interval = $first_date->diff($second_date);
                                                                                echo $interval->format('%a');
                                                                            ?>
                                                                    <?php endif; ?> 
                                                                </td>
                                                                <td>
                                                                <table style="width:100%;">
                                                                <tr style="background-color:#000;">
                                                                     	<td style="color:#fff;">Name</td>
                                                                        <td style="color:#fff;">Qty</td>
                                                                        <td style="color:#fff;">Rate</td>
                                                                     </tr>
                                                                </table>
                                                                    <?php echo e($debit->payment_note); ?>

                                                                    <?php if($debit->status == 1): ?> 
                                                                        Manually : Rs. <?php echo e($debit->manual_amount); ?>, Note: <?php echo e($debit->updated_note); ?>

                                                                     <?php endif; ?>
                                                                     <?php if($debit->sale_id == 'Receiving'): ?> 
                                                                        Receiving
                                                                     <?php else: ?>
                                                                     <table style="width:100%;">
                                                                     
                                                                            <?php
                                                                            	$product_sales = DB::table('product_sales')->where('sale_id','=',$debit->sale_id)->get();
                                                                            ?>
                                                                            <?php $__currentLoopData = $product_sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row_product_sales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            	<tr>
                                                                            	<td>
                                                                                <?php
                                                                                    $product_name = DB::table('products')->where('id','=',$row_product_sales->product_id)->first();
                                                                                ?>
                                                                                <?php echo e($product_name->name); ?></td>
                                                                                <td><?php echo e($row_product_sales->qty); ?></td>
                                                                                <td><?php echo e($row_product_sales->net_unit_price); ?></td>
                                                                            </tr>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                            
                                                                        </table>
                                                                     <?php endif; ?>
                                                                     <?php if($debit->sale_id != 'Opening Balance'): ?>
                                                                    	
                                                                    <?php else: ?>
                                                                    	Opening Balance
                                                                    <?php endif; ?>
                                                                    
                                                                </td>
                                                                <td>
                                                                    <?php if($debit->sale_id == 'Opening Balance'): ?>
                                                                    <?php echo e($debit->sale_id); ?>

                                                                    <?php endif; ?>

                                                                    <?php if($debit->bill_no != 'Opening Balance'): ?>
                                                                         <?php if($debit->bill_no != NULL): ?>
                                                                            <?php 
                                                                                $sales = DB::table('sales')->where('bill_no', '=', $debit->bill_no)->get(); 
                                                                            ?>
                                                                                <?php if(!$sales->isEmpty() ): ?>
                                                                                     <a style="font-weight:bold; color:blue; text-decoration:underline" target="_blank" href="<?php echo e(url('sales/'.$debit->bill_no.'/viewinvoice')); ?>"> Bill # <?php echo e($debit->bill_no); ?> </a>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?> 
                                                                </td>

                                                                <?php if($debit->debit != NULL): ?>
                                                                <?php 
                                                                    $balance = $debit->debit + $balance; 
                                                                    $debitBalance += $debit->debit;
                                                                ?>

 																
                                                                <td><?php echo e(number_format((float)$debit->debit, 2, '.', '')); ?></td>
                                                                <td>0.00</td>
                                                                <td><?php echo e(number_format((float)$balance, 2, '.', '')); ?></td>
                                                                <?php elseif($debit->credit != NULL): ?>
                                                                <?php
                                                                    $balance = $balance - $debit->credit;
                                                                    $creditBalance += $debit->credit;
                                                                ?>

                                                                <td>0.00</td>
                                                                <td><?php echo e(number_format((float)$debit->credit, 2, '.', '')); ?></td>
                                                                <td><?php echo e(number_format((float)$balance, 2, '.', '')); ?></td>
                                                                <?php else: ?>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <?php endif; ?>
                                                            </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><strong><?php echo e($debitBalance); ?>.00 </strong></td>
                                                                <td><strong><?php echo e($creditBalance); ?>.00 </strong></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </tfoot>
                                                        <?php echo e($debit_list_two->links()); ?>

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
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>