<?php

namespace common\models;

use Yii;
use kartik\mpdf\Pdf;

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

    # local parameters for variable notation
    const view = 'view';
    const view_params = 'view_params';
    const mode = 'mode';
    const paper_size = 'paper_size';
    const orientation = 'orientation';
    const location = 'location';
    const destination = 'destination';
    const css_file = 'css_file';
    const css_inline = 'css_inline';
    const title = 'title';
    const html_header = 'html_header';
    const footer = 'footer';

    /**
     * 
     * @param array $render_params view file and its parameters
     * @param array $pdf_params other values for generating the pdf file
     */
    public static function go($render_params, $pdf_params) {

        $content = Yii::$app->controller->renderPartial($render_params[self::view], $render_params[self::view_params]);

        $location = empty($pdf_params[self::location]) ? '' : $pdf_params[self::location];

        $destination = empty($pdf_params[self::destination]) ? '' : $pdf_params[self::destination];

        $pdf_config = [
            'mode' => empty($pdf_params[self::mode]) ? self::MODE_UTF8 : $pdf_params[self::mode],
            'format' => empty($pdf_params[self::paper_size]) ? self::FORMAT_A4 : $pdf_params[self::paper_size],
            'orientation' => empty($pdf_params[self::orientation]) ? self::ORIENT_PORTRAIT : $pdf_params[self::orientation],
            'cssFile' => empty($pdf_params[self::css_file]) ? '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css' : $pdf_params[self::css_file],
        ];

        empty($pdf_params[self::css_inline]) ? '' : $pdf_config['cssInline'] = $pdf_params[self::css_inline];

        $pdf_config['options'] = ['title' => $title = empty($pdf_params[self::title]) ? StaticMethods::stripNonNumeric(StaticMethods::now()) : $pdf_params[self::title]];

        $pdf_config['methods'] = [
            'SetHeader' => [$title],
            'SetFooter' => [empty($pdf_params[self::footer]) ? '{PAGENO}' : $pdf_params[self::footer]]
        ];
        
        $pdf = new Pdf($pdf_config);

        empty($location) ? '' : $pdf->output($content, $location, $destination == self::DEST_DOWNLOAD ? $destination : self::DEST_FILE);

        empty($location) || !in_array($destination, [self::DEST_FILE, self::DEST_DOWNLOAD]) ? $pdf->output($content, null, empty($destination) ? self::DEST_BROWSER : $destination) : '';
    }

}
