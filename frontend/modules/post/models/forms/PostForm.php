<?php
namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;
use Intervention\Image\ImageManager;

class PostForm extends Model {
    
    const MAX_DESCRIPTION_LENGHT = 1000;
    
    public $picture;
    public $description;
    
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
            [['description'], 'string', 'max' => self::MAX_DESCRIPTION_LENGHT],
            ];
    }
    
    /**
     * @param User $user
     */
    public function __construct(User $user) {
        $this->user = $user;
        //Обработчик собития окончания валидации
        $this->on(self::EVEN_AFTER_VALIDATE, [$this, 'resizePicture']);
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
            $post->description = $this->description;
            $post->created_at = time();
            $post->filename = Yii::$app->storage->saveUploadedFile($this->picture);
            $post->user_id = $this->user->getId();
            return $post->save(false); //false - Валидация в модели Post не требуется
        }
        
    }
    
    /**
     * Maximum size of the uploaded file
     * @return integer
     */
    public function getMaxFileSize() {
        return Yii::$app->params['maxFileSize'];
    }
    
} 