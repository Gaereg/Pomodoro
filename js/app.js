var app = angular.module('pomoApp',[]);

app.filter('numberFixedLen', function () {
    return function(a,b){
        return(1e4+""+a).slice(-b);
    };
});
