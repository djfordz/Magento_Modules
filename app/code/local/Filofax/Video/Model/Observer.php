<?php

class Filofax_Video_Model_Observer 
{

    public function saveProductVideos(Varien_Event_Observer $observer) {
        $product = $observer->getEvent()->getProduct();
        $product_id = $product->getId();
        $existingProduct = Mage::getModel('filovid/video')->loadByProductId($product_id);

        if ($videos = $this->_getRequest()->getPost('videos')) {
            $youtube = array();
            foreach ($videos as $video) {
                if ($video['type'] === 'youtube') {
                    $youtube[] = $video['video_id'];
                } 
            }

            if ($existingProduct->getSize()) {
                $videoModel = $existingProduct->getFirstItem();
            } else {
                $videoModel = Mage::getModel('filovid/video');
                $videoModel->setProduct($product_id);
            }

            $videoModel->setYoutube(json_encode($youtube));
            $videoModel->save();
        } else {
            if ($existingProduct->getSize()) {
                $videoModel = $existingProduct->getFirstItem();
                $videoModel->delete();
            }
        }
    }

    protected function _getRequest() {
        return Mage::app()->getRequest();
    }

}
