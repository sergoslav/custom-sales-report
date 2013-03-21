<?php

class SLV_SalesReport_Model_BlockObserver extends Mage_Core_Model_Abstract
{
    protected static $_observers = null;
    protected static $_observersKeys = array();

    public function __construct()
    {
        self::$_observersKeys = self::getKeysBlockObservers();
    }

    public static function getBlockObserver($key)
    {
        if (self::$_observers === null) {
            $cacheId = 'SLV_SALES_REPORT_CONFIG_BLOCK_OBSERVERS';
            $cacheData = Mage::app()->getCache()->load($cacheId);
            if ($cacheData !== false) {
                $result = unserialize($cacheData);
            } else {
                $entities = Mage::getConfig()->getNode('block_events', 'global');
                $result = array();
                foreach ((array)$entities as $_key => $value) {
                    $result[$_key] = (array)$value->observers;
                }
                Mage::app()->getCache()->save(serialize($result), $cacheId, array('CONFIG'));
            }
            self::$_observers = $result;
        }
        try {
            return self::$_observers[$key];
        } catch (Exception $e) {
            Mage::log($e->getMessage());

            return false;
        }
    }

    public static function getKeysBlockObservers()
    {
        $cacheId = 'SLV_SALES_REPORT_CONFIG_BLOCK_OBSERVERS_KEYS';
        $cacheData = Mage::app()->getCache()->load($cacheId);
        if ($cacheData !== false) {
            $result = unserialize($cacheData);
        } else {
            $entities = Mage::getConfig()->getNode('block_events', 'global');
            $result = array();
            foreach ((array)$entities as $key => $value) {
                $result[] = $key;
            }
            Mage::app()->getCache()->save(serialize($result), $cacheId, array('CONFIG'));
        }

        return $result;
    }

    public function runBlockObserver($observer)
    {
        if (in_array($classOfObserver = get_class($observer->getEvent()->getBlock()), self::$_observersKeys)) {
            $blockObserver = $this->getBlockObserver($classOfObserver);
        } elseif (in_array($classOfObserver = get_parent_class($observer->getEvent()->getBlock()), self::$_observersKeys)) {
            $blockObserver = $this->getBlockObserver($classOfObserver);
            if (!$blockObserver['parent']) {
                return false;
            }

        } else {
            return false;
        }

        switch ($blockObserver['type']) {
            case 'singleton':
                Mage::getBlockSingleton($blockObserver['block'])->$blockObserver['method'](
                    $observer->getEvent()->getBlock()
                );
                
                break;
            default:
                Mage::getBlock($blockObserver['block'])->$blockObserver['method']($observer->getEvent()->getBlock());

                break;
        }

    }
}
