<?php

namespace common\models;

use Yii;

/**
 * document handler
 */
class Docs {

    const root_dir = '../../docs/';
    const name = 'name';
    const dir = 'dir';
    const location = 'location';
    const locator = 'locator';
    const category = 'category';
    const category_laf = 'laf';
    const category_client = 'client';

    /**
     * 
     * @return array category details
     */
    public static function categoryDetails() {
        return [
            self::category_laf => [self::name => 'Loan Application Form', self::dir => 'lafs'],
            self::category_client => [self::name => 'Export To Client', self::dir => 'client']
        ];
    }

    /**
     * 
     * @return array categories
     */
    public static function categories() {
        foreach (static::categoryDetails() as $category => $detail)
            $categories[$category] = $detail[self::name];

        return empty($categories) ? [] : $categories;
    }

    /**
     * 
     * @return string filename
     */
    public static function fileNameNow() {
        return StaticMethods::stripNonNumeric(StaticMethods::now());
    }

    /**
     * 
     * @param string $category document category
     * @return string location of document category
     */
    public static function location($category) {
        return Yii::$app->basePath . self::root_dir . static::categoryDetails()[$category][self::dir] . '/';
    }

    /**
     * 
     * @param string $category document category
     * @return string locator of document category
     */
    public static function locator($category) {
        return Yii::$app->homeUrl . self::root_dir . static::categoryDetails()[$category][self::dir] . '/';
    }

    /**
     * 
     * @param string $category document category
     * @param string $filename file name
     * @param string $response location or locator
     * @return string file name
     */
    public static function theFileName($category, $filename, $response) {
        return ($response == self::locator ? static::locator($category) : static::location($category)) . $filename;
    }

    /**
     * 
     * @param string $category document category
     * @param string $filename file name
     * @param string $response location or locator
     * @return boolean|string true - location or locator
     */
    public static function fileExists($category, $filename, $response) {
        return is_file($location = static::theFileName($category, $filename, self::location)) ? ($response == self::locator ? (static::theFileName($category, $filename, $response)) : ($location)) : (false);
    }

    /**
     * 
     * @param string $category document category
     * @param string $filename file name
     * @return boolean true - file deleted
     */
    public static function deleteFile($category, $filename) {
        return ($file = static::fileExists($category, $filename, self::location)) == false || @unlink($file);
    }

    /**
     * 
     * @param string $category document category
     * @param string $source source file location
     * @param string $filename destination name for file
     * @param boolean $overwrite overwrite destination file
     * @return boolean|string true - destination file name
     */
    public static function copyDocument($category, $source, $filename, $overwrite) {
        $source_name = explode('.', $source);

        empty($filename) || (!$overwrite && static::fileExists($category, $filename . end($source_name), self::location)) ? $filename = static::fileNameNow() : '';

        return is_file($source) && copy($source, static::theFileName($category, $dest = "$filename." . end($source_name), self::location)) ? $dest : false;
    }

    /**
     * 
     * @param string $source_category source category
     * @param string $destination_category destination category
     * @param string $source_name source name
     * @param string $destination_name destination name
     * @param boolean $overwrite true - overwrite destination file
     * @return boolean|string true - destination file name
     */
    public static function moveFile($source_category, $destination_category, $source_name, $destination_name, $overwrite) {
        return ($copy = static::copyDocument($destination_category, $source = static::theFileName($source_category, $source_name, self::location), $destination_name, $overwrite)) &&
                static::deleteFile($source_category, $source_name) ? ($copy) : ($copy ? (static::deleteFile($destination_category, $copy) && false) : (false) );
    }

    /**
     * 
     * @param string $category document category
     * @param string $filename file name
     * @param string $response location or locator
     * @return boolean|string true - location or locator for file export
     */
    public static function fileLocate($category, $filename, $response) {
        if ($location = static::fileExists($category, $filename, self::location)){
            $category != self::category_client ? $filename = static::copyDocument(self::category_client, $location, null, false) : '';
            
            return static::fileExists(self::category_client, $filename, $response);
        }
        
        return false;
    }

    /**
     * 
     * @param string $category document category
     * @param string $filename file name
     * @return mixed redirect browser to file resource
     */
    public static function export($category, $filename) {
        return Yii::$app->controller->redirect(static::fileExists($category, $filename, self::locator));
    }

}
