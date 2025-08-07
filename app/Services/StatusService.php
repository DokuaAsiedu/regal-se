<?php

namespace App\Services;

use App\Repositories\StatusRepository;

class StatusService
{
    protected $statusRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(StatusRepository $statusRepository)
    {
        $this->statusRepository = $statusRepository;
    }

    public function find($id)
    {
        return $this->statusRepository->find($id);
    }

    public function all()
    {
        return $this->statusRepository->all();
    }

    public function allQuery($search = [])
    {
        return $this->statusRepository->allQuery($search);
    }

    public function store($input)
    {
        return $this->statusRepository->create($input);
    }

    public function update($id, $input)
    {
        return $this->statusRepository->update($input, $id);
    }

    public function active()
    {
        return $this->statusRepository->allQuery(['code' => 'active'])
            ->first();
    }

    public function inActive()
    {
        return $this->statusRepository->allQuery(['code' => 'inactive'])
            ->first();
    }

    public function pending()
    {
        return $this->statusRepository->allQuery(['code' => 'pending'])
            ->first();
    }

    public function approved()
    {
        return $this->statusRepository->allQuery(['code' => 'approved'])
            ->first();
    }

    public function completed()
    {
        return $this->statusRepository->allQuery(['code' => 'completed'])
            ->first();
    }

    public function declined()
    {
        return $this->statusRepository->allQuery(['code' => 'declined'])
            ->first();
    }

    public function rejected()
    {
        return $this->statusRepository->allQuery(['code' => 'rejected'])
            ->first();
    }

    public function suspended()
    {
        return $this->statusRepository->allQuery(['code' => 'suspended'])
            ->first();
    }

    public function success()
    {
        return $this->statusRepository->allQuery(['code' => 'success'])
            ->first();
    }

    public function failed()
    {
        return $this->statusRepository->allQuery(['code' => 'failed'])
            ->first();
    }

    public function abandoned()
    {
        return $this->statusRepository->allQuery(['code' => 'abandoned'])
            ->first();
    }

    public function cancelled()
    {
        return $this->statusRepository->allQuery(['code' => 'cancelled'])
            ->first();
    }

    public function invalid()
    {
        return $this->statusRepository->allQuery(['code' => 'invalid'])
            ->first();
    }

    public function unknown()
    {
        return $this->statusRepository->allQuery(['code' => 'unknown'])
            ->first();
    }

    public function isActive($status_id)
    {
        return $status_id == $this->active()->id;
    }

    public function isInactive($status_id)
    {
        return $status_id == $this->inActive()->id;
    }

    public function isPending($status_id)
    {
        return $status_id == $this->pending()->id;
    }

    public function isApproved($status_id)
    {
        return $status_id == $this->approved()->id;
    }

    public function isCompleted($status_id)
    {
        return $status_id == $this->completed()->id;
    }

    public function isDeclined($status_id)
    {
        return $status_id == $this->declined()->id;
    }

    public function isRejected($status_id)
    {
        return $status_id == $this->rejected()->id;
    }

    public function isSuspended($status_id)
    {
        return $status_id == $this->suspended()->id;
    }

    public function isSuccessful($status_id)
    {
        return $status_id == $this->success()->id;
    }

    public function isFailure($status_id)
    {
        return $status_id == $this->failed()->id;
    }

    public function isAbandoned($status_id)
    {
        return $status_id == $this->abandoned()->id;
    }

    public function isCancelled($status_id)
    {
        return $status_id == $this->cancelled()->id;
    }

    public function isInvalid($status_id)
    {
        return $status_id == $this->invalid()->id;
    }

    public function isUnknown($status_id)
    {
        return $status_id == $this->unknown()->id;
    }
}
