<?php

/* @var $this yii\web\View */
/* @var $attributes array */
/* @var $section1 array */
/* @var $section2 array */
/* @var $section3 array */
/* @var $section4 array */
/* @var $section5 array */

use yii\jui\Accordion;
?>

<?= \yii\bootstrap\Tabs::widget(
    [
        'linkOptions' => ['class' => 'btn btn-primary'],
        'itemOptions' => ['style' => 'margin-top: 10px;'],
        'items' => [
            [
                'label' => 'Раздел 1',
                'content' => $this->render('_view-section-table', ['attributes' => $attributes, 'section' => $section1])
            ],
            [
                'label' => 'Раздел 2',
                'content' => $this->render('_view-section-table', ['attributes' => $attributes, 'section' => $section2])
            ],
            [
                'label' => 'Раздел 3',
                'content' => $this->render('_view-section-table', ['attributes' => $attributes, 'section' => $section3])
            ],
            [
                'label' => 'Раздел 4',
                'content' => $this->render('_view-section-table', ['attributes' => $attributes, 'section' => $section4])
            ],
            [
                'label' => 'Раздел 5',
                'content' => $this->render('_view-section-table', ['attributes' => $attributes, 'section' => $section5])
            ],
        ]
    ]
) ?>
