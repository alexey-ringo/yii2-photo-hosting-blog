<?php
namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;
use Intervention\Image\ImageManager;
use frontend\models\events\PostCreatedEvent;

/**
 * Обработка публикации поста
 */
class PostForm extends Model {
    
    const MAX_DESCRIPTION_LENGHT = 1000;
    const MAX_HASHTAG_LENGHT = 50;
    //Событие публикации поста
    const EVENT_POST_CREATED = 'post_created';
    
    //Загрузили из формы методом Load
    public $picture;
    public $hashtag;
    public $description;
    
    //Получили через Конструктор объект текущего пользователя (кто создает пост)
    //Передано из actionCreate при создании нового поста (создание объекта PostForm)
    private $user;
    
    /**
     * @inheritdoc
     */
    
    public function rules() {
        return [
            [['picture'], 'file',
                'skipOnEmpty' => false,
                'extensions' => ['jpg', 'png'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize()],
            [['hashtag'], 'string', 'max' => self::MAX_HASHTAG_LENGHT],
            [['description'], 'string', 'max' => self::MAX_DESCRIPTION_LENGHT],
            ];
    }
    
    /**
     * @param User $user
     */
    public function __construct(User $user) {
        //При создании поста из контроллера передали объект текущего пользователя, создающего пост 
        $this->user = $user;
        //Обработчик собития окончания валидации
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizePicture']);
        //Подпишем addToFeeds (в компоненте-сервисе новостной ленты) на событие публикации
        $this->on(self::EVENT_POST_CREATED, [Yii::$app->feedService, 'addToFeeds']);
    }
    
    /**
     * Resize downloaded image if it needs
     */
    public function resizePicture() {
        $width = Yii::$app->params['postPicture']['maxWidth'];
        $height = Yii::$app->params['postPicture']['maxHeight'];
        
        $manager = new ImageManager(array('driver' => 'imagick'));
        
        //пример значения [tempName] => /tmp/phpCXpi4U - см коменты в валидации
        $image = $manager->make($this->picture->tempName);
        
        $image->resize($width, $height, function($constraint) {
            //Сохранение пропорций изображения
            $constraint->aspectRatio();
            //Если разрешение изображения меньше, чем в params['postPicture'][]
            //то не изменяем его
            $constraint->upsize();
        })->save(); //измененное изображение сохраняем пока в тот же /tmp/...
    }
    
    /**
     * @return boolean
     */
    public function save() {
        if($this->validate()) {
            /*
            В свойстве picture объекта PostForm сохраняется объект класса UploadedFile - пример ниже:
            
            frontend\modules\post\models\forms\PostForm Object
                (
                    [picture] => yii\web\UploadedFile Object
                        (
                            [name] => legenda_35-750x750.jpg
                            [tempName] => /tmp/phpCXpi4U
                            [type] => image/jpeg
                            [size] => 94247
                            [error] => 0
                        )

                    [description] => my description
                    ...
                )
            */
            
            $post = new Post();
            $post->hashtag = $this->hashtag;
            $post->description = $this->description;
            //created_at заполняем в основной модели Post с помошью TimestampBehavior
            //$post->created_at = time();
            $post->filename = Yii::$app->storage->saveUploadedFile($this->picture);
            $post->user_id = $this->user->getId();
            
            //Если пост успешно сохранен:
            if ($post->save(false)) { //false - Валидация в модели Post не требуется
                //Перед вызовом события публикации поста EVENT_POST_CREATED:
                
                //Объект для передачи данных о сохраненном посте в сервис новостной ленты вместе с событием публикации
                $event = new PostCreatedEvent();
                //Прикрепляем данные для создания ленты новостей:
                //Пользователь, создающий пост
                $event->user = $this->user;
                $event->post = $post;
                
                //Передаем $event вместе с событием в компонент ленты Yii::$app->feedService addToFeeds()
                //т.е. - вызываем событие публикации поста в новостной ленте
                //начинает работу подписанный на это событие Yii::$app->feedService, 'addToFeeds'
                $this->trigger(self::EVENT_POST_CREATED, $event);
                return true;
            }
            
        }
        return false;
    }
    
    /**
     * Maximum size of the uploaded file
     * @return integer
     */
    public function getMaxFileSize() {
        return Yii::$app->params['maxFileSize'];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'picture' => 'Изображение',
            'hashtag' => 'ХэшТэг',
            'description' => 'Описание',
        ];
    }
    
} 