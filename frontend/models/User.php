<?php
namespace frontend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $about
 * @property integer $type
 * @property string $nickname
 * @property string $picture
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    
    const DEFAULT_IMAGE = '/img/profile_default_image.jpg';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    //Не используется - ищем по email
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    
    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    //Возвращает nickname пользователя, переданного из view (т.е. - просматриваемого пользователя),
    //если он у него есть - или id пользователя, если нет
    /**
     * Return mixed
     */ 
    public function getNickname() {
        /*
        if ($this->nickname) {
            return $this->nickname;
        }
        
        return $this->getId();
        */
        return $this->nickname ? $this->nickname : $this->getId();
    }
    
    //Подписываем текущего пользователя на выбранного им пользователя
    /**
     * Subscribe current user to given user
     * @param \frontend\models\User $user
     */
    public function followUser(User $user) {
        $redis = Yii::$app->redis;
        
        /*добавляем в список подписок текущего залогиненного пользователя, вызвавшего followUser(),
        выбранного им пользователя $user, id которого было передано на вход метода */
        $redis->sadd("user:{$this->getId()}:subscriptions", $user->getId());
        
        /*добавляем в список подписчиков пользователя $user, 
        id которого было передано на вход метода,
        текущего залогиненного пользователя, вызвавшего followUser() */
        $redis->sadd("user:{$user->getId()}:followers", $this->getId());
    }
    
    //Отписываем текущего пользователя от выбранного им пользователя
    /**
     * Unsubscribe current user from given user
     * @param \frontend\models\User $user
     */
     public function unfollowUser(User $user) {
        $redis = Yii::$app->redis;
        
        /*удаляем из списка подписок текущего залогиненного пользователя, вызвавшего followUser(),
        выбранного им пользователя $user, id которого было передано на вход метода */
        $redis->srem("user:{$this->getId()}:subscriptions", $user->getId());
        
        /*удаляем из списка подписчиков пользователя $user, 
        id которого было передано на вход метода,
        текущего залогиненного пользователя, вызвавшего followUser() */
        $redis->srem("user:{$user->getId()}:followers", $this->getId());
    }
    
    //получить список подписок пользователя, профиль которого просматривается в profile/view
    //т.е., $user->getSubscriptions() на view профиля, где $user - пользователь, чью стр. просматриваем
    /**
     * @return array
     */
    public function getSubscriptions() {
        /* @var redis Connection */
        $redis = Yii::$app->redis;
        $key = "user:{$this->getId()}:subscriptions";
        //получаем из redis массив нужных id
        $ids = $redis->smembers($key);
        
        return User::find()->select('id, username, nickname')->where(['id' => $ids])->orderBy('username')->asArray()->all();
        
    }
    
    //получить список подписчиков пользователя, профиль которого просматривается в profile/view
    //т.е., $user->getFollowers() на view профиля, где $user - пользователь, чью стр. просматриваем
    /**
     * @return array
     */
    public function getFollowers() {
        /* @var redis Connection */
        $redis = Yii::$app->redis;
        $key = "user:{$this->getId()}:followers";
        //получаем из redis массив нужных id
        $ids = $redis->smembers($key);
        
        return User::find()->select('id, username, nickname')->where(['id' => $ids])->orderBy('username')->asArray()->all();
    }
    
    //
    public function countSubscriptions() {
        /* @var redis Connection */
        $redis = Yii::$app->redis;
        return $redis->scard("user:{$this->getId()}:subscriptions");
    }
    
    public function countFollowers() {
        /* @var redis Connection */
        $redis = Yii::$app->redis;
        return $redis->scard("user:{$this->getId()}:followers");
    }
    
    /**
     * @param \frontend\models\User $user
     */
    public function getMutualSubscriptionsTo(User $user) {
        //Current user subscriptions
        //Подписки текущего зарегистрированного пользователя
        $key1 = "user:{$this->getId()}:subscriptions";
        
        //Given user followers
        //Подписчики интересующего нас пользователя
        $key1 = "user:{$user->getId()}:followers";
        
        /* @var redis Connection */
        $redis = Yii::$app->redis;
        
        //Пересечения двух множеств
        $ids = $redis->sinter($key1, $key2);
        
        return User::find()->select('id, username, nickname')->where(['id' => $ids])->orderBy('username')->asArray()->all();
    }
    
    /**
     * Check whether current user if following given user
     * @param \frontend\models\User $user
     * @return boolean
     */
    public function isFollowing(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return (bool) $redis->sismember("user:{$this->getId()}:subscriptions", $user->getId());
    }
    
    //Возвращаем картинку на страницу пользователя,
    //вставленную в его атрибут picture
    /**
     * Get profile picture
     * @return string
     */
    public function getPicture()
    {
        //Если у юзера не пустой атрибут picture,
        if ($this->picture) {
            //то получаем полный путь к файлу картинки
            return Yii::$app->storage->getFile($this->picture);
        }
        return self::DEFAULT_IMAGE;
    }
    
    /**
     * Get data for newsFeed
     * @param integer $limit
     * @return array
     */ 
    public function getFeed(/* int */$limit) {
        $order = ['post_created_at' => SORT_DESC];
        return $this->hasMany(Feed::className(), ['user_id' => 'id'])->orderBy($order)->limit($limit)->all();
    }
    
    //Проверка - лайкнул ли пользователь пост с $postId
    /**
     * Check whether current user likes post with given id
     * @param integer $postId
     * @return boolean
     */
    public function likesPost(/*int*/ $postId)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        //Проверка соответвия $postId множеству
        //Само множество - перечень постов, которых лайкнул пользователь
        return (bool) $redis->sismember("user:{$this->getId()}:likes", $postId);
        
    }
}
