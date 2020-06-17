<?php

namespace app\services\brand;

use app\models\Brand;
use app\models\BrandCorrespondence;

class BrandService
{
    /* @var string */
    private $name;

    /* @var Brand|null */
    private $brand;

    /* @var BrandCorrespondence|null */
    private $brandCorrespondence;

    public function __construct($name)
    {
        $this->name = $name;

        if (empty($name)) {
            $this->brand = null;
            $this->brandCorrespondence = null;
        }

        $this->brand = Brand::findByName($this->name);

        if (is_null($this->brand)) {
            $this->brandCorrespondence = BrandCorrespondence::findByName($this->name);
            if ($this->brandCorrespondence) {
                $this->brand = $this->brandCorrespondence->brand;
            }
        } else {
            $this->brandCorrespondence = null;
        }
    }

    public function getName()
    {
        if (empty($this->name)) {
            return 'Нет бренда';
        }

        if (!is_null($this->brand)) {
            return $this->brand->name;
        }

        return $this->name;
    }

    public function getBrandId()
    {
        return $this->brand ? $this->brand->id : null;
    }

    public function getBrandCorrespondenceId()
    {
        return $this->brandCorrespondence ? $this->brandCorrespondence->id : null;
    }

    public function getManufacturerId()
    {
        return $this->brand ? $this->brand->manufacturer_id : null;
    }

}