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
        // যদি row start না করা থাকে, তাহলে একটি row তৈরি করো
        if (empty($this->formConfig['inputs'])) {
            $this->startRow();
        }
        
        $lastRowIndex = count($this->formConfig['inputs']) - 1;
        
        // যদি 'col' না থাকে, তাহলে default হিসেবে 'col-md-12' যোগ করো
        if (!isset($input['col'])) {
            $input['col'] = 'col-md-12';
        }
        
        $this->formConfig['inputs'][$lastRowIndex]['row'][] = $input;
        return $this;
    }

    public function repeatable(array $config): self
    {
        $config['type'] = 'repeatable';

        // যদি row start না করা থাকে
        if (empty($this->formConfig['inputs'])) {
            $this->startRow();
        }

        return $this->addInput($config);
    }

    public function addFormFields(array $fields): self
    {
        // যদি row start না করা থাকে, তাহলে একটি row তৈরি করো
        if (empty($this->formConfig['inputs'])) {
            $this->startRow();
        }
        
        foreach ($fields as $field) {
            // যদি 'col' না থাকে, তাহলে default হিসেবে 'col-md-12' যোগ করো
            if (!isset($field['col'])) {
                $field['col'] = 'col-md-12';
            }
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
