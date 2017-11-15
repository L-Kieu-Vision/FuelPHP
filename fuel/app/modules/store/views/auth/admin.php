<main>
<div class="wrapper">
    <div class="main-container">
        <div class="header-container">
            <div class="header-title-container">
                <label class="">Hello!</label>
                <label class="pull-right">Modules Store</label>
            </div>
        </div>
        <div class="body-container admin-container">
            <div class="search-block">
                <form class="form-horizontal" action="" method="POST">
                    <div class="form-group">
                        <label class="control-label col-sm-1">Emp ID</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="" name="sEmp" id="sEmp" placeholder="Emp ID">
                        </div>

                        <label class="control-label col-sm-1">Name</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="" id="sName" name="sName" placeholder="Name">
                        </div>
                        <label class="control-label col-sm-1">Email</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="" id="sEmail" name="sEmail" placeholder="Email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-1">Phone</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="" id="sPhone" name="sPhone" placeholder="Phone">
                        </div>
                        <label class="control-label col-sm-1">Department</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="sDepar" id="sDepars">
                                
                            </select>
                        </div>
                        <label class="control-label col-sm-1">Created</label>
                        <div class="col-md-2">
                            <input type="text" name="datefrom" value="" class="form-control" id="datepicker" placeholder="From">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="dateto" value="" class="form-control" id="datepickerto" placeholder="To">
                        </div>
                    </div>
                </form>
            </div>
            <div class="datatable-block">
                <div class="statistic-datatable">
                    <div class="col-md-6" style="margin-top:10px;margin-bottom:10px;text-align:left;padding-left: 0px;">
                        <button type="buton" id="btn_search" name="btn_search" class="btn btn-success">Search</button>
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
                            <tbody id="listuser">
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="statistic-datatable-footer">
                        <div class="datatable-pagination text-center">
                            <ul class="pagination" id="pagination">
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
                                <option value=""></option>
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
                <form name="form_admin_add" action="" method="POST">
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
                                <option value=""></option>
                        </select>
                    </div>
                    <button type="button" id="buton_admin_add" class="btn btn-primary">Add User</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div><!-- End ADD Modal -->
