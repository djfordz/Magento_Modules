<?php

class Filofax_Upsells_Block_Catalog_Product_View_Type_Configurable extends Amasty_Conf_Block_Catalog_Product_View_Type_Configurable
{
    protected $_currentAttributes;
    const SMALL_SIZE = 50;
    const BIG_SIZE = 100;

    protected function _afterToHtml($html)
    {
        $html = parent::_afterToHtml($html);
        if ('product.info.options.configurable' == $this->getNameInLayout() && !Mage::app()->getRequest()->isAjax()) {
            if ($this->getModuleConfig('general/hide_dropdowns')) {
                $attributeIdsWithImages = Mage::registry('amconf_images_attrids');
                if (!empty($attributeIdsWithImages)) {
                    foreach ($attributeIdsWithImages as $attrIdToHide) {
                        $html = preg_replace(
                            '@(id="attribute' . $attrIdToHide . ')(-)?([0-9]*)(")(\s+)(class=")(.*?)(super-attribute-select)(-)?([0-9]*)@',
                            '$1$2$3$4$5$6$7$8$9$10 no-display',
                            $html
                        );
                    }
                }
            }

            if ($this->_currentAttributes) {
                $this->_currentAttributes = array_unique($this->_currentAttributes);
                $confData = $this->_generateConfig();

                if (Mage::getStoreConfig('amconf/general/show_clear')) {
                    $html = '<a href="#" onclick="javascript: spConfig.clearConfig(); return false;">'
                        . $this->__('Reset Configuration') . '</a>' . $html;
                }

                $html = '<script type="text/javascript">
                            try{
                                var amConfAutoSelectAttribute = ' . intval(Mage::getStoreConfig('amconf/general/auto_select_attribute')) . ';
                                confData = new AmConfigurableData(' . Zend_Json::encode($confData) . ');                               
                            }
                            catch(ex){}
                        </script>' . $html;
            }
        }

        return $html;
    }

    protected function getSettingConfig()
    {
        $this->_useSimplePrice = ($this->getAmastyHelper()->getConfigUseSimplePrice() == 2
            || ($this->getAmastyHelper()->getConfigUseSimplePrice() == 1
                AND $this->getProduct()->getData('amconf_simple_price'))
        ) ? 1 : 0;

        $url = $this->getUrl('amconf/ajax', array('id' => $this->getProduct()->getId()));
        if (isset($_SERVER['HTTPS']) && 'off' != $_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "") {
            $url = str_replace('http:', 'https:', $url);
        }

        $confData = array(
            'textNotAvailable'   => $this->__('Choose previous option please...'),
            'mediaUrlMain'       => $url,
            'showAllOptions'     => $this->getModuleConfig('general/show_all'),
            'imageContainer'     => $this->getModuleConfig('css_selector/image'),
            'useSimplePrice'     => $this->_useSimplePrice,
            'dropdownPrice'      => $this->getModuleConfig('price/dropdown_price'),
            'swatchesPrice'      => $this->getModuleConfig('price/swatch_price'),
        );

        return $confData;
    }

    protected function _generateConfig()
    {
        $confData = $this->getSettingConfig();

        if ($this->getModuleConfig('price/dropdown_price')) {
            $confData['parentPrice'] =
                Mage::helper('core')->currency($this->getProduct()->getFinalPrice(), false, false);
        }

        $reloadValues = explode(',', $this->getModuleConfig('general/reload_content'));
        foreach ($reloadValues as $reloadValue) {
            $confData['selector'][$reloadValue] = $this->getModuleConfig('css_selector/' . $reloadValue);
        }

        $simpleProducts = $this->getProduct()->getTypeInstance(true)->getUsedProducts(null, $this->getProduct());
        /** @var Mage_Catalog_Model_Product $simple */
        foreach ($simpleProducts as $simple) {
            $strKey = $this->getProductKey($simple);
            if ($strKey) {
                $confData[$strKey] = $this->generateChildConfig($simple, $reloadValues);

                /*generate image block reloading link*/
                if ($simple->getImage()
                    && $simple->getImage() != "no_selection"
                    && in_array('image', $reloadValues)
                ) {
                    $url = $this->getUrl('amconf/ajax', array('id' => $simple->getId()));
                    if (isset($_SERVER['HTTPS']) && 'off' != $_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "") {
                        $url = str_replace('http:', 'https:', $url);
                    }
                    $confData[$strKey]['media_url'] = $url;

                    /* if setting enabled  - copy value to first selected value*/
                    if (Mage::getStoreConfig('amconf/general/oneselect_reload')) {
                        if (strpos($strKey, ',') !== FALSE) {
                            $k = substr($strKey, 0, strpos($strKey, ','));
                            if (!(array_key_exists($k, $confData) && array_key_exists('media_url', $confData[$k]))) {
                                $confData[$k]['media_url'] = $confData[$strKey]['media_url'];
                            }
                        }
                    }
                }
            }
        }

        return $confData;
    }

    /**
     * array key here is a combination of choosen options
     * @param Mage_Catalog_Model_Product $simple
     * @return string
     */
    protected function getProductKey(Mage_Catalog_Model_Product $simple)
    {
        $key = array();
        foreach ($this->_currentAttributes as $attributeCode) {
            $key[] = $simple->getData($attributeCode);
        }

        return implode(',', $key);
    }

    /**
     * Generate product information for child simple product
     * @param Mage_Catalog_Model_Product $simple
     * @param $reloadValues
     * @return array
     */
    protected function generateChildConfig(Mage_Catalog_Model_Product $simple, $reloadValues)
    {
        $result = array();
        if (in_array('name', $reloadValues)) {
            $result['name'] = $simple->getName();
        }
        if (in_array('short_description', $reloadValues)) {
            $result['short_description'] =
                $this->helper('catalog/output')->productAttribute(
                    $simple,
                    nl2br($simple->getShortDescription()),
                    'short_description'
                );
        }
        if (in_array('description', $reloadValues)) {
            $result['description'] =
                $this->helper('catalog/output')->productAttribute(
                    $simple,
                    $simple->getDescription(),
                    'description'
                );
        }
        if (in_array('attributes', $reloadValues)) {
            /* change registry because in attribute block current product is got from it*/
            $_currProduct = Mage::registry('product');
            Mage::unregister('product');
            Mage::register('product', $simple);

            $result['attributes'] = Mage::app()->getLayout()->createBlock(
                'catalog/product_view_attributes',
                'product.attributes.child',
                array('template' => "catalog/product/view/attributes.phtml")
            )->setProduct($simple)->toHtml();

            Mage::unregister('product');
            Mage::register('product', $_currProduct);
        }

        $result['not_is_in_stock'] = !$simple->isSaleable();
        $result['product_alert_allow'] = Mage::getStoreConfig('catalog/productalert/allow_stock');

        if ($result['product_alert_allow'] == 1) {
            if (!$simple->isSaleable()) {
                $_currProduct = Mage::registry('product');
                Mage::unregister('product');
                Mage::register('product', $simple);

                $result['product_alert'] = Mage::app()
                  ->getLayout()
                  ->createBlock(
                    'productalert/stock',
                    'productalert.stock',
                    array('template' => 'productalert/stock.phtml')
                  )->setProduct($simple)
                  ->toHtml();

                Mage::unregister('product');
                Mage::register('product', $_currProduct);
            }
        }

        $result['yearly_upsell_enable'] = Mage::getStoreConfig('filoupsell/general/enable');
        
        if ($result['yearly_upsell_enable'] == 1) {
            $result['upsell_year_show'] = Mage::getStoreConfig('filoupsell/general/year');
            $productModel = Mage::getModel('catalog/product');
            $tmpProduct = $productModel->loadByAttribute('sku', $simple->getSku());
            $upsellSku = $tmpProduct->getFiloupsellUpsellSku();
            $result['upsell_year'] = $tmpProduct->getYear();
            if (!empty($upsellSku)) {
              $upsellProduct = $productModel->loadByAttribute('sku', $upsellSku);
              $result['upsell_product_id'] = $upsellProduct->getId();
            }
            $_currProduct = Mage::registry('product');
            Mage::unregister('product');
            Mage::register('product', $simple);
            $result['yearly_upsell_html'] = Mage::app()
                ->getLayout()
                ->createBlock(
                    'filoupsell/upsell',
                    'filoupsell.banner.after_name',
                    array('template' => 'filofax/filoupsell/upsell.phtml')
                )->setProduct($simple)
                ->toHtml();

            Mage::unregister('product');
            Mage::register('product', $_currProduct);
        }

        if (in_array('sku', $reloadValues)) {
            $result['sku'] = $simple->getSku();
        }

        $result['new_product'] = Mage::helper('ThemeOptionsBizarre')
            ->getProductLabels($simple, 'new');

        if ($this->_useSimplePrice) {
            $tierPriceHtml = $this->getTierPriceHtml($simple);

            $search = 'product-price-' . $simple->getId();
            $replace = 'product-price-' . $this->getProduct()->getId();
            $result['price_html'] =
                str_replace($search, $replace, $this->getPriceHtml($simple) . $tierPriceHtml);
            $result['price_clone_html'] =
                str_replace($search, $replace, $this->getPriceHtml($simple, false, '_clone') . $tierPriceHtml);
            $result['price'] =
                Mage::helper('core')->currency(
                    Mage::helper('tax')->getPrice($simple, $simple->getFinalPrice(), true),
                    false,
                    false
                );
            if (Mage::helper('core')->isModuleEnabled('Amasty_ProductMatrix')) {
                $result['price_tax'] = $result['price'];
                $result['price'] =
                    Mage::helper('core')->currency(
                        Mage::helper('tax')->getPrice($simple, $simple->getFinalPrice(), false),
                        false,
                        false
                    );
                $result['old_price'] =
                    Mage::helper('core')->currency(
                        Mage::helper('tax')->getPrice($simple, $simple->getPrice(), true),
                        false,
                        false
                    );
            }

        }

        return $result;
    }

    protected function getImagesFromProductsAttributes()
    {
        $collection = Mage::getModel('amconf/product_attribute')->getCollection();
        $collection->addFieldToFilter('use_image_from_product', 1);

        $collection->getSelect()->join(
            array(
                'prodcut_super_attr' => $collection->getTable('catalog/product_super_attribute')),
            'main_table.product_super_attribute_id = prodcut_super_attr.product_super_attribute_id',
            array('prodcut_super_attr.attribute_id')
        );

        $collection->addFieldToFilter('prodcut_super_attr.product_id', $this->getProduct()->getEntityId());


        $attributes = $collection->getItems();
        $ret = array();

        foreach ($attributes as $attribute) {
            $ret[] = $attribute->getAttributeId();
        }

        return $ret;
    }

    public function getJsonConfig()
    {
        $productParent = $this->getProduct();
        $attributeIdsWithImages = array();
        $jsonConfig = parent::getJsonConfig();
        $config = Zend_Json::decode($jsonConfig);
        $productImagesAttributes = $this->getImagesFromProductsAttributes();
        $resModel = Mage::getResourceModel('catalog/product');

        foreach ($config['attributes'] as $attributeId => $attribute) {
            $this->_currentAttributes[] = $attribute['code'];

            $attr = Mage::getModel('amconf/attribute')->load($attributeId, 'attribute_id');
            if ($attr->getUseImage()) {
                $attributeIdsWithImages[] = $attributeId;
                $config['attributes'][$attributeId]['use_image'] = 1;
                $config['attributes'][$attributeId]['enable_carousel'] = $this->getModuleConfig('general/swatch_carou');
                $config['attributes'][$attributeId]['config'] = $attr->getData();

                $smWidth = $attr->getSmallWidth() != "0" ? $attr->getSmallWidth() : self::SMALL_SIZE;
                $smHeight = $attr->getSmallHeight() != "0" ? $attr->getSmallHeight() : self::SMALL_SIZE;
                $bigWidth = $attr->getBigWidth() != "0" ? $attr->getBigWidth() : self::BIG_SIZE;
                $bigHeight = $attr->getBigHeight() != "0" ? $attr->getBigHeight() : self::BIG_SIZE;

                foreach ($attribute['options'] as $i => $option) {
                    if (in_array($attributeId, $productImagesAttributes)) {
                        foreach ($option['products'] as $productId) {
                            $image = $resModel->getAttributeRawValue($productId, 'image', $productParent->getStore());
                            $config['attributes'][$attributeId]['options'][$i]['image'] =
                                (string)Mage::helper('catalog/image')->init($productParent, 'image', $image)
                                ->resize($smWidth, $smHeight);

                            $config['attributes'][$attributeId]['options'][$i]['bigimage'] =
                                (string)Mage::helper('catalog/image')->init($productParent, 'image', $image)
                                    ->resize($bigWidth, $bigHeight);
                            break;
                        }
                    } else {
                        $imgUrl = $this->getAmastyHelper()->getImageUrl($option['id'], $smWidth, $smHeight);
                        $tooltipUrl = $this->getAmastyHelper()->getImageUrl($option['id'], $bigWidth, $bigHeight);
                        if ($imgUrl == "") {
                            $imgUrl = $this->getAmastyHelper()->getPlaceholderUrl($attributeId, $smWidth, $smHeight);
                            $tooltipUrl = $this->getAmastyHelper()->getPlaceholderUrl($attributeId, $bigWidth, $bigHeight);
                        }
                        $config['attributes'][$attributeId]['options'][$i]['image'] = $imgUrl;
                        $config['attributes'][$attributeId]['options'][$i]['bigimage'] = $tooltipUrl;

                        $swatchModel = Mage::getModel('amconf/swatch')->load($option['id']);
                        $config['attributes'][$attributeId]['options'][$i]['color'] = $swatchModel->getColor();
                    }
                }
            }
        }
        Mage::unregister('amconf_images_attrids');
        Mage::register('amconf_images_attrids', $attributeIdsWithImages, true);

        return Zend_Json::encode($config);
    }

    /**
     * @return Amasty_Conf_Helper_Data
     */
    protected function getAmastyHelper()
    {
        return Mage::helper('amconf');
    }


    /**
     * @param $name
     * @return mixed
     */
    protected function getModuleConfig($name)
    {
        return Mage::getStoreConfig('amconf/' . $name);
    }

    public function getAddToCartUrl($product, $additional = array())
    {
        if ($this->hasCustomAddToCartUrl()) {
            return $this->getCustomAddToCartUrl();
        }
        if ($this->getRequest()->getParam('wishlist_next')) {
            $additional['wishlist_next'] = 1;
        }
        $addUrlKey = Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED;
        $addUrlValue = Mage::getUrl('*/*/*', array('_use_rewrite' => true, '_current' => true));
        $additional[$addUrlKey] = Mage::helper('core')->urlEncode($addUrlValue);
        return $this->helper('checkout/cart')->getAddUrl($product, $additional);
    }

    public function isSalable($product = null)
    {
        $salable = parent::isSalable($product);

        if ($salable !== false) {
            $salable = false;
            if (!is_null($product)) {
                $this->setStoreFilter($product->getStoreId(), $product);
            }

            if (!Mage::app()->getStore()->isAdmin() && $product) {
                $collection = $this->getUsedProductCollection($product)
                    ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                    ->setPageSize(1);
                if ($collection->getFirstItem()->getId()) {
                    $salable = true;
                }
            } else {
                foreach ($this->getUsedProducts(null, $product) as $child) {
                    if ($child->isSalable()) {
                        $salable = true;
                        break;
                    }
                }
            }
        }

        return $salable;
    }

    public function getAllowProducts()
    {
        if (!$this->hasAllowProducts()) {
            $products = array();
            $allProducts = $this->getProduct()->getTypeInstance(true)
                ->getUsedProducts(null, $this->getProduct());
            foreach ($allProducts as $product) {
                /**
                 * Should show all products (if setting set to Yes), but not allow "out of stock" to be added to cart
                 */
                if ($product->isSaleable() || Mage::getStoreConfig('amconf/general/out_of_stock')) {
                    if ($product->getStatus() != Mage_Catalog_Model_Product_Status::STATUS_DISABLED) {
                        if (in_array(Mage::app()->getStore()->getWebsiteId(), $product->getWebsiteIds())) {
                            $product->getStockItem()->setData('is_in_stock', 1);
                            $products[] = $product;
                        }
                    }
                }
            }
            $this->setAllowProducts($products);
        }
        return $this->getData('allow_products');
    }
}


