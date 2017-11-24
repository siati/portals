<?php

namespace common\models;

use Yii;
use DateTime;
use yii\helpers\ArrayHelper;

/**
 * more static functions
 */
class StaticMethods {

    const short = 'short';
    const long = 'long';
    const longer = 'longer';
    const longest = 'longest';
    const name = 'name';
    const points = 'points';

    /**
     * 
     * @param array $array elements to order
     * @param array $order custom order array
     * @return array $array elements ordered
     */
    public static function arrayCustomSort($array, $order) {

        usort($array, function ($a, $b) use ($order) {
            return array_search($a, $order) - array_search($b, $order);
        });

        return $array;
    }

    /**
     * 
     * @return array months
     */
    public static function months() {
        return [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];
    }

    /**
     * 
     * @param integer $month month: 1 - 12
     * @param integer $year year yyyy
     * @return integer max date 28 - 31
     */
    public function monthMaxDate($month, $year) {
        return in_array($month * 1, [1, 3, 5, 7, 8, 10, 12]) ? (31) : ($month * 1 != 2 ? (30) : ($year % 4 == 0 ? 29 : 28));
    }

    /**
     * 
     * @param string $date yyyy-mm-dd
     * @return string month name
     */
    public static function monthForDate($date) {

        $monthName = self::months()[$month = substr($date, 5, 2) * 1];

        return empty($monthName) ? $month : $monthName;
    }

    /**
     * 
     * @param string $date yyyy-mm-dd
     * @param string $monthHow short / long
     * @return string Jan 01, 2016
     */
    public static function dateString($date, $monthHow) {
        return strlen($date) > 9 ? (($monthHow == self::short ? (substr(self::monthForDate($date), 0, 3)) : (
                ($monthHow == self::long ? ('') : (date($monthHow == self::longest ? 'l' : 'D', strtotime($date)) . ' ')) . self::monthForDate($date))
                ) . ' ' . substr($date, 8, 2) . ', ' . substr($date, 0, 4)) : ('NULL');
    }

    /**
     * 
     * @param array $models models
     * @param string $key attribute of each of the [[$models]]
     * @param string $value value of
     * @param boolean $sort true - sort array
     * @return array extracted associative array
     */
    public static function modelsToArray($models, $key, $value, $sort) {
        $array = ArrayHelper::map($models, $key, $value);

        $sort && asort($array);

        return $array;
    }

    /**
     * populate a dropdown using given array
     * 
     * @param array $array simple or associative array
     * @param string $prompt prompt
     * @param string $selected selected
     */
    public static function populateDropDown($array, $prompt, $selected) {
        if (!empty($prompt))
            echo "<option " . ($selected == '' || $selected == null ? "selected='selected' " : '') . "value=''>-- $prompt --</option>";

        foreach ($array as $key => $value)
            echo "<option " . ($key == $selected ? "selected='selected' " : '') . "value='$key'>$value</option>";
    }

    /**
     * 
     * @return string yyyy-mm-dd
     */
    public static function today() {
        return date('Y-m-d');
    }

    /**
     * 
     * @return string current time to micro seconds
     */
    public static function now() {
        $t = microtime(true);

        $micro = sprintf('%06d', ($t - floor($t)) * 1000000);

        $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));

        return $d->format('Y-m-d H:i:s.u');
    }

    /**
     * 
     * @param string $dateTime date
     * @return boolean true - time empty
     */
    public static function timeEmpty($dateTime) {
        return empty($dateTime) || self::stripNonNumeric($dateTime) == 0;
    }

    /**
     * 
     * @param string $date date
     * @return boolean true `$date` is date
     */
    public static function isDate($date) {
        try {
            list($y, $m, $d) = explode('-', $date);
        } catch (\Exception $ex) {
            list($y, $m, $d) = [0, 0, 0];
        }

        return checkdate($m, $d, $y);
    }

    /**
     * 
     * @param string $date date yyyy-mm-dd hh:mm:ss
     * @param integer|double $days interval days
     * @return string final date
     */
    public static function dateAddDays($date, $days) {
        $since = date_create($date);

        date_add($since, date_interval_create_from_date_string("$days days"));

        return date_format($since, 'Y-m-d H:i:s');
    }

    /**
     * 
     * @param integer $start first value
     * @param integer $end last value
     * @param integer $step interval / step
     * @param boolean $capThisYear true - max this year
     * @return array values
     */
    public static function ranges($start, $end, $step, $capThisYear) {
        foreach (range($start, $end, $step) as $value)
            if (!$capThisYear || $value <= date('Y'))
                $values[$value] = $value;

        return empty($values) ? [] : $values;
    }

    /**
     * 
     * @param string $date date yyyy-mm-dd hh:mm:ss
     * @param integer|double $days interval days
     * @return string final date
     */
    public static function dateSubtractDays($date, $days) {
        $since = date_create($date);

        date_sub($since, date_interval_create_from_date_string("$days days"));

        return date_format($since, 'Y-m-d H:i:s');
    }
    
    /**
     * 
     * @param integer $start_year start year
     * @param integer $start_month start month
     * @param integer $add_years add years
     * @param integer $add_months add months
     * @return array end year and month
     */
    public static function monthArithmentics($start_year, $start_month, $add_years, $add_months) {
        is_numeric($start_year) ? '' : $start_year = date('Y') - 1;
        
        is_numeric($start_month) ? '' : $start_month = date('m');
        
        is_numeric($add_years) ? '' : $add_years = 0;
        
        is_numeric($add_months) ? '' : $add_months = 0;
        
        $total_add_months = $add_years * 12 + $add_months;
        
        $end_year = $start_year + floor($total_add_months / 12);
        
        $end_month = $start_month + ($total_add_months % 12);
        
        $extra_years = floor($end_month / 12);
        
        $extra_months = $end_month % 12;
        
        return [$end_year + $extra_years, $extra_months];
    }

    /*
     * remove non-numeric characters from a string.
     */

    public static function stripNonNumeric($str) {
        return preg_replace('(\D+)', '', $str);
    }

    /**
     * 
     * @param string $string string
     * @param string $delimiter string
     * @return array
     */
    public static function stringExplode($string, $delimiter) {
        return explode($delimiter, $string);
    }

    /**
     * 
     * @param array $pieces pieces / elements
     * @param string $glue
     * @return string final string
     */
    public static function arrayImplode($pieces, $glue) {
        return implode($glue, $pieces);
    }

    /**
     * 
     * @param string $html_view mail html view
     * @param string $text_view mail text view
     * @param array $mail parameter for the mail views
     * @param array $from sender - [email => name]
     * @param array $to primary recipients - [email1 => name1, email2 => name2]
     * @param array $cc secondary recipients - [email1 => name1, email2 => name2]
     * @param array $bcc tertiary recipients - [email1 => name1, email2 => name2]
     * @param string $subject subject of email
     * @param array $attachments file locations
     * @return array|boolean true -sent, false - not sent, array - connection fail exception
     */
    public static function sendMail($html_view, $text_view, $mail, $from, $to, $cc, $bcc, $subject, $attachments) {
        try {
            $mailer = Yii::$app->mailer->compose(['html' => $html_view, 'text' => $text_view], $mail);

            $mailer->setFrom($from)->setTo($to)->setCc($cc)->setBcc($bcc)->setSubject($subject);

            if (is_array($attachments))
                foreach ($attachments as $attachment)
                    $mailer->attach($attachment);

            return $mailer->send();
        } catch (\Exception $ex) {
            return [substr($ex, 0, 100), self::connection_failed];
        }
    }

    /**
     * 
     * @param string $target_url target url / endpoint
     * @param array $post parameters being parsed via post
     * @return string|boolean success or failure message
     */
    public static function seekService($target_url, $post) {
        is_object($auth = AuthKey::loadKey(null)) ? $post['auth_key'] = $auth->auth_key : '';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, static::buildQueryForCurl($post));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: multipart/form-data']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * 
     * @param array $post post values in array form
     * @return array formatted post values
     */
    public static function buildQueryForCurl($post) {
        foreach ($post as $className => $attributes)
            if (is_array($attributes))
                foreach ($attributes as $attribute => $values)
                    if (is_array($values))
                        foreach ($values as $index => $value)
                            $thePost["$className" . "[$attribute][$index]"] = $value;
                    else
                        $thePost["$className" . "[$attribute]"] = $values;
            else
                $thePost["$className"] = $attributes;

        return empty($thePost) ? [] : $thePost;
    }
    
    /**
     * 
     * @param string $link link
     * @return boolean true - link exists
     */
    public static function linkExists($link) {
        $headers = @get_headers($link);
        
        return stripos($headers[0], '200') !== false;
    }

    /**
     * 
     * @return array grades
     */
    public static function grades() {
        return [
            'A' => [self::name => 'A Plain', self::points => 12],
            'A-' => [self::name => 'A Minus', self::points => 11],
            'B+' => [self::name => 'B Plus', self::points => 10],
            'B' => [self::name => 'B Plain', self::points => 9],
            'B-' => [self::name => 'B Minus', self::points => 8],
            'C+' => [self::name => 'C Plus', self::points => 7],
            'C' => [self::name => 'C Plain', self::points => 6],
            'C-' => [self::name => 'C Minus', self::points => 5],
            'D+' => [self::name => 'D Plus', self::points => 4],
            'D' => [self::name => 'D Plain', self::points => 3],
            'D-' => [self::name => 'D Minus', self::points => 2],
            'E' => [self::name => 'E Plain', self::points => 1]
                ]
        ;
    }

    /**
     * 
     * @return array grades for drop down
     */
    public static function gradesForDropDown() {
        foreach ($grades = self::grades() as $grd => $grade)
            $grades[$grd] = $grade[self::name];

        return $grades;
    }

    /**
     * 
     * @param real $points points
     * @return string grade
     */
    public static function gradeForPoints($points) {
        foreach (self::grades() as $grd => $grade)
            if ($points > 0 && $points <= 12 && $points >= $grade[self::points] - 0.5 && $points < $grade[self::points] + 0.5)
                return $grd;
    }

    /**
     * 
     * @return merits for certificates and diplomas
     */
    public static function certificateAndDiplomaMerits() {
        return [
            'd' => 'Distinction',
            'c' => 'Credit',
            'p' => 'Pass',
            'f' => 'Fail'
        ];
    }

    /**
     * 
     * @return merits for degrees
     */
    public static function degreeMerits() {
        return [
            's' => 'First Class',
            'u' => 'Second Class Upper',
            'l' => 'Second Class Lower',
            'p' => 'Pass',
            'f' => 'Fail'
        ];
    }

}
