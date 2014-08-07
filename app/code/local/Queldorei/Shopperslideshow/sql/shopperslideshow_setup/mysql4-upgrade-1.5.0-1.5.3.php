<?php

$installer = $this;
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer->startSetup();

$installer->getConnection()
	->addColumn(
		$installer->getTable('shopperslideshow/revolution_slides'),
		'global', 'smallint(6) not null default "0"');
$installer->getConnection()
	->addColumn(
		$installer->getTable('shopperslideshow/revolution_slides'),
		'city', 'varchar(2048) default ""');

$installer->endSetup();
