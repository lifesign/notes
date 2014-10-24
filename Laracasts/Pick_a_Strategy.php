<?php

//1. define a family of algorithms

//2. encapsulate and make them interchangeable


/**
 * Define The Logger interface and force other Log algorithms implement it
 * The Logger should have the log function
 */
interface Logger
{
    public function log($data);
}


class LogToFile implements Logger
{
    public function log($data)
    {
        var_dump('Log to file:' . $data);
    }
}

class LogToDatabase implements Logger
{
    public function log($data)
    {
        var_dump('Log to database: ' . $data);
    }
}

class LogToXWebservice implements Logger
{
    public function log($data)
    {
        var_dump('Log to webservice: ' . $data);
    }
}

//3. set a context to use one algorithms

// ------------example one-------------
// hardcoding the LogToFile

/*class App
{
    public function log($data)
    {
        $logger = new LogToFile;
        $logger->log($data);
    }
}

(new App)->log('some data');*/

//----------------- example two--------------
// use second param to set the specify Logger
/*class App
{
    public function log($data, $logger)
    {
        $logger->log($data);
    }
}

(new App)->log('some data', new LogToXWebservice);*/

// -------------------example three----------------
// use default logger

/*
class App
{
    public function log($data, Logger $logger = null) {
        //default logger set to filelogger
        $logger = $logger ?: new LogToFile;
        $logger->log($data);
    }
}

(new App)->log('some data');
(new App)->log('some data', new LogToDatabase);
*/

//where do laravel use strategy pattern?

//see the mail driver part
//
//in config/mail.php we see the driver config

//然后在MailServiceProdiver中 注册SwiftMailer的时候 根据config中的配置 注册Transport
//
/*public function registerSwiftMailer()
{
    $config = $this->app['config']['mail'];

    $this->registerSwiftTransport($config);
}

可以看到返回的都是一个
SendmailTransport、MailTransport  => {xxx}Transport

都继承了Swift_Transport的interface

所以统一有send方法 相当于上面例子里的log*/


