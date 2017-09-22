<?php

namespace frontend\modules\client\modules\student\activeQueries;

use frontend\modules\client\modules\student\models\ApplicantsParents;

/**
 * This is the ActiveQuery class for [[\frontend\modules\client\modules\student\models\ApplicantsParents]].
 *
 * @see \frontend\modules\client\modules\student\models\ApplicantsParents
 */
class ApplicantsParentsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsParents[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsParents|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $relationship relationship
     * @param string $gender gender
     * @param integer $birth_cert_no birth cert. no.
     * @param integer $id_no national id. no.
     * @param integer $phone phone number
     * @param string $email email
     * @param string $kra_pin kra pin
     * @param integer $is_minor is a minor
     * @return ApplicantsParents ActiveRecords
     */
    public function searchParents($applicant, $relationship, $gender, $birth_cert_no, $id_no, $phone, $email, $kra_pin, $is_minor) {
        return $this->where(
                        'id > 0' .
                        (empty($applicant) ? '' : " && applicant = '$applicant'") .
                        (empty($relationship) ? '' : " && relationship = '$relationship'") .
                        (empty($gender) ? '' : " && gender = '$gender'") .
                        (empty($birth_cert_no) ? '' : " && birth_cert_no = '$birth_cert_no'") .
                        (empty($id_no) ? '' : " && id_no = '$id_no'") .
                        (empty($phone) ? '' : " && phone like '%$phone%'") .
                        (empty($email) ? '' : " && email like '%$email%'") .
                        (empty($kra_pin) ? '' : " && kra_pin = '$kra_pin'") .
                        (is_null($is_minor) || $is_minor == '' ? '' : " && is_minor = '$is_minor'")
                )->all();
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $relationship relationship
     * @return ApplicantsParents ActiveRecord
     */
    public function byApplicantAndRelationship($applicant, $relationship) {
        foreach ($this->searchParents($applicant, $relationship, null, null, null, null, null, null, null) as $parent)
            return $parent;
    }
    
    /**
     * 
     * @param string $attribute attribute of parent
     * @param string $value value of [[$attribute]]
     * @param integer $id parent id
     * @return ApplicantsParents ActiveRecord
     */
    public function distinctDetails($attribute, $value, $id) {
        return $this->where("$attribute = '$value' && id != '$id'")->one();
    }

}
