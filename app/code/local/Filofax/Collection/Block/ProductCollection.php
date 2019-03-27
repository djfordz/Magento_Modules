<?php

class Filofax_Collection_Block_ProductCollection extends Mage_Core_Block_Template
{
    public function getProductCollection($categoryName)
    {
        $categoryId = null;
        $categories = null;

        $categoryList = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addIsActiveFilter()
            ->addLevelFilter(2)
            ->addOrderField('name');

        foreach ($categoryList as $cat) {
            if (strtolower($cat->getName()) == $categoryName) {
                $categoryId = $cat->getId();
            }
        }

        if (isset($categoryId)) {
            $category = Mage::getModel('catalog/category')
                ->load($categoryId);
            $children = Mage::getModel('catalog/category')
                ->getCollection()
                ->setStoreId(Mage::app()->getStore()->getId());

            $categories = $children->addAttributeToSelect('*')
                ->addAttributeToFilter('parent_id', $category->getId())
                ->addAttributeToFilter('is_active', 1)
                ->addAttributeToSort('name');
        }

        return $categories;
    }

     public function getProductCollectionById($categoryId)
    {
        $category = Mage::getModel('catalog/category')->load($categoryId );
        $children = Mage::getModel('catalog/category')
            ->getCollection()
            ->setStoreId(Mage::app()->getStore()->getId());

        return $children->addAttributeToSelect('*')
            ->addAttributeToFilter('parent_id', $category->getId())
            ->addAttributeToFilter('is_active', 1)
            ->addAttributeToSort('name');
    }

    protected function _resizeImage($img, $size)
    {

        $imagePath = Mage::getBaseDir ('media') . DS . "catalog" . DS . "category" . DS . $img;
        if (!is_file( $imagePath )) {
            return false;
        }

        $dir = Mage::getBaseDir( 'media' ) . DS . "catalog" . DS . "category" . DS . "cache" .DS. "resized";
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $resizedPath = $dir . DS . $img;

        try {
            $image = new Varien_Image($imagePath);
            $image->constrainOnly(false);
            $image->keepFrame(true);
            $image->backgroundColor(array(255,255,255));
            $image->keepAspectRatio(true);
            $image->resize($size, $size);
            $image->save($resizedPath);
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
        }

        $storeCode = Mage::app()->getStore()->getCode();
        $baseUrl = str_replace("$storeCode/", '', $this->getBaseUrl());

        return $baseUrl . DS . 'media' . DS . 'catalog' . DS . 'category' . DS . 'cache' . DS . 'resized' . DS . $img;

    }

    public function resizeImageUrl($img, $size)
    {
        $storeCode = Mage::app()->getStore()->getCode();
        $resizedPath = Mage::getBaseDir( 'media' ) . DS . "catalog" . DS . "category" . DS . "cache" .DS. "resized" .DS. $img;


        if (file_exists($resizedPath)) {
            $baseUrl = str_replace("$storeCode/", '', $this->getBaseUrl());
            return $baseUrl . 'media' . DS . 'catalog' . DS . 'category' . DS . 'cache' . DS . 'resized' . DS . $img;
        }

        return $this->_resizeImage($img, $size);

    }
}
