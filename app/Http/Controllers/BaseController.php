<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
   	public function __construct(){

   		if(isset($this->module)){
			\View::share('module', $this->module);
   		}
	}
}
