<?php
    function __autoload($class)
    {
        if (file_exists(realpath("classes/" . strtolower($class) . ".class.php"))) {
            include_once("classes/" . strtolower($class) . ".class.php");
        }
        else {
            if (file_exists(realpath("classes/" . $class . ".class.php"))) {
                include_once("classes/" . $class . ".class.php");
            }
            else {
                // file does not exist, but if the table exists in the database, we can just create the class at runtime
                eval("class " . $class . " extends RootObject {  }");
            }
        }
    }

    spl_autoload_register('__autoload');