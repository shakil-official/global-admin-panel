<?php

namespace App\Helpers\Builder;

class FormMaking
{
    protected array $formConfig = [
        'action' => '',
        'method' => 'POST',
        'enctype' => null,
        'inputs' => [],
    ];

    public function action(string $action): self
    {
        $this->formConfig['action'] = $action;
        return $this;
    }

    public function method(string $method): self
    {
        $this->formConfig['method'] = $method;
        return $this;
    }

    public function enctype(string $enctype): self
    {
        $this->formConfig['enctype'] = $enctype;
        return $this;
    }

    public function startRow(): self
    {
        $this->formConfig['inputs'][] = ['row' => []];
        return $this;
    }

    public function addInput(array $input): self
    {
        $lastRowIndex = count($this->formConfig['inputs']) - 1;
        $this->formConfig['inputs'][$lastRowIndex]['row'][] = $input;
        return $this;
    }

    public function addFormFields(array $fields): self
    {
        foreach ($fields as $field) {
            $this->addInput($field);
        }
        return $this;
    }

    public function endRow(): self
    {
        return $this;
    }

    public function build(): array
    {
        return $this->formConfig;
    }
}
