<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use App\Services\Service;


trait RulesTrait
{
    public function validate(array $data, array $rules, array $changeErrorName = null)
    {
        $messages = $this->messages;
        (empty($this->messages)) && $messages = $this->createMessages($rules, $changeErrorName)
            ->toDot()
            ->getMessages();

        return (new Service())->validatorAndResponse(
            $data,
            $rules,
            $messages
        );
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