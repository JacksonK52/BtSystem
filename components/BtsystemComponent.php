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
        $title = str_replace("'", "", $title);
        return str_replace(" ", "-", strtolower($title . "-" . Yii::$app->security->generateRandomString(8)));
    }
}