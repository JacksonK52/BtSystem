<?php

namespace app\components;

use Yii;
use yii\base\Component;

class BtsystemComponent extends Component
{
    /**
     * Slug Generator
     * ==========================================
     * 
     * @param $title String
     */
    public function slugGenerator($title = "random-slug")
    {
        if ($title === 'random-slug') {
            $title = 'random-slug-' . Yii::$app->security->generateRandomString(8);
        }
        
        $title = str_replace("'", "", $title);
        return str_replace(" ", "-", strtolower($title . "-" . Yii::$app->security->generateRandomString(8)));
    }
}
