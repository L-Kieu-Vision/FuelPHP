<!DOCTYPE html>

<html ng-app="myApp" ng-controller="userController">

<head>
	<base href="<?php echo \Uri::base(false); ?>" />
	<title>Angular with Fuelphp</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<?php echo $partials['head_admin'];?>

</head>

<body>
	<?php echo $partials['menu_admin']?>

	<div ng-view=""></div>
	
</body>
<script type="text/javascript">
	var baseUrl = '<?php echo Uri::base() ;?>';
</script>

<script type="text/javascript" src="http://localhost/fuel-angular/public/themes/main/js/app.js"></script>
<script type="text/javascript" src="http://localhost/fuel-angular/public/themes/main/js/directive/directive.js"></script>
<script type="text/javascript" src="http://localhost/fuel-angular/public/themes/main/js/controller/userController.js"></script>


</html>



