<?php

namespace frontend\modules\client\modules\student\activeQueries;

use frontend\modules\client\modules\student\models\FinancialLiteracyScores;

/**
 * This is the ActiveQuery class for [[\frontend\modules\client\modules\student\models\FinancialLiteracyScores]].
 *
 * @see \frontend\modules\client\modules\student\models\FinancialLiteracyScores
 */
class FinancialLiteracyScoresQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\FinancialLiteracyScores[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\FinancialLiteracyScores|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param string $academic_year academic year
     * @param integer $application application id
     * @param string $module module
     * @param integer $attempts attempts
     * @param integer $score score as percentage
     * @param string $created_at created at
     * @param string $modified_at modified at
     * @param string $oneOrAll one or all
     * @return FinancialLiteracyScores ActiveRecord(s)
     */
    public function searchScores($applicant, $academic_year, $application, $module, $attempts, $score, $created_at, $modified_at, $oneOrAll) {
        $aplcn = \frontend\modules\business\models\Applications::tableName();

        $opng = \frontend\modules\business\models\ProductOpening::tableName();

        $t = FinancialLiteracyScores::tableName();

        return $this->select('t.*, aplcn.applicant, opng.id as opening, opng.product, opng.academic_year')
                        ->from("$aplcn aplcn")
                        ->leftJoin("$opng opng", "aplcn.application = opng.id && opng.id > 0")
                        ->leftJoin("$t t", "aplcn.id = t.application && t.id > 0")
                        ->where(
                                'aplcn.id > 0 && opng.id is not null && t.id is not null' .
                                (empty($applicant) ? '' : " && aplcn.applicant = '$applicant'") .
                                (empty($academic_year) ? '' : " && opng.academic_year = '$academic_year'") .
                                (empty($application) ? '' : " && aplcn.id = '$application'") .
                                (empty($module) ? '' : " && t.module = '$module'") .
                                (empty($attempts) ? '' : " && t.attempts = '$attempts'") .
                                (is_numeric($score) ? " && t.score = '$score'" : '') .
                                (empty($created_at) ? '' : " && t.created_at >= '$created_at'") .
                                (empty($modified_at) ? '' : " && t.modified_at <= '$modified_at'")
                        )->groupBy('t.id')->orderBy('t.id desc')->$oneOrAll();
    }

}
