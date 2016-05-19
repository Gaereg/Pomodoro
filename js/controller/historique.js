app.controller('HistoCtrl', function($scope, $http){
    var now = new Date();
    var day = now.getDate();
    var month = now.getMonth()<10 ? '0'+(parseInt(now.getMonth())+1) : now.getMonth()+1;
    var year = now.getFullYear();
    $scope.time = false;

    var beginDate = 0;
    var endDate = 0;

    $scope.searchAjax = function(beginDate, endDate, recherche){

        $http({
            method: 'GET',
            url: 'php/search.php?beginDate='+beginDate+'&endDate='+endDate+'&recherche='+recherche,
            data: {"foo":"bar"}
        }).then(function successCallback(response) {
            $scope.historique = response.data;
            console.log($scope.historique);
        }, function errorCallback(response) {
            $scope.test = response || "Request failed";
      });
    };

    $scope.prev = function(){
        if(day === 1 && month === 1){
            month = 12;
            day = 31;
            --year;
        } else if (day === 1){
            month = month-1 < 10 ? '0'+(month-1) : month-1;
            day = mois(month);
        } else {
            --day;
        }

        $scope.date = day+'/'+month+'/'+year;

        beginDate = year+'-'+month+'-'+day+' 00:00:00';
        dayEnd = day + 1;
        endDate = year+'-'+month+'-'+dayEnd+' 00:00:00';

        $scope.searchAjax(beginDate, endDate, 'time');
    };

    $scope.next = function(){
        var daysMonth = mois(month+1);

        if(day === daysMonth && month === 12){
            month = 1;
            day = 1;
            ++year;
        } else if (day === daysMonth){
            month = parseInt(month);
            month = month+1 < 10 ? '0'+(month+1) : month+1;
            day = 1;
        } else {
            ++day;
        }

        $scope.date = day+'/'+month+'/'+year;

        beginDate = year+'-'+month+'-'+day+' 00:00:00';
        dayEnd = day + 1;
        endDate = year+'-'+month+'-'+dayEnd+' 00:00:00';

        $scope.searchAjax(beginDate, endDate, 'time');
    };

    $scope.prev();

    $scope.preSearch = function(typeSearch){
        if (typeSearch === true){
            $scope.searchAjax('null', 'null', 'lang');
        } else {
            $scope.searchAjax(beginDate, endDate, 'time');
        }
    };

    var mois = function(a){
    	div = document.getElementById('mois-div');
    	if (a == 2){
    		return 28;
    	} else if ((a<8 && a%2 > 0) || (a>7 && a%2 === 0)){
    		return 31;
    	} else{
    		return 30;
    	}
    };
});
