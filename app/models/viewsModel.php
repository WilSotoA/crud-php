<?php

namespace app\models;

class viewsModel
{
    protected function obtainViewsModel($view)
    {
        $blankList = ['dashboard'];

        if (in_array($view, $blankList)) {
            if (is_file('app/views/content/' . $view . '-view.php')) {
                $content = 'app/views/content/' . $view . '-view.php';
            } else {
                $content = '404';
            }
        } elseif ($view === 'login' || $view === 'index') {
            $content = 'login';
        } else {
            $content = '404';
        }

        return $content;
    }
}
