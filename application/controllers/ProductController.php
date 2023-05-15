<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductController extends CI_Controller {

    // protected $productModel;
	// protected $form_validation;
	// protected $input;

    public function __construct()
    {
        parent::__construct();

		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model(['ProductModel']);
        $this->productModel = new ProductModel();
    }

	public function index()
	{
        $products = $this->productModel->get_product();
        $this->load->view('product', array('products' => $products));
	}

	public function getProduct()
    {

        $list = $this->productModel->get_product();
		$data = array();
		foreach ($list as $products) {
            $row = array();
			$row[] = $products->id;
            $row[] = $products->name;
            $row[] = $products->price;
			$row[] = $products->description;
            $row[] = sprintf('<button style="margin-right: 20px" type="button" class="btn btn-sm btn-primary ml-2 showModal" data-id="%s" >Edit</button>  ', $products->id);
            $row[] = sprintf('<button  type="button" class="btn btn-sm btn-danger ml-2 DeleteProduct"  data-id="%s" >Delete</button>', $products->id);
            $data[] = $row;
        }
		echo json_encode(array('data' => $data));
    }

	public function insert_product()
    {	
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('price', 'Price', 'required|numeric');
		$this->form_validation->set_rules('description', 'description', 'required');
		if($this->form_validation->run()== FALSE){// Kiểm tra dữ liệu có hợp lệ hay không 
			$errors = $this->form_validation->error_array();
			$message = [
			  'status' => 'warning',
			  'massage' => 'Vui long kiem tra lai',
			  'errors' => $errors
			];
		  }else{
		$data = array(
            'name' => $this->input->post('name'),
            'price' => $this->input->post('price'),
			'description' => $this->input->post('description'),
        );
        $this->productModel->insert_product($data);
		$message = [
			'status' => 'success',
			'massage' => 'Thêm sản phẩm thành công',
		  ];
		}
		echo json_encode($message);
	
    }

	public function edit_product($id) {
		$data=$this->productModel->edit_product($id);
		echo json_encode($data);
	}

	public function update_product($id) {
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('price', 'Price', 'required|numeric');
		$this->form_validation->set_rules('description', 'description', 'required');
		if($this->form_validation->run()== FALSE){// Kiểm tra dữ liệu có hợp lệ hay không 
			$errors = $this->form_validation->error_array();
			$message = [
			  'status' => 'warning',
			  'massage' => 'Vui long kiem tra lai',
			  'errors' => $errors
			];
		  }else{
		  $data = array(
			'name' => $this->input->post('name'),
            'price' => $this->input->post('price'),
			'description' => $this->input->post('description'),
			);
			$this->productModel->update_product($data,$id);
			$message = [
			  'status' => 'success',
			  'massage' => 'Update Product Succsessfully',
			];
		}
		echo json_encode($message);
	}

	public function DeleteProduct($id){
		$data=$this->productModel->delete($id);
		$message = [
			 'status' => 'success',
			 'massage' => 'Deleted Product Successsfully',
		   ];
		echo json_encode($message);
	   }

}
