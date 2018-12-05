<?php
namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use ReflectionClass;
use frontend\models\Post;
use frontend\models\User;
use frontend\models\Comment;
use frontend\models\events\CommentCreatedEvent;

/**
 * Обработка публикации поста
 */
class CommentForm extends Model {
    
    const MAX_COMMENT_LENGHT = 300;
    //Событие публикации комментария
    const EVENT_COMMENT_CREATED = 'comment_created';
    
    //Загружено из post()
    public $text;
    
    public $post_id;
    
    public $parent_id = 0;
    
    //Получили через Конструктор объект текущего пользователя (кто создает пост)
    //Передано из actionCreate при создании нового поста (создание объекта PostForm)
    private $user;
    
    /*private $text;*/
    
    /*private $post;*/
    
    /*private $parent_id;*/
    
    
    /**
     * @inheritdoc
     */
    
public function rules() {
    return [
        [['text'], 'string', 'max' => self::MAX_COMMENT_LENGHT],
        [['parent_id', /*'user_id', */'post_id'], 'integer'],
        [['post_id'], 'required'],
        //[['user_id', 'post_id'], 'required'],
        ];
    }
    
    /**
     * @param User $user
     */
    public function __construct(User $user/*,*/ /*int*//*$post_id,*/ /*string*//*$text,*/ /*int*//*$parent_id = 0*/) {
        //При создании комментеря из контроллера передали объект текущего пользователя, создающего комментарий 
        $this->user = $user;
        //Параметры, получаемые из Ajax - загрузили в модель через load() для валидации
        /*$this->post = $post_id;*/
        /*$this->text = $text;*/
        /*$this->parent_id = $parent_id;*/
        //Подпишем addToFeeds (в компоненте-сервисе уведомлении о коментариях) на событие публикации комментария
        //$this->on(self::EVENT_COMMENT_CREATED, [Yii::$app->commentNotifyService, 'addToComment']);
        
    }
    
    /**
     * Returns the list of attribute names.
     * By default, this method returns all public non-static properties of the class.
     * You may override this method to change the default behavior.
     * @return array list of attribute names.
     */
    
    
    /**
     * @return boolean
     */
    public function save() {
        if($this->validate()) {
         
            
            $comment = new Comment();
            $comment->text = $this->text;
            //created_at заполняем в основной модели Comment с помошью TimestampBehavior
            //$comment->created_at = time();
            $comment->parent_id = $this->parent_id;
            $comment->user_id = $this->user->getId();
            $comment->post_id = $this->post_id;
            
            //Если пост успешно сохранен:
            if ($comment->save(false)) {//false - Валидация в модели Comment не требуется
                //Перед вызовом события публикации поста EVENT_COMMENT_CREATED:
                
                //Объект для передачи данных о новом сохраненном комментарии в сервис нотификационной ленты вместе с событием публикации
                $event = new CommentCreatedEvent();
                //Прикрепляем данные для создания ленты новостей:
                //Пользователь, создающий комментарий
                $event->user = $this->user;
                $event->comment = $comment;
                
                //Передаем $event вместе с событием в компонент уведомления о комментариях Yii::$app->commentNotifyService addToComment()
                //т.е. - вызываем событие публикации нового комментария под постом пользователя
                //начинает работу подписанный на это событие Yii::$app->commentNotifyService, 'addToComment'
                $this->trigger(self::EVENT_COMMENT_CREATED, $event);
                return true;
            }
            
        }
        return false;
    }
    
  
    
} 