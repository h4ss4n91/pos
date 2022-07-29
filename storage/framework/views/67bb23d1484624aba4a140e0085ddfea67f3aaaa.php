<?php $__env->startSection('content'); ?>
<?php
                                $debitBalance = 0;
                                $creditBalance = 0;
                                $balance = 0;
                            ?>
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
          <div class="col-md-12">
          <div class="col-md-6">
            <label style="font-size:18px;font-weight:bold;"> Select Product </label>
          <select id="selectbox" class="form-control selectpicker" data-live-search="true" data-live-search-style="begins" onchange="javascript:location.href = this.value;">
              <option style="font-size:18px; font-weight:bold;" value="">Select Product</option>
              <?php
                $product_list = DB::table('products')->get();
              ?>
              <?php $__currentLoopData = $product_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row_product_list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option style="font-size:18px; font-weight:bold;" value="<?php echo e($row_product_list->id); ?>"><?php echo e($row_product_list->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
</div>
                
                <div class="col-md-12">
                       <div class="card">
                                              <div class="">
                                                <div class="">
                                                  <!-- Modal Header -->
                                                  <div style="background-color:green;" class="card-header">
                                                    <h4 style="color:#fff;" class="card-title">
                                                    <?php
                                                        $product_name = DB::table('products')->where('id','=',last(request()->segments()))->first();
                                                    ?>
                                                    	Product Ledger of <span style="font-size:31px;"> <?php echo e($product_name->name); ?> </span> </h4>
                                                  </div>

                                                  <!-- Modal body -->
                                                  <div class="card-body">
                                                    <?php
                                                        $product_sales = DB::table('product_sales')->where('product_id','=',last(request()->segments()))->get();
                                                    ?>
                                                    <table style="width:100% !important">
                                                    	<tr>
                                                            <td style="vertical-align: top; width:100% !important">
    															                              <table id="table" class="table">
                                                                      <thead class="thead-light">
                                                                        <tr>
                                                                          <th scope="col">Date</th>
                                                                          <th scope="col">Purchase Detail</th>
                                                                          <th scope="col">Sale Detail</th>
                                                                          <th scope="col">Purchase Return Detail</th>
                                                                          <th scope="col">Sale Return Detail</th>
                                                                          <th scope="col">Stock</th>
                                                                        </tr>
                                                                      </thead>
                                                                      <tbody>
                                                                      <?php
                                                                      	$product_ledger = DB::table('product_ledgers')->where('product_id','=',last(request()->segments()))->orderBy('id','ASC')->paginate(
                                                                        $perPage = 15, $columns = ['*'], $pageName = 'product_ledger'
                                                                        );
                                                                      ?>
                                                                      <?php $__currentLoopData = $product_ledger; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row_product_sales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                      
                                                                      <tr class="item <?php echo e($row_product_sales->id); ?>">
                                                                          <td scope="col">
                                                                           <?php
                                                                          	echo date('d-m-Y', strtotime($row_product_sales->created_at))
                                                                          ?>
                                                                          </td>
                                                                          
                                                                          <td scope="col"><?php echo e($row_product_sales->purchase); ?></td>
                                                                          <td scope="col"><span style="font-weight:bold; color:#000">
                                                                          <?php if($row_product_sales->sale_id != NULL): ?>
                                                                            Sale ID:</span> <?php echo e($row_product_sales->sale_id); ?> <br/>
                                                                            <span style="font-weight:bold; color:#000"> Customer Name: 
                                                                            	<?php
                                                                                	$customer_name = DB::table('customers')->where('id','=',$row_product_sales->customer_id)->get();
                                                                              	?>
                                                                            
                                                                            <?php if(!$customer_name->isEmpty()): ?>
                                                                            	<?php echo e($customer_name[0]->name); ?> <br/>
                                                                            <?php endif; ?>
                                                                            </span>
                                                                            
                                                                          
                                                                          <?php endif; ?>
                                                                          
                                                                          
                                                                          <?php echo e($row_product_sales->sale); ?></td>
                                                                          <td><?php echo e($row_product_sales->purchase_return_qty); ?></td>
                                                                          <td><?php echo e($row_product_sales->sale_return_qty); ?></td>
                                                                          <td scope="col"><?php echo e($row_product_sales->stock); ?></td>
                                                                        </tr>
                                                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                                      </tbody>
                                                                      <?php echo e($product_ledger->render()); ?>

                                                                    </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                  </div>
                                                  
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



      jQuery("#selectbox").change(function () {
        location.href = jQuery(this).val();
    })

    

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>