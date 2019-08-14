<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
$installer = $this;
$installer->startSetup();
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('delete_sales_flat_order')};    
CREATE TABLE {$this->getTable('delete_sales_flat_order')}
AS (SELECT * FROM sales_flat_order WHERE 1=2);
ALTER TABLE delete_sales_flat_order CHANGE COLUMN entity_id id INT(10) NOT NULL AUTO_INCREMENT primary key;
ALTER TABLE delete_sales_flat_order ADD
(
    entity_id int(10) unsigned,
    payment_type varchar(100),
    shipping_name varchar(100),
    billing_name varchar(100),
    order_delete_date DATETIME
);

DROP TABLE IF EXISTS {$this->getTable('delete_sales_flat_order_item')};    
CREATE TABLE {$this->getTable('delete_sales_flat_order_item')}
AS (SELECT * FROM sales_flat_order_item WHERE 1=2);
ALTER TABLE delete_sales_flat_order_item CHANGE COLUMN item_id id INT(10) NOT NULL AUTO_INCREMENT primary key;
ALTER TABLE delete_sales_flat_order_item ADD
(
    item_id int(10) unsigned
);

DROP TABLE IF EXISTS {$this->getTable('deleteorder_counter')};    
CREATE TABLE {$this->getTable('deleteorder_counter')}(
  `id` int(11) unsigned NOT NULL auto_increment,
  `count` varchar(255) NOT NULL default '',
  PRIMARY KEY (`id`)
);

INSERT INTO {$this->getTable('deleteorder_counter')}(count) values ('1');

DROP TABLE IF EXISTS {$this->getTable('delete_sales_flat_order_address')};    
CREATE TABLE {$this->getTable('delete_sales_flat_order_address')}
AS (SELECT * FROM sales_flat_order_address WHERE 1=2);
ALTER TABLE delete_sales_flat_order_address CHANGE COLUMN entity_id id INT(10) NOT NULL AUTO_INCREMENT primary key;
ALTER TABLE delete_sales_flat_order_address ADD
(
    entity_id int(10) unsigned
),
ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
$installer->endSetup();


