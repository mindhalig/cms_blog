<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Administrator extends CI_Controller {
	function index(){
		if (isset($_POST['submit'])){
			if ($this->input->post() && (strtolower($this->input->post('security_code')) == strtolower($this->session->userdata('mycaptcha')))){
				$username = $this->input->post('a');
				$password = $this->input->post('b');
				$cek = $this->Model_app->cek_user($username );
				$row = $cek->row_array();
				$total = $cek->num_rows();
				if ($total > 0){
					$hash = $row['password'];
					if (password_verify($password, $hash)) {
					$this->session->set_userdata('upload_image_file_manager',true);
					$this->session->set_userdata(array('username'=>$row['username'],
									   'level'=>$row['level'],
									   'id_session'=>$row['id_session']));

					redirect('administrator/home');
					}else{
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
							
						echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Username atau Password Salah!!</center></div>');
						$data['title'] = 'Username atau Password salah!';
						$this->load->view('administrator/view_login',$data);
					}
				}else{
					$data['title'] = 'username salah atau akun anda sedang diblokir';
					$this->load->view('administrator/view_login',$data);
				}
			}else{
				  echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Security Code salah!</center></div>');
				  redirect($this->uri->segment(1).'/index');
			}
		}else{
            if ($this->session->level!=''){
              redirect($this->uri->segment(1).'/home');
            }else{
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
    			$data['title'] = 'Administrator &rsaquo; Log In';
    			$this->load->view('administrator/view_login',$data);
            }
		}
	}

    function reset_password(){
        if (isset($_POST['submit'])){
            $usr = $this->Model_app->edit('users', array('id_session' => $this->input->post('id_session')));
            if ($usr->num_rows()>=1){
                if ($this->input->post('a')==$this->input->post('b')){
                    $data = array('password'=>hash("sha512", md5($this->input->post('a'))));
                    $where = array('id_session' => $this->input->post('id_session'));
                    $this->Model_app->update('users', $data, $where);

                    $row = $usr->row_array();
                    $this->session->set_userdata('upload_image_file_manager',true);
                    $this->session->set_userdata(array('username'=>$row['username'],
                                       'level'=>$row['level'],
                                       'id_session'=>$row['id_session']));
                    redirect('administrator/home');
                }else{
                    $data['title'] = 'Password Tidak sama!';
                    $this->load->view('administrator/view_reset',$data);
                }
            }else{
                $data['title'] = 'Terjadi Kesalahan!';
                $this->load->view('administrator/view_reset',$data);
            }
        }else{
            $this->session->set_userdata(array('id_session'=>$this->uri->segment(3)));
            $data['title'] = 'Reset Password';
            $this->load->view('administrator/view_reset',$data);
        }
    }

    //home
	function home(){
        if ($this->session->level=='admin'){
		  $this->template->load('administrator/template','administrator/view_home_admin');
        }else{
          $data['users'] = $this->Model_app->view_where('users',array('username'=>$this->session->username))->row_array();
          $data['modul'] = $this->Model_app->view_join_one('users','users_modul','id_session','id_umod','DESC');
          $this->template->load('administrator/template','administrator/view_home_users',$data);
        }
	}
    //identitas web
	function identitaswebsite(){
		cek_session_akses('identitaswebsite',$this->session->id_session);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/images/';
            $config['allowed_types'] = 'gif|jpg|png|ico';
            $config['max_size'] = '500'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('j');
            $hasil=$this->upload->data();

            if ($hasil['file_name']==''){
            	$data = array('nama_website'=>$this->db->escape_str($this->input->post('a')),
                                'email'=>$this->db->escape_str($this->input->post('b')),
                                'url'=>$this->db->escape_str($this->input->post('c')),
                                'facebook'=>$this->input->post('d'),
                                'rekening'=>$this->db->escape_str($this->input->post('e')),
                                'no_telp'=>$this->db->escape_str($this->input->post('f')),
                                'meta_deskripsi'=>$this->input->post('g'),
                                'meta_keyword'=>$this->db->escape_str($this->input->post('h')),
                                'maps'=>$this->input->post('i'));
            }else{
            	$data = array('nama_website'=>$this->db->escape_str($this->input->post('a')),
                                'email'=>$this->db->escape_str($this->input->post('b')),
                                'url'=>$this->db->escape_str($this->input->post('c')),
                                'facebook'=>$this->input->post('d'),
                                'rekening'=>$this->db->escape_str($this->input->post('e')),
                                'no_telp'=>$this->db->escape_str($this->input->post('f')),
                                'meta_deskripsi'=>$this->input->post('g'),
                                'meta_keyword'=>$this->db->escape_str($this->input->post('h')),
                                'favicon'=>$hasil['file_name'],
                                'maps'=>$this->input->post('i'));
            }
	    	$where = array('id_identitas' => $this->input->post('id'));
			$this->Model_app->update('identitas', $data, $where);

			redirect('administrator/identitaswebsite');
		}else{
			$proses = $this->Model_app->edit('identitas', array('id_identitas' => 1))->row_array();
			$data = array('record' => $proses);
			$this->template->load('administrator/template','administrator/mod_identitas/view_identitas',$data);
		}
	}

	// Controller Modul Menu Website

	function menuwebsite(){
		cek_session_akses('menuwebsite',$this->session->id_session);
		$data['record'] = $this->Model_app->view_ordering('menu','urutan','ASC');
		$this->template->load('administrator/template','administrator/mod_menu/view_menu',$data);
	}

	function tambah_menuwebsite(){
		cek_session_akses('menuwebsite',$this->session->id_session);
		if (isset($_POST['submit'])){
			$data = array('id_parent'=>$this->db->escape_str($this->input->post('b')),
                            'nama_menu'=>$this->db->escape_str($this->input->post('c')),
                            'link'=>$this->db->escape_str($this->input->post('a')),
                            'position'=>$this->db->escape_str($this->input->post('d')),
                            'urutan'=>$this->db->escape_str($this->input->post('e')),
                            'deskripsi'=>$this->db->escape_str($this->input->post('g')));
			$this->Model_app->insert('menu',$data);
			redirect('administrator/menuwebsite');
		}else{
			$proses = $this->Model_app->view_where_ordering('menu', array('position' => 'Bottom'), 'id_menu','DESC');
			$data = array('record' => $proses);
			$this->template->load('administrator/template','administrator/mod_menu/view_menu_tambah',$data);
		}
	}

	function edit_menuwebsite(){
		cek_session_akses('menuwebsite',$this->session->id_session);
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$data = array('id_parent'=>$this->db->escape_str($this->input->post('b')),
                            'nama_menu'=>$this->db->escape_str($this->input->post('c')),
                            'link'=>$this->db->escape_str($this->input->post('a')),
                            'position'=>$this->db->escape_str($this->input->post('d')),
                            'urutan'=>$this->db->escape_str($this->input->post('e')),
                            'deskripsi'=>$this->db->escape_str($this->input->post('g')),
                            'aktif'=>$this->db->escape_str($this->input->post('f')));
			$where = array('id_menu' => $this->input->post('id'));
			$this->Model_app->update('menu', $data, $where);
			redirect('administrator/menuwebsite');
		}else{
			$menu_utama = $this->Model_app->view_where_ordering('menu', array('position' => 'Bottom'), 'id_menu','DESC');
			$proses = $this->Model_app->edit('menu', array('id_menu' => $id))->row_array();
			$data = array('rows' => $proses, 'record' => $menu_utama);
			$this->template->load('administrator/template','administrator/mod_menu/view_menu_edit',$data);
		}
	}

	function delete_menuwebsite(){
        cek_session_akses('menuwebsite',$this->session->id_session);
		$id = array('id_menu' => $this->uri->segment(3));
		$this->Model_app->delete('menu',$id);
		redirect('administrator/menuwebsite');
	}


	// Controller Modul Halaman Baru

	function halamanbaru(){
		cek_session_akses('halamanbaru',$this->session->id_session);
        if ($this->session->level=='admin'){
            $data['record'] = $this->Model_app->view_ordering('halamanstatis','id_halaman','DESC');
        }else{
            $data['record'] = $this->Model_app->view_where_ordering('halamanstatis',array('username'=>$this->session->username),'id_halaman','DESC');
        }
		$this->template->load('administrator/template','administrator/mod_halaman/view_halaman',$data);
	}

	function tambah_halamanbaru(){
		cek_session_akses('halamanbaru',$this->session->id_session);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_statis/';
            $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
            $config['max_size'] = '3000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('c');
            $hasil=$this->upload->data();
            if ($hasil['file_name']==''){
                    $data = array('judul'=>$this->db->escape_str($this->input->post('a')),
                                    'judul_seo'=>seo_title($this->input->post('a')),
                                    'isi_halaman'=>$this->input->post('b'),
                                    'tgl_posting'=>date('Y-m-d'),
                                    'username'=>$this->session->username,
                                    'dibaca'=>'0',
                                    'jam'=>date('H:i:s'),
                                    'hari'=>hari_ini(date('w')));
            }else{
            		$data = array('judul'=>$this->db->escape_str($this->input->post('a')),
                                    'judul_seo'=>seo_title($this->input->post('a')),
                                    'isi_halaman'=>$this->input->post('b'),
                                    'tgl_posting'=>date('Y-m-d'),
                                    'gambar'=>$hasil['file_name'],
                                    'username'=>$this->session->username,
                                    'dibaca'=>'0',
                                    'jam'=>date('H:i:s'),
                                    'hari'=>hari_ini(date('w')));
            }
            $this->Model_app->insert('halamanstatis',$data);
			redirect('administrator/halamanbaru');
		}else{
			$this->template->load('administrator/template','administrator/mod_halaman/view_halaman_tambah');
		}
	}

	function edit_halamanbaru(){
		cek_session_akses('halamanbaru',$this->session->id_session);
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_statis/';
            $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
            $config['max_size'] = '3000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('c');
            $hasil=$this->upload->data();
            if ($hasil['file_name']==''){
                    $data = array('judul'=>$this->db->escape_str($this->input->post('a')),
                                    'judul_seo'=>seo_title($this->input->post('a')),
                                    'isi_halaman'=>$this->input->post('b'));
            }else{
            		$data = array('judul'=>$this->db->escape_str($this->input->post('a')),
                                    'judul_seo'=>seo_title($this->input->post('a')),
                                    'isi_halaman'=>$this->input->post('b'),
                                    'gambar'=>$hasil['file_name']);
            }
            $where = array('id_halaman' => $this->input->post('id'));
			$this->Model_app->update('halamanstatis', $data, $where);
			redirect('administrator/halamanbaru');
		}else{
            if ($this->session->level=='admin'){
                 $proses = $this->Model_app->edit('halamanstatis', array('id_halaman' => $id))->row_array();
            }else{
                $proses = $this->Model_app->edit('halamanstatis', array('id_halaman' => $id, 'username' => $this->session->username))->row_array();
            }
			$data = array('rows' => $proses);
			$this->template->load('administrator/template','administrator/mod_halaman/view_halaman_edit',$data);
		}
	}

	function delete_halamanbaru(){
        cek_session_akses('halamanbaru',$this->session->id_session);
		if ($this->session->level=='admin'){
            $id = array('id_halaman' => $this->uri->segment(3));
        }else{
            $id = array('id_halaman' => $this->uri->segment(3), 'username'=>$this->session->username);
        }
		$this->Model_app->delete('halamanstatis',$id);
		redirect('administrator/halamanbaru');
	}

	// Controller Modul List Berita

	function listberita(){
		cek_session_akses('listberita',$this->session->id_session);
        if ($this->session->level=='admin'){
            $data['record'] = $this->Model_app->view_ordering('berita','id_berita','DESC');
        }else{
            $data['record'] = $this->Model_app->view_where_ordering('berita',array('username'=>$this->session->username),'id_berita','DESC');
        }
        $data['rss'] = $this->Model_utama->view_joinn('berita','users','kategori','username','id_kategori','id_berita','DESC',0,10);
        $data['iden'] = $this->Model_utama->view_where('identitas',array('id_identitas' => 1))->row_array();
        $this->load->view('administrator/rss',$data);
		$this->template->load('administrator/template','administrator/mod_berita/view_berita',$data);
	}

	function tambah_listberita(){
		cek_session_akses('listberita',$this->session->id_session);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_berita/';
	        $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
	        $config['max_size'] = '3000'; // kb
	        $this->load->library('upload', $config);
	        $this->upload->do_upload('k');
	        $hasil=$this->upload->data();
            
            $config['source_image'] = 'asset/foto_berita/'.$hasil['file_name'];
            $config['wm_text'] = '';
            $config['wm_type'] = 'text';
            $config['wm_font_path'] = './system/fonts/texb.ttf';
            $config['wm_font_size'] = '18';
            $config['wm_font_color'] = 'ffffff';
            $config['wm_vrt_alignment'] = 'middle';
            $config['wm_hor_alignment'] = 'center';
            $config['wm_padding'] = '20';
            $this->load->library('image_lib',$config);
            $this->image_lib->watermark();

            if ($this->session->level == 'kontributor'){ $status = 'N'; }else{ $status = 'Y'; }
            if ($this->input->post('j')!=''){
                $tag_seo = $this->input->post('j');
                $tag=implode(',',$tag_seo);
            }else{
                $tag = '';
            }
            if ($hasil['file_name']==''){
                    $data = array('id_kategori'=>$this->db->escape_str($this->input->post('a')),
                                    'username'=>$this->session->username,
                                    'judul'=>$this->db->escape_str($this->input->post('b')),
                                    'sub_judul'=>$this->db->escape_str($this->input->post('c')),
                                    'youtube'=>$this->db->escape_str($this->input->post('d')),
                                    'judul_seo'=>seo_title($this->input->post('b')),
                                    'headline'=>$this->db->escape_str($this->input->post('e')),
                                    'aktif'=>$this->db->escape_str($this->input->post('f')),
                                    'utama'=>$this->db->escape_str($this->input->post('g')),
                                    'isi_berita'=>$this->input->post('h'),
                                    'keterangan_gambar'=>$this->input->post('i'),
                                    'hari'=>hari_ini(date('w')),
                                    'tanggal'=>date('Y-m-d'),
                                    'jam'=>date('H:i:s'),
                                    'dibaca'=>'0',
                                    'tag'=>$tag,
                                    'status'=>$status);
            }else{
                    $data = array('id_kategori'=>$this->db->escape_str($this->input->post('a')),
                                    'username'=>$this->session->username,
                                    'judul'=>$this->db->escape_str($this->input->post('b')),
                                    'sub_judul'=>$this->db->escape_str($this->input->post('c')),
                                    'youtube'=>$this->db->escape_str($this->input->post('d')),
                                    'judul_seo'=>seo_title($this->input->post('b')),
                                    'headline'=>$this->db->escape_str($this->input->post('e')),
                                    'aktif'=>$this->db->escape_str($this->input->post('f')),
                                    'utama'=>$this->db->escape_str($this->input->post('g')),
                                    'isi_berita'=>$this->input->post('h'),
                                    'keterangan_gambar'=>$this->input->post('i'),
                                    'hari'=>hari_ini(date('w')),
                                    'tanggal'=>date('Y-m-d'),
                                    'jam'=>date('H:i:s'),
                                    'gambar'=>$hasil['file_name'],
                                    'dibaca'=>'0',
                                    'tag'=>$tag,
                                    'status'=>$status);
            }
            $this->Model_app->insert('berita',$data);
			redirect('administrator/listberita');
		}else{
            $data['tag'] = $this->Model_app->view_ordering('tag','id_tag','DESC');
            $data['record'] = $this->Model_app->view_ordering('kategori','id_kategori','DESC');
			$this->template->load('administrator/template','administrator/mod_berita/view_berita_tambah',$data);
		}
	}

	function edit_listberita(){
		cek_session_akses('listberita',$this->session->id_session);
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_berita/';
	        $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
	        $config['max_size'] = '3000'; // kb
	        $this->load->library('upload', $config);
	        $this->upload->do_upload('k');
	        $hasil=$this->upload->data();

            $config['source_image'] = 'asset/foto_berita/'.$hasil['file_name'];
            $config['wm_text'] = '';
            $config['wm_type'] = 'text';
            $config['wm_font_path'] = './system/fonts/texb.ttf';
            $config['wm_font_size'] = '18';
            $config['wm_font_color'] = 'ffffff';
            $config['wm_vrt_alignment'] = 'middle';
            $config['wm_hor_alignment'] = 'center';
            $config['wm_padding'] = '20';
            $this->load->library('image_lib',$config);
            $this->image_lib->watermark();

            if ($this->session->level == 'kontributor'){ $status = 'N'; }else{ $status = 'Y'; }
            if ($this->input->post('j')!=''){
                $tag_seo = $this->input->post('j');
                $tag=implode(',',$tag_seo);
            }else{
                $tag = '';
            }
            if ($hasil['file_name']==''){
                    $data = array('id_kategori'=>$this->db->escape_str($this->input->post('a')),
                                    'username'=>$this->session->username,
                                    'judul'=>$this->db->escape_str($this->input->post('b')),
                                    'sub_judul'=>$this->db->escape_str($this->input->post('c')),
                                    'youtube'=>$this->db->escape_str($this->input->post('d')),
                                    'judul_seo'=>seo_title($this->input->post('b')),
                                    'headline'=>$this->db->escape_str($this->input->post('e')),
                                    'aktif'=>$this->db->escape_str($this->input->post('f')),
                                    'utama'=>$this->db->escape_str($this->input->post('g')),
                                    'isi_berita'=>$this->input->post('h'),
                                    'keterangan_gambar'=>$this->input->post('i'),
                                    'hari'=>hari_ini(date('w')),
                                    'tanggal'=>date('Y-m-d'),
                                    'jam'=>date('H:i:s'),
                                    'dibaca'=>'0',
                                    'tag'=>$tag,
                                    'status'=>$status);
            }else{
                    $data = array('id_kategori'=>$this->db->escape_str($this->input->post('a')),
                                    'username'=>$this->session->username,
                                    'judul'=>$this->db->escape_str($this->input->post('b')),
                                    'sub_judul'=>$this->db->escape_str($this->input->post('c')),
                                    'youtube'=>$this->db->escape_str($this->input->post('d')),
                                    'judul_seo'=>seo_title($this->input->post('b')),
                                    'headline'=>$this->db->escape_str($this->input->post('e')),
                                    'aktif'=>$this->db->escape_str($this->input->post('f')),
                                    'utama'=>$this->db->escape_str($this->input->post('g')),
                                    'isi_berita'=>$this->input->post('h'),
                                    'keterangan_gambar'=>$this->input->post('i'),
                                    'hari'=>hari_ini(date('w')),
                                    'tanggal'=>date('Y-m-d'),
                                    'jam'=>date('H:i:s'),
                                    'gambar'=>$hasil['file_name'],
                                    'dibaca'=>'0',
                                    'tag'=>$tag,
                                    'status'=>$status);
            }
            $where = array('id_berita' => $this->input->post('id'));
			$this->Model_app->update('berita', $data, $where);
			redirect('administrator/listberita');
		}else{
			$tag = $this->Model_app->view_ordering('tag','id_tag','DESC');
            $record = $this->Model_app->view_ordering('kategori','id_kategori','DESC');
            if ($this->session->level=='admin'){
                 $proses = $this->Model_app->edit('berita', array('id_berita' => $id))->row_array();
            }else{
                $proses = $this->Model_app->edit('berita', array('id_berita' => $id, 'username' => $this->session->username))->row_array();
            }
			$data = array('rows' => $proses,'tag' => $tag,'record' => $record);
			$this->template->load('administrator/template','administrator/mod_berita/view_berita_edit',$data);
		}
	}

	function publish_listberita(){
        cek_session_admin();
		if ($this->uri->segment(4)=='Y'){
			$data = array('status'=>'N');
		}else{
			$data = array('status'=>'Y');
		}
        $where = array('id_berita' => $this->uri->segment(3));
		$this->Model_app->update('berita', $data, $where);
		redirect('administrator/listberita');
	}

	function delete_listberita(){
        cek_session_akses('listberita',$this->session->id_session);
        if ($this->session->level=='admin'){
    		$id = array('id_berita' => $this->uri->segment(3));
        }else{
            $id = array('id_berita' => $this->uri->segment(3), 'username'=>$this->session->username);
        }
		$this->Model_app->delete('berita',$id);
		redirect('administrator/listberita');
	}


	// Controller Modul Kategori Berita

	function kategoriberita(){
		cek_session_akses('kategoriberita',$this->session->id_session);
        if ($this->session->level=='admin'){
            $data['record'] = $this->Model_app->view_ordering('kategori','id_kategori','DESC');
        }else{
            $data['record'] = $this->Model_app->view_where_ordering('kategori',array('username'=>$this->session->username),'id_kategori','DESC');
        }
		$this->template->load('administrator/template','administrator/mod_kategori/view_kategori',$data);
	}

	function tambah_kategoriberita(){
		cek_session_akses('kategoriberita',$this->session->id_session);
		if (isset($_POST['submit'])){
			$data = array('nama_kategori'=>$this->db->escape_str($this->input->post('a')),
                        'username'=>$this->session->username,
                        'kategori_seo'=>seo_title($this->input->post('a')),
                        'aktif'=>$this->db->escape_str($this->input->post('b')),
                        'sidebar'=>$this->db->escape_str($this->input->post('c')));
			$this->Model_app->insert('kategori',$data);
			redirect('administrator/kategoriberita');
		}else{
			$this->template->load('administrator/template','administrator/mod_kategori/view_kategori_tambah');
		}
	}

	function edit_kategoriberita(){
		cek_session_akses('kategoriberita',$this->session->id_session);
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$data = array('nama_kategori'=>$this->db->escape_str($this->input->post('a')),
                        'username'=>$this->session->username,
                        'kategori_seo'=>seo_title($this->input->post('a')),
                        'aktif'=>$this->db->escape_str($this->input->post('b')),
                        'sidebar'=>$this->db->escape_str($this->input->post('c')));
			$where = array('id_kategori' => $this->input->post('id'));
			$this->Model_app->update('kategori', $data, $where);
			redirect('administrator/kategoriberita');
		}else{
            if ($this->session->level=='admin'){
                 $proses = $this->Model_app->edit('kategori', array('id_kategori' => $id))->row_array();
            }else{
                $proses = $this->Model_app->edit('kategori', array('id_kategori' => $id, 'username' => $this->session->username))->row_array();
            }
			$data = array('rows' => $proses);
			$this->template->load('administrator/template','administrator/mod_kategori/view_kategori_edit',$data);
		}
	}

	function delete_kategoriberita(){
		cek_session_akses('kategoriberita',$this->session->id_session);
        if ($this->session->level=='admin'){
            $id = array('id_kategori' => $this->uri->segment(3));
        }else{
            $id = array('id_kategori' => $this->uri->segment(3), 'username'=>$this->session->username);
        }
		$this->Model_app->delete('kategori',$id);
		redirect('administrator/kategoriberita');
	}


	// Controller Modul Tag Berita

	function tagberita(){
		cek_session_akses('tagberita',$this->session->id_session);
        if ($this->session->level=='admin'){
            $data['record'] = $this->Model_app->view_ordering('tag','id_tag','DESC');
        }else{
            $data['record'] = $this->Model_app->view_where_ordering('tag',array('username'=>$this->session->username),'id_tag','DESC');
        }
		$this->template->load('administrator/template','administrator/mod_tag/view_tag',$data);
	}

	function tambah_tagberita(){
		cek_session_akses('tagberita',$this->session->id_session);
		if (isset($_POST['submit'])){
			$data = array('nama_tag'=>$this->db->escape_str($this->input->post('a')),
                        'username'=>$this->session->username,
                        'tag_seo'=>seo_title($this->input->post('a')),
                        'count'=>'0');
			$this->Model_app->insert('tag',$data);	
			redirect('administrator/tagberita');
		}else{
			$this->template->load('administrator/template','administrator/mod_tag/view_tag_tambah');
		}
	}

	function edit_tagberita(){
		cek_session_akses('tagberita',$this->session->id_session);
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$data = array('nama_tag'=>$this->db->escape_str($this->input->post('a')),
                        'username'=>$this->session->username,
                        'tag_seo'=>seo_title($this->input->post('a')));
			$where = array('id_tag' => $this->input->post('id'));
			$this->Model_app->update('tag', $data, $where);
			redirect('administrator/tagberita');
		}else{
            if ($this->session->level=='admin'){
                 $proses = $this->Model_app->edit('tag', array('id_tag' => $id))->row_array();
            }else{
                $proses = $this->Model_app->edit('tag', array('id_tag' => $id, 'username' => $this->session->username))->row_array();
            }
			$data = array('rows' => $proses);
			$this->template->load('administrator/template','administrator/mod_tag/view_tag_edit',$data);
		}
	}

	function delete_tagberita(){
        cek_session_akses('tagberita',$this->session->id_session);
		if ($this->session->level=='admin'){
            $id = array('id_tag' => $this->uri->segment(3));
        }else{
            $id = array('id_tag' => $this->uri->segment(3), 'username'=>$this->session->username);
        }
		$this->Model_app->delete('tag',$id);
		redirect('administrator/tagberita');
	}

    // Controller Modul Iklan Sidebar atau link terkait

    function iklansidebar(){
        cek_session_akses('iklansidebar',$this->session->id_session);
        if ($this->session->level=='admin'){
            $data['record'] = $this->Model_app->view_ordering('pasangiklan','id_pasangiklan','DESC');
        }else{
            $data['record'] = $this->Model_app->view_where_ordering('pasangiklan',array('username'=>$this->session->username),'id_pasangiklan','DESC');
        }
        $this->template->load('administrator/template','administrator/mod_iklansidebar/view_iklansidebar',$data);
    }

    function tambah_iklansidebar(){
        cek_session_akses('iklansidebar',$this->session->id_session);
        if (isset($_POST['submit'])){
            $config['upload_path'] = 'asset/foto_pasangiklan/';
            $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG|swf';
            $config['max_size'] = '3000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('c');
            $hasil=$this->upload->data();
            if ($hasil['file_name']==''){
                $data = array('judul'=>$this->db->escape_str($this->input->post('a')),
                                'username'=>$this->session->username,
                                'url'=>$this->input->post('b'),
                                'tgl_posting'=>date('Y-m-d'));
            }else{
                $data = array('judul'=>$this->db->escape_str($this->input->post('a')),
                                'username'=>$this->session->username,
                                'url'=>$this->input->post('b'),
                                'gambar'=>$hasil['file_name'],
                                'tgl_posting'=>date('Y-m-d'));
            }
            $this->Model_app->insert('pasangiklan',$data);
            redirect('administrator/iklansidebar');
        }else{
            $this->template->load('administrator/template','administrator/mod_iklansidebar/view_iklansidebar_tambah');
        }
    }

    function edit_iklansidebar(){
        cek_session_akses('iklansidebar',$this->session->id_session);
        $id = $this->uri->segment(3);
        if (isset($_POST['submit'])){
            $config['upload_path'] = 'asset/foto_pasangiklan/';
            $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG|swf';
            $config['max_size'] = '3000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('c');
            $hasil=$this->upload->data();
            if ($hasil['file_name']==''){
                $data = array('judul'=>$this->db->escape_str($this->input->post('a')),
                                'username'=>$this->session->username,
                                'url'=>$this->input->post('b'),
                                'tgl_posting'=>date('Y-m-d'));
            }else{
                $data = array('judul'=>$this->db->escape_str($this->input->post('a')),
                                'username'=>$this->session->username,
                                'url'=>$this->input->post('b'),
                                'gambar'=>$hasil['file_name'],
                                'tgl_posting'=>date('Y-m-d'));
            }
            $where = array('id_pasangiklan' => $this->input->post('id'));
            $this->Model_app->update('pasangiklan', $data, $where);
            redirect('administrator/iklansidebar');
        }else{
            if ($this->session->level=='admin'){
                $proses = $this->Model_app->edit('pasangiklan', array('id_pasangiklan' => $id))->row_array();
            }else{
                $proses = $this->Model_app->edit('pasangiklan', array('id_pasangiklan' => $id, 'username' => $this->session->username))->row_array();
            }
            $data = array('rows' => $proses);
            $this->template->load('administrator/template','administrator/mod_iklansidebar/view_iklansidebar_edit',$data);
        }
    }

    function delete_iklansidebar(){
        cek_session_akses('iklansidebar',$this->session->id_session);
        if ($this->session->level=='admin'){
            $id = array('id_pasangiklan' => $this->uri->segment(3));
        }else{
            $id = array('id_pasangiklan' => $this->uri->segment(3), 'username'=>$this->session->username);
        }
        $this->Model_app->delete('pasangiklan',$id);
        redirect('administrator/iklansidebar');
    }


    // Controller Modul banner Link

    function banner(){
        cek_session_akses('banner',$this->session->id_session);
        $data['record'] = $this->Model_app->view_ordering('banner','id_banner','DESC');
        $this->template->load('administrator/template','administrator/mod_banner/view_banner',$data);
    }

    function tambah_banner(){
        cek_session_akses('banner',$this->session->id_session);
        if (isset($_POST['submit'])){
            $config['upload_path'] = 'asset/foto_banner/';
            $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG|swf';
            $config['max_size'] = '3000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('c');
            $hasil=$this->upload->data();
			if ($hasil['file_name']==''){
                $data = array('judul'=>$this->db->escape_str($this->input->post('a')),
                                'url'=>$this->input->post('b'),
                                'deskripsi'=>$this->input->post('d'),
                                'tgl_posting'=>date('Y-m-d'));
            }else{
                $data = array('judul'=>$this->db->escape_str($this->input->post('a')),
                                'url'=>$this->input->post('b'),
                                'deskripsi'=>$this->input->post('d'),
                                'gambar'=>$hasil['file_name'],
                                'tgl_posting'=>date('Y-m-d'));
            }
            $this->Model_app->insert('banner',$data);  
            redirect('administrator/banner');
        }else{
            $this->template->load('administrator/template','administrator/mod_banner/view_banner_tambah');
        }
    }

    function edit_banner(){
        cek_session_akses('banner',$this->session->id_session);
        $id = $this->uri->segment(3);
        if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_banner/';
            $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG|swf';
            $config['max_size'] = '3000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('c');
            $hasil=$this->upload->data();
            if ($hasil['file_name']==''){
                $data = array('judul'=>$this->db->escape_str($this->input->post('a')),
                                'url'=>$this->input->post('b'),
                                'deskripsi'=>$this->input->post('d'),
                                'tgl_posting'=>date('Y-m-d'));
            }else{
                $data = array('judul'=>$this->db->escape_str($this->input->post('a')),
                                'url'=>$this->input->post('b'),
                                'deskripsi'=>$this->input->post('d'),
                                'gambar'=>$hasil['file_name'],
                                'tgl_posting'=>date('Y-m-d'));
            }
            $where = array('id_banner' => $this->input->post('id'));
            $this->Model_app->update('banner', $data, $where);
            redirect('administrator/banner');
        }else{
            $proses = $this->Model_app->edit('banner', array('id_banner' => $id))->row_array();
            $data = array('rows' => $proses);
            $this->template->load('administrator/template','administrator/mod_banner/view_banner_edit',$data);
        }
    }

    function delete_banner(){
        cek_session_akses('banner',$this->session->id_session);
        $id = array('id_banner' => $this->uri->segment(3));
        $this->Model_app->delete('banner',$id);
        redirect('administrator/banner');
    }


   
    // Controller Modul Template Website

    function templatewebsite(){
        cek_session_akses('templatewebsite',$this->session->id_session);
        if ($this->session->level=='admin'){
            $data['record'] = $this->Model_app->view_ordering('templates','id_templates','DESC');
        }else{
            $data['record'] = $this->Model_app->view_where_ordering('templates',array('username'=>$this->session->username),'id_templates','DESC');
        }
        $this->template->load('administrator/template','administrator/mod_template/view_template',$data);
    }

    function tambah_templatewebsite(){
        cek_session_akses('templatewebsite',$this->session->id_session);
        if (isset($_POST['submit'])){
            $data = array('judul'=>$this->db->escape_str($this->input->post('a')),
                                'username'=>$this->session->username,
                                'pembuat'=>$this->input->post('b'),
                                'folder'=>$this->input->post('c'));
            $this->Model_app->insert('templates',$data);
            redirect('administrator/templatewebsite');
        }else{
            $this->template->load('administrator/template','administrator/mod_template/view_template_tambah');
        }
    }

    function edit_templatewebsite(){
        cek_session_akses('templatewebsite',$this->session->id_session);
        $id = $this->uri->segment(3);
        if (isset($_POST['submit'])){
            $data = array('judul'=>$this->db->escape_str($this->input->post('a')),
                                'username'=>$this->session->username,
                                'pembuat'=>$this->input->post('b'),
                                'folder'=>$this->input->post('c'));
            $where = array('id_templates' => $this->input->post('id'));
            $this->Model_app->update('templates', $data, $where);
            redirect('administrator/templatewebsite');
        }else{
            if ($this->session->level=='admin'){
                $proses = $this->Model_app->edit('templates', array('id_templates' => $id))->row_array();
            }else{
                $proses = $this->Model_app->edit('templates', array('id_templates' => $id, 'username' => $this->session->username))->row_array();
            }
            $data = array('rows' => $proses);
            $this->template->load('administrator/template','administrator/mod_template/view_template_edit',$data);
        }
    }

    function aktif_templatewebsite(){
        cek_session_akses('templatewebsite',$this->session->id_session);
        $id = $this->uri->segment(3);
        if ($this->uri->segment(4)=='Y'){ $aktif = 'N'; }else{ $aktif = 'Y'; }

        $data = array('aktif'=>$aktif);
        $where = array('id_templates' => $id);
        $this->Model_app->update('templates', $data, $where);

        $dataa = array('aktif'=>'N');
        $wheree = array('id_templates !=' => $id);
        $this->Model_app->update('templates', $dataa, $wheree);

        redirect('administrator/templatewebsite');
    }

    function delete_templatewebsite(){
        cek_session_akses('templatewebsite',$this->session->id_session);
        if ($this->session->level=='admin'){
            $id = array('id_templates' => $this->uri->segment(3));
        }else{
            $id = array('id_templates' => $this->uri->segment(3), 'username'=>$this->session->username);
        }
        $this->Model_app->delete('templates',$id);
        redirect('administrator/templatewebsite');
    }


   
	// Controller Modul Agenda atau event

	function agenda(){
		cek_session_akses('agenda',$this->session->id_session);
        if ($this->session->level=='admin'){
    		$data['record'] = $this->Model_app->view_ordering('agenda','id_agenda','DESC');
        }else{
            $data['record'] = $this->Model_app->view_where_ordering('agenda',array('username'=>$this->session->username),'id_agenda','DESC');
        }
		$this->template->load('administrator/template','administrator/mod_agenda/view_agenda',$data);
	}

	function tambah_agenda(){
		cek_session_akses('agenda',$this->session->id_session);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_agenda/';
            $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
            $config['max_size'] = '3000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('c');
            $hasil=$this->upload->data();
            $ex = explode(' - ',$this->input->post('f'));
            $exx = explode('/',$ex[0]);
            $exy = explode('/',$ex[1]);
            $mulai = $exx[2].'-'.$exx[0].'-'.$exx[1];
            $selesai = $exy[2].'-'.$exy[0].'-'.$exy[1];
            if ($hasil['file_name']==''){
                    $data = array('tema'=>$this->db->escape_str($this->input->post('a')),
                                    'tema_seo'=>seo_title($this->input->post('a')),
                                    'isi_agenda'=>$this->input->post('b'),
                                    'tempat'=>$this->db->escape_str($this->input->post('d')),
                                    'pengirim'=>$this->db->escape_str($this->input->post('g')),
                                    'tgl_mulai'=>$mulai,
                                    'tgl_selesai'=>$selesai,
                                    'tgl_posting'=>date('Y-m-d'),
                                    'jam'=>$this->db->escape_str($this->input->post('e')),
                                    'dibaca'=>'0',
                                    'username'=>$this->session->username);
            }else{
                    $data = array('tema'=>$this->db->escape_str($this->input->post('a')),
                                    'tema_seo'=>seo_title($this->input->post('a')),
                                    'isi_agenda'=>$this->input->post('b'),
                                    'tempat'=>$this->db->escape_str($this->input->post('d')),
                                    'pengirim'=>$this->db->escape_str($this->input->post('g')),
                                    'gambar'=>$hasil['file_name'],
                                    'tgl_mulai'=>$mulai,
                                    'tgl_selesai'=>$selesai,
                                    'tgl_posting'=>date('Y-m-d'),
                                    'jam'=>$this->db->escape_str($this->input->post('e')),
                                    'dibaca'=>'0',
                                    'username'=>$this->session->username);
            }
            $this->Model_app->insert('agenda',$data);
			redirect('administrator/agenda');
		}else{
			$this->template->load('administrator/template','administrator/mod_agenda/view_agenda_tambah');
		}
	}

	function edit_agenda(){
		cek_session_akses('agenda',$this->session->id_session);
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_agenda/';
            $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
            $config['max_size'] = '3000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('c');
            $hasil=$this->upload->data();
            $ex = explode(' - ',$this->input->post('f'));
            $exx = explode('/',$ex[0]);
            $exy = explode('/',$ex[1]);
            $mulai = $exx[2].'-'.$exx[0].'-'.$exx[1];
            $selesai = $exy[2].'-'.$exy[0].'-'.$exy[1];
            if ($hasil['file_name']==''){
                    $data = array('tema'=>$this->db->escape_str($this->input->post('a')),
                                    'tema_seo'=>seo_title($this->input->post('a')),
                                    'isi_agenda'=>$this->input->post('b'),
                                    'tempat'=>$this->db->escape_str($this->input->post('d')),
                                    'pengirim'=>$this->db->escape_str($this->input->post('g')),
                                    'tgl_mulai'=>$mulai,
                                    'tgl_selesai'=>$selesai,
                                    'jam'=>$this->db->escape_str($this->input->post('e')));
            }else{
                    $data = array('tema'=>$this->db->escape_str($this->input->post('a')),
                                    'tema_seo'=>seo_title($this->input->post('a')),
                                    'isi_agenda'=>$this->input->post('b'),
                                    'tempat'=>$this->db->escape_str($this->input->post('d')),
                                    'pengirim'=>$this->db->escape_str($this->input->post('g')),
                                    'gambar'=>$hasil['file_name'],
                                    'tgl_mulai'=>$mulai,
                                    'tgl_selesai'=>$selesai,
                                    'jam'=>$this->db->escape_str($this->input->post('e')));
            }
            
            $where = array('id_agenda' => $this->input->post('id'));
            $this->Model_app->update('agenda', $data, $where);
			redirect('administrator/agenda');
		}else{
            if ($this->session->level=='admin'){
			     $proses = $this->Model_app->edit('agenda', array('id_agenda' => $id))->row_array();
            }else{
                $proses = $this->Model_app->edit('agenda', array('id_agenda' => $id, 'username' => $this->session->username))->row_array();
            }

            $data = array('rows' => $proses);
			$this->template->load('administrator/template','administrator/mod_agenda/view_agenda_edit',$data);
		}
	}

	function delete_agenda(){
        cek_session_akses('agenda',$this->session->id_session);
		if ($this->session->level=='admin'){
            $id = array('id_agenda' => $this->uri->segment(3));
        }else{
            $id = array('id_agenda' => $this->uri->segment(3), 'username'=>$this->session->username);
        }
        $this->Model_app->delete('agenda',$id);
		redirect('administrator/agenda');
	}


    // Controller Modul Alamat

    function alamat(){
        cek_session_akses('alamat',$this->session->id_session);
        $id = $this->uri->segment(3);
        if (isset($_POST['submit'])){
            $data = array('alamat'=>$this->input->post('a'));
            $where = array('id_alamat' => 1);
            $this->Model_app->update('mod_alamat', $data, $where);
            redirect('administrator/alamat');
        }else{
            $proses = $this->Model_app->edit('mod_alamat', array('id_alamat' => 1))->row_array();
            $data = array('rows' => $proses);
            $this->template->load('administrator/template','administrator/mod_alamat/view_alamat',$data);
        }
    }
// Controller Modul testimoni

function testimoni(){
    cek_session_akses('testimoni',$this->session->id_session);
    if ($this->session->level=='admin'){
        $data['record'] = $this->Model_app->view_ordering('testimoni','id_testimoni','DESC');
    }else{
        $data['record'] = $this->Model_app->view_where_ordering('testimoni',array('username'=>$this->session->username),'id_testimoni','DESC');
    }
    $this->template->load('administrator/template','administrator/mod_testimoni/view_testimoni',$data);
}

function tambah_testimoni(){
    cek_session_akses('testimoni',$this->session->id_session);
    if (isset($_POST['submit'])){
        if ($hasil['file_name']==''){
                $data = array('nama_testimoni'=>$this->input->post('a'),
                              
                                'deskripsi_testimoni'=>$this->input->post('b'),
                                'pekerjaan_testimoni'=>$this->input->post('c'),
                               );
        }else{
                $data = array('nama_testimoni'=>$this->db->escape_str($this->input->post('a')),
                               
                                'deskripsi_testimoni'=>$this->input->post('b'),
                                'pekerjaan_testimoni'=>$this->input->post('c'),
                                );
        }
        $this->Model_app->insert('testimoni',$data);
        redirect('administrator/testimoni');
    }else{
        $this->template->load('administrator/template','administrator/mod_testimoni/view_testimoni_tambah');
    }
}

function edit_testimoni(){
    cek_session_akses('testimoni',$this->session->id_session);
    $id = $this->uri->segment(3);
    if (isset($_POST['submit'])){       
        if ($hasil['file_name']==''){
                $data = array('nama_testimoni'=>$this->db->escape_str($this->input->post('a')),
                             
                                'deskripsi_testimoni'=>$this->input->post('b'),
                                'pekerjaan_testimoni'=>$this->db->escape_str($this->input->post('d')),
                              
                              );
        }else{
                $data = array('nama_testimoni'=>$this->db->escape_str($this->input->post('a')),
                               
                                'deskripsi_testimoni'=>$this->input->post('b'),
                                'pekerjaan_testimoni'=>$this->db->escape_str($this->input->post('d')),
                               );
        }
        
        $where = array('id_testimoni' => $this->input->post('id'));
        $this->Model_app->update('testimoni', $data, $where);
        redirect('administrator/testimoni');
    }else{
        if ($this->session->level=='admin'){
             $proses = $this->Model_app->edit('testimoni', array('id_testimoni' => $id))->row_array();
        }else{
            $proses = $this->Model_app->edit('testimoni', array('id_testimoni' => $id, 'username' => $this->session->username))->row_array();
        }

        $data = array('rows' => $proses);
        $this->template->load('administrator/template','administrator/mod_testimoni/view_testimoni_edit',$data);
    }
}

function delete_testimoni(){
    cek_session_akses('testimoni',$this->session->id_session);
    if ($this->session->level=='admin'){
        $id = array('id_testimoni' => $this->uri->segment(3));
    }else{
        $id = array('id_testimoni' => $this->uri->segment(3), 'username'=>$this->session->username);
    }
    $this->Model_app->delete('testimoni',$id);
    redirect('administrator/testimoni');
}



	
	// Controller Modul User

	function manajemenuser(){
		cek_session_akses('manajemenuser',$this->session->id_session);
		$data['record'] = $this->Model_app->view_ordering('users','username','DESC');
		$this->template->load('administrator/template','administrator/mod_users/view_users',$data);
	}

	function tambah_manajemenuser(){
		cek_session_akses('manajemenuser',$this->session->id_session);
		$id = $this->session->username;
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_user/';
            $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
            $config['max_size'] = '1000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('f');
            $hasil=$this->upload->data();
            if ($hasil['file_name']==''){
                    $data = array('username'=>$this->db->escape_str($this->input->post('a')),
                                    'password'=>password_hash($this->input->post('b'), PASSWORD_DEFAULT),
                                    'nama_lengkap'=>$this->db->escape_str($this->input->post('c')),
                                    'email'=>$this->db->escape_str($this->input->post('d')),
                                    'no_telp'=>$this->db->escape_str($this->input->post('e')),
                                    'level'=>$this->db->escape_str($this->input->post('g')),
                                    'blokir'=>'N',
                                    'id_session'=>md5($this->input->post('a')).'-'.date('YmdHis'));
            }else{
                    $data = array('username'=>$this->db->escape_str($this->input->post('a')),
                                    'password'=>password_hash($this->input->post('b'), PASSWORD_DEFAULT),
                                    'nama_lengkap'=>$this->db->escape_str($this->input->post('c')),
                                    'email'=>$this->db->escape_str($this->input->post('d')),
                                    'no_telp'=>$this->db->escape_str($this->input->post('e')),
                                    'foto'=>$hasil['file_name'],
                                    'level'=>$this->db->escape_str($this->input->post('g')),
                                    'blokir'=>'N',
                                    'id_session'=>md5($this->input->post('a')).'-'.date('YmdHis'));
            }
            $this->Model_app->insert('users',$data);

              $mod=count($this->input->post('modul'));
              $modul=$this->input->post('modul');
              $sess = md5($this->input->post('a')).'-'.date('YmdHis');
              for($i=0;$i<$mod;$i++){
                $datam = array('id_session'=>$sess,
                              'id_modul'=>$modul[$i]);
                $this->Model_app->insert('users_modul',$datam);
              }

			redirect('administrator/edit_manajemenuser/'.$this->input->post('a'));
		}else{
            $proses = $this->Model_app->view_where_ordering('modul', array('publish' => 'Y','status' => 'user'), 'id_modul','DESC');
            $data = array('record' => $proses);
			$this->template->load('administrator/template','administrator/mod_users/view_users_tambah',$data);
		}
	}

	function edit_manajemenuser(){
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_user/';
            $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
            $config['max_size'] = '1000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('f');
            $hasil=$this->upload->data();
            if ($hasil['file_name']=='' AND $this->input->post('b') ==''){
                    $data = array('username'=>$this->db->escape_str($this->input->post('a')),
                                    'nama_lengkap'=>$this->db->escape_str($this->input->post('c')),
                                    'email'=>$this->db->escape_str($this->input->post('d')),
                                    'no_telp'=>$this->db->escape_str($this->input->post('e')),
                                    'blokir'=>$this->db->escape_str($this->input->post('h')));
            }elseif ($hasil['file_name']!='' AND $this->input->post('b') ==''){
                    $data = array('username'=>$this->db->escape_str($this->input->post('a')),
                                    'nama_lengkap'=>$this->db->escape_str($this->input->post('c')),
                                    'email'=>$this->db->escape_str($this->input->post('d')),
                                    'no_telp'=>$this->db->escape_str($this->input->post('e')),
                                    'foto'=>$hasil['file_name'],
                                    'blokir'=>$this->db->escape_str($this->input->post('h')));
            }elseif ($hasil['file_name']=='' AND $this->input->post('b') !=''){
                    $data = array('username'=>$this->db->escape_str($this->input->post('a')),
                                    'password'=>password_hash($this->input->post('b'), PASSWORD_DEFAULT),
                                    'nama_lengkap'=>$this->db->escape_str($this->input->post('c')),
                                    'email'=>$this->db->escape_str($this->input->post('d')),
                                    'no_telp'=>$this->db->escape_str($this->input->post('e')),
                                    'blokir'=>$this->db->escape_str($this->input->post('h')));
            }elseif ($hasil['file_name']!='' AND $this->input->post('b') !=''){
                    $data = array('username'=>$this->db->escape_str($this->input->post('a')),
                                    'password'=>password_hash($this->input->post('b'), PASSWORD_DEFAULT),
                                    'nama_lengkap'=>$this->db->escape_str($this->input->post('c')),
                                    'email'=>$this->db->escape_str($this->input->post('d')),
                                    'no_telp'=>$this->db->escape_str($this->input->post('e')),
                                    'foto'=>$hasil['file_name'],
                                    'blokir'=>$this->db->escape_str($this->input->post('h')));
            }
            $where = array('username' => $this->input->post('id'));
            $this->Model_app->update('users', $data, $where);

              $mod=count($this->input->post('modul'));
              $modul=$this->input->post('modul');
              for($i=0;$i<$mod;$i++){
                $datam = array('id_session'=>$this->input->post('ids'),
                              'id_modul'=>$modul[$i]);
                $this->Model_app->insert('users_modul',$datam);
              }

			redirect('administrator/edit_manajemenuser/'.$this->input->post('id'));
		}else{
            if ($this->session->username==$this->uri->segment(3) OR $this->session->level=='admin'){
                $proses = $this->Model_app->edit('users', array('username' => $id))->row_array();
                $akses = $this->Model_app->view_join_where('users_modul','modul','id_modul', array('id_session' => $proses['id_session']),'id_umod','DESC');
                $modul = $this->Model_app->view_where_ordering('modul', array('publish' => 'Y','status' => 'user'), 'id_modul','DESC');
                $data = array('rows' => $proses, 'record' => $modul, 'akses' => $akses);
    			$this->template->load('administrator/template','administrator/mod_users/view_users_edit',$data);
            }else{
                redirect('administrator/edit_manajemenuser/'.$this->session->username);
            }
		}
	}

	function delete_manajemenuser(){
        cek_session_akses('manajemenuser',$this->session->id_session);
		$id = array('username' => $this->uri->segment(3));
        $this->Model_app->delete('users',$id);
		redirect('administrator/manajemenuser');
	}

    function delete_akses(){
        cek_session_admin();
        $id = array('id_umod' => $this->uri->segment(3));
        $this->Model_app->delete('users_modul',$id);
        redirect('administrator/edit_manajemenuser/'.$this->uri->segment(4));
    }

	

	// Controller Modul Modul

	function manajemenmodul(){
		cek_session_akses('manajemenmodul',$this->session->id_session);
        if ($this->session->level=='admin'){
            $data['record'] = $this->Model_app->view_ordering('modul','id_modul','DESC');
        }else{
            $data['record'] = $this->Model_app->view_where_ordering('modul',array('username'=>$this->session->username),'id_modul','DESC');
        }
		$this->template->load('administrator/template','administrator/mod_modul/view_modul',$data);
	}

	function tambah_manajemenmodul(){
		cek_session_akses('manajemenmodul',$this->session->id_session);
		if (isset($_POST['submit'])){
			$data = array('nama_modul'=>$this->db->escape_str($this->input->post('a')),
                        'username'=>$this->session->username,
                        'link'=>$this->db->escape_str($this->input->post('b')),
                        'static_content'=>'',
                        'gambar'=>'',
                        'publish'=>$this->db->escape_str($this->input->post('c')),
                        'status'=>$this->db->escape_str($this->input->post('e')),
                        'aktif'=>$this->db->escape_str($this->input->post('d')),
                        'urutan'=>'0',
                        'link_seo'=>'');
            $this->Model_app->insert('modul',$data);
			redirect('administrator/manajemenmodul');
		}else{
			$this->template->load('administrator/template','administrator/mod_modul/view_modul_tambah');
		}
	}

	function edit_manajemenmodul(){
		cek_session_akses('manajemenmodul',$this->session->id_session);
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
            $data = array('nama_modul'=>$this->db->escape_str($this->input->post('a')),
                        'username'=>$this->session->username,
                        'link'=>$this->db->escape_str($this->input->post('b')),
                        'static_content'=>'',
                        'gambar'=>'',
                        'publish'=>$this->db->escape_str($this->input->post('c')),
                        'status'=>$this->db->escape_str($this->input->post('e')),
                        'aktif'=>$this->db->escape_str($this->input->post('d')),
                        'urutan'=>'0',
                        'link_seo'=>'');
            $where = array('id_modul' => $this->input->post('id'));
            $this->Model_app->update('modul', $data, $where);
			redirect('administrator/manajemenmodul');
		}else{
            if ($this->session->level=='admin'){
                 $proses = $this->Model_app->edit('modul', array('id_modul' => $id))->row_array();
            }else{
                $proses = $this->Model_app->edit('modul', array('id_modul' => $id, 'username' => $this->session->username))->row_array();
            }
            $data = array('rows' => $proses);
			$this->template->load('administrator/template','administrator/mod_modul/view_modul_edit',$data);
		}
	}

	function delete_manajemenmodul(){
        cek_session_akses('manajemenmodul',$this->session->id_session);
		if ($this->session->level=='admin'){
            $id = array('id_modul' => $this->uri->segment(3));
        }else{
            $id = array('id_modul' => $this->uri->segment(3), 'username'=>$this->session->username);
        }
        $this->Model_app->delete('modul',$id);
		redirect('administrator/manajemenmodul');
	}

	function logout(){
		$this->session->sess_destroy();
		redirect('main');
	}
	
	function linkmenu(){
		cek_session_akses('menuwebsite',$this->session->id_session);
		$data['record'] = $this->Model_app->view_ordering('link','urutan','ASC');
		$this->template->load('administrator/template','administrator/mod_link/view_link',$data);
	}
	
	function tambah_linkmenu(){
		cek_session_akses('menuwebsite',$this->session->id_session);
		
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_link/';
			$config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
			$config['max_size'] = '3000'; // kb
			$this->load->library('upload', $config);
			$this->upload->do_upload('k');
			$hasil=$this->upload->data();
			if ($hasil['file_name']==''){
			$data = array('id_parent'=>$this->db->escape_str($this->input->post('b')),
                            'nama_menu'=>$this->db->escape_str($this->input->post('c')),
                            'link'=>$this->db->escape_str($this->input->post('a')),
                            'aktif'=>"Ya",
                            'groupname'=>$this->db->escape_str($this->input->post('h')),
                            'urutan'=>$this->db->escape_str($this->input->post('e')),
                            'deskripsi'=>$this->db->escape_str($this->input->post('g')),
                            'icon'=>$this->db->escape_str($this->input->post('l')));
			}else{
			$data = array('id_parent'=>$this->db->escape_str($this->input->post('b')),
                            'nama_menu'=>$this->db->escape_str($this->input->post('c')),
                            'link'=>$this->db->escape_str($this->input->post('a')),
                            'aktif'=>"Ya",
                            'groupname'=>$this->db->escape_str($this->input->post('h')),
                            'urutan'=>$this->db->escape_str($this->input->post('e')),
                            'deskripsi'=>$this->db->escape_str($this->input->post('g')),
                            'gambar'=>$hasil['file_name'],
                            'icon'=>$this->db->escape_str($this->input->post('l')));
				
			}
			
			$this->Model_app->insert('link',$data);
			redirect('administrator/linkmenu');
		}else{
			$proses = $this->Model_app->view_where_ordering('link', array('id_parent' => 0), 'id_link','DESC');
			$data = array('record' => $proses);
			$this->template->load('administrator/template','administrator/mod_link/view_link_tambah',$data);
		}
	}

	function edit_linkmenu(){
		cek_session_akses('menuwebsite',$this->session->id_session);
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_link/';
	        $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
	        $config['max_size'] = '3000'; // kb
	        $this->load->library('upload', $config);
	        $this->upload->do_upload('k');
	        $hasil=$this->upload->data();

            $config['source_image'] = 'asset/foto_link/'.$hasil['file_name'];
            $config['wm_text'] = '';
            $config['wm_type'] = 'text';
            $config['wm_font_path'] = './system/fonts/texb.ttf';
            $config['wm_font_size'] = '18';
            $config['wm_font_color'] = 'ffffff';
            $config['wm_vrt_alignment'] = 'middle';
            $config['wm_hor_alignment'] = 'center';
            $config['wm_padding'] = '20';
            $this->load->library('image_lib',$config);
            $this->image_lib->watermark();
			
			if ($hasil['file_name']==''){
				$data = array('id_parent'=>$this->db->escape_str($this->input->post('b')),
                            'nama_menu'=>$this->db->escape_str($this->input->post('c')),
                            'link'=>$this->db->escape_str($this->input->post('a')),
                            'urutan'=>$this->db->escape_str($this->input->post('e')),
                            'deskripsi'=>$this->db->escape_str($this->input->post('g')),
                            'aktif'=>$this->db->escape_str($this->input->post('f')));
			}else{
				$data = array('id_parent'=>$this->db->escape_str($this->input->post('b')),
                            'nama_menu'=>$this->db->escape_str($this->input->post('c')),
                            'link'=>$this->db->escape_str($this->input->post('a')),
                            'urutan'=>$this->db->escape_str($this->input->post('e')),
                            'deskripsi'=>$this->db->escape_str($this->input->post('g')),
							'gambar'=>$hasil['file_name'],
                            'aktif'=>$this->db->escape_str($this->input->post('f')));
			}
			$where = array('id_link' => $this->input->post('id'));
			$this->Model_app->update('link', $data, $where);
			redirect('administrator/linkmenu');
		}else{
			$menu_utamax = $this->Model_utama->view_where('link',"aktif='Ya'");
			$proses = $this->Model_app->edit('link', array('id_link' => $id))->row_array();
			$data = array('rows' => $proses, 'record' => $menu_utamax);
			$this->template->load('administrator/template','administrator/mod_link/view_link_edit',$data);
		}
	}
	
}
