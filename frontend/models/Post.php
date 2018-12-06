<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property string $description
 * @property int $likes
 * @property string $hashtag
 * @property int $created_at
 */
class Post extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                 'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }

    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'filename' => 'Filename',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }
    
    public function getImage() {
        return Yii::$app->storage->getFile($this->filename);
    }
    
    /**
     * Get author of the post
     * @return User|null
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /**
     * Like current post by given user
     * @param \frontend\models\User $user
     */
    public function like(User $user) {
        /* @var redis Connection */
        $redis = Yii::$app->redis;
        //Список пользователей, которые лайкнули пост
        $redis->sadd("post:{$this->getId()}:likes", $user->getId());
        //Список постов, который лайкнул пользователь
        $redis->sadd("user:{$user->getId()}:likes", $this->getId());
        //в случае повтороного вызова like() - переменные не добавдяются повторно
    }
    
    /**
     * Unlike current post by given user
     * @param \frontend\models\User $user
     */
    public function unlike(User $user) {
        /* @var redis Connection */
        $redis = Yii::$app->redis;
        //Список пользователей, которые лайкнули пост
        $redis->srem("post:{$this->getId()}:likes", $user->getId());
        //Список постов, который лайкнул пользователь
        $redis->srem("user:{$user->getId()}:likes", $this->getId());
        //в случае повтороного вызова like() - переменные не добавдяются повторно
    }
    
    
    public function getId() {
        return $this->id;
    }
    
    /**
     * @return mixed
     * 
     */
    public function countLikes() {
        /* @var redis Connection */
        $redis = Yii::$app->redis;
        
        //Подсчет кол-ва элементов в множестве
        return $redis->scard("post:{$this->getId()}:likes");
    }
    
    /**
     * Check whether given user liked current post
     * @param \frontend\models\User $user
     * @return integer
     */
    //Лайкнут ли пост пользователем, переданным в метод
    public function isLikedBy(User $user) {
        /* @var redis Connection */
        $redis = Yii::$app->redis;
        //Является ли id-пользователя одним из членов множества лайков поста
        return $redis->sismember("post:{$this->getId()}:likes", $user->getId());
    }
    
    public function getPosts() {
        return self::find()->all();
    }
}
