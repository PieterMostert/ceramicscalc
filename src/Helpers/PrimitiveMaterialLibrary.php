<?php

namespace DerekPhilipAu\Ceramicscalc\Helpers;

use DerekPhilipAu\Ceramicscalc\Models\Material\PrimitiveMaterial;

class PrimitiveMaterialLibrary
{
    protected static $jsonMaterials = null;

    protected static function init()
    {
        if (!self::$jsonMaterials) {
            self::$jsonMaterials = json_decode(file_get_contents("data/primitive_materials.json"), true);
        }
    }

    public function getPrimitiveMaterialByName($name)
    {
        return $this->getPrimitiveMaterialByKey('name', $name);
    }

    public function getPrimitiveMaterialByKey($key, $value)
    {
        self::init();

        foreach (self::$jsonMaterials as $jsonMaterial) {
            if (isset($jsonMaterial[$key]) && $jsonMaterial[$key] === $value) {
                return PrimitiveMaterial::createFromJson($jsonMaterial);
            }
        }
        return false;
    }
}

?>