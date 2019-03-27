<?php

class Filofax_Affiliate_Model_Affiliate extends Filofax_Affiliate_Model_Abstract
{
    public function getTrackingCode()
    {
        $_settings = Mage::getStoreConfig('filoaffiliate/general');

		$_orderdata = $this->getFormattedOrderData($_settings);
		
		if (strstr($_settings['event_id'], ',')) {
			$event_id = explode(',', $_settings['event_id']);
			$event_id = trim($event_id[0]); 
		} else {
			$event_id = trim($_settings['event_id']);
        }

		$groups = array();
		foreach ($_orderdata['items'] as $item) {
            if ($item['event_id']) { 
                $event_id = $item['event_id']; 
            } 

            $product = Mage::getModel('catalog/product')
                ->loadByAttribute('sku',$item['sku']);
			$categoryCollection = $product->getCategoryIds(); 
			$categoriesArray = array(); 
            
            if (is_array($categoryCollection)) {
				foreach ($categoryCollection as $cid) {
					$currentCategory = Mage::getModel('catalog/category')->load($cid);
					$categoriesArray[] = $currentCategory->getName(); 
				}
            }

			$categoriesList = implode(',', $categoriesArray); 

			$categoriesIds = implode(',', $categoryCollection); 
            
            if (strpos($categoriesIds,'5') !== false) {
				$event_id = 'PAPER'; 
			} else {
				$event_id = 'DEFAULT'; 
			}
			
            $item_data[] = 'AW:P|' . $_settings['program_id'] . '|' . $_orderdata['increment_id'] . '|' . $item['product_id'] . '|' . $item['name'] . '|' . number_format($item['price_each'], 2, '.', '') . '|' . number_format($item['qty'], 0, '.', '') . '|' . $item['sku'] . '|' . $event_id.'|' . $categoriesList;

			if (isset($groups[$event_id])) {
				$groups[$event_id] .= number_format(($item['price_each']*$item['qty']), 2, '.', '');
			} else {
				$groups[$event_id] = number_format(($item['price_each']*$item['qty']), 2, '.', '');
			}
        }

        $parts = array();

		foreach ($groups as $group_id => $group_price) {
			$parts[] = $group_id . ':' . $group_price;
        }

        $data = array('tt'		=> 'ns',
					  'tv'		=> '2',
					  'merchant'=> $_settings['program_id'],
					  'amount'	=> $_orderdata['item_total_sale_count'],
					  'ref'		=> $_orderdata['increment_id'],
					  'parts'	=> implode('|',$parts),
					  'vc'		=> $_orderdata['coupon_code'],
					  'ch'		=> 'aw',
					  'cr'		=> $_orderdata['global_currency_code']);

        $html = '<!-- affiliate window tracking code -->';
        $noscript_string = array();
        foreach ($data as $key => $value) {
		    $noscript_string[] = $key . '=' . urlencode($value);
        }
        
        $html .= '<img src="https://www.awin1.com/sread.img?' . implode('&amp;', $noscript_string) . '" border="0" width="0" height="0"/>
<form style="display:none;" name="aw_basket_form">
<textarea wrap="physical" id="aw_basket">';
 		foreach($item_data as $item){
	 		$html .= "\r" . $item;
 		}
		$html .= '
</textarea>
</form>
<script type="text/javascript">
var AWIN = {};
AWIN.Tracking = {};
AWIN.Tracking.Sale = {};

/*** Set your transaction parameters ***/ 

AWIN.Tracking.Sale.amount = \'' . number_format($_orderdata['item_total_sale_count'], 2, '.', '') . '\';
AWIN.Tracking.Sale.currency = \'' . $_orderdata['global_currency_code'] . '\';
AWIN.Tracking.Sale.orderRef = \'' . $_orderdata['increment_id'] . '\';
AWIN.Tracking.Sale.parts = \'' . implode('|',$parts) . '\';
AWIN.Tracking.Sale.voucher = \'' . $_orderdata['coupon_code'] . '\';
AWIN.Tracking.Sale.channel = \'' . 'aw' . '\'; 
</script>';

	return $html;
    }
}
