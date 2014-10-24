<?php

//适配器模式 生活中比较常见 电源适配器 sd读卡器 usb
//An adapter allows you to translate(wrap) one interface for use with another.

//这里jeff举了一个书的例子

//一本书有打开和翻页的方法 可以认为是某种接口
interface BookInterface
{
    public function open();
    public function turnPage();
}

class Book implements BookInterface
{
    public function open()
    {
        var_dump('open the book');
    }

    public function turnPage()
    {
        var_dump('turn on page');
    }

}


//person 作为一个 consumer  来执行操作
class Person
{
    public function read($book)
    {
        $book->open();
        $book->turnPage();
    }
}

(new Person)->read(new Book);

//但是如果现在是一个kindle kindle的open对应着的可能是turnOnPower, turnPage可能是click 也就是说之前的那个接口不满足 适配器模式的出现就可以来解决这个问题。回到定义 Adapter allows you to translate
// or wrapper one interface for use another
// 所以需要重新定义接口 来满足这两者 最后的consumer 只要调用统一的接口即可

//假设现在有一个kindle 定义如下

interface EreaderInterface
{
    public function turnOn();
    public function pressNextButton();
}

class Kindle implements EreaderInterface
{
    public function turnOn(){
        var_dump("turn on kindle ");
    }

    public function pressNextButton()
    {
        var_dump("press next button ");
    }
}

//此时如果用 (new Person)->read(new Kindle) 会报错 因为kindle没有所谓的open turnPage方法
//

//开始定义我们的kindle适配器
// 1. 首先创建一个KindleAdapter 注意这个适配器要实现的接口是你想要适配的那个接口 这里也就是book的接口
// 2. 由于是adapter 也就是说最终执行的还是被adapter的物件本身 也就意味着 必须要注入一个实例

class KindleAdaptor implements BookInterface
{
    protected $kindle;

    public function __construct(EreaderInterface $kindle) {
        $this->kindle = $kindle;
    }

    public function open()
    {
        $this->kindle->turnOn();
    }

    public function turnPage()
    {
        $this->kindle->pressNextButton();
    }
}

//这里调用的时候就可以把kindle传给adapter了
(new Person)->read(new KindleAdaptor(new Kindle));

//总结一下
// 1. 创建一个需要适配某个接口的适配器
// 2. 注入被适配的实例
// 3. 转换实例对应的方法


//好处可以发现 我们不需要改动之前的已经创建的接口 那么在重构一些老旧的代码又不影响线上业务的时候
//应该就可以考虑使用适配器模式


//laravel5.0中的filesystem系统就采用了一个filesystemadapter的东东, 利用适配器把原本laravel的接口
//适配到了flysystem中

//这里laravel相当于一个已有项目 当我们想要使用外部的一个新的接口 又想保证之前的接口调用
// 那么我们就要创建一个adapter 注意是想要实现的接口也就是laravel本身的接口 那么需要注入flysystem对应的driver
// 代码里也是这么干的