<?php


namespace App\Inspections;


use Exception;

class KeyHeldDown
{
    
    /**
     * @param $body
     *
     * @throws \Exception
     */
    public function detect($body)
    {
        if (preg_match('/(.)\\1{4,}/', $body)){
            throw new Exception('your reply has key held down');
        };
    }
    
    
}