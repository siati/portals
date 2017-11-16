<?php

namespace frontend\modules\client\modules\student\models;

use common\models\User;
use common\models\LmBaseEnums;
use common\models\LmInstitution;
use common\models\Counties;
use common\models\SubCounties;
use common\models\Constituencies;
use common\models\Wards;
use common\models\StaticMethods;

/**
 * define the checks against which applicant can access a product for application
 */
class ApplicantProductAccessCheckers {

    const section_applicant = 'applicant';
    const section_employment = 'employment';
    const section_institution = 'institution';
    const section_parents = 'parents';
    const section_residence = 'residence';
    const section_siblings = 'siblings';
    const section_spouse = 'spouse';
    const section_education_background = 'education_background';
    const section_guarantors = 'guarantors';
    const section_name = 'section_name';
    const section_items = 'section_items';
    const property = 'property';
    const name = 'name';
    const table = 'table';
    const column = 'columns';
    const model = 'model';
    const attribute = 'attribute';
    const type = 'type';
    const type_large_array = 'large_array';
    const type_text = 'string';
    const type_date = 'date';
    const type_numeric = 'numeric';
    const type_integer = 'integer';
    const type_not_numeric = 'not_numeric';
    const value_set = 'value_set';
    const min_length = 'min_length';
    const max_length = 'max_length';
    const operation = 'operation';
    const placeholder = 'placeholder';
    const comma = ',';
    const equal_to = self::column . " = '" . self::placeholder . "'";
    const like = self::equal_to . ' || ' . self::column . " like '" . self::placeholder . ",%' || " . self::column . " like '%," . self::placeholder . ",%' || " . self::column . " like '%," . self::placeholder . "'";
    const active = 'active';
    const active_yes = '1';
    const active_no = '0';

    /**
     * @return array[] all the checkers grouped in sections
     */
    public static function checkerSections() {
        return [
            self::section_applicant => [self::section_name => 'Personal Details', self::section_items => static::applicant()],
            self::section_residence => [self::section_name => 'Current Residence Details', self::section_items => static::currentResidenceDetails()],
            self::section_employment => [self::section_name => 'Employment Details', self::section_items => static::employmentDetails()],
            self::section_spouse => [self::section_name => 'Spouse Details', self::section_items => static::spouseDetails()],
            self::section_siblings => [self::section_name => 'Applicants\' Siblings', self::section_items => static::siblingDetails()],
            self::section_education_background => [self::section_name => 'Education Background', self::section_items => static::educationBackground()],
            self::section_guarantors => [self::section_name => 'Guarantors / Referees', self::section_items => static::guarantorDetails()],
            self::section_parents => [self::section_name => 'Parent\'s Details', self::section_items => static::parentsDetails()],
            self::section_institution => [self::section_name => 'Institution Details', self::section_items => static::institutionDetails()]
        ];
    }

    /**
     * 
     * @param string $column column name to replace column placeholder
     * @param string $value value to replace placeholder
     * @param string $operation where with placeholder for value
     * @return string where with column and value
     */
    public static function placeholderToValue($column, $value, $operation) {
        return str_replace(self::column, $column, str_replace(self::placeholder, str_replace('\\', '', str_replace('"', '', str_replace("'", '', $value))), $operation));
    }

    /**
     * 
     * @param string $property access property
     * @return string column name
     */
    public static function applicantIDColumn($property) {
        return in_array($property, ['app_birth_cert', 'app_id_number', 'app_kra_pin']) ? 'id' : 'applicant';
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param string $property access property
     * @param string $column column name to replace column placeholder
     * @param string $value value to replace placeholder
     * @param string $operation where with placeholder for value
     * @return array where sub-clause
     */
    public static function theWhereSubClauses($applicant, $property, $column, $value, $operation) {
        return [
            static::applicantIDColumn($property) . " = '$applicant' && $column != '' && $column is not null",
            static::placeholderToValue($column, $value, $operation)
        ];
    }

    /**
     * 
     * @return array[] checks against the applicants details
     */
    public static function applicant() {
        return [
            [self::property => 'app_birth_cert', self::name => 'Birth Certificate No.', self::table => User::tableName(), self::column => $col = 'birth_cert_no', self::model => get_class(new User), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 6, self::max_length => 7, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'app_id_number', self::name => 'National ID. Card No.', self::table => User::tableName(), self::column => $col = 'id_no', self::model => get_class(new User), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 7, self::max_length => 8, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'app_kra_pin', self::name => 'KRA PIN', self::table => User::tableName(), self::column => $col = 'kra_pin', self::model => get_class(new User), self::attribute => $col, self::type => self::type_not_numeric, self::value_set => null, self::min_length => 11, self::max_length => 11, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'app_dob', self::name => 'Date of Birth', self::table => Applicants::tableName(), self::column => $col = 'dob', self::model => get_class(new Applicants), self::attribute => $col, self::type => self::type_date, self::value_set => null, self::min_length => 10, self::max_length => 10, self::operation => self::like, self::active => self::active_no],
            [self::property => 'app_gender', self::name => 'Applicant\'s Gender', self::table => Applicants::tableName(), self::column => $col = 'gender', self::model => get_class(new Applicants), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::genders(), self::min_length => 1, self::max_length => 1, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'app_disability', self::name => 'Applicant\'s Disability', self::table => Applicants::tableName(), self::column => $col = 'disability', self::model => get_class(new Applicants), self::attribute => $col, self::type => self::type_text, self::value_set => Applicants::disabilities(), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'app_married', self::name => 'Applicant Married', self::table => Applicants::tableName(), self::column => $col = 'married', self::model => get_class(new Applicants), self::attribute => $col, self::type => self::type_text, self::value_set => Applicants::marrieds(), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'app_parental_status', self::name => 'Parental Status', self::table => Applicants::tableName(), self::column => $col = 'parents', self::model => get_class(new Applicants), self::attribute => $col, self::type => self::type_text, self::value_set => Applicants::parentalStatuses(), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'app_county', self::name => 'Applicant\'s County', self::table => Applicants::tableName(), self::column => $col = 'county', self::model => get_class(new Applicants), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(Counties::allCounties(), 'id', 'name', true), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'app_sub_county', self::name => 'Applicant\'s Subcounty', self::table => Applicants::tableName(), self::column => $col = 'sub_county', self::model => get_class(new Applicants), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(SubCounties::allSubcounties(), 'id', 'name', true), self::min_length => 1, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'app_constituency', self::name => 'Applicant\'s Constituency', self::table => Applicants::tableName(), self::column => $col = 'constituency', self::model => get_class(new Applicants), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(Constituencies::allConstituencies(), 'id', 'name', true), self::min_length => 1, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'app_ward', self::name => 'Applicant\'s Ward', self::table => Applicants::tableName(), self::column => $col = 'ward', self::model => get_class(new Applicants), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(Wards::allWards(), 'id', 'name', true), self::min_length => 1, self::max_length => 5, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'app_employed', self::name => 'Applicant Employed', self::table => Applicants::tableName(), self::column => $col = 'employed', self::model => get_class(new Applicants), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::yesNo(), self::min_length => 1, self::max_length => 1, self::operation => self::like, self::active => self::active_yes]
        ];
    }

    /**
     * 
     * @return array[] checks against the applicants current residence details
     */
    public static function currentResidenceDetails() {
        return [
            [self::property => 'res_county', self::name => 'County of Residence', self::table => ApplicantsResidence::tableName(), self::column => $col = 'county', self::model => get_class(new ApplicantsResidence), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(Counties::allCounties(), 'id', 'name', true), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'res_sub_county', self::name => 'Subcounty of Residence', self::table => ApplicantsResidence::tableName(), self::column => $col = 'sub_county', self::model => get_class(new ApplicantsResidence), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(SubCounties::allSubcounties(), 'id', 'name', true), self::min_length => 1, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'res_constituency', self::name => 'Constituency of Residence', self::table => ApplicantsResidence::tableName(), self::column => $col = 'constituency', self::model => get_class(new ApplicantsResidence), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(Constituencies::allConstituencies(), 'id', 'name', true), self::min_length => 1, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'res_ward', self::name => 'Ward of Residence', self::table => ApplicantsResidence::tableName(), self::column => $col = 'ward', self::model => get_class(new ApplicantsResidence), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(Wards::allWards(), 'id', 'name', true), self::min_length => 1, self::max_length => 5, self::operation => self::like, self::active => self::active_yes],
        ];
    }

    /**
     * 
     * @return array[] checks against the applicants employment details
     */
    public static function employmentDetails() {
        return [
            [self::property => 'emp_employer_name', self::name => 'Name of Employer', self::table => ApplicantsEmployment::tableName(), self::column => $col = 'employer_name', self::model => get_class(new ApplicantsEmployment), self::attribute => $col, self::type => self::type_large_array, self::value_set => null, self::min_length => 1, self::max_length => 20, self::operation => "$col " . self::like, self::active => self::active_yes],
            [self::property => 'emp_county', self::name => 'County Posted', self::table => ApplicantsEmployment::tableName(), self::column => $col = 'county', self::model => get_class(new ApplicantsEmployment), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(Counties::allCounties(), 'id', 'name', true), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'emp_employment_terms', self::name => 'Employment Terms', self::table => ApplicantsEmployment::tableName(), self::column => $col = 'employment_terms', self::model => get_class(new ApplicantsEmployment), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::employmentTerms(), self::min_length => 1, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'emp_employment_date', self::name => 'Employment Date', self::table => ApplicantsEmployment::tableName(), self::column => $col = 'employment_date', self::model => get_class(new ApplicantsEmployment), self::attribute => $col, self::type => self::type_date, self::value_set => null, self::min_length => 10, self::max_length => 10, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'emp_employment_period', self::name => 'Employment Period', self::table => ApplicantsEmployment::tableName(), self::column => $col = 'employment_period', self::model => get_class(new ApplicantsEmployment), self::attribute => $col, self::type => self::type_integer, self::value_set => ApplicantsEmployment::employmentPeriod(null), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'emp_basic_pay', self::name => 'Basic Pay', self::table => ApplicantsEmployment::tableName(), self::column => $col = 'basic_salary', self::model => get_class(new ApplicantsEmployment), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 4, self::max_length => 7, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'emp_net_pay', self::name => 'Net Pay', self::table => ApplicantsEmployment::tableName(), self::column => $col = 'net_salary', self::model => get_class(new ApplicantsEmployment), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 4, self::max_length => 7, self::operation => self::like, self::active => self::active_yes],
        ];
    }

    /**
     * 
     * @return array[] checks against the applicants spouse details
     */
    public static function spouseDetails() {
        return [
            [self::property => 'spo_relationship', self::name => 'Spouse Relationship', self::table => ApplicantsSpouse::tableName(), self::column => $col = 'relationship', self::model => get_class(new ApplicantsSpouse), self::attribute => $col, self::type => self::type_text, self::value_set => ApplicantsSpouse::relatioships(), self::min_length => 1, self::max_length => 1, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'spo_id_number', self::name => 'Spouse ID. No.', self::table => ApplicantsSpouse::tableName(), self::column => $col = 'id_no', self::model => get_class(new ApplicantsSpouse), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 7, self::max_length => 8, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'spo_kra_pin', self::name => 'Spouse KRA PIN', self::table => ApplicantsSpouse::tableName(), self::column => $col = 'kra_pin', self::model => get_class(new ApplicantsSpouse), self::attribute => $col, self::type => self::type_not_numeric, self::value_set => null, self::min_length => 11, self::max_length => 11, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'spo_employed', self::name => 'Spouse Employed', self::table => ApplicantsSpouse::tableName(), self::column => $col = 'employed', self::model => get_class(new ApplicantsSpouse), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::yesNo(), self::min_length => 1, self::max_length => 1, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'spo_employer', self::name => 'Spouse\'s Employer', self::table => ApplicantsSpouse::tableName(), self::column => $col = 'employer_name', self::model => get_class(new ApplicantsSpouse), self::attribute => $col, self::type => self::type_not_numeric, self::value_set => null, self::min_length => 10, self::max_length => 60, self::operation => "$col " . self::like, self::active => self::active_yes]
        ];
    }

    /**
     * 
     * @return array[] checks against the applicants sibling details
     */
    public static function siblingDetails() {
        return [
            [self::property => 'sib_birth_cert', self::name => 'Sibling\'s Birth Cert. No.', self::table => ApplicantsSiblingEducationExpenses::tableName(), self::column => $col = 'birth_cert_no', self::model => get_class(new ApplicantsSiblingEducationExpenses), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 6, self::max_length => 7, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'sib_id_number', self::name => 'Sibling\'s ID. No.', self::table => ApplicantsSiblingEducationExpenses::tableName(), self::column => $col = 'id_no', self::model => get_class(new ApplicantsSiblingEducationExpenses), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 7, self::max_length => 8, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'sib_study_level', self::name => 'Sibling\'s Level of Study', self::table => ApplicantsSiblingEducationExpenses::tableName(), self::column => $col = 'study_level', self::model => get_class(new ApplicantsSiblingEducationExpenses), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::studyLevels(), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'sib_inst_type', self::name => 'Sibling\'s Institution Type', self::table => ApplicantsSiblingEducationExpenses::tableName(), self::column => $col = 'institution_type', self::model => get_class(new ApplicantsSiblingEducationExpenses), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::institutionTypes(null, true), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'sib_inst_name', self::name => 'Sibling\'s Institution', self::table => ApplicantsSiblingEducationExpenses::tableName(), self::column => $col = 'institution_name', self::model => get_class(new ApplicantsSiblingEducationExpenses), self::attribute => $col, self::type => self::type_not_numeric, self::value_set => null, self::min_length => 10, self::max_length => 60, self::operation => "$col " . self::like, self::active => self::active_yes],
            [self::property => 'sib_annual_fees', self::name => 'Sibling\'s Annual Fees', self::table => ApplicantsSiblingEducationExpenses::tableName(), self::column => $col = 'annual_fees', self::model => get_class(new ApplicantsSiblingEducationExpenses), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 4, self::max_length => 7, self::operation => self::like, self::active => self::active_yes]
        ];
    }

    /**
     * 
     * @return array[] checks against the applicants education background
     */
    public static function educationBackground() {
        return [
            [self::property => 'edu_inst_name', self::name => 'Institution Name', self::table => EducationBackground::tableName(), self::column => $col = 'institution_name', self::model => get_class(new EducationBackground), self::attribute => $col, self::type => self::type_not_numeric, self::value_set => null, self::min_length => 10, self::max_length => 60, self::operation => "$col " . self::like, self::active => self::active_yes],
            [self::property => 'edu_inst_type', self::name => 'Institution Type', self::table => EducationBackground::tableName(), self::column => $col = 'institution_type', self::model => get_class(new EducationBackground), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::institutionTypes(null, true), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'edu_school_type', self::name => 'School Type', self::table => EducationBackground::tableName(), self::column => $col = 'school_type', self::model => get_class(new EducationBackground), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::schoolTypes(), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'edu_study_level', self::name => 'Level of Study', self::table => EducationBackground::tableName(), self::column => $col = 'study_level', self::model => get_class(new EducationBackground), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::studyLevels(), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'edu_course_name', self::name => 'Course Name', self::table => EducationBackground::tableName(), self::column => $col = 'course_name', self::model => get_class(new EducationBackground), self::attribute => $col, self::type => self::type_text, self::value_set => null, self::min_length => 10, self::max_length => 60, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'edu_adm_year', self::name => 'Year of Admission', self::table => EducationBackground::tableName(), self::column => $col = 'since', self::model => get_class(new EducationBackground), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 4, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'edu_exam_year', self::name => 'Examination Year', self::table => EducationBackground::tableName(), self::column => $col = 'till', self::model => get_class(new EducationBackground), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 4, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'edu_adm_no', self::name => 'Adm. / Reg. / Exam No.', self::table => EducationBackground::tableName(), self::column => $col = 'exam_no', self::model => get_class(new EducationBackground), self::attribute => $col, self::type => self::type_text, self::value_set => null, self::min_length => 7, self::max_length => 20, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'edu_exam_score', self::name => 'Examination Score', self::table => EducationBackground::tableName(), self::column => $col = 'score', self::model => get_class(new EducationBackground), self::attribute => $col, self::type => self::type_text, self::value_set => null, self::min_length => 1, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'edu_exam_grade', self::name => 'Examination Grade', self::table => EducationBackground::tableName(), self::column => $col = 'grade', self::model => get_class(new EducationBackground), self::attribute => $col, self::type => self::type_text, self::value_set => null, self::min_length => 1, self::max_length => 4, self::operation => self::like, self::active => self::active_yes]
        ];
    }

    /**
     * 
     * @return array[] checks against the applicant guarantor details
     */
    public static function guarantorDetails() {
        return [
            [self::property => 'grt_yob', self::name => 'Year of Birth', self::table => ApplicantsGuarantors::tableName(), self::column => $col = 'yob', self::model => get_class(new ApplicantsGuarantors), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 4, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'grt_gender', self::name => 'Guarantor\'s Gender', self::table => ApplicantsGuarantors::tableName(), self::column => $col = 'gender', self::model => get_class(new ApplicantsGuarantors), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::genders(), self::min_length => 1, self::max_length => 1, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'grt_id_number', self::name => 'Guarantor\'s ID. No.', self::table => ApplicantsGuarantors::tableName(), self::column => $col = 'id_no', self::model => get_class(new ApplicantsGuarantors), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 7, self::max_length => 8, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'grt_kra_pin', self::name => 'Guarantor\'s KRA PIN', self::table => ApplicantsGuarantors::tableName(), self::column => $col = 'kra_pin', self::model => get_class(new ApplicantsGuarantors), self::attribute => $col, self::type => self::type_not_numeric, self::value_set => null, self::min_length => 11, self::max_length => 11, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'grt_county', self::name => 'Guarantor\'s County', self::table => ApplicantsGuarantors::tableName(), self::column => $col = 'county', self::model => get_class(new ApplicantsGuarantors), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(Counties::allCounties(), 'id', 'name', true), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'grt_sub_county', self::name => 'Guarantor\'s Subcounty', self::table => ApplicantsGuarantors::tableName(), self::column => $col = 'sub_county', self::model => get_class(new ApplicantsGuarantors), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(SubCounties::allSubcounties(), 'id', 'name', true), self::min_length => 1, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'grt_constituency', self::name => 'Guarantor\'s Constituency', self::table => ApplicantsGuarantors::tableName(), self::column => $col = 'constituency', self::model => get_class(new ApplicantsGuarantors), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(Constituencies::allConstituencies(), 'id', 'name', true), self::min_length => 1, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'grt_ward', self::name => 'Guarantor\'s Ward', self::table => ApplicantsGuarantors::tableName(), self::column => $col = 'ward', self::model => get_class(new ApplicantsGuarantors), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(Wards::allWards(), 'id', 'name', true), self::min_length => 1, self::max_length => 5, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'grt_employed', self::name => 'Guarantor Employed', self::table => ApplicantsGuarantors::tableName(), self::column => $col = 'employed', self::model => get_class(new ApplicantsGuarantors), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::yesNo(), self::min_length => 1, self::max_length => 1, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'grt_employer', self::name => 'Guarantor\'s Employer', self::table => ApplicantsGuarantors::tableName(), self::column => $col = 'employer_name', self::model => get_class(new ApplicantsGuarantors), self::attribute => $col, self::type => self::type_not_numeric, self::value_set => null, self::min_length => 10, self::max_length => 60, self::operation => "$col " . self::like, self::active => self::active_yes]
        ];
    }

    /**
     * 
     * @return array[] checks against the applicant parents details
     */
    public static function parentsDetails() {
        return [
            [self::property => 'prt_relationship', self::name => 'Parent Relationship', self::table => ApplicantsParents::tableName(), self::column => $col = 'relationship', self::model => get_class(new ApplicantsParents), self::attribute => $col, self::type => self::type_text, self::value_set => ApplicantsParents::relationships(), self::min_length => 1, self::max_length => 1, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'prt_yob', self::name => 'Year of Birth', self::table => ApplicantsParents::tableName(), self::column => $col = 'yob', self::model => get_class(new ApplicantsParents), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 4, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'prt_gender', self::name => 'Parent\'s Gender', self::table => ApplicantsParents::tableName(), self::column => $col = 'gender', self::model => get_class(new ApplicantsParents), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::genders(), self::min_length => 1, self::max_length => 1, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'prt_birth_cert', self::name => 'Parent\'s Birth Cert. No.', self::table => ApplicantsParents::tableName(), self::column => $col = 'birth_cert_no', self::model => get_class(new ApplicantsParents), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 6, self::max_length => 7, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'prt_id_number', self::name => 'Parent\'s ID. No.', self::table => ApplicantsParents::tableName(), self::column => $col = 'id_no', self::model => get_class(new ApplicantsParents), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 7, self::max_length => 8, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'prt_kra_pin', self::name => 'Parents\'s KRA PIN', self::table => ApplicantsParents::tableName(), self::column => $col = 'kra_pin', self::model => get_class(new ApplicantsParents), self::attribute => $col, self::type => self::type_not_numeric, self::value_set => null, self::min_length => 11, self::max_length => 11, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'prt_education', self::name => 'Parents\'s Education', self::table => ApplicantsParents::tableName(), self::column => $col = 'education_level', self::model => get_class(new ApplicantsParents), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::studyLevels(), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'prt_county', self::name => 'Parents\'s County', self::table => ApplicantsParents::tableName(), self::column => $col = 'county', self::model => get_class(new ApplicantsParents), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(Counties::allCounties(), 'id', 'name', true), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'prt_sub_county', self::name => 'Parents\'s Subcounty', self::table => ApplicantsParents::tableName(), self::column => $col = 'sub_county', self::model => get_class(new ApplicantsParents), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(SubCounties::allSubcounties(), 'id', 'name', true), self::min_length => 1, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'prt_constituency', self::name => 'Parents\'s Constituency', self::table => ApplicantsParents::tableName(), self::column => $col = 'constituency', self::model => get_class(new ApplicantsParents), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(Constituencies::allConstituencies(), 'id', 'name', true), self::min_length => 1, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'prt_ward', self::name => 'Parents\'s Ward', self::table => ApplicantsParents::tableName(), self::column => $col = 'ward', self::model => get_class(new ApplicantsParents), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(Wards::allWards(), 'id', 'name', true), self::min_length => 1, self::max_length => 5, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'prt_employed', self::name => 'Parents Employed', self::table => ApplicantsParents::tableName(), self::column => $col = 'employed', self::model => get_class(new ApplicantsParents), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::yesNo(), self::min_length => 1, self::max_length => 1, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'prt_employer', self::name => 'Parents\'s Employer', self::table => ApplicantsParents::tableName(), self::column => $col = 'employer_name', self::model => get_class(new ApplicantsParents), self::attribute => $col, self::type => self::type_not_numeric, self::value_set => null, self::min_length => 10, self::max_length => 60, self::operation => "$col " . self::like, self::active => self::active_yes]
        ];
    }

    /**
     * 
     * @return array[] checks against the applicant institution details
     */
    public static function institutionDetails() {
        return [
            [self::property => 'inst_country', self::name => 'Country', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'country', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::countries(), self::min_length => 1, self::max_length => 5, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_study_level', self::name => 'Level of Study', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'level_of_study', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::studyLevels(), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_inst_type', self::name => 'Institution Type', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'institution_type', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::institutionTypes(null, true), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_adm_category', self::name => 'Admission Category', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'admission_category', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::admissionCategories(null), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_inst_code', self::name => 'Institution Name', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'institution_code', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_text, self::value_set => StaticMethods::modelsToArray(LmInstitution::searchInstitutions(null, null, null, LmBaseEnums::byNameAndElement(LmBaseEnums::yes_no, LmBaseEnums::yes)->VALUE), 'INSTITUTIONCODE', 'INSTITUTIONNAME', true), self::min_length => 1, self::max_length => 20, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_inst_branch', self::name => 'Institution Branch', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'institution_branch_code', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_large_array, self::value_set => null, self::min_length => 1, self::max_length => 20, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_faculty', self::name => 'Faculty', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'faculty', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_not_numeric, self::value_set => null, self::min_length => 7, self::max_length => 60, self::operation => "$col " . self::like, self::active => self::active_yes],
            [self::property => 'inst_department', self::name => 'Department', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'department', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_not_numeric, self::value_set => null, self::min_length => 7, self::max_length => 60, self::operation => "$col " . self::like, self::active => self::active_yes],
            [self::property => 'inst_course_category', self::name => 'Course Category', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'course_category', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::courseCategories(), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_course_type', self::name => 'Course Type', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'course_type', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_text, self::value_set => LmBaseEnums::courseTypes(), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_course_name', self::name => 'Course Name', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'course_code', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_large_array, self::value_set => null, self::min_length => 1, self::max_length => 20, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_adm_year', self::name => 'Admission Year', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'year_of_admission', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 4, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_adm_month', self::name => 'Month of Admission', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'admission_month', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_integer, self::value_set => StaticMethods::months(), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_course_duration', self::name => 'Course Duration', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'duration', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_numeric, self::value_set => LmBaseEnums::courseDurationWithFractions(), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_exit_year', self::name => 'Completion Year', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'year_of_completion', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 4, self::max_length => 4, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_exit_month', self::name => 'Month of Completion', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'completion_month', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_integer, self::value_set => StaticMethods::months(), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_study_year', self::name => 'Year of Study', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'year_of_study', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_integer, self::value_set => LmBaseEnums::studyYears(LmBaseEnums::byNameAndElement(LmBaseEnums::study_level, LmBaseEnums::study_level_degree)->VALUE), self::min_length => 1, self::max_length => 2, self::operation => self::like, self::active => self::active_yes],
            [self::property => 'inst_annual_fees', self::name => 'Annual Fees', self::table => ApplicantsInstitution::tableName(), self::column => $col = 'annual_fees', self::model => get_class(new ApplicantsInstitution), self::attribute => $col, self::type => self::type_integer, self::value_set => null, self::min_length => 4, self::max_length => 7, self::operation => self::like, self::active => self::active_yes]
        ];
    }

}
