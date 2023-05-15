<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductModel extends CI_Model {

	public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

	public function get_product()
	{
		$query = $this->db->get('product');
		return $query->result();
	}

	public function insert_product($data) {
        $this->db->insert('product', $data);
    }

	public function edit_product( $id) {

		$this->db->select("*");
        $this->db->from("product");
        $this->db->where("id",$id);
        $query = $this->db->get()->row();
        return $query;
	}

	public function update_product($data,$id) 
    {   
        $this->db->where('id', $id);
        $query = $this->db->update('product', $data);
        return $query;
    }

	public function delete($id)
    {
        $this->db->where('id', $id);
        $query= $this->db->delete('product');
        return $query;
    }


}
