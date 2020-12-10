<?php namespace App\Controllers;

use App\Libraries\TfIdf;
use App\Libraries\PanjangVektor;
use App\Libraries\CosineSimilarity;
use App\Libraries\Query;
use App\Libraries\Dokumen;
use App\Libraries\Tesaurus;
use App\Libraries\Proses;


class Home extends BaseController
{	

	function __construct() {						
		helper('form');		
		$this->tfidf = TfIdf::getInstance();
		$this->panjangVektor = PanjangVektor::getInstance();
		$this->cosim = CosineSimilarity::getInstance();
		$this->request = \Config\Services::request();
		$this->tesaurus = Tesaurus::getInstance();
		$this->query = Query::getInstance();
		$this->dokumen = Dokumen::getInstance();
		$this->proses = Proses::getInstance();	
		$this->session = \Config\Services::session($config);	
	}

	public function index()
	{
		return view('index');
	}

	public function result()
	{
		try {
			$model = new \App\Models\HasilModel();
			
			$model->orderBy('rangking','DESC');
			$data = [
				'documents' => $model->paginate(10, 'no'),
				'pager' => $model->pager
			];

			return view('result', $data);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
		finally {
			unset($model, $data);
		}
	}

	public function search() 
	{					
		try {			
			$query = $this->query->queryExpansion($this->request->getVar('query'), $this->session->get('btn_QE'));			
			
			list($tfidf_query, $paVek_query) = $this->query->bobotQuery($query);	
			if (empty($tfidf_query)) {			
				$this->dokumen->resetResult();			
			} else {			
				$tfidf_dokumen = $this->dokumen->getRelevanDokumen(array_keys($tfidf_query));		
				$bobot_dokumen = $this->cosim->cosineSimilarity($tfidf_query, $tfidf_dokumen, $paVek_query);
				$this->dokumen->result($bobot_dokumen);						
			}					
			return redirect('result')->withInput();
		}
		catch (\Throwable $e) {                                                                     
            die("Caught exception
                <br>File: {$e->getFile()}
                <br>Line: {$e->getLine()}
                <br>Message: {$e->getMessage()}"
            );
        }
		finally {
			unset($query, $tfidf_query, $paVek_query, $tfidf_dokumen, $bobot_dokumen);
		}
	}

	public function proses() {	
		$data['proses_query_expansion'] = $this->proses->getProsesQueryExpansion();
		$data['proses_pembobotan_query'] = $this->proses->getProsesPembobotanQuery();
		$data['proses_pengambilan_dokumen'] = $this->proses->getProsesPengambilanDokumen();
		$data['proses_perangkingan_dokumen'] = $this->proses->getProsesPerangkinganDokumen();
		$data['proses_preprocessing_query'] = $this->proses->getProsesPreprocessingQuery();
		return view('proses', $data);
	}

	public function button($value) {		
		$this->session->set('btn_QE', $value);						
		return var_dump((bool)$value);
	}
	public function hitungBobot()
	{		
		$this->tfidf->tf(); 
		$this->tfidf->idf(); 
		$this->tfidf->tfidf(); 
		$this->panjangVektor->panjangVektor();
	}	
	
	public function admin() 
	{
		try {
			$data['tesaurus'] = $this->tesaurus->getAll();		
			return view('admin/page', $data);
		}
		catch (\Throwable $e) {                                                                     
            die("Caught exception
                <br>File: {$e->getFile()}
                <br>Line: {$e->getLine()}
                <br>Message: {$e->getMessage()}"
            );
        }
		finally {
			unset($data);
		}
	}

	public function tesaurus($action)
	{
		switch ($action) {
			case 'form':
				$data['action'] = 'tambah';
				return view('admin/form-tesaurus', $data);
				break;					
			case 'tambah':
				$result = $this->tesaurus->insert(
					$this->request->getVar('kata'),
					$this->request->getVar('gugus_kata')
				);
				$status = $result ? 'sukses' : 'gagal';
				return redirect()->to('form')->with('status', $status)->withInput();
				break;
			case 'edit':
				$data['lema'] = $this->tesaurus->get($this->request->getVar('id'));
				$data['action'] = 'update';								
				return view('admin/form-tesaurus', $data);
				break;
			case 'update':
				$result = $this->tesaurus->update(
					$this->request->getVar('id'),
					$this->request->getVar('kata'),
					$this->request->getVar('gugus_kata')
				);
				$data['action'] = 'update';	
				$status = $result ? 'sukses' : 'gagal';			
				//return redirect()->to('form')->with('status', $data)->withInput();
				return view('admin/form-tesaurus', $data);
				break;
			case 'delete':
				$this->tesaurus->delete($this->request->getVar('id'));
				return redirect('admin');
				break;
			default:
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
				break;
		}		
	}	
}
