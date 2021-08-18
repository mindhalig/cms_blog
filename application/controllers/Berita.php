<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Berita extends CI_Controller {
	public function index(){
			$jumlah= $this->Model_utama->view('berita')->num_rows();
			$config['base_url'] = base_url().'berita/index/';
			$config['total_rows'] = $jumlah;
			$config['per_page'] = 15; 	
			if ($this->uri->segment('3')==''){
				$dari = 0;
			}else{
				$dari = $this->uri->segment('3');
			}
			
			if (is_numeric($dari)) {
				if ($this->input->post('kata')<>''){
					$data['title'] = "Hasil Pencarian keyword - ".cetak($this->input->post('kata'));
					$data['description'] = description();
					$data['keywords'] = keywords();
					$data['berita'] = $this->Model_utama->cari_berita($this->db->escape_str($this->input->post('kata')));
					$this->pagination->initialize($config);
				}else{
					$data['title'] = "Semua Berita";
					$data['description'] = description();
					$data['keywords'] = keywords();
					$data['berita'] = $this->Model_utama->view_joinn('berita','users','kategori','username','id_kategori','id_berita','DESC',$dari,$config['per_page']);
					$this->pagination->initialize($config);
				}
			}else{
				redirect('main');
			}
			
			$this->template->load(template().'/template',template().'/berita',$data);
	}

	public function detail(){
		$query = $this->Model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('judul_seo' => $this->uri->segment(3)),'id_berita','DESC',0,1);
		
		$data['kategori'] = $this->Model_utama->view('kategori');
		
		if ($query->num_rows()<=0){
			redirect('main');
		}else{
			$row = $query->row_array();
			$data['title'] = cetak($row['judul']);
			$data['description'] = cetak_meta($row['isi_berita'],0,500);
			$data['keywords'] = cetak($row['tag']);
			$data['rows'] = $row;
			$data['kategori'] = $this->Model_utama->view('kategori');

			$dataa = array('dibaca'=>$row['dibaca']+1);
			$where = array('id_berita' => $row['id_berita']);
			$this->Model_utama->update('berita', $dataa, $where);

			$this->load->helper('captcha');
			$vals = array(
                'img_path'	 => './captcha/',
                'img_url'	 => base_url().'captcha/',
                'font_path' => base_url().'asset/Tahoma.ttf',
                'font_size'     => 37,
                'img_width'	 => '200',
                'img_height' => 55,
                'border' => 0, 
                'word_length'   => 5,
                'expiration' => 7200
            );
			$cap = create_captcha($vals);
            $data['image'] = $cap['image'];
            $this->session->set_userdata('mycaptcha', $cap['word']);
			$this->template->load(template().'/template',template().'/detailberita',$data);
		}
	}

	function indeks_berita(){
		$data['title'] = 'Indeks Berita';
		$data['description'] = description();
		$data['keywords'] = keywords();
		$data['record'] = $this->Model_utama->view('kategori');
		if (isset($_POST['filter'])){
			$bulan = strlen($_POST['bulan']);
			$tanggal = strlen($_POST['tanggal']);	
			if ($bulan <= 1){ $bulann = '0'.$this->input->post('bulan'); }else{ $bulann = $this->input->post('bulan'); }
			if ($tanggal <= 1){ $tanggall = '0'.$this->input->post('tanggal'); }else{ $tanggall = $this->input->post('tanggal'); }
			$fil = $_POST['tahun'].'-'.$bulann.'-'.$tanggall;
			$data['hari_ini'] = $fil;
			$data['hitung'] = $this->Model_utama->view_where('berita',array('tanggal'=>$fil));
		}else{
			$data['hari_ini'] = date('Y-m-d');
			$data['hitung'] = $this->Model_utama->view_where('berita',array('tanggal'=>date('Y-m-d')));
		}
		$this->template->load(template().'/template',template().'/indeks_berita',$data);
	}
}
