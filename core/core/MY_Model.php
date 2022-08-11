<?php

class MY_Model extends CI_Model {

	const DEBUG = false;

	var $fields = array();
	var $table = '';

	public function get_query_list($filters = null, $orders = null, $paginator = array(), $fields = '*') {
		$items = array();

		$sql = $this->sql_query_list($filters, $orders, $paginator, $fields);
		if (self::DEBUG) {
			log_message('error', '|| ' . get_class($this) . ' || get_query_list: ' . $sql);
		}
		try {
			$query = $this->db->query($sql);
		} catch (Exception $e) {
			log_message('error', $sql);
			throw new Exception($e->getMessage());
		}

		foreach ($query->result() as $it) {
			$items[] = $it;
		}

		return $items;
	}

	public function sql_query_list($filters, $orders, $paginator = array(), $fields = '*') {
		$sql = 'SELECT ' . $fields . ' FROM ' . $this->table;

		if ($filters) {
			$str = trim(implode(' ', $filters));
			if (substr($str, 0, strlen('AND')) == 'AND') {
				$str = substr($str, strlen('AND'));
			} elseif (substr($str, 0, strlen('OR')) == 'OR') {
				$str = substr($str, strlen('OR'));
			}
			$sql .= $str ? ' WHERE ' . $str : '';
		}

		$orders = $orders ? $orders : array($this->table . '.orden ASC', $this->table . '.id DESC');
		$sql .= ' ORDER BY ' . implode(',', $orders);

		if (isset($paginator['startrow']) && isset($paginator['pagination']) && is_numeric($paginator['startrow']) && is_numeric($paginator['pagination'])) {
			$sql .= ' LIMIT ' . $paginator['startrow'] . ', ' . $paginator['pagination'];
		}
		return $sql;
	}

	public function get_item($id) {
		$sql = 'SELECT * FROM ' . $this->table . ' WHERE id = ' . $id . ' LIMIT 1';
		if (self::DEBUG) {
			log_message('error', '|| ' . get_class($this) . ' || get_item: ' . $sql);
		}

		$res = $this->db->query($sql);
		if ($res) {
			return $res->row();
		} else {
			log_message('error', "MY_Model - Error(" . $this->db->_error_number() . ") " . $this->db->_error_message());
			return false;
		}
	}

	public function save_item($o, $isUpdate, $validar = true) {
		if ($validar) {
			// Validación de ejemplo
			// if(! $o->novedad_id){
			// 	throw new Exception('Seleccioná alguna novedad.');
			// 	return false;
			// }
		}

		$data = array();

		if ($o) {
			foreach ($this->fields as $f) {
				if (isset($o->$f)) {
					$data[$f] = $o->$f;
				}
			}
		}

		if ($isUpdate) {
			$this->db->where('id', $o->id);
			$this->db->update($this->table, $data);
			$id = $o->id;
		} else {
			$data['id'] = null;
			$this->db->insert($this->table, $data);
			$id = $this->db->insert_id();
		}

		if (self::DEBUG) {
			log_message('error', '|| ' . get_class($this) . ' || save_item: ' . $this->db->last_query());
		}

		if ($this->db->_error_message()) {
			log_message('error', 'MY_Model - ' . $this->table . ': ' . $this->db->_error_message());
		}
		return $id;
	}

	public function delete_item($id) {
		$this->db->where('id', $id);
		$this->db->delete($this->table);

		if (self::DEBUG) {
			log_message('error', '|| ' . get_class($this) . ' || delete_item: ' . $this->db->last_query());
		}
	}

	public function bulk_update_field($field_key, $field_value, $items_ids) {
		$items_ids = array_map('intval', $items_ids);
		if ($items_ids) {
			$this->db->update(
				$this->table,
				[$field_key => 0],
				'id NOT IN(' . implode(',', $items_ids) . ') AND ' . $field_key . '=' . $field_value
			);
			$this->db->update(
				$this->table,
				[$field_key => $field_value],
				'id IN(' . implode(',', $items_ids) . ')'
			);
		} else {
			$this->db->update(
				$this->table,
				[$field_key => 0],
				$field_key . '=' . $field_value
			);
		}
	}

	public function clonarRelacion($fk, $id_origen, $id_clon) {
		$relaciones = $this->get_query_list(array(' AND ' . $fk . '=' . intval($id_origen)));
		$nuevas_relaciones = array_map(function ($r) use ($id_clon, $fk) {
			unset($r->id);
			$r->{$fk} = $id_clon;
			return (array) $r;
		}, $relaciones);
		if ($nuevas_relaciones) {
			$this->db->insert_batch($this->table, $nuevas_relaciones);
		}
	}
}