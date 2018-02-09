<?php

namespace common\models;

use Yii;
use kartik\mpdf\Pdf;
use mPDF;
use common\models\Docs;

/**
 * use this class to generate pdf files
 */
class PDFGenerator {

    // innerited values from pdf class
    const MODE_BLANK = Pdf::MODE_BLANK;
    const MODE_CORE = Pdf::MODE_CORE;
    const MODE_UTF8 = Pdf::MODE_UTF8;
    const MODE_ASIAN = Pdf::MODE_ASIAN;
    const FORMAT_A3 = Pdf::FORMAT_A3;
    const FORMAT_A4 = Pdf::FORMAT_A4;
    const FORMAT_LETTER = Pdf::FORMAT_LETTER;
    const FORMAT_LEGAL = Pdf::FORMAT_LEGAL;
    const FORMAT_FOLIO = Pdf::FORMAT_FOLIO;
    const FORMAT_LEDGER = Pdf::FORMAT_LEDGER;
    const FORMAT_TABLOID = Pdf::FORMAT_TABLOID;
    const ORIENT_PORTRAIT = Pdf::ORIENT_PORTRAIT;
    const ORIENT_LANDSCAPE = Pdf::ORIENT_LANDSCAPE;
    const DEST_BROWSER = Pdf::DEST_BROWSER;
    const DEST_DOWNLOAD = Pdf::DEST_DOWNLOAD;
    const DEST_FILE = Pdf::DEST_FILE;
    const DEST_STRING = Pdf::DEST_STRING;

    # Docs consts
    const category = Docs::category;
    const category_laf = Docs::category_laf;
    const category_client = Docs::category_client;
    const location = Docs::location;
    const locator = Docs::locator;

    # local parameters for variable notation
    const view = 'view';
    const view_params = 'view_params';
    const mode = 'mode';
    const paper_size = 'paper_size';
    const orientation = 'orientation';
    const destination = 'destination';
    const css_file = 'css_file';
    const css_inline = 'css_inline';
    const title = 'title';
    const html_header = 'html_header';
    const water_mark = 'water_mark';
    const footer = 'footer';
    const filename = 'filename';

    /**
     * 
     * @param string $file file location
     * @return string file contents
     */
    public static function readCssFile($file) {
        return file_get_contents(Yii::$app->basePath . '../../' . (empty($file) ? 'vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css' : $file));
    }

    /**
     * 
     * @param string $category document category
     * @param string $filename filename
     * @param string $response location or locator
     * @return string file name
     */
    public static function fileNameNow($category, $filename, $response) {
        return Docs::theFileName($category, (empty($filename) ? Docs::fileNameNow() : $filename) . '.pdf', $response);
    }

    /**
     * 
     * @param array $render_params view file and its parameters
     * @param array $pdf_params other values for generating the pdf file
     */
    public static function go($render_params, $pdf_params) {

        $mpdf = new mPDF(null, empty($pdf_params[self::paper_size]) ? self::FORMAT_A4 : $pdf_params[self::paper_size], false, null, 15, 15, 16, 16, 9, 9, empty($pdf_params[self::orientation]) ? self::ORIENT_PORTRAIT : $pdf_params[self::orientation]);

        /* $mpdf->showImageErrors = true; */

        empty($pdf_params[self::html_header]) ? '' : $mpdf->SetHeader($pdf_params[self::html_header]);

        $mpdf->SetFooter(empty($pdf_params[self::footer]) ? '{PAGENO}' : $pdf_params[self::footer]);

        $mpdf->SetWatermarkImage($pdf_params[self::water_mark], 0.2, 'F', 'F');

        $mpdf->showWatermarkImage = true;

        $mpdf->SetDisplayMode('fullpage');

        $mpdf->WriteHtml(static::readCssFile($pdf_params[self::css_file]), 1);

        $mpdf->WriteHtml(Yii::$app->controller->renderPartial($render_params[self::view], $render_params[self::view_params]), 2);

        return static::fileSave($mpdf, empty($pdf_params[self::category]) ? '' : $pdf_params[self::category]);
    }

    /**
     * 
     * @param mPDF $mpdf model
     * @param string $category category
     * @return array file category and name
     */
    public static function fileSave($mpdf, $category) {
        $only_client = empty($category) || $category == self::category_client;

        $mpdf->Output($filename = static::fileNameNow($cat = $only_client ? self::category_client : $category, null, self::location), self::DEST_FILE);

        return [self::category => $cat, self::filename => basename($filename)];
    }

}
