<?php
namespace frontend\models;

use common\models\UserModel;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $repetPassword;
    public $verifyCode;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            // 用户名唯一性检测
            ['username', 'unique', 'targetClass' => '\common\models\UserModel', 'message' => \Yii::t('common', 'This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            // 邮箱唯一性检测
            ['email', 'unique', 'targetClass' => '\common\models\UserModel', 'message' => \Yii::t('common', 'This email address has already been taken.')],

            [['password', 'repetPassword'], 'required'],
            [['password', 'repetPassword'], 'string', 'min' => 6],
            // 两次密码输出相同检测，具体看 rules 一章
            ['repetPassword', 'compare', 'compareAttribute' => 'password', 'message' => \Yii::t('common', 'Two times the password is not consitent.')],

            ['verifyCode', 'captcha'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => \Yii::t('common', 'username'),
            'email' => \Yii::t('common', 'email'),
            'password' => \Yii::t('common', 'password'),
            'repetPassword' => \Yii::t('common', 'repetPassword'),
            'verifyCode' => \Yii::t('common', 'verifyCode'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return UserModel|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new UserModel();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
