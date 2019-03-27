<?php
/**
 * Created by PhpStorm.
 * User: dford
 * Date: 9/27/18
 * Time: 1:08 PM
 */
class Filofax_Instagram_Helper_Data extends Mage_Core_Helper_Data
{
    public function isEnabled()
    {
        return Mage::getStoreConfig('filogram/general/enable');
    }

    public function getInstagramSliderOptions()
    {
        Mage::log(Mage::getStoreConfig('filogram/slideroptions'), null, test.log);
        return Mage::getStoreConfig('filogram/slideroptions');
    }

    public function getMediaUrl()
    {
        return Mage::getBaseUrl('media') . 'images/';
    }
}
