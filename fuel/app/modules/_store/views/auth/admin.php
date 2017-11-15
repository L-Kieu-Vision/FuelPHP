
<div class="wrapper">
    <div class="main-container">
        <div class="header-container">
            <div class="header-title-container">
                <label class="">Hello! <?php echo (Auth::check())? Auth::get('name') : '';?></label>
                <label class="pull-right">Store</label>
            </div>
        </div>
        <div class="body-container admin-container">
            <div class="search-block">
                <form class="form-horizontal" action="<?php echo Uri::create('store/home/index');?>" method="GET">
                    <div class="form-group">
                        <label class="control-label col-sm-1">Emp ID</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="<?php echo !empty(Input::get('sEmp')) ? Input::get('sEmp') :''?>" name="sEmp" id="sEmp" placeholder="Emp ID">
                        </div>

                        <label class="control-label col-sm-1">Name</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="<?php echo !empty(Input::get('sName')) ? Input::get('sName') :''?>" id="sName" name="sName" placeholder="Name">
                        </div>
                        <label class="control-label col-sm-1">Email</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="<?php echo !empty(Input::get('sEmail')) ? Input::get('sEmail') :''?>" id="sEmail" name="sEmail" placeholder="Email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-1">Phone</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="<?php echo !empty(Input::get('sPhone')) ? Input::get('sPhone') :''?>" id="sPhone" name="sPhone" placeholder="Phone">
                        </div>
                        <label class="control-label col-sm-1">Department</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="sDepar" id="sDepars">
                                <?php foreach($department as $depar):?>
                                    <option value="<?php echo $depar['department_code'];?>" <?php echo ($depar['department_code'] == Input::get('sDepar')) ? 'selected': ''?>><?php echo $depar['department_name']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <label class="control-label col-sm-1">Created</label>
                        <div class="col-md-2">
                            <input type="text" name="datefrom" value="<?php echo !empty(Input::get('datefrom')) ? Input::get('datefrom') :''?>" class="form-control" id="datepicker" placeholder="From">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="dateto" value="<?php echo !empty(Input::get('dateto')) ? Input::get('dateto') :''?>" class="form-control" id="datepickerto" placeholder="To">
                        </div>
                    </div>
            </div>
            <div class="datatable-block">
                <div class="statistic-datatable">
                    <div class="col-md-6" style="margin-top:10px;margin-bottom:10px;text-align:left;padding-left: 0px;">
                        <button type="submit" class="btn btn-success">Search</button>
                    </form>
                        <button class="btn btn-warning" type="button" data-toggle="modal" data-target="#admin-add-new">Add New</button>
                        <button type="submit" id="btn_delete" class="btn btn-danger">Delete</button>
                    </div>
                    <div class="col-md-6" style="margin-top:10px;margin-bottom:10px;text-align:right">
                        <div class="statistic-datatable-header">
                            <div class="datatable-button">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default">10件</button>
                                    <button type="button" class="btn btn-default">20件</button>
                                    <button type="button" class="btn btn-default">50件</button>
                                    <button type="button" class="btn btn-default">100件</button>
                                    <button type="button" class="btn btn-default">ALL</button>
                                </div>
                            </div>
                            <div class="datatable-info">
                                <span>00</span> 件 / <span>00</span> <span>件</span>
                            </div>
                        </div>
                    </div>
                    <div class="statistic-datatable-body">
                        <table class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th class="col-sm-1"><input type="checkbox"></th>
                                    <th class="col-sm-1">Created</th>
                                    <th class="col-sm-1">Emp ID</th>
                                    <th class="col-sm-2">Name</th>
                                    <th class="col-sm-1">Email</th>
                                    <th class="col-sm-2">Phone</th>
                                    <th class="col-sm-2">Department</th>
                                    <th class="col-sm-1">EDIT</th>
                                    <th class="col-sm-1">Reset Password</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($users):?>
                                    <?php foreach($users as $user):?>
                                        <tr id="<?php echo $user['id']?>">
                                            <td class="col-sm-1"><input type="checkbox" name="user_id[]" id="user_id" class="delete_customer" value="<?php echo $user['id']?>" /></td>
                                            <td class="col-sm-1"><?php echo Date::time_ago(strtotime($user['create_time']));?></td>
                                            <td class="col-sm-1"><?php echo $user['employee_id']?></td>
                                            <td class="col-sm-1"><?php echo $user['name']?></td>
                                            <td class="col-sm-1"><?php echo $user['email']?></td>
                                            <td class="col-sm-1"><?php echo $user['phone_num']?></td>
                                            <td class="col-sm-1"><?php echo $user['department_name']?></td>
                                            <td class="col-sm-1">
                                                <button type="button" class="btn btn-xs btn-info edit_data" name="edit" id="<?php echo $user['id']?>" data-toggle="modal"  data-target="#admin-detail-modal">EDIT</button>
                                            </td>
                                            <td class="col-sm-1">
                                                <button type="button" class="btn btn-xs btn-info" name="reset_password" id="<?php echo $user['id'];?>" data-toggle="modal" data-target="#admin-detail-reset-password">Reset Password</button>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php else:?>
                                        <tr><td colspan="9">No data available</td></tr>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="statistic-datatable-footer">
                        <div class="datatable-pagination">
                            <ul class="pagination">

                                <?php for($i = 1; $i <= $page_info['total_page']; $i++) : ?>
                                        <?php if ($i == $page_info['current_page']): ?>
                                            <li class="active"><a href="<?php echo Uri::create('store/home/index/'.$i);?>"><?php echo $i;?></a></li>
                                        <?php else:?>
                                            <li><a href="<?php echo Uri::create('store/home/index/'.$i);?>"><?php echo $i;?></a></li>
                                        <?php endif;?>
                                <?php endfor;?>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
<!-- Edit Modal -->
<div id="admin-detail-modal" class="modal fade admin-detail-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit User</h4>
            </div>
            <div class="modal-body">
                <form name="form_admin_edit" action="#" method="POST">
                    <input type="hidden" id="edit_user_id" name="edit_user_id">
                    <div class="alert alert-danger hide"></div>
                    <div class="alert alert-success hide"></div>
                    <div class="form-group">
                        <label for="empid">Emp ID</label>
                        <input type="text" class="form-control" id="empid1" name="empid1" value="" placeholder="Emp ID">
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name1" name="name1" value="" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email1" name="email1" value="" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone1" name="phone1" value="" placeholder="Phone">
                    </div>
                    <div class="form-group">
                        <label for="department1">Department</label>
                        <select class="form-control" id="department1" name="department1">
                            <?php foreach($department as $depar):?>
                                <option value="<?php echo $depar['department_code'];?>"><?php echo $depar['department_name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <button type="button" id="buton_admin_edit" class="btn btn-primary">Edit User</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div><!-- End Edit Modal -->
<!-- ADD Modal -->
<div id="admin-add-new" class="modal fade admin-detail-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add User</h4>
            </div>
            <div class="modal-body">
                <form name="form_admin_add" action="<?php echo Uri::create('store/home/add')?>" method="POST">
                    <div class="alert alert-danger hide"></div>
                    <div class="alert alert-success hide"></div>
                    <div class="form-group">
                        <label for="empid">Emp ID</label>
                        <input type="text" class="form-control" id="empid" name="empid" placeholder="Emp ID">
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    </div>

                    <div class="form-group">
                        <label for="confirm">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm" name="confirm" placeholder="Confirm Password">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select id="department" name="department" class="form-control">
                            <?php foreach($department as $depar):?>
                                <option value="<?php echo $depar['department_code'];?>"><?php echo $depar['department_name']?></option>
                            <?php endforeach;?>
                            
                        </select>
                    </div>
                    <button type="button" id="buton_admin_add" class="btn btn-primary">Add User</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div><!-- End ADD Modal -->
<div id="admin-detail-reset-password" class="modal fade admin-detail-reset-password" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="exampleInputEmail1">New Password</label>
                        <input type="password" class="form-control" id="exampleInputEmail1" placeholder="New Password">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Confirm New Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Confirm New Password">
                    </div>
                    <button type="submit" class="btn btn-default">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        // Add User
        $("#buton_admin_add").click(function(){
            var empid               = $("input[name='empid']").val();
            var name                = $("input[name='name']").val();
            var email               = $("input[name='email']").val();
            var password            = $("input[name='password']").val();
            var confirm_password    = $("input[name='confirm']").val();
            var phone               = $("input[name='phone']").val();
            var department          = $("#department option:selected").val();
            $.ajax({
                method: "POST",
                url: "<?php echo Uri::create('store/home/add')?>", 
                data: { empid:empid, name:name, email:email, password:password, confirm_password:confirm_password, phone:phone, department:department },
                success: function(data){
                        console.log(data);
                        if(data.status == 200){
                               $('#admin-add-new').modal('hide');

                        } else {
                            var html = '';
                            $.each(data.error, function(key, item){
                                if (key != 'status'){ 
                                    html += '<li>'+item+'</li>';
                                }
                            });
                            $('.alert-danger').html(html).removeClass('hide');
                        }
                    }
                });
        });

        //Show User detail
        $(document).on('click','.edit_data', function() {
            var user_id  = $(this).attr("id");
            $.ajax({
                method: "POST",
                dataType: 'JSON',
                url: "<?php echo Uri::create('store/home/detail')?>", 
                data: { user_id:user_id},
                success: function(data) {
                        console.log(data);
                        $('#edit_user_id').val(data.id);
                        $('#empid1').val(data.employee_id);
                        $('#name1').val(data.name);
                        $('#email1').val(data.email);
                        $('#phone1').val(data.phone_num);
                        $('#department1').val(data.department_code);
                    }
                });
        });
        //Edit user by user_id
        $(document).on('click','#buton_admin_edit', function() {
            var edit_user_id      = $("input[name='edit_user_id']").val();
            var edit_empid        = $("input[name='empid1']").val();
            var edit_name         = $("input[name='name1']").val();
            var edit_email        = $("input[name='email1']").val();
            var edit_phone        = $("input[name='phone1']").val();
            var edit_department   = $("#department1 option:selected").val();
            $.ajax({
                method: "POST",
                url: "<?php echo Uri::create('store/home/edit')?>", 
                data: { user_id:edit_user_id,empid:edit_empid, name:edit_name, email:edit_email, phone:edit_phone, department:edit_department },
                success: function(data){
                        console.log(data);
                        if(data.status == 200){
                            $('#admin-detail-modal').modal('hide');
                        } else {
                            var html = '';
                            $.each(data.error, function(key, item){
                                html += '<li>'+item+'</li>';
                            });
                            $('.alert-danger').html(html).removeClass('hide');
                        }
                    }
                });
        });
        //Delete user and multi delete user
        $('#btn_delete').click(function(){
          
            var id = [];
           
            $(':checkbox:checked').each(function(i){
                id[i] = $(this).val();
            });
            if(id.length === 0) {
                alert("Please Select at least one checkbox");
            } else {
                if (confirm("Are you sure you want to delete this?")) {
                
                    $.ajax({
                            method:'POST',
                            url:'<?php echo Uri::create('store/home/multidelete')?>',
                            data:{id:id},
                            success: function() {
                                for(var i = 0; i < id.length; i++) {
                                    $('tr#'+id[i]+'').css('background-color', '#ccc');
                                    $('tr#'+id[i]+'').fadeOut('slow');
                                }
                            }
                        });
                    } else {
                        $('input:checkbox:checked').prop('checked', false);
                    }
                }
        });

        $('#admin-add-new').on('hidden.bs.modal', function () {
            location.reload();
            $('.alert-danger').addClass('hide');
        });
        $('#admin-detail-modal').on('hidden.bs.modal', function () {
            location.reload();
            $('.alert-danger').addClass('hide');
        });

    });
    function openEdit() {
        // open modal
        //call ajax
        // fill data 
    }
    function openAdd() {
        
        //clear data
        // open modal
    }

</script>