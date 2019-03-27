<?php

class Filofax_Video_Block_Adminhtml_Catalog_Product_Tab extends Mage_Adminhtml_Block_Template implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    public function _construct() {
        parent::_construct();
        $this->setTemplate('filofax/filovid.phtml');
    }

    public function getTabLabel() {
        return $this->__('Videos');
    }

    public function getTabTitle() {
        return $this->__('Videos');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }

    public function getVideos() {
        $index = 0;
        $videos = $this->hasVideos();
        $html = '';
        if (strlen(trim($videos['youtube']))) {
            $youtube = json_decode($videos['youtube']);
            foreach ($youtube as $id) {
                $html.='<tr><td><select id="videos_' . $index . '_type" name="videos[' . $index . '][type]" disabled><option value="youtube" selected>Youtube</option</select></td><td class="nobr"><input type="text" id="videos_' . $index . '_video_id" value="' . $id . '" name="videos[' . $index . '][video_id]" class="input-text required-entry"></td><td class="last"><button onclick="removeVideo(this);" id="videos_' . $index . '_delete_button" class="scalable delete icon-btn delete-product-option" type="button" title="Delete Tier"><span><span><span>Delete</span></span></span></button></td></tr>';
                $index++;
            }
        }
        return $html;
    }

    public function hasVideos() {
        $product = $this->getProduct();
        $existingProduct = Mage::getModel('filovid/video')->loadByProductId($product->getId());
        if ($existingProduct->getSize()) {
            $videoModel = $existingProduct->getFirstItem();
            return $videoModel->getData();
        } else {
            return false;
        }
    }

    public function getProduct() {
        return Mage::registry('product');
    }

}
