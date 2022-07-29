 <?php $__env->startSection('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.5/dist/html2canvas.min.js"></script>
  
<style>
@import  url(https://fonts.googleapis.com/earlyaccess/notonaskharabic.css);

.tableRow, .tableCol{
    bordeR:1px solid #000;
    padding:10px; 
}


#customerUrduName{
  font-family: 'Noto Naskh Arabic', serif;
  font-size: 1.5em;
}

    table tr td{
        font-size:23px !important;
        color:#000;
    }
    .account{s
        font-weight:bold !important;
        font-size:27px !important;
        color:#000 !important;
    }
    .blue{
        background:blue;
    }
</style>
<section class="forms">
    <div class="row">
        <div class="col-md-12">
            <div class="container">
        <div class="card">
            <div style="background:#7c5cc4; padding:10px;" class="card-header mt-2">
                <h1 style="color:#fff;" class="text-center">Supplier <?php echo e(trans('file.Account Statement')); ?></h1>
            </div>
            <div class="card-body">
               
            <div class="modal-content">
                <div style="width:100%" class="modal-header">
                                
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
                                                           <?php echo Form::open(['route' => 'accounts.supplier-statement', 'method' => 'post']); ?>

                                                                <div class="row">
                                                                    <div class="col-md-12 form-group">
                                                                        <select class="form-control selectpicker" id="account_id_latest" name="account_id_latest" data-live-search="true" data-live-search-style="begins" title="Select Supplier...">
                                                                            <?php $customers = DB::table('suppliers')->where('is_active', true)->get(); ?>
                                                                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <option style="font-size:21px; font-weight:bold;" value="<?php echo e($account->id); ?>"><?php echo e($account->name); ?> (<?php echo e($account->city); ?>) - (<?php echo e($account->id); ?>)</option>
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
                                            
                                        </td>
                                    </tr>
                                </table>

                                <table>
                                    <tr>
                                        <td>
                                            <h5>Total Balance: <span id="supplier_total_balance"> </span></h5>
                                        </td>
                                    </tr>
                                </table>
                                <div id="supplier_new_table">

                                </div>
                        </td>
                        
                    </tr>
                    
                </table>
                 
                 
                <hr/>
            </div>
            </div>
        </div>
    </div>
    
    
        </div>
    
    </div>
    
    
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
                url:"<?php echo e(env('APP_URL')); ?>/sales/"+id+"/supplier_balance",
            })
            .done(function(data) {
                //var balance = jQuery.parseJSON(data);
                $('#customerBalance_latest').text(data[4]);
                $('#customerUrduName_latest').text(data[1]);
                $('#customer_last_date').text(data[2]);
                $('#total_receiving').text(data[3]);
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
});


        $('#from_date').change(function() {
            var date = $(this).val();
            var id = $('#account_id').val();
            fetchRecords_by_date(id, date);
        });
        
         function openSolution() {
                $('#solTitle a').click(function(evt) {
                    evt.preventDefault();
                    //var divId = 'summary' + $(this).attr('id');
                    var divId = $(this).attr('id');
                    //document.getElementById(divId).className = '';
                    var current_balance = $(this).attr('class');
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

       

     function fetchRecords(id){
       $.ajax({
         url: 'getSupplierRecords/'+id,
         type: 'get',
         dataType: 'json',
         success: function(response){
                console.log(response);
            $('#supplier_new_table').html(response.supplier_table);
            $('#supplier_total_balance').html(response.supplier_total_balance);

           var len = 0;
           $('#userTable tbody').empty(); // Empty <tbody>
           if(response['data_previous'] != null){
              len = response['data_previous'].length;
           }
           
           var currentBalance = 0;

           if(len > 0){
              for(var i=0; i<len; i++){
                 var id = response['data_previous'][i].id;
                 var date = response['data_previous'][i].date;
                 var new_date = response['data_previous'][i].formatted_date;
                 var bill_no = response['data_previous'][i].bill_no;
                 
                 let purchase_id = response['data_previous'][i].purchase_id; 
                 
                 
                 
                 var credit = response['data_previous'][i].credit;
                 var debit = response['data_previous'][i].debit;
                 var payment_note = response['data_previous'][i].payment_note;
                 var checked_amount = response['data_previous'][i].checked_amount;
                 var receive_voucher_id = response['data_previous'][i].receive_voucher_id;
                 var payment_voucher_id = response['data_previous'][i].payment_voucher_id;
                 
                 
                 var checked_amount = response['data_previous'][i].checked_amount;
                 
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
                 var status = response['data_previous'][i].status;
                
                    if(purchase_id == 'Transfered'){
                            var bill_no  = 'Transfered';
                    }else if(bill_no != null){
                        var bill_no = "Bill No: <a style='color:blue; font-weight:bold;' target='_blank' href='http://localhost/cd/pos/sales/"+response['data_previous'][i].bill_no+"/viewinvoice'>"+response['data_previous'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = "<a target='_blank'  href='../supplier-voucher-edit/"+ payment_voucher_id +"' style='font-weight:bold; color:blue'>Payments </a>";
                    }
                
                    if(debit != null){
                        currentBalance = currentBalance - debit; 
                         var debit = response['data_previous'][i].debit;
                         var credit = 0.00
                    }else{
                        currentBalance = credit + currentBalance; 
                        var credit = response['data_previous'][i].credit;
                        var debit = 0.00;
                        if(purchase_id == 'Transfered'){
                            var bill_no  = 'Transfered';
                        }else if(purchase_id == 'Opening Balance'){
                            var bill_no  = purchase_id;
                        }else{
                            var bill_no  = "<a target='_blank'  href='../purchases/"+ purchase_id +"/edit' style='font-weight:bold; color:#38BC1C'> Bill No: "+ purchase_id + "</a>";
                        }
                        
                    }
                    

                if(status == 1){
                 var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red; font-weight:bold;'>"+payment_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   

                 "</tr>";   
                }else{
                    var tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red; font-weight:bold;'>"+payment_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
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
           if(response['data_previous'] != null){
              len = response['data_previous'].length;
           }
           
           var currentBalance_two = response['total_receiving'];

            if(len > 0){
              for(var i=0; i<len; i++){
                 var id = response['data_previous'][i].id;
                 var date = response['data_previous'][i].date;
                 var new_date = response['data_previous'][i].formatted_date;
                 var bill_no = response['data_previous'][i].bill_no;
                 var purchase_id = response['data_previous'][i].purchase_id;
                 var debit_two = response['data_previous'][i].debit;
                 var payment_note = response['data_previous'][i].payment_note;
                 var checked_amount = response['data_previous'][i].checked_amount;
                 var receive_voucher_id = response['data_previous'][i].receive_voucher_id;
                 var payment_voucher_id = response['data_previous'][i].payment_voucher_id;
                 
                 if(receive_voucher_id == null){
                     var receive_voucher_id = '';
                 }
                 
                 if(checked_amount == null){
                     var checked_amount = '';
                 }
                 
                 if(payment_note == null){
                     var payment_note = '';
                 }
                 var status = response['data_previous'][i].status;
                    var isRow=1;
                    if(bill_no != null){
                        var bill_no = "Bill No: <a style='color:blue; font-weight:bold;' target='_blank' href='http://localhost/cd/pos/sales/"+response['data_previous'][i].bill_no+"/viewinvoice'>"+response['data_previous'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = "<a target='_blank'  href='../supplier-voucher-edit/"+ payment_voucher_id +"' style='font-weight:bold; color:blue'>Payments </a>";
                    }
                
                    if(debit_two != null){
                        currentBalance_two = currentBalance_two - debit_two; 
                         var debit_two = response['data_previous'][i].debit;
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
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   
                 "</tr>";   
                }else{
                    tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'>" + bill_no + "</td>" +
                   "<td class='receipt' align='center'>" + debit_two + "</td>" +
                   "<td class='balance' align='center'>" + currentBalance_two + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   
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
         url: 'getSupplierRecords_by_date/'+id+'/'+date,
         type: 'get',
         dataType: 'json',
         success: function(response){

           var len = 0;
           $('#userTable tbody').empty(); // Empty <tbody>
           if(response['data_previous'] != null){
              len = response['data_previous'].length;
           }
           console.log(response['data_previous']);
           var currentBalance = response['current_balance'];

           if(len > 0){
              for(var i=0; i<len; i++){
                 var id = response['data_previous'][i].id;
                 var date = response['data_previous'][i].date;
                 var new_date = response['data_previous'][i].formatted_date;
                 var bill_no = response['data_previous'][i].bill_no;
                 var purchase_id = response['data_previous'][i].purchase_id;
                 var credit = response['data_previous'][i].credit;
                 var debit = response['data_previous'][i].debit;
                 var payment_note = response['data_previous'][i].payment_note;
                 var checked_amount = response['data_previous'][i].checked_amount;
                 var receive_voucher_id = response['data_previous'][i].receive_voucher_id;
                 var payment_voucher_id = response['data_previous'][i].payment_voucher_id;
                 
                 if(receive_voucher_id == null){
                     var receive_voucher_id = '';
                 }
                 if(checked_amount == null){
                     var checked_amount = '';
                 }
                 
                 if(payment_note == null){
                     var payment_note = '';
                 }
                 var status = response['data_previous'][i].status;
                
                    if(bill_no != null){
                        var bill_no = "Bill No: <a target='_blank' style='color:blue; font-weight:bold;' href='http://localhost/cd/pos/sales/"+response['data_previous'][i].bill_no+"/viewinvoice'>"+response['data_previous'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = "<a target='_blank'  href='../supplier-voucher-edit/"+ payment_voucher_id +"' style='font-weight:bold; color:blue'>Payments </a>";
                    }
                
                    if(debit != null){
                        currentBalance = debit + currentBalance; 
                         var debit = response['data_previous'][i].debit;
                         var credit = 0.00
                    }else{
                        currentBalance = currentBalance - credit; 
                        var credit = response['data_previous'][i].credit;
                        var debit = 0.00;
                        if(purchase_id == 'Transfered'){
                            var bill_no  = purchase_id;
                        }else{
                            var bill_no  = 'Receipt';
                        }
                        
                    }
                    

                if(status == 1){
                 var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red; font-weight:bold;'>"+payment_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a onClick='openSolution();' id='"+ (i+1) +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='color:red; font-weight:bold;'>"+checked_amount+"</span> <a style='' target='_blank' href='edit_opening_balance/"+ id +"'> <i class='fa fa-edit' aria-hidden='true'></i></a></td>" +
                   
                   
                 "</tr>";   
                }else{
                    var tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red; font-weight:bold;'>"+payment_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a onClick='openSolution();' id='"+ (i+1) +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='color:red; font-weight:bold;'>"+checked_amount+"</span> <a style='' target='_blank' href='edit_opening_balance/"+ id +"'> <i class='fa fa-edit' aria-hidden='true'></i></a></td>" +
                   
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
           if(response['data_previous'] != null){
              len = response['data_previous'].length;
           }
           
           var currentBalance_two = response['total_receiving'];

            if(len > 0){
              for(var i=0; i<len; i++){
                 var id = response['data_previous'][i].id;
                 var date = response['data_previous'][i].date;
                 var new_date = response['data_previous'][i].formatted_date;
                 var bill_no = response['data_previous'][i].bill_no;
                 var purchase_id = response['data_previous'][i].purchase_id;
                 var debit_two = response['data_previous'][i].debit;
                 var payment_note = response['data_previous'][i].payment_note;
                 var checked_amount = response['data_previous'][i].checked_amount;
                 var receive_voucher_id = response['data_previous'][i].receive_voucher_id;
                 var payment_voucher_id = response['data_previous'][i].payment_voucher_id;
                 
                 if(receive_voucher_id == null){
                     var receive_voucher_id = '';
                 }
                 
                 if(checked_amount == null){
                     var checked_amount = '';
                 }
                 
                 if(payment_note == null){
                     var payment_note = '';
                 }
                 var status = response['data_previous'][i].status;
                    var isRow=1;
                    if(bill_no != null){
                        var bill_no = "Bill No: <a target='_blank' style='color:blue; font-weight:bold;' href='http://localhost/cd/pos/sales/"+response['data_previous'][i].bill_no+"/viewinvoice'>"+response['data_previous'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = "<a target='_blank'  href='../supplier-voucher-edit/"+ payment_voucher_id +"' style='font-weight:bold; color:blue'>Payments </a>";
                    }
                
                    if(debit_two != null){
                        currentBalance_two = currentBalance_two - debit_two; 
                         var debit_two = response['data_previous'][i].debit;
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
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   
                 "</tr>";   
                }else{
                    tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td align='center'>" + bill_no + "</td>" +
                   "<td class='receipt' align='center'>" + debit_two + "</td>" +
                   "<td class='balance' align='center'>" + currentBalance_two + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   
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
         url: 'getSupplierRecords_by_todate/'+id+'/'+to_date+'/'+from_date,
         type: 'get',
         dataType: 'json',
         success: function(response){

           var len = 0;
           $('#userTable tbody').empty(); // Empty <tbody>
           if(response['data_previous'] != null){
              len = response['data_previous'].length;
           }
           console.log(response['data_previous']);
           var currentBalance = response['current_balance'];

           if(len > 0){
              for(var i=0; i<len; i++){
                 var id = response['data_previous'][i].id;
                 var date = response['data_previous'][i].date;
                 var new_date = response['data_previous'][i].formatted_date;
                 var bill_no = response['data_previous'][i].bill_no;
                 var purchase_id = response['data_previous'][i].purchase_id;
                 var credit = response['data_previous'][i].credit;
                 var debit = response['data_previous'][i].debit;
                 var payment_note = response['data_previous'][i].payment_note;
                 var checked_amount = response['data_previous'][i].checked_amount;
                 var receive_voucher_id = response['data_previous'][i].receive_voucher_id;
                 var payment_voucher_id = response['data_previous'][i].payment_voucher_id;
                 
                 if(receive_voucher_id == null){
                     var receive_voucher_id = '';
                 }
                 
                 if(checked_amount == null){
                     var checked_amount = '';
                 }
                 
                 if(payment_note == null){
                     var payment_note = '';
                 }
                 var status = response['data_previous'][i].status;
                
                    if(bill_no != null){
                        var bill_no = "Bill No: <a target='_blank' style='color:blue; font-weight:bold;' href='http://localhost/cd/pos/sales/"+response['data_previous'][i].bill_no+"/viewinvoice'>"+response['data_previous'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = "<a target='_blank'  href='../supplier-voucher-edit/"+ payment_voucher_id +"' style='font-weight:bold; color:blue'>Payments </a>";
                    }
                
                    if(debit != null){
                        currentBalance = debit + currentBalance; 
                         var debit = response['data_previous'][i].debit;
                         var credit = 0.00
                    }else{
                        currentBalance = currentBalance - credit; 
                        var credit = response['data_previous'][i].credit;
                        var debit = 0.00;
                        if(purchase_id == 'Transfered'){
                            var bill_no  = purchase_id;
                        }else{
                            var bill_no  = 'Receipt';    
                        }
                        
                    }
                    

                if(status == 1){
                 var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red; font-weight:bold;'>"+payment_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a onClick='openSolution();' id='"+ (i+1) +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='color:red; font-weight:bold;'>"+checked_amount+"</span>  <a style='' target='_blank' href='edit_opening_balance/"+ id +"'> <i class='fa fa-edit' aria-hidden='true'></i></a></td>" +
                   
                   
                 "</tr>";   
                }else{
                    var tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red; font-weight:bold;'>"+payment_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a onClick='openSolution();' id='"+ (i+1) +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='color:red; font-weight:bold;'>"+checked_amount+"</span> <a style='' target='_blank' href='edit_opening_balance/"+ id +"'> <i class='fa fa-edit' aria-hidden='true'></i></a></td>" +
                   
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
           if(response['data_previous'] != null){
              len = response['data_previous'].length;
           }
           
           var currentBalance_two = response['total_receiving'];

            if(len > 0){
              for(var i=0; i<len; i++){
                 var id = response['data_previous'][i].id;
                 var date = response['data_previous'][i].date;
                 var new_date = response['data_previous'][i].formatted_date;
                 var bill_no = response['data_previous'][i].bill_no;
                 var purchase_id = response['data_previous'][i].purchase_id;
                 var debit_two = response['data_previous'][i].debit;
                 var payment_note = response['data_previous'][i].payment_note;
                 var checked_amount = response['data_previous'][i].checked_amount;
                 var receive_voucher_id = response['data_previous'][i].receive_voucher_id;
                 var payment_voucher_id = response['data_previous'][i].payment_voucher_id;
                 
                 
                 if(receive_voucher_id == null){
                     var receive_voucher_id = '';
                 }
                 
                 if(checked_amount == null){
                     var checked_amount = '';
                 }
                 
                 if(payment_note == null){
                     var payment_note = '';
                 }
                 var status = response['data_previous'][i].status;
                    var isRow=1;
                    if(bill_no != null){
                        var bill_no = "Bill No: <a style='color:blue; font-weight:bold;' target='_blank' href='http://localhost/cd/pos/sales/"+response['data_previous'][i].bill_no+"/viewinvoice'>"+response['data_previous'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = "<a target='_blank'  href='../supplier-voucher-edit/"+ payment_voucher_id +"' style='font-weight:bold; color:blue'>Payments </a>";
                    }
                
                    if(debit_two != null){
                        currentBalance_two = currentBalance_two - debit_two; 
                         var debit_two = response['data_previous'][i].debit;
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
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   
                 "</tr>";   
                }else{
                    tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   
                   "<td align='center'>" + bill_no + "</td>" +
                   "<td class='receipt' align='center'>" + debit_two + "</td>" +
                   "<td class='balance' align='center'>" + currentBalance_two + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   
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
     
     
     
  
$('select[name="account_id_latest"]').on('change', function() {
    var id = 'payment_'+$(this).val();
    
    $.ajax( {
                type:'GET',
                header:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                url:"http://localhost/cd/pos/sales/"+id+"/supplier_balance",
            })
            .done(function(data) {
                //var balance = jQuery.parseJSON(data);
                $('#customerBalance').text(data[0]);
                $('#customerUrduName').text(data[1]);
                $('#customer_last_date').text(data[2]);
                $('#total_receiving').text(data[3]);
                $('#currentBalance').text(data);
                var currentBalance = data;
            })
            .fail(function() {
               // alert("error");
                });
        //$.get('getcustomergroup/' + id, function(data) {
          //  customer_group_rate = (data / 100);
        //});
    
    fetchRecords_latest(id);
    
    
    
});


        $('#from_date_latest_latest').change(function() {
            var date = $(this).val();
            var id = $('#account_id_latest').val();
            fetchRecords_latest_by_date(id, date);
        });
        
         function openSolution() {
                $('#solTitle a').click(function(evt) {
                    evt.preventDefault();
                    //var divId = 'summary' + $(this).attr('id');
                    var divId = $(this).attr('id');
                    //document.getElementById(divId).className = '';
                    var current_balance = $(this).attr('class');
                   $.ajax({
                         url: 'getSupplierRecords_tick/'+divId+'/'+current_balance,
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
                                   fetchRecords_latest(response.id);
                              }, 3000); 
                           }
                         }
                         
                         
                    });

                });
            }
            
         
        
        $('#to_date_latest').change(function() {
            var to_date_latest = $(this).val();
            var id = $('#account_id_latest').val();
            var from_date_latest = $('#from_date_latest').val();
            fetchRecords_latest_by_todate(id, to_date_latest, from_date_latest);
        });

       

     function fetchRecords_latest(id){
       $.ajax({
         url: 'getSupplierRecords/'+id,
         type: 'get',
         dataType: 'json',
         success: function(response){

            $('#supplier_new_table').html(response.supplier_table);
            $('#supplier_total_balance').html(response.total_balance);

           var len = 0;
           $('#userTable_latest tbody').empty(); // Empty <tbody>
           if(response['data'] != null){
              len = response['data'].length;
           }
           
           var currentBalance = 0;

           if(len > 0){
              for(var i=0; i<len; i++){
                 var id = response['data'][i].id;
                 var date = response['data'][i].date;
                 var new_date = response['data'][i].formatted_date;
                 var bill_no = response['data'][i].bill_no;
                 
                 let purchase_id = response['data'][i].purchase_id; 
                 
                 var credit = response['data'][i].credit;
                 var debit = response['data'][i].debit;
                 var payment_note = response['data'][i].payment_note;
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
                 var status = response['data'][i].status;
                
                    if(purchase_id == 'Transfered'){
                            var bill_no  = 'Transfered';
                    }else if(bill_no != null){
                        var bill_no = "Bill No: <a style='color:blue; font-weight:bold;' target='_blank' href='http://localhost/cd/pos/sales/"+response['data'][i].bill_no+"/viewinvoice'>"+response['data'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = "<a target='_blank'  href='../supplier-voucher-edit/"+ payment_voucher_id +"' style='font-weight:bold; color:blue'>Payments </a>";
                    }
                
                    if(debit != null){
                        currentBalance = currentBalance - debit; 
                         var debit = response['data'][i].debit;
                         var credit = 0.00
                    }else{
                        currentBalance = credit + currentBalance; 
                        var credit = response['data'][i].credit;
                        var debit = 0.00;
                        if(purchase_id == 'Transfered'){
                            var bill_no  = 'Transfered';
                        }else if(purchase_id == 'Opening Balance'){
                            var bill_no  = purchase_id;
                        }else{
                            var bill_no  = "<a target='_blank'  href='../purchases/"+ purchase_id +"/edit' style='font-weight:bold; color:#38BC1C'> Bill No: "+ purchase_id + "</a>";
                        }
                        
                    }
                    

                if(status == 1){
                 var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red; font-weight:bold;'>"+payment_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   

                 "</tr>";   
                }else{
                    var tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red; font-weight:bold;'>"+payment_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                 "</tr>";
                }
                 

                 $("#userTable_latest tbody").append(tr_str);
              }
           }else{
              var tr_str = "<tr>" +
                  "<td align='center' colspan='4'>No record found.</td>" +
              "</tr>";

              $("#userTable_latest tbody").append(tr_str);
           }
           
           
           var len = 0;
           $('#userTable_latest_two tbody').empty(); // Empty <tbody>
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
                 var purchase_id = response['data'][i].purchase_id;
                 var debit_two = response['data'][i].debit;
                 var payment_note = response['data'][i].payment_note;
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
                 var status = response['data'][i].status;
                    var isRow=1;
                    if(bill_no != null){
                        var bill_no = "Bill No: <a style='color:blue; font-weight:bold;' target='_blank' href='http://localhost/cd/pos/sales/"+response['data'][i].bill_no+"/viewinvoice'>"+response['data'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = "<a target='_blank'  href='../supplier-voucher-edit/"+ payment_voucher_id +"' style='font-weight:bold; color:blue'>Payments </a>";
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
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   
                 "</tr>";   
                }else{
                    tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'>" + bill_no + "</td>" +
                   "<td class='receipt' align='center'>" + debit_two + "</td>" +
                   "<td class='balance' align='center'>" + currentBalance_two + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   
                 "</tr>";
                }
    }            

                 $("#userTable_latest_two tbody").append(tr_str);
              }
           }else{
              var tr_str = "<tr>" +
                  "<td align='center' colspan='4'>No record found.</td>" +
              "</tr>";

              $("#userTable_latest_two tbody").append(tr_str);
           }
           
           

         }
       });
     }
     
     function fetchRecords_latest_by_date(id, date){
       $.ajax({
         url: 'getSupplierRecords_by_date/'+id+'/'+date,
         type: 'get',
         dataType: 'json',
         success: function(response){

           var len = 0;
           $('#userTable_latest tbody').empty(); // Empty <tbody>
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
                 var purchase_id = response['data'][i].purchase_id;
                 var credit = response['data'][i].credit;
                 var debit = response['data'][i].debit;
                 var payment_note = response['data'][i].payment_note;
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
                 var status = response['data'][i].status;
                
                    if(bill_no != null){
                        var bill_no = "Bill No: <a target='_blank' style='color:blue; font-weight:bold;' href='http://localhost/cd/pos/sales/"+response['data'][i].bill_no+"/viewinvoice'>"+response['data'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = "<a target='_blank'  href='../supplier-voucher-edit/"+ payment_voucher_id +"' style='font-weight:bold; color:blue'>Payments </a>";
                    }
                
                    if(debit != null){
                        currentBalance = debit + currentBalance; 
                         var debit = response['data'][i].debit;
                         var credit = 0.00
                    }else{
                        currentBalance = currentBalance - credit; 
                        var credit = response['data'][i].credit;
                        var debit = 0.00;
                        if(purchase_id == 'Transfered'){
                            var bill_no  = purchase_id;
                        }else{
                            var bill_no  = 'Receipt';
                        }
                        
                    }
                    

                if(status == 1){
                 var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red; font-weight:bold;'>"+payment_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a onClick='openSolution();' id='"+ (i+1) +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='color:red; font-weight:bold;'>"+checked_amount+"</span> <a style='' target='_blank' href='edit_opening_balance/"+ id +"'> <i class='fa fa-edit' aria-hidden='true'></i></a></td>" +
                   
                   
                 "</tr>";   
                }else{
                    var tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red; font-weight:bold;'>"+payment_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a onClick='openSolution();' id='"+ (i+1) +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='color:red; font-weight:bold;'>"+checked_amount+"</span> <a style='' target='_blank' href='edit_opening_balance/"+ id +"'> <i class='fa fa-edit' aria-hidden='true'></i></a></td>" +
                   
                 "</tr>";
                }
                 

                 $("#userTable_latest tbody").append(tr_str);
              }
           }else{
              var tr_str = "<tr>" +
                  "<td align='center' colspan='4'>No record found.</td>" +
              "</tr>";

              $("#userTable_latest tbody").append(tr_str);
           }
           
           
           var len = 0;
           $('#userTable_latest_two tbody').empty(); // Empty <tbody>
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
                 var purchase_id = response['data'][i].purchase_id;
                 var debit_two = response['data'][i].debit;
                 var payment_note = response['data'][i].payment_note;
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
                 var status = response['data'][i].status;
                    var isRow=1;
                    if(bill_no != null){
                        var bill_no = "Bill No: <a target='_blank' style='color:blue; font-weight:bold;' href='http://localhost/cd/pos/sales/"+response['data'][i].bill_no+"/viewinvoice'>"+response['data'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = "<a target='_blank'  href='../supplier-voucher-edit/"+ payment_voucher_id +"' style='font-weight:bold; color:blue'>Payments </a>";
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
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   
                 "</tr>";   
                }else{
                    tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td align='center'>" + bill_no + "</td>" +
                   "<td class='receipt' align='center'>" + debit_two + "</td>" +
                   "<td class='balance' align='center'>" + currentBalance_two + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   
                 "</tr>";
                }
    }            

                 $("#userTable_latest_two tbody").append(tr_str);
              }
           }else{
              var tr_str = "<tr>" +
                  "<td align='center' colspan='4'>No record found.</td>" +
              "</tr>";

              $("#userTable_latest_two tbody").append(tr_str);
           }
           
           

         }
       });
     }
     
      function fetchRecords_latest_by_todate(id, to_date_latest, from_date_latest){
       $.ajax({
         url: 'getSupplierRecords_by_todate/'+id+'/'+to_date_latest+'/'+from_date_latest,
         type: 'get',
         dataType: 'json',
         success: function(response){

           var len = 0;
           $('#userTable_latest tbody').empty(); // Empty <tbody>
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
                 var purchase_id = response['data'][i].purchase_id;
                 var credit = response['data'][i].credit;
                 var debit = response['data'][i].debit;
                 var payment_note = response['data'][i].payment_note;
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
                 var status = response['data'][i].status;
                
                    if(bill_no != null){
                        var bill_no = "Bill No: <a target='_blank' style='color:blue; font-weight:bold;' href='http://localhost/cd/pos/sales/"+response['data'][i].bill_no+"/viewinvoice'>"+response['data'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = "<a target='_blank'  href='../supplier-voucher-edit/"+ payment_voucher_id +"' style='font-weight:bold; color:blue'>Payments </a>";
                    }
                
                    if(debit != null){
                        currentBalance = debit + currentBalance; 
                         var debit = response['data'][i].debit;
                         var credit = 0.00
                    }else{
                        currentBalance = currentBalance - credit; 
                        var credit = response['data'][i].credit;
                        var debit = 0.00;
                        if(purchase_id == 'Transfered'){
                            var bill_no  = purchase_id;
                        }else{
                            var bill_no  = 'Receipt';    
                        }
                        
                    }
                    

                if(status == 1){
                 var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red; font-weight:bold;'>"+payment_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a onClick='openSolution();' id='"+ (i+1) +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='color:red; font-weight:bold;'>"+checked_amount+"</span>  <a style='' target='_blank' href='edit_opening_balance/"+ id +"'> <i class='fa fa-edit' aria-hidden='true'></i></a></td>" +
                   
                   
                 "</tr>";   
                }else{
                    var tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   "<td align='center'> &nbsp; </td>" +
                   "<td style='width:250px;' align='center'>" + bill_no + "<span style='color:red; font-weight:bold;'>"+payment_note+"</span></td>" +
                   "<td class='receipt' align='center'>" + debit + "</td>" +
                   "<td class='payment' align='center'>" + credit +  "</td>" +
                   "<td class='balance' align='center'>" + currentBalance + "</td>" +
                   "<td align='center'><div id='solTitle'><a onClick='openSolution();' id='"+ (i+1) +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='color:red; font-weight:bold;'>"+checked_amount+"</span> <a style='' target='_blank' href='edit_opening_balance/"+ id +"'> <i class='fa fa-edit' aria-hidden='true'></i></a></td>" +
                   
                 "</tr>";
                }
                 

                 $("#userTable_latest tbody").append(tr_str);
              }
           }else{
              var tr_str = "<tr>" +
                  "<td align='center' colspan='4'>No record found.</td>" +
              "</tr>";

              $("#userTable_latest tbody").append(tr_str);
           }
           
           
           var len = 0;
           $('#userTable_latest_two tbody').empty(); // Empty <tbody>
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
                 var purchase_id = response['data'][i].purchase_id;
                 var debit_two = response['data'][i].debit;
                 var payment_note = response['data'][i].payment_note;
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
                 var status = response['data'][i].status;
                    var isRow=1;
                    if(bill_no != null){
                        var bill_no = "Bill No: <a style='color:blue; font-weight:bold;' target='_blank' href='http://localhost/cd/pos/sales/"+response['data'][i].bill_no+"/viewinvoice'>"+response['data'][i].bill_no+'</a>';
                    }else{
                       var bill_no  = "<a target='_blank'  href='../supplier-voucher-edit/"+ payment_voucher_id +"' style='font-weight:bold; color:blue'>Payments </a>";
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
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   
                 "</tr>";   
                }else{
                    tr_str = "<tr id='refinement-menu'>" +
                   "<td align='center'>" + (i+1) + "</td>" +
                   "<td align='center'>" + new_date + "</td>" +
                   
                   "<td align='center'>" + bill_no + "</td>" +
                   "<td class='receipt' align='center'>" + debit_two + "</td>" +
                   "<td class='balance' align='center'>" + currentBalance_two + "</td>" +
                   "<td align='center'><div id='solTitle'><a class='"+currentBalance+"' onClick='openSolution();' id='"+ id +"'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; color:red; font-weight:bold;'>"+checked_amount+"</span></td>" +
                   
                 "</tr>";
                }
    }            

                 $("#userTable_latest_two tbody").append(tr_str);
              }
           }else{
              var tr_str = "<tr>" +
                  "<td align='center' colspan='4'>No record found.</td>" +
              "</tr>";

              $("#userTable_latest_two tbody").append(tr_str);
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
           var divToPrint=document.getElementById("printTable_previous");
           newWin= window.open("");
           newWin.document.write(divToPrint.outerHTML);
           newWin.print();
           newWin.close();
        }
        
        
        
function printData_latest()
        {
           var divToPrint=document.getElementById("printTable_latest");
           newWin= window.open("");
           newWin.document.write(divToPrint.outerHTML);
           newWin.print();
           newWin.close();
        }
        
        
        
        $('#take_print').on('click',function(){
        printData();
        
        })
        
        $('#take_print_latest').on('click',function(){
        printData_latest();
        
        })
        
        
       
    
    
    
</script>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>