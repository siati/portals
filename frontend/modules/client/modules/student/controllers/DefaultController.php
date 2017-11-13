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
                    'expenses', 'spouse', 'sponsors', 'bank-branches', 'dynamic-employers', 'employment-periods', 'loans'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'residence', 'parents', 'check-parent-status', 'parent-is-guarantor', 'education', 'grade', 'merits', 'inst-types', 'out-ofs', 'educ-since-till', 'guarantors', 'id-no-is-parents',
                            'institution', 'employment', 'dynamic-institutions', 'dynamic-institution-branches', 'dynamic-inst-types', 'dynamic-admission-categories', 'dynamic-course-durations', 'dynamic-study-years', 'completion-year',
                            'expenses', 'spouse', 'sponsors', 'bank-branches', 'dynamic-employers', 'employment-periods', 'loans'
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
     * @return string view to update residence details
     */
    public function actionResidence() {
        $model = ApplicantsResidence::residenceToLoad(empty($_POST['ApplicantsResidence']['id']) ? '' : $_POST['ApplicantsResidence']['id'], empty($_POST['ApplicantsResidence']['applicant']) ? '' : $_POST['ApplicantsResidence']['applicant']);

        if (isset($_POST['ApplicantsResidence']['nearest_primary']) && $model->load(Yii::$app->request->post())) {

            if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                return is_array($ajax) ? $ajax : [];

            $model->modelSave();
        }

        return $this->render('residence', ['model' => $model]);
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

        if (isset($_POST['ApplicantsGuarantors']['id_no']) && ($model->IDNoIsParents() || ($model->load(Yii::$app->request->post()) && ($model->IDNoIsSpouses() || true)))) {

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

            if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                return is_array($ajax) ? $ajax : [];

            $model->modelSave();
        }

        return $this->render('institution', ['model' => $model]);
    }

    /**
     * 
     * @return string view to update employment details
     */
    public function actionEmployment() {
        $model = ApplicantsEmployment::employmentToLoad(empty($_POST['ApplicantsEmployment']['id']) ? '' : $_POST['ApplicantsEmployment']['id'], empty($_POST['ApplicantsEmployment']['applicant']) ? '' : $_POST['ApplicantsEmployment']['applicant']);

        if (isset($_POST['ApplicantsEmployment']['employer_name']) && $model->load(Yii::$app->request->post())) {

            if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                return is_array($ajax) ? $ajax : [];

            $model->modelSave();
        }

        return $this->render('employment', ['model' => $model]);
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

        if (isset($_POST['ApplicantsFamilyExpenses']) && ApplicantsFamilyExpenses::loadMultiple($family_expenses, Yii::$app->request->post()))
            if (($ajax = $this->ajaxValidateMultiple($family_expenses)) === self::IS_AJAX || count($ajax) > 0) {
                $ajaxes += (is_array($ajax) ? $ajax : []);
                $is_ajax = true;
            } else
                foreach ($family_expenses as $family_expense)
                    $family_expense->modelSave();

        if (isset($_POST['ApplicantsSiblingEducationExpenses']['fname']) && $sibling_expense->load(Yii::$app->request->post()))
            if (($ajax = $this->ajaxValidate($sibling_expense)) === self::IS_AJAX || count($ajax) > 0) {
                $ajaxes += (is_array($ajax) ? $ajax : []);
                $is_ajax = true;
            } else
                $sibling_expense->modelSave();

        if ($is_ajax)
            return $ajaxes;

        return $this->render('expenses', ['applicant' => $applicant, 'family_expenses' => $family_expenses, 'sibling_expenses' => ApplicantsSiblingEducationExpenses::expensesForApplicant($applicant), 'sibling_expense' => $sibling_expense]);
    }

    /**
     * 
     * @return string view to update spouse details
     */
    public function actionSpouse() {
        $model = ApplicantsSpouse::spouseToLoad(empty($_POST['ApplicantsSpouse']['id']) ? '' : $_POST['ApplicantsSpouse']['id'], empty($_POST['ApplicantsSpouse']['applicant']) ? '' : $_POST['ApplicantsSpouse']['applicant']);

        if (isset($_POST['ApplicantsSpouse']['id_no']) && $model->load(Yii::$app->request->post())) {

            if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                return is_array($ajax) ? $ajax : [];

            $model->modelSave();
        }

        return $this->render('spouse', ['model' => $model]);
    }

    /**
     * 
     * @return string view to update sponsor's details
     */
    public function actionSponsors() {
        $model = ApplicantSponsors::sponsorToLoad(empty($_POST['ApplicantSponsors']['id']) ? '' : $_POST['ApplicantSponsors']['id'], empty($_POST['ApplicantSponsors']['applicant']) ? '' : $_POST['ApplicantSponsors']['applicant']);

        if (isset($_POST['ApplicantSponsors']['name']) && $model->load(Yii::$app->request->post())) {

            if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                return is_array($ajax) ? $ajax : [];

            $model->modelSave();
        }

        return $this->render('sponsors', ['sponsors' => ApplicantSponsors::sponsorsToLoad($model->applicant), 'form_content' => $this->renderPartial('sponsor', ['model' => $model]), 'applicant' => empty($model->applicant) ? '' : $model->applicant]);
    }
    
    /**
     * 
     * @return string view for applicants to select their desired loans from application
     */
    public function actionLoans() {
        return $this->render('loans', ['user' => User::returnUser($_POST['Applications']['applicant']), 'applicant' => Applicants::returnApplicant($_POST['Applications']['applicant'])]);
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
     * amateur application form
     */
    public function actionAmateurForm() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $application = Applications::applicationToLoad(null, Yii::$app->user->identity->id, 2, null);

        return [PDFGenerator::category_client, basename(Docs::fileLocate(PDFGenerator::category_laf, $application->modelSave(false) ? $application->print_out : 'nope', Docs::locator))];
    }

}
