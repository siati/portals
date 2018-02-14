<?php

namespace frontend\modules\client\modules\student\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use frontend\modules\client\modules\student\models\Applicants;
use frontend\modules\client\modules\student\models\ApplicantsResidence;
use frontend\modules\client\modules\student\models\ApplicantsParents;
use frontend\modules\client\modules\student\models\EducationBackground;
use frontend\modules\client\modules\student\models\ApplicantsGuarantors;
use frontend\modules\client\modules\student\models\ApplicantsInstitution;
use frontend\modules\client\modules\student\models\ApplicantsFamilyExpenses;
use frontend\modules\client\modules\student\models\ApplicantsSiblingEducationExpenses;
use frontend\modules\client\modules\student\models\ApplicantsEmployment;
use frontend\modules\client\modules\student\models\ApplicantsSpouse;
use frontend\modules\client\modules\student\models\ApplicantSponsors;
use frontend\modules\business\models\ProductOpening;
use frontend\modules\business\models\ProductOpeningSettings;
use frontend\modules\business\models\Applications;
use common\models\User;
use common\models\StaticMethods;
use common\models\LmBankBranch;
use common\models\LmBaseEnums;
use common\models\LmInstitution;
use common\models\LmInstitutionBranches;
use common\models\LmCourses;
use common\models\LmEmployers;
use common\models\PDFGenerator;
use common\models\Docs;

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
                    'index', 'register', 'residence', 'parents', 'check-parent-status', 'parent-is-guarantor', 'education', 'grade', 'merits', 'inst-types', 'out-ofs', 'educ-since-till', 'guarantors', 'id-no-is-parents',
                    'institution', 'employment', 'dynamic-institutions', 'dynamic-institution-branches', 'dynamic-inst-types', 'dynamic-admission-categories', 'dynamic-course-durations', 'dynamic-study-years', 'completion-year',
                    'expenses', 'spouse', 'sponsors', 'bank-branches', 'dynamic-employers', 'employment-periods', 'application', 'application-timeline', 'load-application', 'institution-partial', 'personal-partial', 'education-partial', 'application-compile', 'amateur-form'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'residence', 'parents', 'check-parent-status', 'parent-is-guarantor', 'education', 'grade', 'merits', 'inst-types', 'out-ofs', 'educ-since-till', 'guarantors', 'id-no-is-parents',
                            'institution', 'employment', 'dynamic-institutions', 'dynamic-institution-branches', 'dynamic-inst-types', 'dynamic-admission-categories', 'dynamic-course-durations', 'dynamic-study-years', 'completion-year',
                            'expenses', 'spouse', 'sponsors', 'bank-branches', 'dynamic-employers', 'employment-periods', 'application', 'application-timeline', 'load-application', 'institution-partial', 'personal-partial', 'education-partial', 'application-compile', 'amateur-form'
                        ],
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

            if (Yii::$app->request->isAjax && !isset($_POST['sbmt'])) {
                $ajax1 = ($ajax = $this->ajaxValidate($applicant)) === self::IS_AJAX ? [] : $ajax;

                $ajax2 = ($ajax = $this->ajaxValidate($user)) === self::IS_AJAX ? [] : $ajax;

                if (is_array($ajax1) || is_array($ajax2))
                    return ((is_array($ajax1) ? $ajax1 : []) + (is_array($ajax2) ? $ajax2 : []));
            }

            $wasNew = $user->isNewRecord;

            $saved = $user->validate() && $applicant->validate() && $applicant->theTransaction($user);

            $wasNew && !$user->isNewRecord && Yii::$app->getResponse()->redirect(Yii::$app->getUser()->loginUrl);
        }

        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render($user->isNewRecord ? 'register' : 'personal', ['applicant' => $applicant, 'user' => $user, 'saved' => !empty($saved)]);
    }

    /**
     * 
     * @return string view to update residence details
     */
    public function actionResidence() {
        $model = ApplicantsResidence::residenceToLoad(empty($_POST['ApplicantsResidence']['id']) ? '' : $_POST['ApplicantsResidence']['id'], empty($_POST['ApplicantsResidence']['applicant']) ? '' : $_POST['ApplicantsResidence']['applicant']);

        if (isset($_POST['ApplicantsResidence']['nearest_primary']) && $model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax && !isset($_POST['sbmt']))
                if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                    return is_array($ajax) ? $ajax : [];

            $saved = $model->modelSave();
        }

        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('residence', ['model' => $model, 'saved' => !empty($saved)]);
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

                if (Yii::$app->request->isAjax && !isset($_POST['sbmt']))
                    if (($ajax = $this->ajaxValidate($parent)) === self::IS_AJAX || count($ajax) > 0)
                        return is_array($ajax) ? $ajax : [];

                $saved = $parent->modelSave();
            }

            $form_content = $this->renderPartial('parent', ['parent' => isset($relations[$parent->relationship]) ? $parent : $parent = ApplicantsParents::parentToLoad(null, $parent->applicant, empty($relationships[0]) ? ApplicantsParents::relationship_father : $relationships[0], $dob), 'relationship' => $relations[$parent->relationship]]);
        }

        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('parents', ['relationships' => isset($_POST['ApplicantsParents']) ? $relationships : [], 'relations' => $relations, 'form_content' => $form_content, 'applicant' => empty($applicant->id) ? '' : $applicant->id, 'saved' => !empty($saved)]);
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

            if (Yii::$app->request->isAjax && !isset($_POST['sbmt']))
                if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                    return is_array($ajax) ? $ajax : [];

            $saved = $model->modelSave();
        }

        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('education', ['backgrounds' => EducationBackground::levelsToLoad($applicant), 'applicant' => $model->applicant, 'form_content' => $this->renderPartial('education-form', ['model' => $model]), 'saved' => !empty($saved)]);
    }

    /**
     * 
     * @return string view to update guarantor's details
     */
    public function actionGuarantors() {
        $applicant = Applicants::applicantToLoad($applicant_id = $_POST['ApplicantsGuarantors']['applicant']);

        $model = ApplicantsGuarantors::guarantorToLoad(empty($_POST['ApplicantsGuarantors']['id']) ? '' : $_POST['ApplicantsGuarantors']['id'], empty($applicant->id) ? '' : $applicant->id, empty($_POST['ApplicantsGuarantors']['id_no']) ? '' : $_POST['ApplicantsGuarantors']['id_no'], $dob = empty($applicant->dob) ? Applicants::min_age : substr($applicant->dob, 0, 4));

        if (isset($_POST['ApplicantsGuarantors']['id_no']) && ($model->IDNoIsParents() || ($model->load(Yii::$app->request->post()) && ($model->IDNoIsSpouses() || true)))) {

            if (Yii::$app->request->isAjax && !isset($_POST['sbmt']))
                if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                    return is_array($ajax) ? $ajax : [];

            $saved = $model->modelSave();
        }

        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('guarantors', ['guarantors' => ApplicantsGuarantors::guarantorsToLoad($applicant_id), 'form_content' => $this->renderPartial('guarantor', ['model' => $model, 'posted' => isset($saved)]), 'applicant' => empty($model->applicant) ? '' : $model->applicant, 'saved' => !empty($saved)]);
    }

    /**
     * 
     * client validation for guarantor's id no
     */
    public function actionIdNoIsParents() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = ApplicantsGuarantors::guarantorToLoad($_POST['ApplicantsGuarantors']['id'], $_POST['ApplicantsGuarantors']['applicant'], $_POST['ApplicantsGuarantors']['id_no'], Applicants::min_age);

        $attributes = ['0' => ($isParent = $model->IDNoIsParents()) || ($isSpouse = $model->IDNoIsSpouses()), '1' => [], '2' => []];

        $mdl = !empty($isSpouse) ? (new ApplicantsSpouse()) : (!empty($isParent) ? new ApplicantsParents() : []);

        $attributeLabels = empty($mdl) ? [] : $mdl->attributeLabels();

        !empty($isSpouse) ? $attributeLabels += [$attr = 'gender' => $model->getAttributeLabel($attr), $attr = 'postal_no' => $model->getAttributeLabel($attr), $attr = 'postal_code' => $model->getAttributeLabel($attr), $attr = 'county' => $model->getAttributeLabel($attr), $attr = 'sub_county' => $model->getAttributeLabel($attr), $attr = 'constituency' => $model->getAttributeLabel($attr), $attr = 'ward' => $model->getAttributeLabel($attr), $attr = 'location' => $model->getAttributeLabel($attr), $attr = 'sub_location' => $model->getAttributeLabel($attr), $attr = 'village' => $model->getAttributeLabel($attr)] : '';

        foreach ($model->attributeLabels() as $attribute => $label)
            if (!in_array($attribute, ['id', 'applicant', 'id_no', 'created_by', 'created_at', 'modified_by', 'modified_at'])) {
                $attributes['1']["applicantsguarantors-$attribute"] = $model->$attribute;
                isset($attributeLabels[$attribute]) ? '' : $attributes['2']["applicantsguarantors-$attribute"] = $model->$attribute;
            }

        return $attributes;
    }

    /**
     * 
     * @return string view to update applicant institution details
     */
    public function actionInstitution() {
        $model = ApplicantsInstitution::institutionToLoad(empty($_POST['ApplicantsInstitution']['id']) ? '' : $_POST['ApplicantsInstitution']['id'], empty($_POST['ApplicantsInstitution']['applicant']) ? '' : $_POST['ApplicantsInstitution']['applicant']);

        if (isset($_POST['ApplicantsInstitution']['institution_code']) && $model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax && !isset($_POST['sbmt']))
                if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                    return is_array($ajax) ? $ajax : [];

            $saved = $model->modelSave();
        }

        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('institution', ['model' => $model, 'saved' => !empty($saved)]);
    }

    /**
     * 
     * @return string view to update employment details
     */
    public function actionEmployment() {
        $model = ApplicantsEmployment::employmentToLoad(empty($_POST['ApplicantsEmployment']['id']) ? '' : $_POST['ApplicantsEmployment']['id'], empty($_POST['ApplicantsEmployment']['applicant']) ? '' : $_POST['ApplicantsEmployment']['applicant']);

        if (isset($_POST['ApplicantsEmployment']['employer_name']) && $model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax && !isset($_POST['sbmt']))
                if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                    return is_array($ajax) ? $ajax : [];

            $saved = $model->modelSave();
        }

        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('employment', ['model' => $model, 'saved' => !empty($saved)]);
    }

    /**
     * 
     * @return string view to update family expenses
     */
    public function actionExpenses() {
        $family_expenses = ApplicantsFamilyExpenses::expensesToLoad($applicant = $_POST['applicant']);

        $sibling_expense = ApplicantsSiblingEducationExpenses::expenseToLoad(empty($_POST['ApplicantsSiblingEducationExpenses']['id']) ? '' : $_POST['ApplicantsSiblingEducationExpenses']['id'], $applicant, empty($_POST['ApplicantsSiblingEducationExpenses']['birth_cert_no']) ? '' : $_POST['ApplicantsSiblingEducationExpenses']['birth_cert_no'], empty($_POST['ApplicantsSiblingEducationExpenses']['id_no']) ? '' : $_POST['ApplicantsSiblingEducationExpenses']['id_no']);

        $ajaxes = [];

        $is_ajax = false;

        $ajax_init = Yii::$app->request->isAjax && !isset($_POST['sbmt']);

        if (isset($_POST['ApplicantsFamilyExpenses']) && ApplicantsFamilyExpenses::loadMultiple($family_expenses, Yii::$app->request->post()))
            if ($ajax_init && (($ajax = $this->ajaxValidateMultiple($family_expenses)) === self::IS_AJAX || count($ajax) > 0)) {
                $ajaxes += (is_array($ajax) ? $ajax : []);
                $is_ajax = true;
            } else
                foreach ($family_expenses as $family_expense)
                    $saved = (isset($saved) ? $saved : true) && $family_expense->modelSave();

        if (isset($_POST['ApplicantsSiblingEducationExpenses']['fname']) && ((!$sibling_expense->isNewRecord || static::newSiblingSubmitted())) && $sibling_expense->load(Yii::$app->request->post()))
            if ($ajax_init && (($ajax = $this->ajaxValidate($sibling_expense)) === self::IS_AJAX || count($ajax) > 0)) {
                $ajaxes += (is_array($ajax) ? $ajax : []);
                $is_ajax = true;
            } else
                $saved2 = (isset($saved2) ? $saved2 : true) && $sibling_expense->modelSave();

        if ($is_ajax)
            return $ajaxes;

        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('expenses', ['applicant' => $applicant, 'family_expenses' => $family_expenses, 'sibling_expenses' => ApplicantsSiblingEducationExpenses::expensesForApplicant($applicant), 'sibling_expense' => $sibling_expense, 'saved' => !empty($saved), 'save_attempt' => isset($saved), 'saved2' => !empty($saved2), 'save_attempt2' => isset($saved2)]);
    }

    /**
     * 
     * @return boolean true - new sibling submitted
     */
    public static function newSiblingSubmitted() {
        return !empty($_POST['ApplicantsSiblingEducationExpenses']['fname']) || !empty($_POST['ApplicantsSiblingEducationExpenses']['mname']) || !empty($_POST['ApplicantsSiblingEducationExpenses']['lname']) || !empty($_POST['ApplicantsSiblingEducationExpenses']['birth_cert_no']) || !empty($_POST['ApplicantsSiblingEducationExpenses']['id_no']) || !empty($_POST['ApplicantsSiblingEducationExpenses']['institution_name']) || !empty($_POST['ApplicantsSiblingEducationExpenses']['annual_fees']);
    }

    /**
     * 
     * @return string view to update spouse details
     */
    public function actionSpouse() {
        $model = ApplicantsSpouse::spouseToLoad(empty($_POST['ApplicantsSpouse']['id']) ? '' : $_POST['ApplicantsSpouse']['id'], empty($_POST['ApplicantsSpouse']['applicant']) ? '' : $_POST['ApplicantsSpouse']['applicant']);

        if (isset($_POST['ApplicantsSpouse']['id_no']) && $model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax && !isset($_POST['sbmt']))
                if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                    return is_array($ajax) ? $ajax : [];

            $saved = $model->modelSave();
        }

        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('spouse', ['model' => $model, 'saved' => !empty($saved)]);
    }

    /**
     * 
     * @return string view to update sponsor's details
     */
    public function actionSponsors() {
        $model = ApplicantSponsors::sponsorToLoad(empty($_POST['ApplicantSponsors']['id']) ? '' : $_POST['ApplicantSponsors']['id'], empty($_POST['ApplicantSponsors']['applicant']) ? '' : $_POST['ApplicantSponsors']['applicant']);

        if (isset($_POST['ApplicantSponsors']['name']) && $model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax && !isset($_POST['sbmt']))
                if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                    return is_array($ajax) ? $ajax : [];

            $saved = $model->modelSave();
        }

        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('sponsors', ['sponsors' => ApplicantSponsors::sponsorsToLoad($model->applicant), 'form_content' => $this->renderPartial('sponsor', ['model' => $model]), 'applicant' => empty($model->applicant) ? '' : $model->applicant, 'saved' => !empty($saved)]);
    }

    /**
     * 
     * @return string view for applicants to select their desired loans from application
     */
    public function actionApplication() {
        return $this->render('products', ['user' => User::returnUser($_POST['Applications']['applicant']), 'applicant' => Applicants::returnApplicant($_POST['Applications']['applicant'])]);
    }

    /**
     * 
     * @return string view for 
     */
    public function actionApplicationTimeline() {
        $openings = ProductOpening::applicantViewableApplications($_POST['product'], $_POST['applicant'], StaticMethods::now());

        return $this->renderAjax('applicant-product-timeline', ['openings' => $openings[0], 'appeals' => $openings[1], 'applicant' => $_POST['applicant']]);
    }

    /**
     * 
     * @return type view to apply for a loan
     */
    public function actionLoadApplication() {
        $application = Applications::applicationToLoad(empty($_POST['Applications']['id']) ? '' : $_POST['Applications']['id'], empty($_POST['Applications']['applicant']) ? '' : $_POST['Applications']['applicant'], $opening = empty($_POST['Applications']['application']) ? '' : $_POST['Applications']['application'], empty($_POST['Applications']['serial_no']) ? '' : $_POST['Applications']['serial_no']);

        if (!empty($_POST['appeal']))
            return $this->renderAjax('appeal-form', ['application' => $application]);

        if (ProductOpening::returnOpening($opening)->subsequent == LmBaseEnums::applicantType(LmBaseEnums::applicant_type_subsequent)->VALUE)
            return $this->renderAjax('subsequent-form', ['personal_det' => Applicants::returnApplicant($application->applicant), 'institution' => ApplicantsInstitution::forApplicant($application->applicant), 'upkeep' => ProductOpeningSettings::tuitionOrUpkeep($opening)[\frontend\modules\business\models\ProductSettings::no]]);

        return $this->renderAjax('first-time-form', [
                    'application' => $application,
                    'settings' => ProductOpeningSettings::forApplicationSettingAndActive($opening, null, ProductOpeningSettings::active, ProductOpeningSettings::all)
                        ]
        );
    }

    /**
     * @return array any errors on capture institution details
     */
    public function actionInstitutionPartial() {
        $model = ApplicantsInstitution::institutionToLoad(empty($_POST['ApplicantsInstitution']['id']) ? '' : $_POST['ApplicantsInstitution']['id'], empty($_POST['ApplicantsInstitution']['applicant']) ? '' : $_POST['ApplicantsInstitution']['applicant']);

        isset($_POST['ApplicantsInstitution']['annual_fees']) && !isset($_POST['ApplicantsInstitution']['annual_upkeep']) ? $model->annual_upkeep = null : '';

        isset($_POST['ApplicantsInstitution']['annual_upkeep']) && !isset($_POST['ApplicantsInstitution']['annual_fees']) ? $model->annual_fees = null : '';

        if ($model->load(Yii::$app->request->post())) {

            $ajax = $this->ajaxValidate($model);

            $model->modelSave();
        }

        return isset($ajax) && is_array($ajax) ? $ajax : [];
    }

    /**
     * @return array any errors on capture personal details
     */
    public function actionPersonalPartial() {
        $model = Applicants::returnApplicant($_POST['Applicants']['id']);

        if ($model->load(Yii::$app->request->post())) {

            $ajax = $this->ajaxValidate($model);

            $model->modelSave();
        }

        return isset($ajax) && is_array($ajax) ? $ajax : [];
    }

    /**
     * @return array any errors on capture education background details
     */
    public function actionEducationPartial() {
        $model = EducationBackground::returnEducation($_POST['EducationBackground']['id']);

        if ($model->load(Yii::$app->request->post())) {

            $ajax = $this->ajaxValidate($model);

            $model->modelSave();
        }

        return isset($ajax) && is_array($ajax) ? $ajax : [];
    }

    /**
     * load employers dynamically
     */
    public function actionDynamicEmployers() {
        StaticMethods::populateDropDown(StaticMethods::modelsToArray(LmEmployers::searchEmployers(null, $_POST['search_name'], LmEmployers::all), 'ACCOUNTNUM', 'NAME', true), 'Select Employer', $_POST['selected']);
    }

    /**
     * load bank branches dynamically
     */
    public function actionBankBranches() {
        StaticMethods::populateDropDown(StaticMethods::modelsToArray(LmBankBranch::searchBranches($_POST['bank'], null, LmBankBranch::all), 'BRANCHCODE', 'BRANCHNAME', true), 'Bank Branch', $_POST['branch']);
    }

    /**
     * load institutions dynamically
     */
    public function actionDynamicInstitutions() {
        StaticMethods::populateDropDown(LmInstitution::institutions($_POST['country'], $_POST['institution_type'], LmBaseEnums::schoolTypeFromAdmissionCategory($_POST['admission_category'])->VALUE, LmBaseEnums::yesOrNo(LmBaseEnums::yes)->VALUE), 'Institution', $_POST['institution_code']);
    }

    /**
     * load institution branches dynamically
     */
    public function actionDynamicInstitutionBranches() {
        StaticMethods::populateDropDown(LmInstitutionBranches::branches($_POST['institution_code'], LmBaseEnums::yesOrNo(LmBaseEnums::yes)->VALUE), 'Branch', $_POST['institution_branch_code']);
    }

    /**
     * load institution types dynamically
     */
    public function actionDynamicInstTypes() {
        StaticMethods::populateDropDown(LmBaseEnums::institutionTypes(LmBaseEnums::byNameAndValue(LmBaseEnums::study_level, $_POST['level_of_study'])->ELEMENT, isset($_POST['pri_sec'])), 'Institution Type', $_POST['institution_type']);
    }

    /**
     * load admission categories dynamically
     */
    public function actionDynamicAdmissionCategories() {
        StaticMethods::populateDropDown(LmBaseEnums::admissionCategories(LmBaseEnums::byNameAndValue(LmBaseEnums::study_level, $_POST['level_of_study'])->ELEMENT), 'Admission Category', $_POST['admission_category']);
    }

    /**
     * load institution courses dynamically
     */
    public function actionDynamicCourses() {
        StaticMethods::populateDropDown(LmCourses::courses($_POST['institution_code'], $_POST['institution_branch_code'], $_POST['level_of_study'], $_POST['faculty'], $_POST['course_type'], $_POST['course_category'], LmBaseEnums::yesOrNo(LmBaseEnums::yes)->VALUE), 'Course', $_POST['course_code']);
    }

    /**
     * load course duration dynamically
     */
    public function actionDynamicCourseDurations() {
        StaticMethods::populateDropDown(LmBaseEnums::courseDurations($_POST['level_of_study']), 'Duration', $_POST['duration']);
    }

    /**
     * load dynamic years dynamically
     */
    public function actionDynamicStudyYears() {
        StaticMethods::populateDropDown(LmBaseEnums::studyYears($_POST['level_of_study']), 'Study Year', $_POST['year_of_study']);
    }

    /**
     * compute completion year dynamically
     */
    public function actionCompletionYear() {
        echo StaticMethods::monthArithmentics($_POST['year_of_admission'], $_POST['admission_month'], $_POST['duration'], null)[0];
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

    /**
     * load employment durations dynamically
     */
    public function actionEmploymentPeriods() {
        StaticMethods::populateDropDown(ApplicantsEmployment::employmentPeriod($_POST['terms']), 'Duration', $_POST['period']);
    }

    /**
     * 
     * @return string view to compile application
     */
    public function actionApplicationCompile() {
        $application = Applications::applicationToLoad(null, $_POST['applicant'], $_POST['application'], null);

        return $this->renderAjax('application-compile', ['compilation' => $application->compileApplication(!empty($_POST['appeal'])), 'applicant' => $_POST['applicant'], 'application' => $_POST['application'], 'appeal' => $_POST['appeal'], 'print' => !empty($_POST['print']) || $application->applicationOrAppealPrintOutExists(!empty($_POST['appeal']))]);
    }

    /**
     * amateur application form
     */
    public function actionAmateurForm() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $application = Applications::applicationToLoad(null, $_POST['applicant'], $_POST['application'], null);

        return [PDFGenerator::category_client, basename(Docs::fileLocate(PDFGenerator::category_laf, $application->modelSave($is_appeal = !empty($_POST['appeal'])) ? ($is_appeal ? $application->appeal_print_out : $application->print_out) : ('nope'), Docs::locator)), $application->applicationOrAppealPrintOutExists(!empty($_POST['appeal']))];
    }

}
