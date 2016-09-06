<?php

namespace Xiao;
/**
 * ThinkPHP内置的Dispatcher类
 * 完成URL解析、路由和调度
 */
class Dispatcher
{

    private static $controllerName;
    private static $methodName;
    private static $paramsArray = array();

    static public function dispatch()
    {
//        var_dump($_SERVER['REQUEST_URI']);
        $path = explode('/', $_SERVER['REQUEST_URI']);

        $i = 0;
        foreach ($path as $key => $value) {
            if ($i != 0 && $key != 0 && (!isset($path[$i]) || empty($path[$i]))) {
                break;
            }

            if ($key != $i) {
                self::$paramsArray[$path[$i]] = $path[$i + 1];
                $i++;
            }
            if ($value == APP_NAME) {
                self::$controllerName = 'Xiao\\' . ucfirst($path[$i + 1]) . 'Controller';
                self::$methodName = $path[$i + 2];
                $i += 2;
            }
            $i++;
        }

//        var_dump(self::$controllerName);
        $controllerInstance = new self::$controllerName();
        $controllerInstance->index();

    }

    static public function getControllerName()
    {
        return self::$controllerName;
    }

    static public function getMethodName()
    {
        return self::$methodName;
    }

    static public function getParamsArray()
    {
        return self::$paramsArray;
    }

}
