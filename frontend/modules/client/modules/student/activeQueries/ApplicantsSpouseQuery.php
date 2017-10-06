<?php

namespace frontend\modules\client\modules\student\activeQueries;

use frontend\modules\client\modules\student\models\ApplicantsSpouse;

/**
 * This is the ActiveQuery class for [[\frontend\modules\client\modules\student\models\ApplicantsSpouse]].
 *
 * @see \frontend\modules\client\modules\student\models\ApplicantsSpouse
 */
class ApplicantsSpouseQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsSpouse[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsSpouse|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $relationship relationship
     * @param integer $id_no national id no
     * @param integer $phone phone no
     * @param string $email email
     * @param string $kra_pin kra pin
     * @param string $oneOrAll one or all
     * @return ApplicantsSpouse ActiveRecord(s)
     */
    public function searchSpouses($applicant, $relationship, $id_no, $phone, $email, $kra_pin, $oneOrAll) {
        return $this->where(
                'id > 0' .
                (empty($applicant) ? '' : " && applicant = '$applicant'") .
                (is_numeric($relationship) ? " && relationship = '$relationship'" : '') .
                (empty($id_no) ? '' : " && id_no = '$id_no'") .
                (empty($phone) ? '' : " && phone = '$phone'") .
                (empty($email) ? '' : " && email = '$email'") .
                (empty($kra_pin) ? '' : " && kra_pin = '$kra_pin'")
                )->$oneOrAll();
    }
    
    /**
     * 
     * @param string $attribute attribute of User model
     * @param string $value value of [[$attribute]]
     * @param integer $applicant applicant's id
     * @return ApplicantsParents ActiveRecord
     */
    public function siblingOrParentNotSpouse($attribute, $value, $applicant) {
        return $this->where("applicant = '$applicant' && $attribute = '$value'")->one();
    }

}
