<?php

//jeff 在开头以检查费用举了个例子
// 假设我们有一个基本的检查话费

class BasicInspection {
    public function getCost()
    {
        return 19;
    }
}

//假设油钱变了 我们可能再写一个例子 用来表示油钱变了之后的耗费
class BasicInspectionAndOilChange {
    public function getCost()
    {
        return 19 + 10;
    }
}

//假设还有其他的变化
class BasicInspectionAndOilChangeAndTireRotation
{
    public function getCost()
    {
        return 19 + 10 + 5;
    }
}


/*上面的例子不好的地方在于哪？
1. 当有其他的价格更变, 我们还需要重新写一个类 Duplicate !
2. 假设基准的价格变了, 意味着其他的几个类也得跟着变化.
3. hardcoding了getCost方法, 无法复用.*/


//引入decorator pattern
// 首先定义一个获取基本花费的接口

interface CarService
{
    public function getCost();
}


//实现对应的具体的业务
class BaseInspection implements CarService
{
    public function getCost()
    {
        return 19;
    }
}

//注入service 实现decorator
class OilChange implements CarService
{
    protected $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    public function getCost() {
        return $this->carService->getCost() + 25;
    }
}


class TireRotation implements CarService
{
    protected $carService;

    public function __construct(CarService $carService) {
        $this->carService = $carService;
    }

    public function getCost() {
        return $this->carService->getCost() + 30;
    }
}

//调用的时候只要传入相应的service即可

echo (new TireRotation(new OilChange(new BaseInspection)))->getCost();

//每一层都可以看错是下一层的 wrapping  所以像"装饰"

//思考应用场景
//涉及到钱的 比如电商搞活动 降价之类的 应该可以用这个模式

