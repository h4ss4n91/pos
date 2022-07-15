 <?php $__env->startSection('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
@import  url(https://fonts.googleapis.com/earlyaccess/notonaskharabic.css);

#customerUrduName{
  font-family: 'Noto Naskh Arabic', serif;
  font-size: 1.5em;
}

    table tr td{
        font-size:23px !important;
        color:#000;
    }
    .account{
        font-weight:bold !important;
        font-size:27px !important;
        color:#000 !important;
    }
    .blue{
        background:blue;
    }
</style>
<section class="forms">
    <div class="container-fluid">
        <div class="card">
            <div style="background:#7c5cc4; padding:10px;" class="card-header mt-2">
                <h1 style="color:#fff;" class="text-center">Customer <?php echo e(trans('file.Account Statement')); ?></h1>
            </div>
            <div class="card-body">
               
            <div class="modal-content">
                <div style="width:60%" class="modal-header">
                                
                            </div>
                                <table style="width:100%">
                                    <tr>
                                        <td valign="TOP" style="width:58% !important">
                                            <table style="width:100%">
                                    <tr>
                                        <td>
                                            <div class="modal-body">
                                                <table style="width:80%">
                                                    <tr>
                                                        <td>
                                                            <?php echo Form::open(['route' => 'accounts.statement', 'method' => 'post']); ?>

                                                            <div class="row">
                                                                <div class="col-md-12 form-group">
                                                                    <select class="form-control selectpicker" id="account_id" name="account_id" data-live-search="true" data-live-search-style="begins" title="Select customer...">
                                                                        <?php $customers = DB::table('customers')->where('is_active', true)->get(); ?>
                                                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option style="font-weight:bold; font-size:21px;" value="<?php echo e($account->id); ?>"><?php echo e($account->name); ?> <span style="color:red !mportant">(<?php echo e($account->city); ?>)</span> (<?php echo e($account->id); ?>)</option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </select>
                                                                </div>
                                                              </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                  <button type="submit" class="btn btn-primary"><?php echo e(trans('file.submit')); ?></button>
                                                              </div>
                                                        <?php echo e(Form::close()); ?>      
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="color:green; font-weight:bold; font-size:41px;" id="customerBalance_two"></span>
                                        </td>
                                    </tr>
                                </table>
                                <table>
                                    <tr>
                                        <td> From Date: <input id="from_date" class="form-control" name="from_date" type="date"/> </td>
                                        <td> To Date:  <input id="to_date" class="form-control" name="to_date" type="date"/> </td>
                                        <td style="padding-left:50px;"> &nbsp; <br/> <button id="take_print" style="border-radius:8px; color:#fff;" class="btn btn-primary">Take a Print</button> </td>
                                    </tr>
                                </table>
                                <div>
                                    <table style="border:2px solid #000; width:100%;" border='1' id='userTable' style='border-collapse: collapse;'>
                                       <thead>
                                        <tr>
                                          <td colspan="4">
                                              <h1 style="text-align:center; font-weight:bold; color:green">Balance: <span style="font-weight:bold; font-size:31px;" id="customerBalance"> </span> <br/> <span style="color:blue"> (Last Receiving <span style="font-weight:bold; color:red" id="customer_last_date"> </span> days before)  </span></h1>
                                          </td>
                                          
                                          <td colspan="4">
                                                <h1 style="text-align:center; font-weight:bold; font-size:31px; color:green;" id="customerUrduName"> </h1>
                                                <h1 style="text-align:center; font-weight:bold; font-size:25px; color:black;" id="phone_number"> </h1>
                                          </td>
                                        </tr>
                                        
                                        <tr>
                                          <th style="font-size:21px;">S.No</th>
                                          <th style="width:150px; font-size:21px;">Date</th>
                                          <th style="width:70px; font-size:21px;">Age</th>
                                          <th style="font-size:21px;">Description</th>
                                          <th style="font-size:21px;">Debit</th>
                                          <th style="font-size:21px;">Credit</th>
                                          <th style="font-size:21px;">Balance</th>
                                          <th style="font-size:21px;">Action</th>
                                        </tr>
                                       </thead>
                                       <tbody></tbody>
                                       <tfoot>
                                        <tr>
                                          <th style="font-size:21px;"></th>
                                          <th style="width:150px; font-size:21px;"></th>
                                          <th style="width:70px; font-size:21px;"></th>
                                          <th style="font-size:21px;"></th>
                                          <th style="color:red; font-size:21px;"><span id="total_debit"></span></th>
                                          <th style="color:red; font-size:21px;"><span id="total_credit"></span></th>
                                          <th style="color:red; font-size:21px;"><span id="total_balance"></span> </th>
                                          <th style="font-size:21px;"></th>
                                        </tr>
                                       </tfoot>
                                    </table>
                                </div>
                        </td>
                        
                    </tr>
                </table>
                <hr/>
                <div id="cehck_records">
                    
                </div>
            </div>
        
      
      
               
            </div>
            
            
        </div>
    </div>
    
    <div id="output"></div>
    
</section>



<!-- Script -->
     <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --> <!-- jQuery CDN -->
     <script src="<?php echo e(asset('js/jquery-3.3.1.min.js')); ?>"></script>

     <script type='text/javascript'>
     
$('select[name="account_id"]').on('change', function() {
    var id = 'payment_'+$(this).val();
            $.ajax( {
                type:'GET',
                header:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                url:"<?php echo e(env('APP_URL')); ?>/sales/"+id+"/customer_balance",
            })
            .done(function(data) {
                //var balance = jQuery.parseJSON(data);
                $('#customerBalance').text(data[0]);
                $('#customerBalance_two').text(data[0]);
                $('#customerUrduName').text(data[1]);
                $('#customer_last_date').text(data[2]);
                $('#total_receiving').text(data[3]);
                $('#phone_number').text(data[4]);
                $('#currentBalance').text(data);
                var currentBalance = data;
            })
            .fail(function() {
               // alert("error");
                });
        //$.get('getcustomergroup/' + id, function(data) {
          //  customer_group_rate = (data / 100);
        //});
    
    fetchRecords(id);
    
    checkRecords(id);
    
});


        $('#from_date').change(function() {
            var date = $(this).val();
            var id = $('#account_id').val();
            fetchRecords_by_date(id, date);
        });
        
        function a_bill_hover(){
            $('.billHover').click(function(evt) {
                    evt.preventDefault();
                    //var divId = 'summary' + $(this).attr('id');
                    var aBillHover = $(this).attr('id');

                     $.ajax({
                         url: 'getBillRecords/'+aBillHover,
                         type: 'get',
                         dataType: 'json',
                         beforeSend: function() { 
                                //   $("#solTitle a").hide();
                                },
                         success: function(response){
                             //alert(JSON.stringify(response));
                             //console.log(response);
                             $('.div_bill_hover').html();
                             Swal.fire({
                                  title: 'Bill No: '+response.bill_no, 
                                  html: response.product_sales_json,  
                                  confirmButtonText: "Close", 
                                });
                         }
                         
                         
                    });
            });
            
        }
        
         function openSolution() {
                $('#solTitle a').click(function(evt) {
                    evt.preventDefault();
                    //var divId = 'summary' + $(this).attr('id');
                    var divId = $(this).attr('id');
                    var current_balance = $(this).attr('class');
                    //alert(current_balance);
                    //document.getElementById(divId).className = '';
                  
                   $.ajax({
                         url: 'getRecords_tick/'+divId+'/'+current_balance,
                         type: 'get',
                         dataType: 'json',
                         beforeSend: function() { 
                                  $("#solTitle a").hide();
                                },
                         success: function(response){
                             alert(response.success);
                             $("#solTitle a").show();
                             if(response.success == 'Updated Successfully.'){ // if true (1)
                              setTimeout(function(){// wait for 5 secs(2)
                                   fetchRecords(response.id);
                              }, 3000); 
                           }
                         }
                         
                         
                    });

                });
            }
            
         
        
        $('#to_date').change(function() {
            var to_date = $(this).val();
            var id = $('#account_id').val();
            var from_date = $('#from_date').val();
            fetchRecords_by_todate(id, to_date, from_date);
        });

       
 function checkRecords(id){
     $.ajax({
         url: 'checkRecords/'+id,
         type: 'get',
         dataType: 'json',
         success: function(response){
                $('#cehck_records').html(response);        
         }})
         
     
 };
         
     function fetchRecords(id){
       $.ajax({
         url: 'getRecords/'+id,
         type: 'get',
         dataType: 'json',
         success: function(response){
                var total_debit = response['total_debit'];
                $('#total_debit').html(total_debit);
                var total_credit = response['total_credit'];
                $('#total_credit').html(total_credit);
           var len = 0;
           $('#userTable tbody').empty(); // Empty <tbody>
           if(response['data'] != null){
              len = response['data'].length;
           }
           
           var currentBalance = 0;

           if(len > 0){
              for(var i=0; i<len; i++){
                 var id = response['data'][i].id;
                 var date = response['data'][i].date;
                 var new_date = response['data'][i].formatted_date;
                 var expires_in = response['data'][i].expires_in;
                 
                 var bill_no = response['data'][i].bill_no;
                 var sale_id = response['data'][i].sale_id;
                 var credit = response['data'][i].credit;
                 var debit = response['data'][i].debit;
                 var payment_note = response['data'][i].payment_note;
                 var sale_note = response['data'][i].sale_note;
                 var checked_amount = response['data'][i].checked_amount;
                 var receive_voucher_id = response['data'][i].receive_voucher_id;
                 var payment_voucher_id = response['data'][i].payment_voucher_id;

                 var checked_amount = response['data'][i].checked_amount;
                 
                 if(checked_amount == null){
                     var checked_amount = '';
                 }
                 
                 if(receive_voucher_id == null){
                     var receive_voucher_id = '';
                 }
                 
                 if(payment_voucher_id == null){
                     var payment_voucher_id = '';
                 }
                 
                 if(payment_note == null){
                     var payment_note = '';
                 }
                  if(sale_note == null){
                     var sale_note = '';
                 }
                 var status = response['data'][i].status;
                
                    if(bill_no != null){
                        var bill_no = "<a href='#' class='billHover' onclick='a_bill_hover();' id='"+sale_id+"' data-toggle='tooltip' data-html='true' title='Click Here'><i class='fa fa-eye' aria-hidden='true'></i></a> Bill No: <a class='bill_hover' style='color:blue; font-weight:bold;' target='_blank' href='http://localhost/cd/pos/sales/"+response['data'][i].bill_no+"/viewinvoice'>"+response['data'][i].bill_no+'</a>';
                    }else if(sale_id != null){
                       var bill_no  = sale_id;
                    }else if(sale_return_id != null){
                       var bill_no  = sale_return_id;
                    }
                    
                
                    if(debit != null){
                        currentBalance = debit + currentBalance; 
                         var debit = response['data'][i].debit;
                         var credit = 0.00
                    }else{
                        currentBalance = currentBalance - credit; 
                        var credit = response['data'][i].credit;
                        var debit = 0.00;
                        if(sale_id == 'Transfered'){
                            var bill_no  = sale_id;
                        }else{
                            var bill_no  = "<a target='_blank'  href='../customer-receiving-voucher-edit/"+ receive_voucher_id +"' style='font-weight:bold; color:#38BC1C'> Receipt: "+ receive_voucher_id + "</a>";
                        }
                        
                    }
                    

                if(status == 1){
                 var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'>" + expires_in + "</td>" +
                   "<td style='width:250px;' align='center'>Sale Invoice <br/><a href='http://localhost/cd/pos/sales/" + bill_no + "/edit'>" + bill_no + "</a><span style='color:red;>"+payment_note+"</span><span style='display:block; color:red; font-size:16px;'>"+sale_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                 "</tr>";   
                 
                }else{
                    var tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'>" + expires_in + "</td>" +
                   "<td style='width:250px;' align='center'>Sale Invoice <br/><a href='http://localhost/cd/pos/sales/" + bill_no + "/edit'>" + bill_no + "</a><span style='color:red;'>"+payment_note+"</span><span style='display:block; color:red; font-size:16px;'>"+sale_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a><span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span> </td>" +
                 "</tr>";
                }
                 

                 $("#userTable tbody").append(tr_str);
              }
           }else{
              var tr_str = "<tr>" +
                  "<td align='center' colspan='4'>No record found.</td>" +
              "</tr>";

              $("#userTable tbody").append(tr_str);
           }
           
           
           var len = 0;
           $('#userTable_two tbody').empty(); // Empty <tbody>
           if(response['data'] != null){
              len = response['data'].length;
           }
           
           var currentBalance_two = response['total_receiving'];

            if(len > 0){
              for(var i=0; i<len; i++){
                 var id = response['data'][i].id;
                 var date = response['data'][i].date;
                 var new_date = response['data'][i].formatted_date;
                 var bill_no = response['data'][i].bill_no;
                 var sale_id = response['data'][i].sale_id;
                 var debit_two = response['data'][i].debit;
                 var payment_note = response['data'][i].payment_note;
                 var sale_note = response['data'][i].sale_note;
                 var checked_amount = response['data'][i].checked_amount;
                 var receive_voucher_id = response['data'][i].receive_voucher_id;
                 var payment_voucher_id = response['data'][i].payment_voucher_id;
                 
                 if(receive_voucher_id == null){
                     var receive_voucher_id = '';
                 }
                 
                 if(checked_amount == null){
                     var checked_amount = '';
                 }
                 
                 if(payment_note == null){
                     var payment_note = '';
                 }
                 if(sale_note == null){
                     var sale_note = '';
                 }
                 var status = response['data'][i].status;
                    var isRow=1;
                    if(bill_no != null){
                        var bill_no = "<a href='#' data-toggle='tooltip' data-html='true' title='Click Here'><i class='fa fa-eye' aria-hidden='true'></i></a> Bill No: <a class='bill_hover' style='color:blue; font-weight:bold;' target='_blank' href='http://localhost/cd/pos/sales/"+response['data'][i].bill_no+"/viewinvoice'>"+response['data'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = sale_id;
                    }
                
                    if(debit_two != null){
                        currentBalance_two = currentBalance_two - debit_two; 
                         var debit_two = response['data'][i].debit;
                    }else{
                        bill_no = "";
                        new_date = "";
                    }
                    var tr_str=""
                if(isRow)    
    {
        
                if(status == 1){
                 var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'>" + bill_no + "</td>" +
                   "<td class='receipt' align='center'>" + debit_two + "</td>" +
                   "<td class='balance' align='center'>" + currentBalance_two + "</td>" +
                   
                   
                 "</tr>";   
                }else{
                    tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'>" + bill_no + "</td>" +
                   "<td class='receipt' align='center'>" + debit_two + "</td>" +
                   "<td class='balance' align='center'>" + currentBalance_two + "</td>" +
                   
                   
                 "</tr>";
                }
    }            

                 $("#userTable_two tbody").append(tr_str);
              }
           }else{
              var tr_str = "<tr>" +
                  "<td align='center' colspan='4'>No record found.</td>" +
              "</tr>";

              $("#userTable_two tbody").append(tr_str);
           }
           
           

         }
       });
     }
     
     function fetchRecords_by_date(id, date){
       $.ajax({
         url: 'getRecords_by_date/'+id+'/'+date,
         type: 'get',
         dataType: 'json',
         success: function(response){
                var total_debit = response['total_debit_by_date'];
                $('#total_debit').html(total_debit);
                var total_credit = response['total_credit_by_date'];
                $('#total_credit').html(total_credit);
           var len = 0;
           $('#userTable tbody').empty(); // Empty <tbody>
           if(response['data'] != null){
              len = response['data'].length;
           }
           console.log(response['data']);
           var currentBalance = response['current_balance'];

           if(len > 0){
              for(var i=0; i<len; i++){
                 var id = response['data'][i].id;
                 var date = response['data'][i].date;
                 var new_date = response['data'][i].formatted_date;
                 var bill_no = response['data'][i].bill_no;
                 var sale_id = response['data'][i].sale_id;
                 var credit = response['data'][i].credit;
                 var debit = response['data'][i].debit;
                 var payment_note = response['data'][i].payment_note;
                 var sale_note = response['data'][i].sale_note;
                 var checked_amount = response['data'][i].checked_amount;
                 var receive_voucher_id = response['data'][i].receive_voucher_id;
                 var payment_voucher_id = response['data'][i].payment_voucher_id;
                 
                 if(receive_voucher_id == null){
                     var receive_voucher_id = '';
                 }
                 if(checked_amount == null){
                     var checked_amount = '';
                 }
                 
                 if(payment_note == null){
                     var payment_note = '';
                 }
                 if(sale_note == null){
                     var sale_note = '';
                 }
                 var status = response['data'][i].status;
                
                    if(bill_no != null){
                        var bill_no = "<a href='#' data-toggle='tooltip' data-html='true' title='Click Here'><i class='fa fa-eye' aria-hidden='true'></i></a> Bill No: <a class='bill_hover'  target='_blank' style='color:blue; font-weight:bold;' href='http://localhost/cd/pos/sales/"+response['data'][i].bill_no+"/viewinvoice'>"+response['data'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = sale_id;
                    }
                
                    if(debit != null){
                        currentBalance = debit + currentBalance; 
                         var debit = response['data'][i].debit;
                         var credit = 0.00
                    }else{
                        currentBalance = currentBalance - credit; 
                        var credit = response['data'][i].credit;
                        var debit = 0.00;
                        if(sale_id == 'Transfered'){
                            var bill_no  = sale_id;
                        }else{
                            var bill_no  = 'Receipt';
                        }
                        
                    }
                    

                if(status == 1){
                 var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red;'>"+payment_note+"</span><span style='display:block; color:red; font-size:16px;'>"+sale_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ (i+1) +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   
                   
                 "</tr>";   
                }else{
                    var tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red;'>"+payment_note+"</span><span style='display:block; color:red; font-size:16px;'>"+sale_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ (i+1) +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   
                 "</tr>";
                }
                 

                 $("#userTable tbody").append(tr_str);
              }
           }else{
              var tr_str = "<tr>" +
                  "<td align='center' colspan='4'>No record found.</td>" +
              "</tr>";

              $("#userTable tbody").append(tr_str);
           }
           
           
           var len = 0;
           $('#userTable_two tbody').empty(); // Empty <tbody>
           if(response['data'] != null){
              len = response['data'].length;
           }
           
           var currentBalance_two = response['total_receiving'];

            if(len > 0){
              for(var i=0; i<len; i++){
                 var id = response['data'][i].id;
                 var date = response['data'][i].date;
                 var new_date = response['data'][i].formatted_date;
                 var bill_no = response['data'][i].bill_no;
                 var sale_id = response['data'][i].sale_id;
                 var debit_two = response['data'][i].debit;
                 var payment_note = response['data'][i].payment_note;
                 var sale_note = response['data'][i].sale_note;
                 var checked_amount = response['data'][i].checked_amount;
                 var receive_voucher_id = response['data'][i].receive_voucher_id;
                 var payment_voucher_id = response['data'][i].payment_voucher_id;
                 
                 if(receive_voucher_id == null){
                     var receive_voucher_id = '';
                 }
                 
                 if(checked_amount == null){
                     var checked_amount = '';
                 }
                 
                 if(payment_note == null){
                     var payment_note = '';
                 }
                 if(sale_note == null){
                     var sale_note = '';
                 }
                 var status = response['data'][i].status;
                    var isRow=1;
                    if(bill_no != null){
                        var bill_no = "<a href='#' data-toggle='tooltip' data-html='true' title='Click Here'><i class='fa fa-eye' aria-hidden='true'></i></a> Bill No: <a target='_blank' class='bill_hover'  style='color:blue; font-weight:bold;' href='http://localhost/cd/pos/sales/"+response['data'][i].bill_no+"/viewinvoice'>"+response['data'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = sale_id;
                    }
                
                    if(debit_two != null){
                        currentBalance_two = currentBalance_two - debit_two; 
                         var debit_two = response['data'][i].debit;
                    }else{
                        bill_no = "";
                        new_date = "";
                    }
                    var tr_str=""
                if(isRow)    
    {
        
                if(status == 1){
                 var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td align='center'>" + bill_no + "</td>" +
                   "<td class='receipt' align='center'>" + debit_two + "</td>" +
                   "<td class='balance' align='center'>" + currentBalance_two + "</td>" +
                   
                   
                 "</tr>";   
                }else{
                    tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td align='center'>" + bill_no + "</td>" +
                   "<td class='receipt' align='center'>" + debit_two + "</td>" +
                   "<td class='balance' align='center'>" + currentBalance_two + "</td>" +
                   
                   
                 "</tr>";
                }
    }            

                 $("#userTable_two tbody").append(tr_str);
              }
           }else{
              var tr_str = "<tr>" +
                  "<td align='center' colspan='4'>No record found.</td>" +
              "</tr>";

              $("#userTable_two tbody").append(tr_str);
           }
           
           

         }
       });
     }
     
      function fetchRecords_by_todate(id, to_date, from_date){
       $.ajax({
         url: 'getRecords_by_todate/'+id+'/'+to_date+'/'+from_date,
         type: 'get',
         dataType: 'json',
         success: function(response){

           var len = 0;
                var total_debit = response['total_debit_to_date'];
                $('#total_debit').html(total_debit);
                var total_credit = response['total_credit_to_date'];
                $('#total_credit').html(total_credit);
                
           $('#userTable tbody').empty(); // Empty <tbody>
           if(response['data'] != null){
              len = response['data'].length;
           }
           
           console.log(response['data']);
           var currentBalance = response['current_balance'];
            console.log(response['data'].total_credit_to_date);
            console.log(response['data'].total_debit_to_date);
           if(len > 0){
              for(var i=0; i<len; i++){
                 var id = response['data'][i].id;
                 var date = response['data'][i].date;
                 var new_date = response['data'][i].formatted_date;
                 var bill_no = response['data'][i].bill_no;
                 var sale_id = response['data'][i].sale_id;
                 var credit = response['data'][i].credit;
                 var debit = response['data'][i].debit;
                 var payment_note = response['data'][i].payment_note;
                 var sale_note = response['data'][i].sale_note;
                 var checked_amount = response['data'][i].checked_amount;
                 var receive_voucher_id = response['data'][i].receive_voucher_id;
                 var payment_voucher_id = response['data'][i].payment_voucher_id;
                 var total_credit = response['data'].total_credit_to_date;
                 var total_debit = response['data'].total_debit_to_date;
                 
                 if(receive_voucher_id == null){
                     var receive_voucher_id = '';
                 }
                 
                 if(checked_amount == null){
                     var checked_amount = '';
                 }
                 
                 if(payment_note == null){
                     var payment_note = '';
                 }
                 if(sale_note == null){
                     var sale_note = '';
                 }
                 var status = response['data'][i].status;
                
                    if(bill_no != null){
                        var bill_no = "<a  href='#' data-toggle='tooltip' data-html='true' title='Click Here'><i class='fa fa-eye' aria-hidden='true'></i></a> Bill No: <a target='_blank' class='bill_hover'  style='color:blue; font-weight:bold;' href='http://localhost/cd/pos/sales/"+response['data'][i].bill_no+"/viewinvoice'>"+response['data'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = sale_id;
                    }
                
                    if(debit != null){
                        currentBalance = debit + currentBalance; 
                         var debit = response['data'][i].debit;
                         var credit = 0.00
                    }else{
                        currentBalance = currentBalance - credit; 
                        var credit = response['data'][i].credit;
                        var debit = 0.00;
                        if(sale_id == 'Transfered'){
                            var bill_no  = sale_id;
                        }else{
                            var bill_no  = 'Receipt';    
                        }
                        
                    }
                    

                if(status == 1){
                 var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red; font-weight:bold;'>"+payment_note+"</span><span style='display:block; color:red; font-size:16px;'>"+sale_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ (i+1) +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span> </td>" +
                   
                   
                 "</tr>";   
                }else{
                    var tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red; font-weight:bold;'>"+payment_note+"</span><span style='display:block; color:red; font-size:16px;'>"+sale_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ (i+1) +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span> </td>" +
                   
                 "</tr>";
                }
                 

                 $("#userTable tbody").append(tr_str);
              }
           }else{
              var tr_str = "<tr>" +
                  "<td align='center' colspan='4'>No record found.</td>" +
              "</tr>";

              $("#userTable tbody").append(tr_str);
           }
           
           
           var len = 0;
           $('#userTable_two tbody').empty(); // Empty <tbody>
           if(response['data'] != null){
              len = response['data'].length;
           }
           
           var currentBalance_two = response['total_receiving'];

            if(len > 0){
              for(var i=0; i<len; i++){
                 var id = response['data'][i].id;
                 var date = response['data'][i].date;
                 var new_date = response['data'][i].formatted_date;
                 var bill_no = response['data'][i].bill_no;
                 var sale_id = response['data'][i].sale_id;
                 var debit_two = response['data'][i].debit;
                 var payment_note = response['data'][i].payment_note;
                 var sale_note = response['data'][i].sale_note;
                 var checked_amount = response['data'][i].checked_amount;
                 var receive_voucher_id = response['data'][i].receive_voucher_id;
                 var payment_voucher_id = response['data'][i].payment_voucher_id;
                 
                 
                 if(receive_voucher_id == null){
                     var receive_voucher_id = '';
                 }
                 
                 if(checked_amount == null){
                     var checked_amount = '';
                 }
                 
                 if(payment_note == null){
                     var payment_note = '';
                 }
                 if(sale_note == null){
                     var sale_note = '';
                 }
                 var status = response['data'][i].status;
                    var isRow=1;
                    if(bill_no != null){
                        var bill_no = "<a href='#' data-toggle='tooltip' data-html='true' title='Click Here'><i  class='fa fa-eye' aria-hidden='true'></i></a> Bill No: <a class='bill_hover'  style='color:blue; font-weight:bold;' target='_blank' href='http://localhost/cd/pos/sales/"+response['data'][i].bill_no+"/viewinvoice'>"+response['data'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = sale_id;
                    }
                
                    if(debit_two != null){
                        currentBalance_two = currentBalance_two - debit_two; 
                         var debit_two = response['data'][i].debit;
                    }else{
                        bill_no = "";
                        new_date = "";
                    }
                    var tr_str=""
                if(isRow)    
    {
        
                if(status == 1){
                 var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   
                   "<td align='center'>" + bill_no + "</td>" +
                   "<td class='receipt' align='center'>" + debit_two + "</td>" +
                   "<td class='balance' align='center'>" + currentBalance_two + "</td>" +
                   
                   
                 "</tr>";   
                }else{
                    tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   
                   "<td align='center'>" + bill_no + "</td>" +
                   "<td class='receipt' align='center'>" + debit_two + "</td>" +
                   "<td class='balance' align='center'>" + currentBalance_two + "</td>" +
                   
                   
                 "</tr>";
                }
    }            

                 $("#userTable_two tbody").append(tr_str);
              }
           }else{
              var tr_str = "<tr>" +
                  "<td align='center' colspan='4'>No record found.</td>" +
              "</tr>";

              $("#userTable_two tbody").append(tr_str);
           }
           
           

         }
       });
     }
     
     
     
            


    $("ul#account").siblings('a').attr('aria-expanded','true');
    $("ul#account").addClass("show");
    //$("ul#account #account-statement-menu").addClass("active");

    $('#account-table').DataTable( {
        "order": [],
        'language': {
            'lengthMenu': '_MENU_ <?php echo e(trans("file.records per page")); ?>',
             "info":      '<?php echo e(trans("file.Showing")); ?> _START_ - _END_ (_TOTAL_)',
            "search":  '<?php echo e(trans("file.Search")); ?>',
            'paginate': {
                    'previous': '<?php echo e(trans("file.Previous")); ?>',
                    'next': '<?php echo e(trans("file.Next")); ?>'
            }
        },
        'columnDefs': [
            {
                "orderable": false,
                'targets': 0
            },
            {
                'checkboxes': {
                   'selectRow': true
                },
                'targets': 0
            }
        ],
        'select': { style: 'multi',  selector: 'td:first-child'},
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                text: '<?php echo e(trans("file.PDF")); ?>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                }
            },
            {
                extend: 'csv',
                text: '<?php echo e(trans("file.CSV")); ?>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                }
            },
            {
                extend: 'print',
                text: '<?php echo e(trans("file.Print")); ?>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                }
            },
            {
                extend: 'colvis',
                text: '<?php echo e(trans("file.Column visibility")); ?>',
                columns: ':gt(0)'
            },
        ],
    } );



function printData()
        {
           var divToPrint=document.getElementById("userTable");
           newWin= window.open("");
           newWin.document.write(divToPrint.outerHTML);
           newWin.print();
           newWin.close();
        }
        
        $('#take_print').on('click',function(){
        printData();
        })
 
       
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})    
    


   
 

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>