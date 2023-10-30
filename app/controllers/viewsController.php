<?php

namespace app\controllers;

use app\models\viewsModel;

class viewsController  extends viewsModel
{
    public function obtainViewsController($view)
    {
        if ($view !== '') {
            $response = $this->obtainViewsModel($view);
        } else {
            $response = 'login';
        }

        return $response;
    }
}
