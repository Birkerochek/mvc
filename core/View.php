<?php

class View
{
    public function __construct($template, $data = array())
    {
        ob_start();
        if ($data)

            foreach ($data as $key => $value) 
                $$key = $value;
            include_once __DIR__ . '/../templates/' . $template . '.php';
            
            $content = ob_get_contents();
            ob_get_clean();
            echo $content;
    }
}