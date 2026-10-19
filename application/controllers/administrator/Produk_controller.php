<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_admin_logged_in();
        // Load model
        $this->load->model('produk_model');
        $this->load->model('produk_kategori_model');
        $this->load->model('produk_gambar_model');
    }

    public function index()
    {
        $data['title'] = 'Daftar Produk';
        $data['list_produk'] = $this->produk_model->get_all();
        $this->load->view('administrator/templates/header', $data);
        $this->load->view('administrator/templates/sidebar');
        $this->load->view('administrator/produk/index', $data);
        $this->load->view('administrator/templates/footer');
    }

    public function tambah_produk()
    {
        $data['title'] = 'Tambah Produk';
        $this->form_validation->set_rules('nama_produk', 'Nama produk', 'required');
        $this->form_validation->set_rules('kategori_produk', 'Kategori', 'required');
        
        if (($this->form_validation->run() !== FALSE)) {
            $this->_simpan_produk();
        } else {
            $data['list_kategori'] = $this->produk_kategori_model->get_all();
            $this->load->view('administrator/templates/header', $data);
            $this->load->view('administrator/templates/sidebar');
            $this->load->view('administrator/produk/tambah_produk', $data);
            $this->load->view('administrator/templates/footer');
        }
    }

    private function _simpan_produk()
    {
        $data = [
            'nama'        => ucwords($this->input->post('nama_produk')),
            'kategori_id' => $this->input->post('kategori_produk'),
            'harga'       => $this->input->post('harga_produk'),
            'stok'        => $this->input->post('stok_produk'),
            'deskripsi'   => ucfirst($this->input->post('deskripsi_produk'))
        ];
        
        // simpan dan return id_produk yang disimpan
        $id_produk = $this->produk_model->tambah($data);
        
        // simpan data, trus lakukan upload gambar berdasarkan id produk
        $count = count($_FILES['gambar_produk']['name']);
        if ($count > 0) { // jika ada gambar upload
            $this->_produk_gambar_upload($count, $id_produk);
        }
        
        $this->session->set_flashdata('message', '
            <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                    <use xlink:href="#check-circle-fill" />
                </svg>
                <div>
                    Berhasil menambahkan produk!!
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        ');
        redirect('administrator/produk');
    }

    private function _produk_gambar_upload($count, $id_produk)
    {
        for ($i = 0; $i < $count; $i++) {
            if (!empty($_FILES['gambar_produk']['name'][$i])) {
                $_FILES['file']['name']     = $_FILES['gambar_produk']['name'][$i];
                $_FILES['file']['type']     = $_FILES['gambar_produk']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['gambar_produk']['tmp_name'][$i];
                $_FILES['file']['error']    = $_FILES['gambar_produk']['error'][$i];
                $_FILES['file']['size']     = $_FILES['gambar_produk']['size'][$i];

                $config['upload_path']    = './uploads/produk/';
                $config['allowed_types']  = 'jpg|jpeg|png|gif';
                $config['max_size']       = 5000;
                $config['file_name']      = 'produk-' . $id_produk . '-' . $i;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $uploadData = $this->upload->data();
                    $data_gambar = [
                        'nama_gambar' => $uploadData['file_name'],
                        'produk_id'   => $id_produk
                    ];
                    // PERBAIKAN: gunakan 'tambah' sesuai nama method di model
                    $this->produk_gambar_model->tambah($data_gambar);
                }
            }
        }
    }

    public function ubah_produk($id)
    {
        // check dulu apakah id dengan produk ada?
        $produk = $this->produk_model->get_by_id($id);
        if ($produk) {
            $this->form_validation->set_rules('nama_produk', 'Nama produk', 'required');
            $this->form_validation->set_rules('kategori_produk', 'Kategori', 'required');
            
            if (($this->form_validation->run() !== FALSE)) {
                $this->_ubah_produk($id);
            } else {
                $data['title'] = 'Ubah Produk';
                $data['produk'] = $produk;
                $data['list_kategori'] = $this->produk_kategori_model->get_all();
                
                // PERBAIKAN: Ambil data gambar dari controller, jangan panggil model langsung di view
                $data['list_gambar'] = $this->produk_gambar_model->get_by_produk_id($id);
                
                $this->load->view('administrator/templates/header', $data);
                $this->load->view('administrator/templates/sidebar');
                $this->load->view('administrator/produk/ubah_produk', $data);
                $this->load->view('administrator/templates/footer');
            }
        } else {
            $this->session->set_flashdata('message', '
                <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                        <use xlink:href="#exclamation-triangle-fill" />
                    </svg>
                    <div>
                        Produk tidak ditemukan!!
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ');
            redirect('administrator/produk');
        }
    }

        private function _ubah_produk($id)
{
    $data = [
        'nama'        => ucwords($this->input->post('nama_produk')),
        'kategori_id' => $this->input->post('kategori_produk'),
        'harga'       => $this->input->post('harga_produk'),
        'stok'        => $this->input->post('stok_produk'),
        'deskripsi'   => ucfirst($this->input->post('deskripsi_produk'))
    ];
    
    $ubah = $this->produk_model->ubah($data, $id);
    
    // Upload gambar baru jika ada
    $count = count($_FILES['gambar_produk']['name']);
    $gambar_upload_sukses = false;
    if ($count > 0) {
        $this->_produk_gambar_upload($count, $id);
        $gambar_upload_sukses = true;
    }
    
    // Sukses jika: data berubah ATAU gambar berhasil diupload
    if ($ubah || $gambar_upload_sukses) {
        $this->session->set_flashdata('message', '
            <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                    <use xlink:href="#check-circle-fill" />
                </svg>
                <div>
                    Berhasil mengubah produk!!
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        ');
    } else {
        $this->session->set_flashdata('message', '
            <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
                <div>
                    Gagal mengubah produk!!
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        ');
    }
    redirect('administrator/produk');
}

    public function hapus_produk($id)
    {
        // check apakah ada produk
        $produk = $this->produk_model->get_by_id($id);
        if ($produk) {
            // ambil data gambar
            $list_gambar = $this->produk_gambar_model->get_by_produk_id($id);
            foreach ($list_gambar as $gambar) {
                $id_gambar = $gambar['id_gambar'];
                $nama_gambar = $gambar['nama_gambar'];
                
                // hapus gambar di database
                $this->produk_gambar_model->hapus($id_gambar);
                
                // hapus gambar di file server
                $file_path = './uploads/produk/' . $nama_gambar;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            
            // hapus produk
            $this->produk_model->hapus($id);
            
            $this->session->set_flashdata('message', '
                <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                        <use xlink:href="#check-circle-fill" />
                    </svg>
                    <div>
                        Berhasil menghapus produk!!
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ');
            redirect('administrator/produk');
        } else {
            $this->session->set_flashdata('message', '
                <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                        <use xlink:href="#exclamation-triangle-fill" />
                    </svg>
                    <div>
                        Produk tidak ditemukan!!
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ');
            redirect('administrator/produk');
        }
    }
        public function hapus_gambar($id_gambar, $id_produk)
    {
        // Ambil data gambar
        $list_gambar = $this->produk_gambar_model->get_by_produk_id($id_produk);
        
        foreach ($list_gambar as $gambar) {
            if ($gambar['id_gambar'] == $id_gambar) {
                $nama_gambar = $gambar['nama_gambar'];
                
                // Hapus dari database
                $this->produk_gambar_model->hapus($id_gambar);
                
                // Hapus file dari folder
                $file_path = './uploads/produk/' . $nama_gambar;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                break;
            }
        }
        
        $this->session->set_flashdata('message', '
            <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                    <use xlink:href="#check-circle-fill" />
                </svg>
                <div>
                    Berhasil menghapus gambar!!
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        ');
        
        redirect('administrator/produk/ubah/' . $id_produk);
    }
}