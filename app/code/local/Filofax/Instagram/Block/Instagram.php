<?php
/**
 * Created by PhpStorm.
 * User: dford
 * Date: 9/27/18
 * Time: 1:52 PM
 */
class Filofax_Instagram_Block_Instagram
    extends Mage_Core_Block_Html_Link
    implements Mage_Widget_Block_Interface
{
    protected function _construct() {
        parent::_construct();
    }
    protected function _toHtml() {
        return parent::_toHtml();
    }

    public function getLatestPosts() {
        $feed = array();

        if (Mage::helper('filogram')->isEnabled()) {
            $api = Mage::getModel('filogram/instagram');
            $feed = $api->getFeed();
        }

        return $feed;
    }

    public function getSliderOptions() {

        if ($this->getData('autoSlide') == 1) {
            $options =
            ', autoSlide: 1, '
            . 'autoSlideTimer: '. $this->getData('autoSlideTimer') . ', '
            . 'autoSlideTransTimer: ' . $this->getData('autoSlideTransTimer');
            return $options;
        }
    }

    public function getInstagramOptions()
    {
        return $this->getData();
    }

    public function getUsername()
    {
        return Mage::getStoreConfig('filogram/general/username');
    }

}
