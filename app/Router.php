<?php

namespace APITest\Core;

class Router
{
    public static function init()
    {
        $arrayGlob = glob('../controllers/*.php');

        if (empty($arrayGlob)) {
            throw new \Exception('No route to load');
        }

        $found = false;

        foreach ($arrayGlob as $filepath) {

            if (self::saveRoutesFromFile($filepath)) {

                $found = true;
                break;
            }
        }

        if (!$found) {

            http_response_code(405);
            echo json_encode(array("message" => "Method not allowed"));
        }
    }

    private static function saveRoutesFromFile($path)
    {
        $content = file_get_contents($path);

        $arrayMatches = array();
        $routeUris = array();

        if (!preg_match('/class [a-z]+/i', $content, $arrayMatches))
        {
            return ;
        }

        foreach ($arrayMatches as $arrayMatch)
        {
            $objectName = str_ireplace('class ', '', $arrayMatch);
            $reflectionClass = new \ReflectionClass('\\APITest\\Controller\\' . $objectName);

            if ($reflectionClass === false)
            {
                return ;
            }

            $arrayReflectionMethods = $reflectionClass->getMethods();

            if (empty($arrayReflectionMethods))
            {
                return ;
            }

            foreach ($arrayReflectionMethods as $reflectionMethod) {
                if (!$reflectionMethod instanceof \ReflectionMethod)
                {
                    continue;
                }

                $docComment = $reflectionMethod->getDocComment();

                if (!$docComment
                    || !preg_match('/@Route\\(\'(.*)\'\\)/', $docComment, $routeUri)
                    || !preg_match('/@Method\\(\'(.*)\'\\)/', $docComment, $methodHandled))
                {
                    continue;
                }

                if(basename($_SERVER['REQUEST_URI']) == "public")
                {
                    $currentUri = "/index.php";
                }
                else
                {
                    $currentUri = "/api/" . basename($_SERVER['REQUEST_URI']);
                }

                if($_SERVER['QUERY_STRING'] != '')
                {
                    $currentUri = substr($currentUri,0, stripos($currentUri, '?'));
                }

                if ($routeUri[1] == $currentUri && $methodHandled[1] == $_SERVER['REQUEST_METHOD'])
                {
                    $objectName = '\\APITest\\Controller\\' . $objectName;
                    $methodName = $reflectionMethod->getName();
                    $object = new $objectName();
                    $object->$methodName();
                    return true;
                }
            }



        }
    }
}