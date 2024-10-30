<?php
/**
 * CompleteUpdateURLs class
 */
class CompleteUpdateURLs {
	/* The list of tables => (columns) to update -- some themes may keep their own tables? */
	private static $TABLES = array(
		'wp_options' => array(
			'id' => 'option_id',
			'columns' => array(
				'option_value'
			)
		),
		'wp_posts' => array(
			'id' => 'ID',
			'columns' => array(
				'post_content',
				'guid'
			)
		),
		'wp_postmeta' => array(
			'id' => 'meta_id',
			'columns' => array(
				'meta_value'
			)
		)
	);
	
	private $old_url = null;
	private $new_url = null;
	
	public function __construct($old_url, $new_url) {
		$this->old_url = $old_url;
		$this->new_url = $new_url;
	}
	
	private function update_tables($tables) {
		global $wpdb;
		$count = 0;
		
		foreach ($tables as $table => $table_data) {
			foreach ($table_data['columns'] as $column) {
				$results = $wpdb->get_results("SELECT " . $table_data['id'] . "," . $column . " FROM " . $table, ARRAY_A);
				
				foreach ($results as $row) {
					$id = $row[$table_data['id']];
					$data = $row[$column];
					
                    $unserialized = unserialize($data);
					$new = null;
					
					if ($unserialized !== false) {
						if ($this->update_data($unserialized)) {
							$new = serialize($unserialized);
						}
                    }
                    else {
						if ($this->update_data($data)) {
							$new = $data;
						}
					}
	 
					if ($new) {
						$result = $wpdb->update($table, array($column => str_replace("'", "\'", $new)),
							array($table_data['id'] => $id), array('%s'), array('%d'));

						if (!$result) {
							throw new Exception('Could not update row. Table: ' . $table . " ID: " . $id);
						}
						
						$count++;						
					}
				}
			}
		}
		
		return $count;
	}
	
	private function update_data(&$data) {
		if (is_scalar($data)) {
			if (!is_string($data)) {
				return false;
			}
			
			$new = str_replace($this->old_url, $this->new_url, stripslashes($data));
			$result = $new != $data;
			$data = $new;
			
			return $result; 
		}
		
		if (is_array($data)) {
			$result = false;
			
			foreach ($data as $key => &$d) {
				$result = $this->update_data($d) || $result;
			}
			
			return $result;
		}
		
		return false;
	}
	
	public function update() {
		$count = 0;
		
		try {
			$count = $this->update_tables(self::$TABLES);
		}
		catch (Exception $e) {
			$count = 0;
		}
		
		return $count;
	}
};
?>