<?php

/* @var $installer Mage_Eav_Model_Entity_Setup */
$installer = $this;
$installer->startSetup();

/* @var $eavConfig Mage_Eav_Model_Config */
$eavConfig = Mage::getSingleton('eav/config');

$entityCode = Mage_Catalog_Model_Product::ENTITY;

$installer->removeAttribute($entityCode, 'available_from');
$installer->addAttribute($entityCode, 'available_from',
    array(
        'label'                     => 'available from',
        'group'                     => 'Monoqi',
        'global'                    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'backend'                   => 'monoqi_availability/attribute_datetime',
        'show_on_frontend'          => false,
        'used_in_product_listing'   => true,
        'unique'                    => false,
        'is_configurable'           => false,
        'used_for_price_rules'      => false,
        'visible'                   => true,
        'required'                  => false,
        'user_defined'              => true,
        'is_user_defined'           => false,
        'searchable'                => false,
        'filterable'                => false,
        'visible_on_front'          => true,
        'default'                   => null,
        'type'                      => 'datetime',
        'input'                     => 'date',
        'class'                     => 'validate-date',
    )
);

$from = $eavConfig->getAttribute($entityCode, 'available_from');
$from->setData('apply_to', array('simple'));
$from->setData('frontend_input_renderer', 'monoqi_availability/adminhtml_renderer_datetime');
$from->save();

$installer->removeAttribute($entityCode, 'available_to');
$installer->addAttribute($entityCode, 'available_to',
    array(
        'label'                     => 'available to',
        'group'                     => 'Monoqi',
        'global'                    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'backend'                   => 'monoqi_availability/attribute_datetime',
        'show_on_frontend'          => false,
        'used_in_product_listing'   => true,
        'unique'                    => false,
        'is_configurable'           => false,
        'used_for_price_rules'      => false,
        'visible'                   => true,
        'required'                  => false,
        'user_defined'              => true,
        'is_user_defined'           => false,
        'searchable'                => false,
        'filterable'                => false,
        'visible_on_front'          => true,
        'default'                   => null,
        'type'                      => 'datetime',
        'input'                     => 'date',
        'class'                     => 'validate-date',
    )
);

$to = $eavConfig->getAttribute($entityCode, 'available_to');
$to->setData('apply_to', array('simple'));
$to->setData('frontend_input_renderer', 'monoqi_availability/adminhtml_renderer_datetime');
$to->save();




$installer->endSetup();