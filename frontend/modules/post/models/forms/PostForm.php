<?php
namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;

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
    }
    
    /**
     * @return boolean
     */
    public function save() {
        if($this->validate()) {
            /*
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
            return $post->save(false); //Валидация в модели Post не требуется
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