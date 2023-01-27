<?php

namespace App\TraitLibraries;

use Illuminate\Http\Request;
use App\TraitLibraries\ResponseWithHttpStatus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

trait CrudApiControllerTrait
{
    use ResponseWithHttpStatus;

    public function index(Request $request)
    {
        try {
            $limit = $request->all()['limit'] ?? 20;

            $order = $request->all()['order'] ?? null;
            if ($order !== null) {
                $order = explode(',', $order);
            }
            $order[0] = $order[0] ?? 'id';
            $order[1] = $order[1] ?? 'asc';

            $where = $request->all()['where'] ?? [];

            $like = $request->all()['like'] ?? null;
            if ($like) {
                $like = explode(',', $like);
                if (!isset($like[1])) {
                    $like[0] = 'title';
                    $like[1] = $like[0];
                }
                $like[1] = '%' . $like[1] . '%';
            }

            $result = $this->model->orderBy($order[0], $order[1])
                ->where(function ($query) use ($like) {
                    if ($like) {
                        return $query->where($like[0], 'like', $like[1]);
                    }
                    return $query;
                })
                ->where($where)
                ->with($this->relationships())
                ->paginate($limit);

            return $this->responseSuccess(config('api.response.messages.200'), $result, 200);
        }catch(\Exception $ex){
            return $this->responseFailure("Something went wrong", 500);

        }
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), $this->model->validationRules());

        if ($validator->fails()) {

            return $this->responseFailure($validator->errors()->first(),422);
        }

        DB::beginTransaction();

        try {
            $result = $this->model->create($request->all());

            DB::commit();

            return $this->responseSuccess(config('api.response.messages.201'), $result, 201);

        } catch (\Exception $ex) {

            DB::rollBack();

            return $this->responseFailure(config('api.response.messages.500'), 500);

        }

    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), $this->model->validationRules($id));

        if ($validator->fails()) {

            return $this->responseFailure($validator->errors()->first(),422);
        }

        DB::beginTransaction();

        try {

            $result = $this->model->find($id);

            if (!$result)
                return $this->responseFailure(config('api.response.messages.404'), 404);

            $result->update($request->all());

            DB::commit();

            return $this->responseSuccess(config('api.response.messages.200'), $result, 200);

        } catch (\Exception $ex) {

            DB::rollBack();

            return $this->responseFailure(config('api.response.messages.500'), 500);
        }
    }

    public function show($id)
    {
        $result = $this->model->with($this->relationships())->find($id);

        if (!$result)
            return $this->responseFailure(config('api.response.messages.404'), 404);

        return $this->responseSuccess(config('api.response.messages.200'), $result, 200);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $result = $this->model->find($id);

            if (!$result)
                return $this->responseFailure(config('api.response.messages.404'), 404);

            $result->delete();

            DB::commit();

            return $this->responseSuccess(config('api.response.messages.200'), null, 200);

        } catch (\Exception $ex) {

            DB::rollBack();

            return $this->responseFailure(config('api.response.messages.500'), 500);
        }
    }

    protected function relationships()
    {
        if (isset($this->relationships)) {
            return $this->relationships;
        }
        return [];
    }
}
