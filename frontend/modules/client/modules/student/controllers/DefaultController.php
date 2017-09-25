<?php

namespace frontend\modules\client\modules\student\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use frontend\modules\client\modules\student\models\Applicants;
use frontend\modules\client\modules\student\models\ApplicantsParents;
use frontend\modules\client\modules\student\models\EducationBackground;
use frontend\modules\client\modules\student\models\ApplicantsGuarantors;
use frontend\modules\client\modules\student\models\ApplicantsInstitution;
use common\models\User;
use common\models\StaticMethods;

/**
 * Default controller for the `student` module
 */
class DefaultController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'index', 'register', 'parents', 'check-parent-status', 'parent-is-guarantor', 'education', 'grade', 'merits', 'inst-types', 'out-ofs', 'educ-since-till', 'guarantors', 'id-no-is-parents', 'institution'
                ],
                'rules' => [
                    [
                        'actions' => ['index', 'parents', 'check-parent-status', 'parent-is-guarantor', 'education', 'grade', 'merits', 'inst-types', 'out-ofs', 'educ-since-till', 'guarantors', 'id-no-is-parents', 'institution'],
                        'allow' => !Yii::$app->user->isGuest,
                        'roles' => ['@'],
                        'verbs' => ['post']
                    ],
                    [
                        'actions' => ['register'],
                        'allow' => Yii::$app->user->isGuest || !empty($_POST['User']['id']),
                        'roles' => ['?', '@'],
                        'verbs' => ['post']
                    ]
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * 
     * @return string view to register and update personal details
     */
    public function actionRegister() {
        $applicant = Applicants::applicantToLoad(empty($_POST['Applicants']['id']) ? '' : $_POST['Applicants']['id']);

        $user = User::userToLoad(empty($_POST['User']['id']) ? '' : $_POST['User']['id']);

        if (isset($_POST['Applicants']['fname']) && $applicant->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {

            $ajax1 = ($ajax = $this->ajaxValidate($applicant)) === self::IS_AJAX ? [] : $ajax;

            $ajax2 = ($ajax = $this->ajaxValidate($user)) === self::IS_AJAX ? [] : $ajax;

            if (is_array($ajax1) || is_array($ajax2))
                return ((is_array($ajax1) ? $ajax1 : []) + (is_array($ajax2) ? $ajax2 : []));

            $wasNew = $user->isNewRecord;

            $user->validate() && $applicant->validate() && $applicant->theTransaction($user);

            $wasNew && !$user->isNewRecord && Yii::$app->getResponse()->redirect(Yii::$app->getUser()->loginUrl);
        }

        return $this->render($user->isNewRecord ? 'register' : 'personal', ['applicant' => $applicant, 'user' => $user]);
    }

    /**
     * 
     * @return string view to update parent's details
     */
    public function actionParents() {
        $relationships = ApplicantsParents::parentsToLoad($applicant_id = empty($_POST['Applicants']['id']) ? (empty($_POST['ApplicantsParents']['applicant']) ? '' : $_POST['ApplicantsParents']['applicant']) : ($_POST['Applicants']['id']));

        $relations = ApplicantsParents::relationships();

        $applicant = Applicants::applicantToLoad($applicant_id);

        if (empty($relationships) || isset($_POST['Applicants'])) {
            if (isset($_POST['Applicants']['parents']) && $applicant->load(Yii::$app->request->post())) {

                if (($ajax = $this->ajaxValidate($applicant)) === self::IS_AJAX || count($ajax) > 0)
                    return !is_array($ajax) && ($applicant->updateParentalStatus() || true) ? [] : $ajax;

                Yii::$app->end();
            }

            $form_content = $this->renderPartial('parental-status', ['applicant' => $applicant]);
        } else {
            $parent = ApplicantsParents::parentToLoad(empty($_POST['ApplicantsParents']['id']) ? '' : $_POST['ApplicantsParents']['id'], empty($applicant->id) ? '' : $applicant->id, empty($_POST['ApplicantsParents']['relationship']) ? '' : $_POST['ApplicantsParents']['relationship'], $dob = empty($applicant->dob) ? Applicants::min_age : substr($applicant->dob, 0, 4));

            if (isset($_POST['ApplicantsParents']['fname']) && $parent->load(Yii::$app->request->post())) {

                if (($ajax = $this->ajaxValidate($parent)) === self::IS_AJAX || count($ajax) > 0)
                    return is_array($ajax) ? $ajax : [];

                $parent->modelSave();
            }

            $form_content = $this->renderPartial('parent', ['parent' => isset($relations[$parent->relationship]) ? $parent : $parent = ApplicantsParents::parentToLoad(null, $parent->applicant, empty($relationships[0]) ? ApplicantsParents::relationship_father : $relationships[0], $dob), 'relationship' => $relations[$parent->relationship]]);
        }

        return $this->render('parents', ['relationships' => isset($_POST['ApplicantsParents']) ? $relationships : [], 'relations' => $relations, 'form_content' => $form_content, 'applicant' => empty($applicant->id) ? '' : $applicant->id]);
    }

    /**
     * 
     * @return array|string json object
     */
    public function actionCheckParentStatus() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $applicant = Applicants::applicantToLoad($_POST['Applicants']['id']);

        $applicant->load(Yii::$app->request->post());

        $father = ApplicantsParents::parentToLoad(null, $applicant->id, ApplicantsParents::relationship_father, $dob = empty($applicant->dob) ? Applicants::min_age : substr($applicant->dob, 0, 4));

        $mother = ApplicantsParents::parentToLoad(null, $applicant->id, ApplicantsParents::relationship_mother, $dob);

        return [$applicant->validate(['parents', 'father_death_cert_no', 'mother_death_cert_no']), $father->isMinor(), $mother->isMinor()];
    }
    
    /**
     * check whether parent is guarantor
     */
    public function actionParentIsGuarantor() {
        echo is_object(ApplicantsParents::parentIsGuarantor($_POST['ApplicantsParents']['applicant'], $_POST['ApplicantsParents']['id_no']));
    }

    /**
     * 
     * @return string view to update applicant education background
     */
    public function actionEducation() {
        $model = EducationBackground::educationToLoad(empty($_POST['EducationBackground']['id']) ? '' : $_POST['EducationBackground']['id'], $applicant = $_POST['EducationBackground']['applicant'], empty($_POST['EducationBackground']['study_level']) ? '' : $_POST['EducationBackground']['study_level']);

        if (isset($_POST['EducationBackground']['institution_name']) && $model->load(Yii::$app->request->post())) {

            if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                return is_array($ajax) ? $ajax : [];

            $model->modelSave();
        }

        return $this->render('education', ['backgrounds' => EducationBackground::levelsToLoad($applicant), 'applicant' => $model->applicant, 'form_content' => $this->renderPartial('education-form', ['model' => $model])]);
    }

    /**
     * 
     * @return string view to update guarantor's details
     */
    public function actionGuarantors() {
        $applicant = Applicants::applicantToLoad($applicant_id = $_POST['ApplicantsGuarantors']['applicant']);

        $model = ApplicantsGuarantors::guarantorToLoad(empty($_POST['ApplicantsGuarantors']['id']) ? '' : $_POST['ApplicantsGuarantors']['id'], empty($applicant->id) ? '' : $applicant->id, empty($_POST['ApplicantsGuarantors']['id_no']) ? '' : $_POST['ApplicantsGuarantors']['id_no'], $dob = empty($applicant->dob) ? Applicants::min_age : substr($applicant->dob, 0, 4));

        if (isset($_POST['ApplicantsGuarantors']['id_no']) && ($model->IDNoIsParents() || $model->load(Yii::$app->request->post()))) {

            if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                return is_array($ajax) ? $ajax : [];

            $model->modelSave();
        }

        return $this->render('guarantors', ['guarantors' => ApplicantsGuarantors::guarantorsToLoad($applicant_id), 'form_content' => $this->renderPartial('guarantor', ['model' => $model]), 'applicant' => empty($model->applicant) ? '' : $model->applicant]);
    }

    /**
     * 
     * client validation for guarantor's id no
     */
    public function actionIdNoIsParents() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = ApplicantsGuarantors::guarantorToLoad($_POST['ApplicantsGuarantors']['id'], $_POST['ApplicantsGuarantors']['applicant'], $_POST['ApplicantsGuarantors']['id_no'], Applicants::min_age);

        $attributes = ['0' => $model->IDNoIsParents()];

        foreach ($model->attributeLabels() as $attribute => $label)
            if (!in_array($attribute, ['id', 'applicant', 'id_no', 'created_by', 'created_at', 'modified_by', 'modified_at']))
                $attributes['1']["applicantsguarantors-$attribute"] = $model->$attribute;

        return $attributes;
    }
    
    /**
     * 
     * @return string view to update applicant institution details
     */
    public function actionInstitution() {
        $model = ApplicantsInstitution::institutionToLoad(empty($_POST['ApplicantsInstitution']['id']) ? '' : $_POST['ApplicantsInstitution']['id'], empty($_POST['ApplicantsInstitution']['applicant']) ? '' : $_POST['ApplicantsInstitution']['applicant']);

        if (isset($_POST['ApplicantsInstitution']['institution_code']) && $model->load(Yii::$app->request->post())) {

            if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                return is_array($ajax) ? $ajax : [];

            $model->modelSave();
        }

        return $this->render('institution', ['model' => $model]);
    }

    /**
     * load computed grade to the view
     */
    public function actionGrade() {
        echo EducationBackground::theGrade($_POST['score'], $_POST['out_of']);
    }

    /**
     * load grades to the view
     */
    public function actionMerits() {
        StaticMethods::populateDropDown(EducationBackground::merits($level = $_POST['study_level']), 'Grades', null);
    }

    /**
     * load institution types to the view
     */
    public function actionInstTypes() {
        StaticMethods::populateDropDown(EducationBackground::institutionTypesToDisplay($level = $_POST['study_level']), null, null);
    }

    /**
     * load out of value onto view
     */
    public function actionOutOfs() {
        echo EducationBackground::outOfs($_POST['study_level'], $_POST['year']);
    }

    /**
     * load education years onto view
     */
    public function actionEducSinceTill() {
        $educationYears = EducationBackground::admissionAndExaminationYears($_POST['applicant'], $_POST['study_level']);

        $since = StaticMethods::ranges($educationYears[1] + 10, $educationYears[0], 1, true);

        $till = empty($since) ? [] : StaticMethods::ranges(max($since) + 4, $educationYears[0] + 2, 1, true);

        StaticMethods::populateDropDown(isset($_POST['since']) ? $since : $till, null, $_POST['value']);
    }

}
