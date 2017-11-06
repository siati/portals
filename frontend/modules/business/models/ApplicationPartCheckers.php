<?php

namespace frontend\modules\business\models;

/**
 * define the sections applications can contain
 */
class ApplicationPartCheckers {

    const part_caution = 'caution';
    const part_personal = 'personal';
    const part_residence = 'residence';
    const part_institution = 'institution';
    const part_next_of_kin = 'next_of_kin';
    const part_loan = 'loan';
    const part_siblings = 'siblings';
    const part_education = 'education';
    const part_employment = 'employment';
    const part_parents = 'parents';
    const part_guardians = 'guardians';
    const part_expenses = 'expenses';
    const part_education_expenses = 'education_expenses';
    const part_declaration = 'declaration';
    const part_guarantors = 'guarantors';
    const part_bank = 'bank';
    const part_terms_and_conditions = 'terms_and_conditions';
    const part_check_list = 'check_list';
    const part_submission = 'submission';
    const part_institution_dean_registrar = 'institution_dean_registrar';
    const part_employment_hr_cert = 'employment_hr_cert';
    const part_parents_marital = 'parents_marital';
    const part_parents_parent = 'parents_parent';
    const part_declaration_applicant = 'declaration_applicant';
    const part_declaration_parent_guardian = 'declaration_parent_guardian';
    const part_declaration_priest_kadhi = 'declaration_priest_kadhi';
    const part_declaration_chief = 'declaration_chief';
    const part_declaration_commissioner_of_oath = 'declaration_commissioner_of_oath';
    const part_declaration_institution = 'declaration_institution';
    const part_declaration_finance_officer = 'declaration_finance_officer';
    const part_declaration_county_officer = 'declaration_county_officer';
    const part_declaration_ward_admin = 'declaration_ward_admin';
    const part_declaration_fund_secretary = 'declaration_fund_secretary';
    const part_check_list_applicant = 'check_list_applicant';
    const part_check_list_parent = 'check_list_parent';
    const part_check_list_guarantors = 'check_list_guarantors';
    const part_check_list_attachment_confirm = 'check_list_attachment_confirm';
    const part_check_list_signatures_stamps = 'check_list_signatures_stamps';
    const part_check_list_signatures_stamps_confirm = 'check_list_signatures_stamps_confirm';
    const order = 'order';
    const title = 'title';
    const intro = 'intro';
    const new_page = 'new_page';
    const order_elements = 'order_elements';
    const element = 'element';
    const narration = 'narration';
    const items = 'items';
    const active = 'active';
    const active_no = 0;
    const active_yes = 1;
    const order_elements_no = 0;
    const order_elements_yes = 1;
    const no_order = 0;

    /**
     * this is a line break and must not be modified in any way.
     * In case of loss of it, copy a line break from a text area field and paste in the string
     */
    const line_break = '
';

    /**
     * @return array[] all the checkers grouped in parts
     */
    public static function checkerParts() {
        return [
            self::part_caution => [self::title => 'CAUTION', self::intro => static::cautionStatement(), self::order => $order = 1, self::new_page => false, self::order_elements => self::order_elements_no],
            self::part_personal => [self::title => 'Applicant\'s Personal Details', self::intro => false, self::order => ++$order, self::new_page => false, self::order_elements => self::order_elements_no],
            self::part_residence => [self::title => 'Applicant\'s Current Place of Residence', self::intro => false, self::order => ++$order, self::new_page => false, self::order_elements => self::order_elements_no],
            self::part_institution => [self::title => 'Applicant\'s Institutional Details', self::intro => 'You are required to attach a copy of your Admission Letter', self::order => ++$order, self::new_page => false, self::items => static::institutionItems(), self::order_elements => self::order_elements_no],
            self::part_next_of_kin => [self::title => 'Next of Kin', self::intro => false, self::order => ++$order, self::new_page => false, self::order_elements => self::order_elements_no],
            self::part_loan => [self::title => 'Loan Applied (Per Annum)', self::intro => false, self::order => ++$order, self::new_page => false, self::order_elements => self::order_elements_no],
            self::part_siblings => [self::title => 'Siblings applying for HELB Loan this year', self::intro => false, self::order => ++$order, self::new_page => false, self::order_elements => self::order_elements_no],
            self::part_education => [self::title => 'Education Background', self::intro => 'Attach certificate in each case', self::order => ++$order, self::new_page => false, self::order_elements => self::order_elements_no],
            self::part_employment => [self::title => 'Employment Details', self::intro => false, self::order => ++$order, self::new_page => false, self::items => static::employmentItems(), self::order_elements => self::order_elements_no],
            self::part_parents => [self::title => 'Parents\' Details', self::intro => null, self::order => ++$order, self::new_page => false, self::items => static::parentsItems(), self::order_elements => self::order_elements_no],
            self::part_guardians => [self::title => 'Guardian / Sponsor / Public Trustee', self::intro => 'Attach letter from school or sponsor in each case', self::order => ++$order, self::new_page => false, self::order_elements => self::order_elements_no],
            self::part_expenses => [self::title => 'Estimated Family Monthly Expenses, Kshs', self::intro => null, self::order => ++$order, self::new_page => false, self::order_elements => self::order_elements_no],
            self::part_education_expenses => [self::title => 'Gross Education Expenses, Kshs', self::intro => 'Siblings in Secondary, Tertiary or University, who are not beneficiaries of HELB Loan', self::order => ++$order, self::new_page => false, self::order_elements => self::order_elements_no],
            self::part_declaration => [self::title => 'Declarations', self::intro => null, self::order => ++$order, self::new_page => false, self::items => static::declarationsItems(), self::order_elements => self::order_elements_yes],
            self::part_guarantors => [self::title => 'Guarantors', self::intro => static::guarantorStatement(), self::order => ++$order, self::new_page => false],
            self::part_bank => [self::title => 'Applicant\'s Personal Bank Details', self::intro => 'Attach a copy of bank account card', self::order => ++$order, self::new_page => false, self::order_elements => self::order_elements_no],
            self::part_terms_and_conditions => [self::title => 'Terms and Conditions', self::intro => static::termsAndConditionsStatement(), self::order => ++$order, self::new_page => false, self::order_elements => self::order_elements_no],
            self::part_check_list => [self::title => 'The Checklist', self::intro => null, self::order => ++$order, self::new_page => false, self::items => static::checkListItems(), self::order_elements => self::order_elements_yes],
            self::part_submission => [self::title => 'Submission of the application form', self::intro => static::formSubmissionStatement(), self::order => ++$order, self::new_page => false, self::order_elements => self::order_elements_no]
        ];
    }

    /**
     * @return array institution detail items
     */
    public static function institutionItems() {
        return [
            self::part_institution_dean_registrar => [self::title => 'University Certification(To be done by the Dean of Students or Academic Registrar)', self::intro => static::deanRegistrarStatement(), self::active => self::active_no], # borrowed from ddd1
        ];
    }

    /**
     * @return array employment detail items
     */
    public static function employmentItems() {
        return [
            self::part_employment_hr_cert => [self::title => 'Employer Certification(To be done by the HR manager)', self::intro => static::hrCertificationStatement(), self::active => self::active_no], # borrowed from ddd1
        ];
    }

    /**
     * 
     * @return array parents items
     */
    public static function parentsItems() {
        return [
            self::part_parents_marital => [self::title => 'Parents\' Marital Status', self::narration => null, self::active => self::active_yes, self::order => 1],
            self::part_parents_parent => [self::title => 'Parents\' Details', self::narration => 'Attach a copy of payslip or payment voucher for salary or pension income respectively', self::active => self::active_yes, self::order => 2]
        ];
    }

    /**
     * 
     * @return array declaration items
     */
    public static function declarationsItems() {
        return [
            self::part_declaration_applicant => [self::title => 'Applicant\'s Declaration', self::narration => static::applicantStatement(), self::active => self::active_yes, self::order => 1],
            self::part_declaration_parent_guardian => [self::title => 'Parent / Guardian', self::narration => static::parentStatement(), self::active => self::active_yes, self::order => 2],
            self::part_declaration_priest_kadhi => [self::title => 'Priest / Kadhi', self::narration => static::priestKadhiStatement(), self::active => self::active_yes, self::order => 3],
            self::part_declaration_chief => [self::title => 'Chief/Assistant Chief', self::narration => static::chiefStatement(), self::active => self::active_yes, self::order => 4],
            self::part_declaration_commissioner_of_oath => [self::title => 'Magistrate / Commissioner of Oaths', self::narration => static::commissionerOfOathStatement(), self::active => self::active_yes, self::order => 5],
            self::part_declaration_institution => [self::title => 'Institution Certification', self::narration => static::institutionCertificationStatement(), self::active => self::active_yes, self::order => 6],
            self::part_declaration_finance_officer => [self::title => 'Finance Officer', self::narration => static::financeOfficerStatement(), self::active => self::active_yes, self::order => 7],
            self::part_declaration_county_officer => [self::title => 'County Chief Officer of Education', self::narration => static::countyOfficerStatement(), self::active => self::active_yes, self::order => 8],
            self::part_declaration_ward_admin => [self::title => 'Ward Administrator', self::narration => static::wardAdministratorStatement(), self::active => self::active_yes, self::order => 9],
            self::part_declaration_fund_secretary => [self::title => 'Constituency Education Revolving Fund Secretary', self::narration => static::fundAdministratorStatement(), self::active => self::active_yes, self::order => 10]
        ];
    }

    /**
     * 
     * @return array check list items
     */
    public static function checkListItems() {
        return [
            self::part_check_list_applicant => [self::title => 'Applicant must attach a copy of', self::narration => null, self::active => self::active_yes, self::order => 1],
            self::part_check_list_parent => [self::title => 'From the parent(s), attach a copy of', self::narration => null, self::active => self::active_yes, self::order => 2],
            self::part_check_list_guarantors => [self::title => 'From both referees, attach copies of', self::narration => null, self::active => self::active_yes, self::order => 3],
            self::part_check_list_attachment_confirm => [self::title => 'Attachments confirmation', self::narration => static::attachmentConfirmationStatement(), self::active => self::active_yes, self::order => 4],
            self::part_check_list_signatures_stamps => [self::title => 'Declarations, Signatures and stamps', self::narration => null, self::active => self::active_yes, self::order => 5],
            self::part_check_list_signatures_stamps_confirm => [self::title => 'Declarations, Signatures and stamps confirmation', self::narration => static::signatureAndStampConfirmationStatement(), self::active => self::active_yes, self::order => 6],
        ];
    }

    /**
     * 
     * @return string caution opening statement
     */
    public static function cautionStatement() {
        return
                'Any person or student who when filling a scholarship application form, knowingly makes a false statement whether orally or in writing relating to any matter affecting the'
                . ' request for a scholarship shall be guilty of an offence and shall be liable to a fine of not less than Kenya Shillings Thirty thousand (Ksh. 30,000) or to imprisonment for a'
                . 'term of not less than three years (Section 13 (3) of the Higher Education Loan Board Act (CAP 213A)).'
        ;
    }

    /**
     * 
     * @return string guarantor opening statement
     */
    public static function guarantorStatement() {
        return
                '(also known as "the guarantor" hereby) acknowledge that I am bound to the Higher Education Loans Board in the sum of amount equivalent to what the '
                . 'Board shall grant to ------------------------------------------------------as loan under the agreements together with interest thereon, which amount shall repay to the '
                . 'Higher Education Loans Board in the event that the loanee fails to honor his/her obligation of repaying the same to the Board as from the prescribed time.'
                . self::line_break
                . 'The Board will notify me of the amount granted to the loanee after the award is made. This bond is conditioned to be void only after full repayment by the loanee is effected.'
        ;
    }

    /**
     * 
     * @return string terms and conditions opening statement
     */
    public static function termsAndConditionsStatement() {
        return
                'PLEASE READ THESE TERMS AND CONDITIONS CAREFULLY BEFORE SIGNING THE LOAN APPLICATION FORM.'
                . ' YOUR ACCESS TO THIS LOAN IS CONDITIONED TO YOUR ACCEPTANCE OF THESE TERMS AND CONDITIONS. THESE TERMS ARE APPLICABLE TO ALL LOAN APPLICANTS.'
                . ' BY SIGNING THIS FORM YOU ARE AGREEING TO BE BOUND BY THESE TERMS AND CONDITIONS; DO NOT SIGN THE FORM IF YOU DISAGREE WITH ANY OF THE TERMS.'
        ;
    }

    /**
     * 
     * @return string form submission opening statement
     */
    public static function formSubmissionStatement() {
        return
                'Instructions:'
                . self::line_break
                . ' i. Print 2 copies of the form and take them for the required signatures and stamps.'
                . self::line_break
                . self::line_break
                . 'ii. Please drop 1 hard copy to any of the following centers:'
                . self::line_break
                . '    - The bank where you opened the account'
                . self::line_break
                . '    - HELB Desk at select HUDUMA Kenya Center near you'
                . self::line_break
                . '    - HELB Office at Mezzanine 1, Anniversary Towers, University Way, Nairobi'
                . self::line_break
                . self::line_break
                . 'You may also send the scholarship application form using secure mail/courier service'
        ;
    }

    /**
     * 
     * @return string dean registrar opening statement
     */
    public static function deanRegistrarStatement() {
        return
                'I certify that the applicant is a registered student in this University with Registration No (please do not use a Ref. Number)'
                . ' ...............................................................................................................'
                . self::line_break
                . self::line_break
                . 'Name ......................................................................................................................................'
                . self::line_break
                . self::line_break
                . 'Signature ........................................................................... Date ................................................'
        ;
    }

    /**
     * 
     * @return string hr certification opening statement
     */
    public static function hrCertificationStatement() {
        return
                'I confirm that Mr/Mrs. ....................................................................is an employee of our Company / Organization,'
                . ' and has served for ............. years/months, and has a gross income of KSh. .................................and net income KSH. ...................................'
                . self::line_break
                . 'We pledge to recover/remit any HELB loan funds owed by him/her.'
                . self::line_break
                . self::line_break
                . 'Period remaining if on contract ..........................................................'
                . self::line_break
                . self::line_break
                . 'Name .............................................................................. Telephone No:....................................................'
                . self::line_break
                . self::line_break
                . 'Signature ........................................................................ Official Stamp: .................................................'
        ;
    }

    /**
     * 
     * @return string applicant opening statement
     */
    public static function applicantStatement() {
        return
                'I declare that the information given herein is true to the best of my knowledge. I also understand that this is a loan that must be repaid.'
                . self::line_break
                . self::line_break
                . 'Signature ........................................................................ Date: .................................................'
        ;
    }

    /**
     * 
     * @return string parent opening statement
     */
    public static function parentStatement() {
        return
                'I declare that I have read this form / this form has been read to me and I hereby confirm that the information given herein is true to the best of my knowledge.'
                . self::line_break
                . self::line_break
                . 'Signature ........................................................................ Date: .................................................'
        ;
    }

    /**
     * 
     * @return string priest kadhi opening statement
     */
    public static function priestKadhiStatement() {
        return
                'I confirm that the applicant appeared before me and that I interviewed him/her and hereby state that the information given herein is true to the best of my knowledge.'
                . self::line_break
                . self::line_break
                . 'Signature ........................................................................ Date: .................................................'
        ;
    }

    /**
     * 
     * @return string chief opening statement
     */
    public static function chiefStatement() {
        return
                'I certify that the applicant is a resident of my Sub-Location and that I have checked the information given herein and confirm it to be true to the best of my knowledge.'
                . self::line_break
                . self::line_break
                . 'Signature ........................................................................ Date: .................................................'
        ;
    }

    /**
     * 
     * @return string commissioner of oath opening statement
     */
    public static function commissionerOfOathStatement() {
        return
                'The above applicant and his/her Parent/Guardian appeared before me and made the solemn declaration that the information given herein is correct.'
                . self::line_break
                . self::line_break
                . 'Signature ........................................................................ Date: .................................................'
        ;
    }

    /**
     * 
     * @return string institution certification opening statement
     */
    public static function institutionCertificationStatement() {
        return
                'The above named applicant and his/her parent/guardian appeared before me and made the solemn declaration that the information given herein is correct.'
                . self::line_break
                . self::line_break
                . 'Financial Aid Officer ............................................................ Date:............................................................' # borrowed from Daystar
                . self::line_break
                . self::line_break
                . 'Signature ........................................................................ Official Stamp: .................................................'
        ;
    }

    /**
     * 
     * @return string institution finance officer certification opening statement
     */
    public static function financeOfficerStatement() {
        return
                'The above named applicant appeared before me and made the solemn declaration that the information given herein is correct.'
                . self::line_break
                . self::line_break
                . 'The applicant has a fees balance of KShs. ______________________' # borrowed from barclays
                . self::line_break
                . self::line_break
                . 'Signature ........................................................................ Official Stamp & Date: .......................................'
        ;
    }

    /**
     * 
     * @return string county education officer certification opening statement
     */
    public static function countyOfficerStatement() {
        return
                'The above named applicant and his/her parent/guardian appeared before me and made the solemn declaration that the information given herein is correct.' # borrowed from county form
                . self::line_break
                . self::line_break
                . 'Signature ........................................................................ Official Stamp & Date: .......................................'
        ;
    }

    /**
     * 
     * @return string ward administrator certification opening statement
     */
    public static function wardAdministratorStatement() {
        return
                'I certify that the applicant is a resident of my ward and that I have checked the information given herein and confirm it to be true to the best of my knowledge.' # borrowed from county form
                . self::line_break
                . self::line_break
                . 'Signature ........................................................................ Official Stamp & Date: .......................................'
        ;
    }

    /**
     * 
     * @return string fund administrator certification opening statement
     */
    public static function fundAdministratorStatement() {
        return
                'I certify that the applicant is a resident of my constituency and that I have checked the information given herein and confirm it to be true to the best of my knowledge.' # borrowed from tharaka
                . self::line_break
                . self::line_break
                . 'Signature ........................................................................ Official Stamp & Date: .......................................'
        ;
    }

    /**
     * 
     * @return string attachment confirmation opening statement
     */
    public static function attachmentConfirmationStatement() {
        return
                'I confirm that the above attachments have been attached on the scholarship application form.'
                . self::line_break
                . self::line_break
                . 'Signature ........................................................................ Date: .................................................'
        ;
    }

    /**
     * 
     * @return string signatures and stamps confirmation opening statement
     */
    public static function signatureAndStampConfirmationStatement() {
        return
                'I confirm that the above Signatures and stamps have been effected on the scholarship application form.'
                . self::line_break
                . self::line_break
                . 'Signature ........................................................................ Date: .................................................'
        ;
    }

}
