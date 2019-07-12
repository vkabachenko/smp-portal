<?php
namespace app\templates\excel\act;

use app\templates\excel\ExcelTemplate;

class ExcelActDefault extends ExcelTemplate
{
    protected function getActualColumnSizes()
    {
        return [
            'A' => 2,
            'B' => 24.33,
            'C' => 6,
            'D' => 19,
            'E' => 7.5,
            'F' => 11.83,
            'G' => 25.17,
            'H' => 5.83,
            'I' => 15.5
        ];
    }

    protected function getActualRowSizes()
    {
        return [
            1 => 30,
            2 => 30,
            3 => 17.25,
            4 => 11.25,
            5 => 30,
            6 => 14.25,
            7 => 12.75,
            8 => 18.75,
            9 => 12.75,
            10 => 27,
            11 => 12.75,
            12 => 12.75,
            13 => 5.25,
            14 => 12.75,
            15 => 28.5,
            16 => 38.25,
            17 => 39,
            18 => 12.75,
            19 => 33,
            20 => 15,
            21 => 28.5,
            22 => 13.5,
            23 => 36,
            24 => 13.5,
            25 => 27.75,
            26 => 5.25,
            27 => 13.5,
            28 => 13.5,
            29 => 13.5,
            30 => 32.25,
            31 => 33,
            32 => 8.25,
            33 => 20.25,
            34 => 7.5,
            35 => 18,
            36 => 18,
            37 => 12.75
        ];
    }

    protected function getActualMergedCells()
    {
        return [
            'B1:D1',
            'B3:D3',
            'B4:D4',
            'B5:D5',
            'B6:D6',
            'F6:I6',
            'D8:E8',
            'B9:F9',
            'H10:I10',
            'B11:G11',
            'B12:C12',
            'B14:C14',
            'D14:E14',
            'F14:G14',
            'H14:I14',
            'B15:C15',
            'D15:E15',
            'F15:G15',
            'H15:I15',
            'C16:E16',
            'F16:I16',
            'C17:E17',
            'B18:I18',
            'B19:H19',
            'B20:I20',
            'B21:I21',
            'B22:I22',
            'B23:I23',
            'B24:I24',
            'C25:D25',
            'F25:G25',
            'B27:I27',
            'B29:H29',
            'B31:D31',
            'F31:G31',
            'B35:E35',
            'F35:I35',
            'B36:E36',
            'F36:I36',
        ];
    }

    protected function getActualBorders()
    {
        return [
            ['cells' => 'B3:D3', 'direction' => 'bottom'],
            ['cells' => 'B5:D5', 'direction' => 'bottom'],
            ['cells' => 'B14:C15'],
            ['cells' => 'C14:E15'],
            ['cells' => 'E14:G15'],
            ['cells' => 'H14:I15'],
            ['cells' => 'B16'],
            ['cells' => 'C16:E16'],
            ['cells' => 'F16:I17'],
            ['cells' => 'B17'],
            ['cells' => 'C17:E17'],
            ['cells' => 'E25', 'direction' => 'all', 'style' => 'thick'],
            ['cells' => 'H25', 'direction' => 'all', 'style' => 'thick'],
            ['cells' => 'C33', 'direction' => 'all', 'style' => 'thick'],
            ['cells' => 'H33', 'direction' => 'all', 'style' => 'thick'],
        ];
    }

    protected function getActualAlignments()
    {
        return [
            ['cells' => 'B3:D3', 'horizontal' => 'center'],
            ['cells' => 'B4:D4', 'horizontal' => 'center'],
            ['cells' => 'B5:D5', 'horizontal' => 'center', 'vertical' => 'center', 'wrap' => true],
            ['cells' => 'B6:D6', 'horizontal' => 'center'],
            ['cells' => 'F6:I6', 'horizontal' => 'center'],
            ['cells' => 'C8', 'horizontal' => 'right'],
            ['cells' => 'B9:F9', 'horizontal' => 'center'],
            ['cells' => 'B14:C14', 'horizontal' => 'center'],
            ['cells' => 'F14:G14', 'horizontal' => 'center'],
            ['cells' => 'B15:C15', 'horizontal' => 'center', 'vertical' => 'center', 'wrap' => true],
            ['cells' => 'F15:G15', 'horizontal' => 'center'],
            ['cells' => 'H15:I15', 'horizontal' => 'center'],
            ['cells' => 'B16', 'horizontal' => 'center', 'vertical' => 'center'],
            ['cells' => 'C16:D16', 'horizontal' => 'center', 'vertical' => 'center'],
            ['cells' => 'F16:I16', 'horizontal' => 'center', 'vertical' => 'top'],
            ['cells' => 'B17', 'horizontal' => 'center', 'vertical' => 'center'],
            ['cells' => 'C17:D17', 'horizontal' => 'center', 'vertical' => 'center'],
            ['cells' => 'B19:H19', 'vertical' => 'center'],
            ['cells' => 'B21:I21', 'vertical' => 'center'],
            ['cells' => 'B23:I23', 'vertical' => 'center'],
            ['cells' => 'C25:D25', 'horizontal' => 'center', 'vertical' => 'center'],
            ['cells' => 'F25:G25', 'horizontal' => 'center', 'vertical' => 'center'],
            ['cells' => 'B31:D31', 'horizontal' => 'center', 'vertical' => 'center'],
            ['cells' => 'F31:I31', 'horizontal' => 'center', 'vertical' => 'center'],
            ['cells' => 'B35:E35', 'horizontal' => 'center'],
            ['cells' => 'F35:I35', 'horizontal' => 'center'],
            ['cells' => 'B36:E36', 'horizontal' => 'center'],
            ['cells' => 'F36:I36', 'horizontal' => 'center'],
            ['cells' => 'B37', 'horizontal' => 'right'],
            ['cells' => 'C37:E37', 'horizontal' => 'center'],
            ['cells' => 'F37', 'horizontal' => 'right'],
            ['cells' => 'G37:I37', 'horizontal' => 'center'],
        ];
    }

    protected function getActualFonts()
    {
        return [
            ['cells' => 'B3:D3', 'size' => 12, 'bold' => 'true'],
            ['cells' => 'B4:D4', 'size' => 8],
            ['cells' => 'B5:D5', 'size' => 10, 'bold' => 'true'],
            ['cells' => 'B6:D6', 'size' => 8],
            ['cells' => 'F6:I6', 'size' => 14, 'bold' => 'true'],
            ['cells' => 'C8', 'size' => 14, 'bold' => 'true'],
            ['cells' => 'D8:E8', 'size' => 14, 'bold' => 'true'],
            ['cells' => 'H10', 'size' => 14, 'bold' => 'true'],
            ['cells' => 'B14:C14', 'underline' => 'true'],
            ['cells' => 'D14:E14', 'underline' => 'true'],
            ['cells' => 'F14:G14', 'underline' => 'true'],
            ['cells' => 'H14:I14', 'underline' => 'true'],
            ['cells' => 'B15:C15', 'underline' => 'true'],
            ['cells' => 'C25:D25', 'size' => 12, 'bold' => 'true'],
            ['cells' => 'E25', 'size' => 12, 'bold' => 'true'],
            ['cells' => 'F25:G25', 'size' => 12, 'bold' => 'true'],
            ['cells' => 'H25', 'size' => 12, 'bold' => 'true'],
            ['cells' => 'B31:D31', 'size' => 12, 'bold' => 'true'],
            ['cells' => 'F31:I31', 'size' => 12, 'bold' => 'true'],
            ['cells' => 'B37', 'size' => 8],
            ['cells' => 'C37:E37', 'size' => 8],
            ['cells' => 'F37', 'size' => 8],
            ['cells' => 'G37:I37', 'size' => 8],
        ];
    }

    protected function getActualContents()
    {
        return [
            'B1' => 'Приложение №3 к Догоовру №__от _________г.',
            'B3' => 'СМТ-Сервис',
            'B4' => 'Название авторизованного сервисного центра',
            'B5' => 'Москва, ул.Маршала Прошлякова 6 стр.1а 8(495)255-266-7',
            'B6' => 'Адрес, телефон',
            'F6' => 'АКТ ТЕХНИЧЕСКОГО СОСТОЯНИЯ',
            'C8' => '№',
            'D8' => $this->bid->bid_number,
            'B9' => 'Действителен в течении 90 дней со дня выдачи.',
            'H10' => \Yii::$app->formatter->asDate($this->bid->created_at),
            'B11' => 'Мы, нижеподписавшиеся, подтверждаем, что была проведена техническая диагностика изделия под ',
            'B12' => 'торговой маркой "HYUNDAI".',
            'B14' => 'Название изделия',
            'D14' => 'Модель',
            'F14' => 'Серийный номер',
            'H14' => 'Дата продажи изделия',
            'B15' => $this->bid->equipment,
            'D15' => $this->bid->brand_model_name,
            'F15' => $this->bid->serial_number,
            'H15' => \Yii::$app->formatter->asDate($this->bid->purchase_date),
            'B16' => 'Заказчик',
            'C16' => $this->bid->client_name,
            'F16' => 'Организация продавец',
            'B17' => 'Телефон',
            'C17' => $this->bid->client_phone,
            'B18' => 'Внешний вид изделия (состояние, комплектность, наличие повреждений)',
            'B19' => $this->bid->condition_id ? $this->bid->condition->name : '',
            'B20' => 'Причина обращения',
            'B21' => $this->bid->isWarranty() ? $this->bid->defect : '',
            'B22' => 'Выявленная неисправность',
            'B23' => $this->bid->isWarranty() ? $this->bid->diagnostic : '',
            'B24' => 'На основании пункта _____ "Условий гарантийного сервисного обслуживания" выявленный дефект признается:',
            'E25' => $this->bid->isWarranty() ? 'Да' : '',
            'H25' => !$this->bid->isWarranty() ? 'Да' : '',
            'B27' => 'По причине: (заполняется в случае невозможности гарантийного ремонта',
            'B28' => !$this->bid->isWarranty() ? $this->bid->defect : '',
            'B29' => 'Выявленная неисправность',
            'B30' => !$this->bid->isWarranty() ? $this->bid->diagnostic : '',
            'B31' => 'Изделие выдается покупателю',
            'F31' => 'Изделие оставлено на ответственное храниение в АСЦ',
            'B35' => 'Механик, выполнивший диагностику',
            'F35' => 'Руководитель АСЦ',
            'B36' => '__________________/____________________/',
            'F36' => '__________________/____________________/',
            'B37' => 'Подпись',
            'C37' => 'Расшифровка подписи',
            'F37' => 'Подпись',
            'G37' => 'Расшифровка подписи',
        ];
    }

}