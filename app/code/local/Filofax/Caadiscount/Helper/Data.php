<?php
/**
 * Created by PhpStorm.
 * User: dford
 * Date: 10/23/18
 * Time: 11:56 AM
 */
class Filofax_Caadiscount_Helper_Data extends Mage_Core_Helper_Data
{
    /**
     *
     */
    const CAA_DISCOUNT_MAX_LENGTH = 16;
    /**
     *
     */
    const MODULE = 'filocaa';
    /**
     *
     */
    const USER_ID = 'user_id_';
    /**
     *
     */
    const PASSWORD = 'password_';
    /**
     *
     */
    const ENDPOINT = 'endpoint_';
    /**
     *
     */
    const TEST = 'test_';
    /**
     *
     */
    const ENABLE = 'enable_';
    /**
     *
     */
    const APP = '_application';
    /**
     *
     */
    const GENERAL = 'general';
    /**
     *
     */
    const DEBUG = 'debug';
    /**
     *
     */
    const ACCOUNTID = 'account';
    /**
     *
     */
    const DS = '/';
    /**
     *
     */
    const LOG_FILE = 'caa_discount.log';

    /**
     * @param $application 'validation'|'discount'
     * @return bool
     */
    public function isEnabled($application)
    {
            return Mage::getStoreConfigFlag(self::MODULE . self::DS . $application . self::APP . self::DS .
                self::ENABLE .
                $application);
    }

    /**
     * @param $application
     * @return bool
     */
    public function isTestMode($application)
    {

        return Mage::getStoreConfigFlag(self::MODULE . self::DS . $application . self::APP . self::DS . self::TEST .
                $application);
    }

    /**
     * @param $application
     * @return mixed
     */
    public function getUserId($application)
    {
        $path = self::USER_ID;
        if ($this->isTestMode($application)) {
            $path = self::TEST . self::USER_ID;
        }

        return Mage::getStoreConfig(self::MODULE . self::DS . $application . self::APP . self::DS . $path .
                $application);
    }

    /**
     * @param $application
     * @return mixed
     */
    public function getPassword($application)
    {
        $path = self::PASSWORD;
        if ($this->isTestMode($application)) {
            $path = self::TEST . self::PASSWORD;
        }

        return Mage::getStoreConfig(self::MODULE . self::DS . $application . self::APP . self::DS . $path .
                $application);
    }

    /**
     * @param $application
     * @return mixed
     */
    public function getEndpoint($application)
    {
        $path = self::ENDPOINT;
        if ($this->isTestMode($application)) {
            $path = self::TEST . self::ENDPOINT;
        }

        return Mage::getStoreConfig(self::MODULE . self::DS . $application . self::APP . self::DS . $path .
                $application);
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return Mage::getStoreConfigFlag(self::MODULE . self::DS . self::GENERAL . self::DS . self::DEBUG);
    }

    /**
     * @return mixed
     */
    public function getAccountNumber()
    {
        return Mage::getStoreConfig(self::MODULE . self::DS . self::GENERAL . self::DS . self::ACCOUNTID);
    }
}