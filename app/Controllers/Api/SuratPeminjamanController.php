<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class SuratPeminjamanController extends ResourceController
{
    protected $modelName = 'App\Models\SuratPeminjamanModel';
    protected $format = 'json';

    public function index()
    {
        try {
            $page    = $this->request->getVar('page') ?? 1;
            $perPage = $this->request->getVar('per_page') ?? 10;

            $sortBy    = $this->request->getVar('sort') ?? 'id';
            $sortOrder = substr($sortBy, 0, 1) === '-' ? 'desc' : 'asc';
            $sortBy    = substr($sortBy, 0, 1) === '-' ? substr($sortBy, 1) : $sortBy;

            // Perform sorting
            $this->model->orderBy($sortBy, $sortOrder);

            // Get paginated results
            $data = $this->model
                ->orderBy($sortBy, $sortOrder)
                ->paginate($perPage, 'default', $page);

            // dd($data[0]);

            // Create pagination links
            $pager = $this->model->pager;

            // Prepare data to return
            $responseData = [
                'data'  => $data,
                'pager' => $pager->getDetails(),
            ];

            return $this->respond($responseData);
        } catch (\Throwable $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function show($id = null)
    {
        //
    }

    public function new()
    {
        //
    }

    public function create()
    {
        //
    }

    public function edit($id = null)
    {
        //
    }

    public function update($id = null)
    {
        //
    }

    public function delete($id = null)
    {
        //
    }
}