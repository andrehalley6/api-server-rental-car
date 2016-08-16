<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cars extends CI_Model {
	var $table;

	public function __construct() {
		parent::__construct();
		$this->table = "cars";
	}

	public function get_all_cars() {
		return $this->db->get($this->table);
	}

	public function get_car_by_id($id) {
		$this->db->where("id", $id);
		return $this->db->get($this->table);
	}

	public function get_car_by_plate($plate) {
		$this->db->where("plate", $plate);
		return $this->db->get($this->table);
	}

	public function add_car() {
		$data = array(
			'brand'	=> $this->input->post('brand'),
			'type'	=> $this->input->post('type'),
			'color'	=> $this->input->post('color'),
			'year'	=> $this->input->post('year'),
			'plate'	=> $this->input->post('plate'),
		);

		$this->db->insert($this->table, $data);
		$insert_id = $this->db->insert_id();

		return $insert_id;
	}

	public function edit_car($id, $put_data) {
		$data = array(
			'brand'	=> $put_data['brand'],
			'type'	=> $put_data['type'],
			'color'	=> $put_data['color'],
			'year'	=> $put_data['year'],
			'plate'	=> $put_data['plate'],
		);

		$this->db->where("id", $id);
		return $this->db->update($this->table, $data);
	}

	public function delete_car($id) {
		$this->db->where("id", $id);
		return $this->db->delete($this->table);
	}
}

/* End of file cars.php */
/* Location: ./application/models/cars.php */