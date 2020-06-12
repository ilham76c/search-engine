<?php namespace App\Controllers;

use App\Libraries\Pembobotan;
use App\Libraries\Query;
use App\Libraries\Dokumen;
use App\Libraries\Tesaurus;


class Home extends BaseController
{
	function __construct() {						
		helper('form');
		$this->pembobotan = Pembobotan::getInstance();
		$this->request = \Config\Services::request();
		$this->tesaurus = Tesaurus::getInstance();
		$this->query = Query::getInstance();
		$this->dokumen = Dokumen::getInstance();		
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
			$query = $this->query->queryExpansion($this->request->getVar('query'));						
			list($tfidf_query, $paVek_query) = $this->query->bobotQuery($query);	
			if (empty($tfidf_query)) {			
				$this->dokumen->resetResult();			
			} else {			
				$tfidf_dokumen = $this->dokumen->getRelevanDokumen(array_keys($tfidf_query));		
				$bobot_dokumen = $this->pembobotan->cosineSimilarity($tfidf_query, $tfidf_dokumen, $paVek_query);
				$this->dokumen->result($bobot_dokumen);						
			}					
			return redirect()->to('result')->withInput();
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
		finally {
			unset($query, $tfidf_query, $paVek_query, $tfidf_dokumen, $bobot_dokumen);
		}
	}

	public function hitungBobot()
	{
		$this->pembobotan->hitungBobot();
	}	
	
	public function admin() 
	{
		try {
			$data['tesaurus'] = $this->tesaurus->getAll();		
			return view('admin/page', $data);
		}
		catch (\Exception $e) {
			die($e->getMessage());
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