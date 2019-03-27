<?php

class Filofax_Locale_Block_Switcher extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
    public function getCountries() 
    {
        $countriesTranslated = array();
        $countries = unserialize($this->getConfigData('stores'));
        $num = count($countries['locales']);

        array_shift($countries['locales']);
        array_shift($countries['urls']);

        for ($i = 0; $i < $num - 1; $i++) {
            $temp = $this->_getCountryLabels($countries['locales'][$i]); 
            $temp['url'] = $countries['urls'][$i];
            array_push($countriesTranslated, $temp);
        }

        usort($countriesTranslated, function($a, $b) { return strcmp($a['country_name'], $b['country_name']); });

        return $countriesTranslated;
    } 

    public function getMainCountry()
    {
        $langCode = $this->_getLocaleCode();
        
        return $this->_getCountryLabels($langCode);
    }

    protected function _getCountryLabels($langCode) 
    {
        $allLanguages = $this->_getAllLocales();

        $country = array();
        $country['lang'] = substr($langCode, 0, strpos($langCode, '_'));
        $country['lang_code'] = $langCode;
        $country['country_code'] = substr($langCode, strpos($langCode, "_") + 1);

        foreach ($allLanguages as $language) {
            if ($language['value'] == $langCode) {
                $country['real_country_name'] = $this->_getTranslation($country['country_code'], 'country', $country['country_code']);

                switch ($langCode) {
                    case 'nl_BE' : $country['country_name'] = $country['real_country_name'] . ' (Vlaams)';
                    break;
                    case 'fr_BE' :  $country['country_name'] = 'Belgique' . ' (Français)';        
                    break;
                    case 'de_BE' : $country['country_name'] = $country['real_country_name'] . ' (Deutsch)';
                    break;
                    case 'fr_CH' : $country['country_name'] = 'Suisse' . ' (Français)';
                    break;
                    case 'de_CH' : $country['country_name'] = $country['real_country_name'] . ' (Deutsch)';
                    break;
                    case 'fr_CA' : $country['country_name'] = $country['real_country_name'] . ' (Français)';
                    break;
                    case 'en_CA' : $country['country_name'] = $country['real_country_name'] . ' (English)';
                    break;
                    case 'zh_HK' : $country['country_name'] = 'Hong Kong';
                    break;
                    case 'en_HK' : $country['country_name'] = 'Hong Kong';
                    break;
                    case 'de_LU' : $country['country_name'] = 'Luxemburg' . ' (Deutsch)';
                    break;
                    case 'fr_LU' : $country['country_name'] = $country['real_country_name'] . ' (Français)';
                    break;
                    default : $country['country_name'] = $country['real_country_name'];
                    break;
                }
                
                break;
            }
        }

        return $country;
    }

    protected function _getLocaleCode()
    {
        return Mage::app()->getLocale()->getLocaleCode();
    }

    protected function _getAllLocales()
    {
        return Mage::app()->getLocale()->getOptionLocales();
    }
    
    protected function getConfigData($key)
    {
        return Mage::getStoreConfig('filolocale/general/' . $key);
    }

    protected function _getTranslation($value = null, $type = null, $locale = null)
    {
        return Zend_Locale::getTranslation($value, $type, $locale);
    }
}
