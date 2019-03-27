<?php

class Filofax_Affiliate_Model_Abstract extends Mage_Core_Model_Abstract
{
    public function getAttributeOptions()
	{
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
            ->getItems();

    	return $attributes;
	}

    public function getEventIds()
	{
        $event_setting = Mage::helper('filoaffiliate')
            ->getConfigData('event_id');
        
		if (!$event_setting) {
			return array(array('label' => 'No Event Ids', 'value' => 0));
		}
		if (strstr($event_setting, ',')) {
			$event_ids = explode(',', $event_setting);
		} else {
			$event_ids[0] = $event_setting;
		}

		$attributes = array();

		foreach($event_ids as $event_id) {
			$attributes[] = array('label' => $event_id, 'value' => $event_id);
		}

    	return $attributes;
	}

    public function getFormattedOrderData($settings)
	{	
		$total_sale = 0;

		if(isset($_GET['orderid'])){
			$order = Mage::getModel('sales/order')->load($_GET['orderid']);
		} else {
			$order = Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId());
		}

		if(strstr($settings['event_id'], ',')){
			$event_id = explode(',', $settings['event_id']);
			$event_id = trim($event_id[0]);
		} else {
			$event_id = trim($settings['event_id']);
		}

		$items = array();

		foreach ($order->getItemsCollection() as $item) {
			if ($item->getProductType() == 'simple') {
				$item_event_id = $event_id;
				$product_id = ($item->getParentItemId() != '') ? $parent[$item->getParentItemId()]['product_id'] : $item->getProductId();
                
                if ($new_event_id = Mage::getModel('catalog/product')->load($product_id)->getEventId()) {
					// -- get the event id from product model.
					if ($new_event_id != $event_id) {
						$item_event_id = $new_event_id;
					}
				} else {
					$item_event_id = $event_id;
				}

				$price      = ($item->getParentItemId() != '') ? $parent[$item->getParentItemId()]['base_price'] : $this->decimalFormat($item->getBasePrice());
				$discount	= ($item->getParentItemId() != '') ? $parent[$item->getParentItemId()]['base_discount_amount'] : $this->decimalFormat($item->getBaseDiscountAmount());
				$tax 		= ($item->getParentItemId() != '') ? $parent[$item->getParentItemId()]['base_tax_amount'] : $this->decimalFormat($item->getBaseTaxAmount());
				$line_inc 	= ($item->getParentItemId() != '') ? $parent[$item->getParentItemId()]['base_row_total_incl_tax'] : $this->decimalFormat($item->getBaseRowTotalInclTax());
				$line_ex	= ($item->getParentItemId() != '') ? $parent[$item->getParentItemId()]['base_row_total'] : $this->decimalFormat($item->getBaseRowTotal());
				
				if ($settings['tax'] == 1) {
					$calculated_price = $price-($discount/$item->getQtyOrdered())+ ($tax/$item->getQtyOrdered());
				} else {
					$calculated_price = ($price-($discount/$item->getQtyOrdered()));
				}
				
				$total_sale += $calculated_price*$item->getQtyOrdered();
				
				$items[] = array('type' 			=> $item->getProductType(),
								 'item_id' 			=> $item->getitemId(),
								 'product_id' 		=> $item->getProductId(),
								 'parent_item_id' 	=> $item->getParentItemId(),
								 'product_options' 	=> $item->getProductOptions(),
								 'weight' 			=> $item->getWeight(),
								 'qty'			 	=> $item->getQtyOrdered(),
								 'sku' 				=> $item->getSku(),
								 'name' 			=> $item->getName(),
								 'price_each'		=> $calculated_price,
								 'line_discount'	=> $discount,
								 'line_total'		=> $line_inc,
								 'line_total_ex'	=> $line_ex,
								 'event_id'			=> $item_event_id);
			}


			if ($item->getProductType() == 'configurable') {
				$parent[$item->getId()] = $item->getData();
			}
		}
		
		$data = array(
					'id' 					=> $order->getId(),
					'coupon_code'			=> $order->getCouponCode(),
					'increment_id' 			=> $order->getIncrementId(),
					'state' 				=> $order->getState(),
					'status' 				=> $order->getStatus(),
					'shipping_description' 	=> $order->getShippingDescription(),
					'customer_id' 			=> $order->getCustomerId(),
					'base_discount_amount' 	=> $order->getBaseDiscountAmount(),
					'base_grand_total' 		=> $order->getBaseGrandTotal(),
					'base_shipping_amount' 	=> $order->getBaseShippingAmount(),
					'base_shipping_tax_amount' => $order->getBaseShippingTaxAmount(),
					'base_subtotal' 		=> $order->getBaseSubtotal(),
					'base_tax_amount' 		=> $order->getBaseTaxAmount(),
					'base_to_order_rate'	=> $order->getBaseToOrderRate(),
					'total_qty_ordered'		=> $order->getTotalQtyOrdered(),
					'weight' 				=> $order->getWeight(),
					'customer_email' 		=> $order->getCustomerEmail(),
					'customer_firstname' 	=> $order->getCustomerFirstname(),
					'customer_lastname' 	=> $order->getCustomerLastname(),
					'global_currency_code' 	=> $order->getGlobalCurrencyCode(),
					'remote_ip' 			=> $order->getRemoteIp(),
					'shipping_method' 		=> $order->getShippingMethod(),
					'item_total_sale_count' => $total_sale,
					'items' 				=> $items);

		return $data;
	}

	public function decimalFormat($value)
	{
		return number_format($value, 2, '.', '');
	}
}
