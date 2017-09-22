<?php

namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model {

    public $username;
    public $email;
    public $id_no;
    public $phone;
    public $password;
    public $confirm_password;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            [['password', 'confirm_password'], 'required'],
            [['password', 'confirm_password'], 'string', 'min' => 6, 'max' => 10],
            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'integer'],
            ['phone', 'string', 'min' => 9, 'max' => 13],
            ['phone', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This phone no. has already been taken.'],
            ['id_no', 'trim'],
            ['id_no', 'required'],
            ['id_no', 'integer'],
            ['id_no', 'string', 'min' =>7, 'max' => 8],
            ['id_no', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This ID. No. has already been taken.'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup() {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }

}
