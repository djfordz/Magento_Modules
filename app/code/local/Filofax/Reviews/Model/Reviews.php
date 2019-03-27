<?php
/**
 * Created by PhpStorm.
 * User: dford
 * Date: 3/12/19
 * Time: 10:26 AM
 */
class Filofax_Reviews_Model_Reviews extends Mage_Review_Model_Review
{
    protected function _construct()
    {
        $this->_init('filofax_reviews/review');
    }
}