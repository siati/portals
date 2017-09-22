<?php

namespace common\activeQueries;

/**
 * This is the ActiveQuery class for [[\common\models\User]].
 *
 * @see \common\models\User
 */
class UserQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \common\models\User[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\User|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $birth_cert_no birth certificate no
     * @return User ActiveRecord
     */
    public function byBirthCertNo($birth_cert_no) {
        return $this->where("birth_cert_no = '$birth_cert_no'")->one();
    }

    /**
     * 
     * @param integer $id_no national identity no
     * @return User ActiveRecord
     */
    public function byIDNo($id_no) {
        return $this->where("id_no = '$id_no'")->one();
    }

    /**
     * 
     * @param integer $phone phone no
     * @return User ActiveRecord
     */
    public function byPhone($phone) {
        return $this->where("phone = '$phone'")->one();
    }

    /**
     * 
     * @param string $email email address
     * @return User ActiveRecord
     */
    public function byEmail($email) {
        return $this->where("email = '$email'")->one();
    }

    /**
     * 
     * @param string $username username
     * @return User ActiveRecord
     */
    public function byUsername($username) {
        return $this->where("username = '$username'")->one();
    }
    
    /**
     * 
     * @param string $attribute attribute of User model
     * @param string $value value of [[$attribute]]
     * @return User ActiveRecord
     */
    public function notOwnSenior($attribute, $value) {
        return $this->where("$attribute = '$value'")->one();
    }
    
    /**
     * 
     * @param string $username username or email
     * @param integer $status user status
     * @return User ActiveRecord
     */
    public function userForLogin($username, $status) {
        return $this->where("(username = '$username' || email = '$username') && status = '$status'")->one();
    }

}
