/**
 * Created by Administrator on 2016/2/20.
 */
angular.module('myM1', ['ng', 'ngTouch'])
    .controller("parentCtrl", function ($scope) {
        $scope.jump = function (url) {
            $.mobile.changePage(url);
        }
        //监听每个page创建事件(pagecreate),只要DOM树上添加了一个page，必须编译并链接该page
        $(document).on('pagecreate', function (event) {
            var page = event.target;//新添加page元素
            var scope = $(page).scope();
            $(page).injector().invoke(function ($compile) {
                $compile(page)(scope);
                scope.$digest();
            })
        })
    })
    .controller("mainCon", function ($scope, $http) {
        $scope.hasMore = true; //是否还有更多的数据可供加载
        $scope.dishList = []; //用于保存所有菜品数据的加载
        //控制器初始化/页面加载时，从服务器读取最前面的5条记录
        $http.get('data/dish_listbypage.php?start=0').
            success(function (data) {
                $scope.dishList = data;
                //console.log($scope.dishList);
            });
        //点击加载更多
        $scope.loadMore = function () {
            $http.get('data/dish_listbypage.php?start=' + $scope.dishList.length)
                .success(function (data) {
                    if (data.length < 3) {
                        $scope.hasMore = false;
                    }
                    $scope.dishList = $scope.dishList.concat(data);
                    //console.log($scope.dishList);
                })
        }
        //跳转至详情
        $scope.showDetail = function (did) {
            //console.log("待查寻的编号:"+did);
            if(event.target.nodeName=='IMG'){
                return;
            }
            sessionStorage.did = did;
            $.mobile.changePage('detail.html');
        }
        //显示大图
        $scope.showBig = function(did){
            $('.full-screen').removeClass('hide');
            $('#p'+did).removeClass('hide');
        }
        //点击图片外，显示回到主页面
        $scope.showBack = function(){
            $('.full-screen').addClass('hide');
            $('.bg-img').addClass('hide');
        }
        //搜索关键字
        $scope.$watch('kw', function () {
            if ($scope.kw) {//监视关键字
                $http.get('data/dish_listbykw.php?kw=' + $scope.kw)
                    .success(function (data) {
                        $scope.dishList = data;
                    })
            }
        })
    })
    .controller("detailCon", function ($scope, $http, $rootScope) {
        $scope.dish = [];
        $http.get('data/dish_listbydid.php?did=' + sessionStorage.did)
            .success(function (data) {
                $scope.dish = data;
            })
        $rootScope.did = sessionStorage.did;
    })
    .controller('orderCon', function ($scope, $rootScope, $http) {
        $scope.xian = true;
        $rootScope.order = {};

        $scope.subm = function () {
            $scope.vn = /^[\u4e00-\u9fa5]{3,4}$/.test($scope.order.name);
            $scope.vp = /^1[345678][0-9]{9}$/.test($scope.order.phone);
            $scope.vs = /1|2/.test($scope.order.sex);
            if ($scope.vn && $scope.vp && $scope.vs ) {
                $scope.xian = false;
                $rootScope.order =
                {
                    "user_name": $scope.order.name, "user_sex": $scope.order.sex,
                    "user_phone": $scope.order.phone, "user_addr": $scope.order.addr, "did": $rootScope.did
                };
                //console.log($rootScope.order);
                var orderData = jQuery.param($scope.order);
                //console.log(orderData);
                $scope.oid = [];
                $http.post('data/order_add.php?' + orderData)
                    .success(function (data) {
                        $scope.oid = data;
                    })
            }
        }
    })
.controller('myorder',function($scope,$http,$rootScope){
        $scope.dish = [];
         $http.get('data/order_listbyphone.php?phone='+$rootScope.order.user_phone)
             .success(function(data){
                 $scope.dish = data;
                 console.log($scope.dish);
             })
    })
. run(function($http) {//AngularJS发起POST请求时，必须设置请求头如下
        $http.defaults.headers.post =
        {'Content-Type': 'application/x-www-form-urlencoded'};
    })