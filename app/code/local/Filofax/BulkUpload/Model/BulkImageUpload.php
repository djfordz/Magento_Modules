<?php

class Filofax_BulkUpload_Model_BulkImageUpload extends Mage_Core_Model_Abstract
{
    const XML_IMAGE_PATH = 'import';

    protected $_galleryBackendModel;

    protected $_path;

    public function __construct()
    {
        $this->_path = Mage::getBaseDir('media') . DS . self::XML_IMAGE_PATH;
    }

    public function execute()
    {
        $products = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect(array('sku'))
            ->load(); 

        if ($products->count() > 0) {
            foreach ($products as $value) {
                $sku = $value->getSku();
                $paths = $this->getImagePath($sku);
                $i = 0;
                if (count($paths) > 0) {
                    $skuPaths = array();
                    foreach ($paths as $path) {
                        $simpleExclude = $exclude = false;
                        $parentTypes = array();
                        $pathParts = pathinfo($path);
                        preg_match('/([-\d\w]*)__/', $pathParts['filename'], $skuPaths);
                        if (isset($skuPaths[1]) && $skuPaths[1] == $sku) {
                            try {
                                $product = Mage::getModel('catalog/product')->load($value->getId());
                                $urlKey = $product->getUrlKey();
                                $visibility = $product->getVisibility();
                                $img = $this->splitString($path);
                                $parentProduct = $this->getParentProduct($product);
                                Mage::log($img, null, 'bulkimage.log');
                                if (isset($parentProduct) && $img['front'] == true) {
                                    if (!copy($path, $pathParts['dirname'] . '/' . $pathParts['filename'] . '-1.' . $pathParts['extension'])) {
                                        Mage::log('failed to copy file', null, 'bulkimage.log');
                                    }

                                    if ($img['exclude'] == 'X' || $img['exclude'] == 'Y') {
                                        $exclude = true;
                                    }

                                    if ($i == 0) {
                                        $parentSort = 1;
                                    } else {
                                        $parentSort = $i;
                                    }

                                    if ($img['config_value'] == true) {
                                        $parentTypes = array('image', 'small_image', 'thumbnail');
                                        $parentSort = 0;
                                    }

                                    $path_parts = pathinfo($img['path']);
                                    $parentImgPath = $path_parts['dirname'] . DS . $path_parts['filename'] . '-1.' . $path_parts['extension'];
                                    $this->addImage($parentProduct, $parentImgPath, $parentTypes, $img['label'], $parentSort, true, $exclude);
                                }

                                if ($img['exclude'] == 'X' || $img['exclude'] == 'Z') {
                                    $simpleExclude = true;
                                }

                                $this->addImage($product, $img['path'], $img['types'], $img['label'], $img['sort_order'], true, $simpleExclude);

                                if (isset($product)) {
                                    try {
                                        $product->setUrlKey($urlKey);
                                        $product->setVisibility($visibility);
                                        $product->save();
                                    } catch (Exception $e) {
                                        Mage::log($e->getMessage(), null, 'bulkimage.log');
                                        echo json_encode(array("response" => "true"));
                                    }
                                }

                                if (isset($parentProduct)) {
                                    try {
                                        $parentProduct->save();
                                    } catch (Exception $e) {
                                        Mage::log($e->getMessage(), null, 'bulkimage.log');
                                        echo json_encode(array("response" => "true"));
                                    }
                                }

                                $i++;
                            } catch (Exception $e) {
                                Mage::log($e->getMessage(), null, 'bulkimage.log');
                                echo json_encode(array("response" => "true"));
                            }
                        }
                    }
                }
            }
            $indexer = Mage::getModel('index/indexer');
            $process = $indexer->getProcessByCode('super');
            if ($process) {
                $process->reindexAll();
            }

        }

        echo json_encode(array("response" => "true"));
    }

    protected function getParentProduct($product)
    {
        $parent = null;
        if ($product->getTypeId() == "simple") {
            $parentIds = Mage::getModel('catalog/product_type_grouped')
                ->getParentIdsByChild($product->getId());
            if (!$parentIds) {
                $parentIds = Mage::getModel('catalog/product_type_configurable')
                    ->getParentIdsByChild($product->getId());
            }
            if (isset($parentIds[0])) {
                $parent = Mage::getModel('catalog/product')->load($parentIds[0]);
            }
        }
        
        return $parent;
    }

    protected function addImage($product, $file, $types, $label, $sortOrder, $move = true, $exclude = false) {

        try {
            $attributes = $product
                ->getTypeInstance(true)
                ->getSetAttributes($product);

            $filename = $attributes['media_gallery']
                ->getBackend()
                ->addImage($product, $file, $types, $move, $exclude);

            $attributes['media_gallery']
                ->getBackend()
                ->updateImage($product, $filename, array('label' => $label, 'position' => $sortOrder));

        } catch (Exception $e) {
            Mage::log($e->getMessage(), null, 'bulkimage.log');
            json_encode(array("response" => "true"));
        }
        
    }

    protected function splitString($var)
    {
        $types = array();
        $configVal = $front = $exclude = false;
        $label = '';
        $sortOrder = $orderArr = null;

        if (empty($var)) {
            return false;
        }

        preg_match_all('/__([a-zA-Z-1]+)?_?([a-zA-Z]+[- .]?[a-zA-Z]+[- .]?[a-zA-Z]+)?_?([\d])?_?([10])?_?([10])?_?([10])?-?([XYZ])?(.jpg)/', $var, $imgOptions);

        if (($len = count($imgOptions)) > 0) {
            for ($i = 0; $i < $len; $i++) {
                switch ($i) {
                    case 1: $order = isset($imgOptions[$i][0]) ? $imgOptions[$i][0] : null;
                    break;
                    case 2: $label = isset($imgOptions[$i][0]) ? $imgOptions[$i][0] : '';
                    break;
                    case 3: $sortOrder = isset($imgOptions[$i][0]) ? $imgOptions[$i][0] : null;
                    break;
                    case 4: $types[] = ((int)$imgOptions[$i][0] == 1) ? 'image' : '';
                    break;
                    case 5: $types[] = ((int)$imgOptions[$i][0] == 1) ? 'small_image' : '';
                    break;
                    case 6: $types[] = ((int)$imgOptions[$i][0] == 1) ? 'thumbnail' : '';
                    break;
                    case 7: $exclude = isset($imgOptions[$i][0]) ? $imgOptions[$i][0] : false;
                    break;
                }
            }
        }

        if (isset($order)) {
            $orderArr = $this->_checkOrder($order, $sortOrder);
        }

        if (!empty($label) && strpos($label, '.') !== false) {
            $label = str_replace('.', '/', $label);
        }

        if (isset($orderArr)) {
            if (isset($orderArr['types'])) {
                $types = $orderArr['types'];
            }

            if (isset($orderArr['sort_order'])) {
                $sortOrder = $orderArr['sort_order'];
            }

            if (isset($orderArr['config_value'])) {
                $configVal = $orderArr['config_value'];
            }

            if (isset($orderArr['front'])) {
                $front = $orderArr['front'];
            }
        }

        return array('path' => $var, 'types' => $types, 'label' => $label, 'sort_order' => $sortOrder, 'config_value' => $configVal, 'front' => $front, 'exclude' => $exclude);
    }

    protected function _checkOrder($name, $sortOrder)
    {
        $configVal = $front = false;
        $types = null;

        switch (strtolower($name)) {
            case 'front': $sortOrder = 0; $front = true; $types = array('image', 'small_image', 'thumbnail');
            break;
            case 'iso': $sortOrder = 1;
            break;
            case 'open': $sortOrder = 2;
            break;
            case 'inside' : $sortOrder = 3;
            break;
            case 'front-1' : $sortOrder = 0; $configVal = true; $front = true; $types = array('image', 'small_image', 'thumbnail');
            break;
            default: $sortOrder = !empty($sortOrder) ? $sortOrder : 20;
            break;
        }

        $result = array(
            'sort_order' => $sortOrder,
            'config_value' => $configVal,
            'front' => $front,
            'types' => $types

        );
        return $result;
    }

    protected function getImagePath($pattern) {
        return glob($this->_path . DS . $pattern . '*.jpg');
    }
}
