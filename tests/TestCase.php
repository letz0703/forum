<?php

namespace Tests;

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    protected function setUp()
    {
        parent::setUp();
//        $this->disableExceptionHandling();
        $this->withoutExceptionHandling();
        DB::statement('PRAGMA foreign_keys = on;');
    }


    protected function signIn($user = [])
    {
         $user = $user?:create('App\User');
         $this->actingAs($user);
         return $this;
    }

//    protected function disableExceptionHandling()
//    {
//        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);
//
//        $this->app->instance(ExceptionHandler::class, new class extends Handler{
//            public function __construct() { }
//            public function report(\Exception $e) { }
//            public function render($request, \Exception $e) {
//                throw $e;
//            }
//        });
//    }
//
//    protected function withExceptionHandling()
//    {
//        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);
//        return this;
//    }


}


