<?php

namespace frontend\modules\business\activeQueries;

use frontend\modules\business\models\Applications;

/**
 * This is the ActiveQuery class for [[\frontend\modules\business\models\Applications]].
 *
 * @see \frontend\modules\business\models\Applications
 */
class ApplicationsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\Applications[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\Applications|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $application application id
     * @param string $serial_no serial number
     * @param integer $prints number application printed
     * @param string $printed_at_since time application printed
     * @param string $printed_at_till time application printed
     * @param integer $appeal_prints number application appeal printed
     * @param string $appeal_printed_at_since time application appeal printed
     * @param string $appeal_printed_at_till time application appeal printed
     * @param integer $appeal_origin invoked appeal: 0 - system, 1 - applicant
     * @param string $oneOrAll one or all
     * @return Applications ActiveRecord(s)
     */
    public function searchApplications($applicant, $application, $serial_no, $prints, $printed_at_since, $printed_at_till, $appeal_prints, $appeal_printed_at_since, $appeal_printed_at_till, $appeal_origin, $oneOrAll) {
        return $this->where(
                        'id > 0' .
                        (empty($applicant) ? '' : "applicant = '$applicant'") .
                        (empty($application) ? '' : " && application = '$application'") .
                        (empty($serial_no) ? '' : " && serial_no = '$serial_no'") .
                        (is_numeric($prints) ? " && prints = '$prints'" : '') .
                        (empty($printed_at_since) ? '' : " && printed_at >= '$printed_at_since'") .
                        (empty($printed_at_till) ? '' : " && printed_at <= '$printed_at_till'") .
                        (is_numeric($appeal_prints) ? " && appeal_prints = '$appeal_prints'" : '') .
                        (empty($appeal_printed_at_since) ? '' : " && appeal_printed_at >= '$appeal_printed_at_since'") .
                        (empty($appeal_printed_at_till) ? '' : " && appeal_printed_at <= '$appeal_printed_at_till'") .
                        (is_numeric($appeal_origin) ? " && appeal_origin = '$appeal_origin'" : '')
                )->orderBy('id desc')->$oneOrAll();
    }

}
