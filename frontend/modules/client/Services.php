<?php

namespace frontend\modules\client;

/**
 * client module definition class
 */
class Services extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\client\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->modules = [
            'partnership' => ['class' => 'frontend\modules\client\modules\partnership\Services'],
            'student' => ['class' => 'frontend\modules\client\modules\student\Services'],
            'variable' => ['class' => 'frontend\modules\client\modules\variable\Services'],
            'loan' => ['class' => 'frontend\modules\client\modules\loan\Services']
        ];
    }

}
