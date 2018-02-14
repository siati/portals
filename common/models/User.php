<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use frontend\modules\client\modules\student\models\Applicants;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $birth_cert_no
 * @property string $id_no
 * @property string $kra_pin
 * @property string $phone
 * @property string $email
 * @property string $username
 * @property integer $user_type
 * @property integer $profile
 * @property string $auth_key
 * @property string $password_hash
 * @property integer $status
 * @property string $password_reset_token
 * @property string $registered_by
 * @property string $registered_at
 * @property integer $created_at
 * @property string $modified_by
 * @property string $modified_at
 * @property integer $updated_at
 * @property integer $signed_in
 * @property string $last_signed_in
 * @property string $last_signed_out
 * @property string $user_ip
 * @property string $session_id
 */
class User extends ActiveRecord implements IdentityInterface {

    const STATUS_INACTIVE = 0;
    const STATUS_MAIL_CONFIRMED = 1;
    const STATUS_PHONE_CONFIRMED = 2;
    const STATUS_ACTIVE = 3;
    const USER_STUDENT = 0;
    const USER_INSTITUTION = 1;
    const USER_EMPLOYER = 2;
    const USER_PARTNER = 3;
    const USER_BUSINESS = 4;
    const PROFILE_ADMIN = 1;
    const PROFILE_OTHER = 0;
    const CREATED_BY_SELF = 'self';
    const CURRENTLY_NOT_LOGGED_IN = 0;
    const CURRENTLY_LOGGED_IN = 1;

    public $password, $confirm_password;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['email', 'username', 'user_type'], 'required'],
            [['birth_cert_no', 'id_no'], 'required',
                'when' => function () {
                    return empty($this->birth_cert_no) && empty($this->id_no);
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#user-birth_cert_no, #user-id_no').blur();
                        return ($('#user-id_no').val() === null || $('#user-id_no').val() === '') && ($('#user-birth_cert_no').val() === null || $('#user-birth_cert_no').val() === '');
                    }
                ",
                'message' => 'Birth Cetificate or National ID. No. must be provided'
            ],
            [['birth_cert_no'], 'required',
                'when' => function () {
                    return $this->birtCertNoIsRequired();
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#user-birth_cert_no').blur();
                        return $('#applicants-dob').val() === null || $('#applicants-dob').val() === '' || (new Date).getFullYear() - $('#applicants-dob').val().substring(0, 4) * 1 < " . Applicants::maturity . ";
                    }
                "
            ],
            [['id_no'], 'required',
                'when' => function () {
                    return $this->IDNoIsRequired();
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#user-id_no').blur();
                        return $('#user-user_type').val() !== '" . self::USER_STUDENT . "' || ($('#applicants-dob').val() !== null && $('#applicants-dob').val() !== '' && (new Date).getFullYear() - $('#applicants-dob').val().substring(0, 4) * 1 >= " . Applicants::maturity . ");
                    }
                "
            ],
            [['birth_cert_no', 'id_no', 'phone', 'user_type', 'profile', 'status', 'signed_in'], 'integer'],
            [['registered_at', 'modified_at', 'last_signed_in', 'last_signed_out'], 'safe'],
            [['birth_cert_no', 'id_no'], 'string', 'min' => 7, 'max' => 8],
            [['phone'], 'string', 'min' => 9, 'max' => 13],
            [['email', 'username', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key', 'user_ip', 'session_id'], 'string', 'max' => 32],
            [['registered_by', 'modified_by'], 'string', 'max' => 15],
            [['birth_cert_no', 'id_no', 'kra_pin', 'phone', 'email', 'username', 'password_reset_token'], 'unique'],
            ['email', 'email'],
            [['birth_cert_no', 'id_no', 'phone'], 'positiveValue'],
            ['kra_pin', 'string', 'max' => 11, 'length' => 11],
            ['kra_pin', 'toUpperCase'],
            ['kra_pin', 'KRAPin'],
            [['username'], 'sanitizeString'],
            [['username', 'email'], 'toLowerCase'],
            [['username'], 'notNumerical'],
            ['phone', 'kenyaPhoneNumber'],
            [['password', 'confirm_password'], 'validateConfirmPassword'],
        ];
    }

    /**
     * passwords must match
     */
    public function validateConfirmPassword() {
        if ((isset($_POST['User']['password']) || isset($_POST['User']['confirm_password']))) {
            if (empty($this->password))
                $this->addError('password', 'Password is required');
            else
            if (empty($this->confirm_password))
                $this->addError('confirm_password', 'Please confirm password');
            else
            if ($this->password != $this->confirm_password)
                $this->addError('confirm_password', 'Passwords do not match!');
        }
    }

    /**
     * 
     * @return boolean true - id no is required
     */
    public function birtCertNoIsRequired() {
        return !is_object($applicant = Applicants::applicantToLoad($this->id)) || ($applicant->myAge() < Applicants::maturity);
    }

    /**
     * 
     * @return boolean true - id no is required
     */
    public function IDNoIsRequired() {
        return $this->user_type != self::USER_STUDENT || (is_object($applicant = Applicants::returnApplicant($this->id)) && $applicant->myAge() >= Applicants::maturity);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'birth_cert_no' => Yii::t('app', 'Birth Certificate No.'),
            'id_no' => Yii::t('app', 'National ID. No.'),
            'kra_pin' => Yii::t('app', 'KRA PIN'),
            'phone' => Yii::t('app', 'Phone No.'),
            'email' => Yii::t('app', 'Email Address'),
            'username' => Yii::t('app', 'Username'),
            'user_type' => Yii::t('app', 'User Type'),
            'profile' => Yii::t('app', 'Business Right'),
            'auth_key' => Yii::t('app', 'Authentication Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'status' => Yii::t('app', 'Status'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'registered_by' => Yii::t('app', 'Registered By'),
            'registered_at' => Yii::t('app', 'Registered At'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'password' => Yii::t('app', 'Password'),
            'confirm_password' => Yii::t('app', 'Confirm Password'),
            'signed_in' => Yii::t('app', 'Signed In'),
            'last_signed_in' => Yii::t('app', 'Last Signed In'),
            'last_signed_out' => Yii::t('app', 'Last Signed Out'),
            'user_ip' => Yii::t('app', 'User IP'),
            'session_id' => Yii::t('app', 'Session ID')
        ];
    }

    /**
     * @inheritdoc
     * @return \common\activeQueries\UserQuery the active query used by this AR class.
     */
    public static function find() {
        return new \common\activeQueries\UserQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk user id
     * @return User model
     */
    public static function returnUser($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username or email
     * 
     * @param string $usernameOrEmail username or email
     * @return User model
     */
    public static function userForLogin($usernameOrEmail) {
        return static::find()->userForLogin($usernameOrEmail, self::STATUS_ACTIVE);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return User model
     */
    public static function findByEmail($email) {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User model
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * 
     * @param integer $birth_cert_no birth certificate no
     * @return User model
     */
    public static function byBirthCertNo($birth_cert_no) {
        return static::find()->byBirthCertNo($birth_cert_no);
    }

    /**
     * 
     * @param integer $id_no national identity no
     * @return User model
     */
    public static function byIDNo($id_no) {
        return static::find()->byIDNo($id_no);
    }

    /**
     * 
     * @param integer $phone phone no
     * @return User model
     */
    public static function byPhone($phone) {
        return static::find()->byPhone($phone);
    }

    /**
     * 
     * @param string $email email address
     * @return User model
     */
    public static function byEmail($email) {
        return static::find()->byEmail($email);
    }

    /**
     * 
     * @param string $username username
     * @return User model
     */
    public static function byUsername($username) {
        return static::find()->byUsername($username);
    }

    /**
     * 
     * @param string $attribute attribute of User model
     * @param string $value value of [[$attribute]]
     * @return User model
     */
    public static function notOwnSenior($attribute, $value) {
        return static::find()->notOwnSenior($attribute, $value);
    }

    /**
     * 
     * @param string $attribute attribute of User model
     * @param string $value value of [[$attribute]]
     * @param integer $id applicant's id
     * @return User model
     */
    public static function notOwnSibling($attribute, $value, $id) {
        return static::find()->notOwnSibling($attribute, $value, $id);
    }

    /**
     * 
     * @return User model
     */
    public static function newUser() {
        $model = new User;

        $model->user_type = self::USER_STUDENT;
        $model->profile = self::PROFILE_OTHER;
        $model->status = self::STATUS_ACTIVE;
        $model->registered_by = self::CREATED_BY_SELF;

        return $model;
    }

    /**
     * 
     * @param integer $id user id
     * @return User model
     */
    public static function userToLoad($id) {
        return is_object($model = static::returnUser($id)) ? $model : static::newUser();
    }

    /**
     * 
     * @return boolean true - model saved
     */
    public function modelSave() {
        if ($this->isNewRecord) {
            Yii::$app->user->isGuest ? '' : $this->registered_by = Yii::$app->user->identity->username;
            $this->registered_at = StaticMethods::now();

            $this->setPassword($this->password);
            $this->generateAuthKey();
        } else {
            Yii::$app->user->isGuest ? '' : $this->modified_by = Yii::$app->user->identity->username;
            $this->modified_at = StaticMethods::now();
        }

        return $this->save();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return User model
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token))
            return null;

        return static::findOne(['password_reset_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token))
            return false;

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * mark user as logged in
     */
    public function justLoggedIn() {
        $this->signed_in = self::CURRENTLY_LOGGED_IN;

        $this->removePasswordResetToken();

        $this->update(false, ['signed_in', 'last_signed_in', 'user_ip', 'session_id', 'password_reset_token']);
    }

    /**
     * mark user as logged out
     */
    public function justLoggedOut() {
        $this->signed_in = self::CURRENTLY_NOT_LOGGED_IN;
        $this->update(false, ['signed_in', 'last_signed_out']);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    /**
     * 
     * @return array menu u=items for students
     */
    public static function applicantsMenu() {
        $items['My Profile'] = [
            ['id' => 'sd-nav-prsnl', 'nm' => 'Personal Details', 'fa' => 'fa fa-id-card', 'ax' => 'register', 'wd' => 1, 'hg' => 1, 'tt' => 'Update Your Personal Details', 'ps' => ['Applicants[id]' => $user_id = Yii::$app->user->identity->id, 'User[id]' => $user_id]],
            ['id' => 'sd-nav-rsdnc', 'nm' => 'Current Residence', 'fa' => 'fa fa-home', 'ax' => 'residence', 'wd' => 1, 'hg' => 0.8, 'tt' => 'Update Your Current Residence Details', 'ps' => ['ApplicantsResidence[applicant]' => $user_id]],
            ['id' => 'sd-nav-edctn', 'nm' => 'Education Background', 'fa' => 'fa fa-file-text', 'ax' => 'education', 'wd' => 1, 'hg' => 0.80, 'tt' => 'Update Your Education Background', 'ps' => ['EducationBackground[applicant]' => $user_id]],
            ['id' => 'sd-nav-inst', 'nm' => 'Institution Details', 'fa' => 'fa fa-institution', 'ax' => 'institution', 'wd' => 1, 'hg' => 1, 'tt' => 'Update Your Institution Details', 'ps' => ['ApplicantsInstitution[applicant]' => $user_id]],
            ['id' => 'sd-nav-eplymt', 'nm' => 'Employment Details', 'fa' => 'fa fa-industry', 'ax' => 'employment', 'wd' => 1, 'hg' => 1, 'tt' => 'Update Your Employment Details', 'ps' => ['ApplicantsEmployment[applicant]' => $user_id]],
            ['id' => 'sd-nav-prnts', 'nm' => 'Parents\' Details', 'fa' => 'fa fa-group', 'ax' => 'parents', 'wd' => 1, 'hg' => 1, 'tt' => 'Update Your Pranets\' Details', 'ps' => ['Applicants[id]' => $user_id]],
            ['id' => 'sd-nav-expns', 'nm' => 'Family Expenses', 'fa' => 'fa fa-money', 'ax' => 'expenses', 'wd' => 1, 'hg' => 1, 'tt' => 'Update Your Family Expense Details', 'ps' => ['applicant' => $user_id]],
            ['id' => 'sd-nav-spnsrs', 'nm' => 'Sponsors\' Details', 'fa' => 'fa fa-money', 'ax' => 'sponsors', 'wd' => 1, 'hg' => 0.80, 'tt' => 'Update Your Sponsors\' Details', 'ps' => ['ApplicantSponsors[applicant]' => $user_id]],
            ['id' => 'sd-nav-sps', 'nm' => 'Spouse\'s Details', 'fa' => 'fa fa-heart', 'ax' => 'spouse', 'wd' => 1, 'hg' => 0.80, 'tt' => 'Update Your Spouses\' Details', 'ps' => ['ApplicantsSpouse[applicant]' => $user_id]],
            ['id' => 'sd-nav-grntrs', 'nm' => 'Guarantors\' Details', 'fa' => 'fa fa-group', 'ax' => 'guarantors', 'wd' => 1, 'hg' => 1, 'tt' => 'Update Your Guarantors\' Details', 'ps' => ['ApplicantsGuarantors[applicant]' => $user_id]]
        ];

        
        foreach (\frontend\modules\business\models\Products::allProducts() as $product)
            $items['Available Products'][] = ['id' => "sd-nav-aplctn-$product->code", 'nm' => $product->name, 'fa' => 'fa fa-file', 'ax' => 'application-timeline', 'wd' => 1, 'hg' => 1, 'tt' => $product->description, 'ps' => ['product' => $product->id, 'applicant' => $user_id], 'ap' => 'yeap', 'pr' => $product->id];


        $items['My Applications'] = [
            ['id' => 'sd-nav-ln-sts', 'nm' => 'Application Status', 'fa' => 'fa fa-home', 'ax' => '', 'wd' => 1, 'hg' => 1, 'tt' => 'View Your Application Status', 'ps' => ['applicant' => $user_id]],
            ['id' => 'sd-nav-ln-dsb', 'nm' => 'Disbursement Schedules', 'fa' => 'fa fa-file-text', 'ax' => '', 'wd' => 1, 'hg' => 1, 'tt' => 'View Disbursement Schedules', 'ps' => ['applicant' => $user_id]],
            ['id' => 'sd-nav-ln-enq', 'nm' => 'Enquiries', 'fa' => 'fa fa-institution', 'ax' => '', 'wd' => 1, 'hg' => 1, 'tt' => 'Launch An Enquiry', 'ps' => ['applicant' => $user_id]]
        ];

        return $items;
    }

}
