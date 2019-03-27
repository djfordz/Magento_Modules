<?php

class Filofax_Video_Helper_Data extends Mage_Core_Helper_Abstract 
{
    public function enabled()
    {
       return Mage::getStoreConfig('filovid/general/enable');
    }

    public function hasVideos($product) 
    {
        $existingProduct = Mage::getModel('filovid/video')->loadByProductId($product->getId());
        if ($existingProduct->getSize()) {
            $videoModel = $existingProduct->getFirstItem();
            return $videoModel->getData();
        } else {
            return false;
        }
    }
	
    public function getVideos($videos) {
        $html = '';
		
        if (strlen(trim($videos['youtube']))) {
            $youtube = json_decode($videos['youtube']);
            foreach ($youtube as $id) {
                $html .= '<a href="#">
					<img src="https://img.youtube.com/vi/' . $id . '/maxresdefault.jpg" width="72" height="72" />
				</a>';
            }
        }
        
        return $html;
    }
}
