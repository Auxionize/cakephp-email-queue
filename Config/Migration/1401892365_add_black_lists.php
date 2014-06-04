<?php
class AddBlackLists extends AppMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'email_black_lists' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 300, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'blocked_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'note' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'email' => array('column' => 'email', 'unique' => 0, 'length' => array('255')),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'),
				),
			),
			'create_field' => array(
				'email_queue' => array(
					'black_listed' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index', 'after' => 'modified'),
					'indexes' => array(
						'black_listed' => array('column' => 'black_listed', 'unique' => 0),
					),
				),
			),
			'alter_field' => array(
				'email_queue' => array(
					'config' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'email_black_lists'
			),
			'drop_field' => array(
				'email_queue' => array('black_listed', 'indexes' => array('black_listed')),
			),
			'alter_field' => array(
				'email_queue' => array(
					'config' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 254, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return parent::before($direction);
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return parent::after($direction);
	}
}
