<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use App\Services\Service;


trait RulesTrait
{
    public function validate(array $data, array $rules, array $changeErrorName = null)
    {
        // 直接用 Laravel 內建的 Validator
        $validator = \Validator::make($data, $rules);

        if ($validator->fails()) {
            // 你可以自訂 response 格式
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        return [
            'success' => true,
            'data' => $data
        ];
    }

    public function runValidate($methods)
    {
        if (!empty($this->response))
            return $this;
        $rule = $this->getValidationRules($methods);
        $data = $this->request->toArray();
        ($this->dataId) && $data['id'] = $this->dataId;
        $this->response = $this->validate($data, $rule, $this->changeErrorName);
        return $this;
    }

    private function getValidationRules($methods)
    {
        $methods = collect($methods);
        $rules = collect($this->rules);

        $rule = $methods->reduce(function ($carry, $method) use ($rules) {
            return $carry->merge($rules->get($method, []));
        }, collect());

        return $rule->toArray();
    }
}