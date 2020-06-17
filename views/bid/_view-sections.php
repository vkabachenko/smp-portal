<?php

/* @var $this yii\web\View */
/* @var $attributes array */
/* @var $section1 array */
/* @var $section2 array */
/* @var $section3 array */

use yii\jui\Accordion;
?>

<?= Accordion::widget(
    [
        'items' => [
            [
                'header' => false,
                'content' => $this->render('_view-section-table', ['attributes' => $attributes, 'section' => $section1])
            ],
            [
                'header' => false,
                'content' => $this->render('_view-section-table', ['attributes' => $attributes, 'section' => $section2])
            ],
            [
                'header' => false,
                'content' => $this->render('_view-section-table', ['attributes' => $attributes, 'section' => $section3])
            ]
        ]
    ]
) ?>
