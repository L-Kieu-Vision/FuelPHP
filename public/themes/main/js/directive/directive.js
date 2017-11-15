/*-Directive--------Accept Input Number Only--------------------*/
	app.directive('numbersOnly', function () {
	    return {
	        require: 'ngModel',
	        link: function (scope, element, attr, ngModelCtrl) {
	            element.bind('input', function (e) {
	                var obj = e.target;
	                var value = obj.value;
	                var transformedInput = value;
	                transformedInput = transformedInput ? transformedInput.replace(/[^\d.-]/g, '') : null;
	                ngModelCtrl.$setViewValue(transformedInput);
	                ngModelCtrl.$render();
	            });
	        }
	    };
	});