app.controller('userController', ['$scope', '$http', '$window', function ($scope, $http, $window) {
		$scope.currentPage = 1;
		$scope.limit       = {};
		$scope.search      = {};
		$scope.edit        = {};
		$scope.add         = {};
		$scope.order       = {};
		$scope.listproduct = {};

/*-Scope--------Get ListUser--------------------*/

		$scope.getListUsers = function() {
			var method = "POST";
			var url = "api/home/getlist";
			var data = {
						page:$scope.currentPage,
		    			limit:$scope.limit,
		    			sEmp:$scope.sEmp,
		    			sName:$scope.sName,
		    			sEmail:$scope.sEmail,
		    			sPhone:$scope.sPhone,
		    			department:$scope.search.depart,
		    			datefrom:$scope.datefrom,
		    			dateto:$scope.dateto
		    		};
			callApi(method, url, data, function(response){
				$scope.users = response.data.list;
		    	$scope.total_record = response.data.totalrecord;

		    	if (response.data.total_page == 1) {
					$scope.totalspage = new Array();
		    	} else {
					$scope.totalspage = new Array(response.data.total_page);
		    	}
				console.log($scope.totalspage);
				
			});
			
		}
		
/*-Scope--------Pagination--------------------*/		
		$scope.setPagination = function(numrow){
			console.log(numrow);
			$scope.limit = numrow;
	        $scope.getListUsers();
	    };
/*-Scope--------Search User--------------------*/
		$scope.searchUser = function() {
			$scope.getListUsers();
		}
/*-Scope--------Get List Department--------------------*/
		$scope.getListDept = function(){
			$http.post(baseUrl+'api/home/getdeparment').then(function(response){
				$scope.departs = response.data;
			});

		};
/*-Scope--------Get List Product--------------------*/
		$scope.getListProduct = function(){
			$http.post(baseUrl+'api/order/showProduct').then(function(response){
				$scope.products = response.data;
			});

		};
/*-Scope-----------------------Show Order-----------------------------------*/
		$scope.showOrder = function(id) {
			$scope.employee_id = id;
			var method = "POST";
			var url    = "api/order/order";
			var data   = { id:id };
			callApi(method, url, data, function(response){
				$("#order-admin-orderdetail").modal('show');
		    	$scope.orders = response.data;
				// $window.location.href = 'http://localhost/fuel-angular/public/themes/main/html/listorder/index.html';
			});
		}
/*-Scope-----------------------Change Amount-------------------------------------*/		
	    $scope.changeAmount = function(orderIndex , orderDetailIndex){
	    	$scope.getTotalOrderDetail(orderIndex,orderDetailIndex);
			$scope.getTotalOrder(orderIndex);
	    }

/*-Scope------ng-change option => change price => change total orderdetai => change total order--------------------*/
		$scope.changeProducted = function(idProducted , orderIndex , orderDetailIndex) {
			var method = "POST";
			var url    = "api/order/showProducted";
			var data   = { 
							idProducted:idProducted,
							parentindex:orderIndex,
							index:orderDetailIndex 
						};
			callApi(method, url, data, function(response){
				$scope.orders[orderIndex].order_detail[orderDetailIndex].price = response.data[0].price;
				$scope.getTotalOrderDetail(orderIndex,orderDetailIndex);
				$scope.getTotalOrder(orderIndex);	
			});
		}
/*-Scope-----------------------Get Total Order-------------------------------------*/		
		$scope.getTotalOrder = function(indexOrder) {
			var totalOrder  = 0;
			var price  = 0;
			var amount = 0;

			for (var i = 0; i < $scope.orders[indexOrder].order_detail.length; i++) {
				price       = $scope.orders[indexOrder].order_detail[i].price;
				amount      = $scope.orders[indexOrder].order_detail[i].amount;
				totalOrder 	+= price * amount;
			}
			$scope.orders[indexOrder].total = totalOrder;
		}

/*-Scope--------Get Total Order Detail--------------------*/
		$scope.getTotalOrderDetail = function(indexOrder, indexOrderDetail) {
			$scope.orders[indexOrder].order_detail[indexOrderDetail].row_total = $scope.orders[indexOrder].order_detail[indexOrderDetail].amount * $scope.orders[indexOrder].order_detail[indexOrderDetail].price;
		}
/*-Scope--------Update Order And Order Detail--------------------*/		

		$scope.upDateOrder = function() {
			var data = {
							employee_id	: $scope.employee_id,
							orders 		: $scope.orders
						};
			var url = "api/order/updateOrder";
			var method = "POST";
			callApi(method, url, data, function(response) {
				$('#order-admin-orderdetail').modal('hide');
			});
		}
		
/*-Scope--------Add Product In Order--------------------*/

		$scope.addProductToOrder = function(indexOrder) {
			console.log(indexOrder);
			$scope.orders[indexOrder]['order_detail'].push({
                amount: null,
                id_order: null,
                id: null,
                id_product: null,
                name: null,
                price: null,
                del_flg: 0,
            });
		}
/*-Scope--------Delete Product In Order--------------------*/
		$scope.deleteProductInOrder = function (parentIndex, index) {
            if ($scope.orders[parentIndex]['order_detail'].length > 1) {
                $scope.orders[parentIndex]['order_detail'].splice(index, 1);
            }
            $scope.getTotalOrder(parentIndex);
        };
/*-Scope--------Add Order Detail--------------------*/

        $scope.addOrderDetail = function() {
        	$scope.orders.push({
				del_flg: 0,
				employee_id: $scope.employee_id,
				id: null,
				total: 0,
				status: null,
        		order_detail: [{
					amount:null,
					id_order:null,
					id: null,
					id_product:null,
					name:null,
					price:null,
        		}]
        	});
        }
/*-Scope--------Delete Order Detail--------------------*/
        $scope.deleteOrderDetail = function ($index) {
            if ($scope.orders.length > 1) {
                $scope.orders.splice($index, 1);
            }
        };

        $scope.edit.errors = [];
        $scope.add.errors = [];
/*-Scope--------Set Page--------------------*/
		$scope.setPage = function(page){
			$scope.currentPage = page;
	        $scope.getListUsers();
	    };
/*-Scope--------Show Modal Add User--------------------*/
	    $scope.showAddUser = function() {
	    	$("#admin-add-new").modal('show');

	    	$scope.userAdd = {};
	    	$('.alert-danger').html("").addClass('hide');
	    }
/*-Scope--------Add User--------------------*/
		$scope.addUser = function() {
			var data = {
							empid:$scope.userAdd.empid,
				    		name:$scope.userAdd.name,
				    		email:$scope.userAdd.email,
				    		password:$scope.userAdd.password,
				    		confirm:$scope.userAdd.confirm,
				    		phone:$scope.userAdd.phone,
				    		department:$scope.add.depart
						};
			var method = "POST";
			var url = "api/home/add";
			callApi(method, url, data, function(response) {
				if(response.data.status == 200){
                    console.log(response);
					$scope.getListUsers();
					$("#admin-add-new").modal('hide');
                } else {
                	$scope.errors = response.data.error;
                	var html = '';
                    $.each($scope.errors, function(key, item){
                        html += '<li>'+item+'</li>';
                    });
                    $('.alert-danger').html(html).removeClass('hide');

                }
			});
		}
		
/*-Scope--------Show Detail User--------------------*/
		$scope.showDetail = function(id) {
			var method = "POST";
			var url    = "api/home/detail";
			var data   = { id:id };
			callApi(method, url, data, function(response) {
				console.log(response);
				$("#admin-detail-modal").modal('show');
				$('.alert-danger').html("").addClass('hide');
		        $scope.userEdit = response.data;
			});
		}
		
/*-Scope--------Update User--------------------*/
		$scope.updateUser = function(id) {
			var data = {
							id:id,
							empid:$scope.userEdit.employee_id,
							name:$scope.userEdit.name,
							email:$scope.userEdit.email,
							phone:$scope.userEdit.phone_num,
							department:$scope.edit.depart
						};
			var method = "POST";
			var url    = "api/home/edit";
			callApi(method, url, data, function(response) {
				if(response.data.status == 200){
                    console.log(response);
					$scope.getListUsers();
					$("#admin-detail-modal").modal('hide');
                } else {
                	$scope.errors = response.data.error;
                	var html = '';
                    $.each($scope.errors, function(key, item){
                        html += '<li>'+item+'</li>';
                    });
                    $('.alert-danger').html(html).removeClass('hide');

                }
				
			});	
		}
//Function lay id cua checkbox duoc chon
	    var arr = [];
	    $scope.getIndex = function(id, isTrue) {
	    	
	    	console.log(id);
	    	if (isTrue) 
                arr.push(id);
            else {
                var index = arr.indexOf(id);
                arr.splice(index, 1);
            }
            console.log(arr);
	    }

	    $scope.deleteUser = function(isMaster) {
	    	if (isMaster) {
	    		//Xoa tat ca user tai day
	    		console.log(isMaster);
	    		var data = {
			    			sEmp:$scope.sEmp,
			    			sName:$scope.sName,
			    			sEmail:$scope.sEmail,
			    			sPhone:$scope.sPhone,
			    			department:$scope.search.depart,
			    			datefrom:$scope.datefrom,
			    			dateto:$scope.dateto
			    			};
				var method = "POST";
				var url    = "api/home/deleteall";
	    		if (confirm("Are you sure to delete all recored selected?")) {
	    			callApi(method, url, data, function(response) {
	    				console.log(response);
	    				location.reload();
	    			});
	    			
	    		}
            } else {  
            	//Xoa cac user duoc chon
            	if (arr.length === 0) {
            		alert("Please Select at least one checkbox");
            	} else {
            		if (confirm("Are you sure to delete ids?"+" " +arr)) {
						var method = "POST";
						var url    = "api/home/multidelete";
						var data   = {id:arr};
            			callApi(method, url, data, function(response) {
		    				console.log(response);
		    				$scope.getListUsers();
		    			});
            			
            		} else {
            			// Cancel::Bo nhung row da checked
            			$('input:checkbox:checked').prop('checked', false);
            			arr = [];
            		}
            	}
            };
            arr = [];
	    }

/*-Function--------Call HTTP--------------------*/		
function callApi(method, url, data, func) {
	$http({
		method: method,
		url: baseUrl+url,
		data: $.param(data),
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded'
		}})
		.then(function(res) {
			func(res);
	   }, 
	   function(error) {
		   
	   });				   	
}
// Function refresh page in angular - - same document.ready(function()) in jQuery
		$scope.$on('$viewContentLoaded', function () {
			$scope.getListUsers();
			$scope.getListProduct();
			$scope.getListDept();

			// Custum datetime picker
	        $( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd'});
		    $( "#datepickerto" ).datepicker({dateFormat: 'yy-mm-dd'});

		    // Reload page do not show error before session 
		    // $('#admin-add-new').on('hidden.bs.modal', function () {
		    //     location.reload();
		    //     $('.alert-danger').addClass('hide');
		    // });
		    // $('#admin-detail-modal').on('hidden.bs.modal', function () {
		    //     location.reload();
		    //     $('.alert-danger').addClass('hide');
		    // });
        });
    }]);