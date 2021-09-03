<?php

namespace App\Employees;

use Tahir\Base\Controller;

class EmployeesController extends Controller
{
    public function index()
    {
        return ['tt'=>'rrrrrrr'];//$this->view->render('app/users/views/index',['tt'=>'rrrrrrr']);
    }
}