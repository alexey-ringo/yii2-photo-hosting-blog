<?php

namespace frontend\components\helpers;

use yii\base\Component;
use Yii;

class GenerateHtmlHelper extends Component {
    
    public function getCommentsHtml($comments) {
        ob_start();
        //include __DIR__ . '/menu_tpl/' . $this->tpl;
        include __DIR__ . '/comments_html.php';
        //буферизация - для запрета вывода в браузер. Буферизируем вывод (ob_start), а затем возврящаем, не выводя на экран
        return ob_get_clean();
    }
    
}