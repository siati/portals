<?php

namespace common\activeQueries;

use common\models\LmCourses;

/**
 * This is the ActiveQuery class for [[\common\models\LmCourses]].
 *
 * @see \common\models\LmCourses
 */
class LmCoursesQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \common\models\LmCourses[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\LmCourses|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param string $institution_code institution code
     * @param string $institution_branch_code branch code
     * @param string $level_of_study level of study
     * @param string $faculty faculty
     * @param string $course_type course type
     * @param string $course_category course category
     * @param integer $active active
     * @return LmCourses ActiveRecords
     */
    public function searchCourses($institution_code, $institution_branch_code, $level_of_study, $faculty, $course_type, $course_category, $active) {
        return $this->where(
                        "INSTITUTIONCODE = '$institution_code'" .
                        (empty($institution_branch_code) ? '' : " && INSTITUTIONBRANCHCODE = '$institution_branch_code'") .
                        (empty($level_of_study) ? '' : " && LEVELOFSTUDY = '$level_of_study'") .
                        (empty($faculty) ? '' : " && FACULTY = '$faculty'") .
                        (empty($course_type) ? '' : " && COURSETYPE = '$course_type'") .
                        (empty($course_category) ? '' : " && COURSECATEGORY = '$course_category'") .
                        (is_numeric($active) ? " && ACTIVE = '$active'" : '')
                )->orderBy('COURSEDESCRIPTION asc')->all();
    }

}
