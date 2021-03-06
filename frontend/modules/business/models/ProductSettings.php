<?php

namespace frontend\modules\business\models;

/**
 * define the checks against which applicant can access a product for application
 */
class ProductSettings {

    const setting = 'setting';
    const name = 'name';
    const hint = 'hint';
    const values = 'values';
    const default_value = 'default';
    const active = 'active';
    const yes = 1;
    const no = 0;
    const both = 2;
    const has_subsequent = 'has_subsquent';
    const has_appeal = 'has_appeal';
    const has_bursary = 'has_bursary';
    const employed = 'employed';
    const primary = 'primary';
    const secondary = 'secondary';
    const certificate = 'certificate';
    const diploma = 'diploma';
    const degree = 'degree';
    const masters = 'masters';
    const phd = 'phd';
    const guarantors = 'guarantors';
    const tuition_or_upkeep = 'tuition_or_upkeep';
    const has_financial_literacy = 'has_financial_literacy';
    const has_society_narration = 'has_society_narration';

    /**
     * 
     * @return array general product settings
     */
    public static function theSettings() {
        return [
            self::has_subsequent => [self::name => 'Has Subsequent', self::hint => 'Whether the product has subsequent', self::values => static::yesNo(), self::default_value => self::yes, self::active => self::yes],
            self::has_appeal => [self::name => 'Has Appeal', self::hint => 'Whether the product has appeal', self::values => static::yesNo(), self::default_value => self::yes, self::active => self::yes],
            self::has_bursary => [self::name => 'Has Bursary', self::hint => 'Whether the product has bursary', self::values => static::yesNo(), self::default_value => self::no, self::active => self::yes],
            self::employed => [self::name => 'Employed', self::hint => 'Whether the only for employed', self::values => static::employeds(), self::default_value => self::both, self::active => self::yes],
            self::primary => [self::name => 'Primary Education', self::hint => 'Whether primary education is required', self::values => static::yesNo(), self::default_value => self::yes, self::active => self::yes],
            self::secondary => [self::name => 'Secondary Education', self::hint => 'Whether secondary education is required', self::values => static::yesNo(), self::default_value => self::yes, self::active => self::yes],
            self::certificate => [self::name => 'Tertiary Certificate', self::hint => 'Whether tertiary certificate is required', self::values => static::yesNo(), self::default_value => self::no, self::active => self::yes],
            self::diploma => [self::name => 'Diploma Certificate', self::hint => 'Whether diploma certificate is required', self::values => static::yesNo(), self::default_value => self::no, self::active => self::yes],
            self::degree => [self::name => 'Degree Certificate', self::hint => 'Whether degree certificate is required', self::values => static::yesNo(), self::default_value => self::no, self::active => self::yes],
            self::masters => [self::name => 'Masters Certificate', self::hint => 'Whether masters certificate is required', self::values => static::yesNo(), self::default_value => self::no, self::active => self::yes],
            self::phd => [self::name => 'PhD Certificate', self::hint => 'Whether PhD certificate is required', self::values => static::yesNo(), self::default_value => self::no, self::active => self::yes],
            self::tuition_or_upkeep => [self::name => 'Tuition or Upkeep', self::hint => 'Whether the product considers tuition or upkeep', self::values => static::tuitionUpkeep(), self::default_value => self::both, self::active => self::yes],
            self::has_society_narration => [self::name => 'Society Narration', self::hint => 'Whether applicant will narrate the contibution to society', self::values => static::yesNo(), self::default_value => self::no, self::active => self::yes],
            self::has_financial_literacy => [self::name => 'Has Financial Literacy', self::hint => 'Whether the product has Financial Literacy', self::values => static::yesNo(), self::default_value => self::no, self::active => self::yes],
            self::guarantors => [self::name => 'No. of Guarantors', self::hint => 'No. of guarantors required', self::values => static::numberOfGuarantors(), self::default_value => 2, self::active => self::yes]
        ];
    }

    /**
     * 
     * @return array general setting options
     */
    public static function yesNo() {
        return [self::yes => 'Yes', self::no => 'No'];
    }

    /**
     * 
     * @return array employed setting options
     */
    public static function employeds() {
        return [self::no => 'No', self::yes => 'Yes', self::both => 'Both'];
    }

    /**
     * 
     * @return array tuition setting options
     */
    public static function tuitionUpkeep() {
        return [self::yes => 'Tuition Only', self::no => 'Upkeep Only', self::both => 'Both'];
    }
    
    /**
     * 
     * @return array numbers of guarantors
     */
    public static function numberOfGuarantors() {
        return [0 => 'None', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four'];
    }

}
