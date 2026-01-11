<?php

namespace App\Http\Controllers\Basic;

use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $formConfig = (new FormMaking())
            ->action('#') // Replace with your form action URL
            ->method('POST')
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'first_name',
                    'label' => 'First name',
                    'col' => 'col-md-6 mb-3',
                    'value' => '',
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                ],
                [
                    'type' => 'text',
                    'name' => 'last_name',
                    'label' => 'Last name',
                    'col' => 'col-md-6 mb-3',
                    'value' => '',
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                ],
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'select',
                    'name' => 'state',
                    'label' => 'State',
                    'col' => 'col-md-6 mb-3',
                    'options' => [
                        '' => 'Choose...',
                        'Sylhet' => 'Sylhet',
                        'Dhaka' => 'Dhaka',
                    ],
                    'required' => true,
                    'invalid_feedback' => 'Please select a valid state.',
                ],
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'checkbox',
                'name' => 'agree',
                'label' => 'Agree to terms and conditions',
                'col' => 'col-6 mb-3',
                'required' => true,
                'invalid_feedback' => 'You must agree before submitting.',
            ])
            ->addFormFields([
                [
                    'type' => 'radio',
                    'name' => 'radio-stacked',
                    'label' => 'Select an option',
                    'options' => [
                        'option1' => 'Toggle this radio',
                        'option2' => 'Or toggle this other radio',
                    ],
                    'col' => 'col-6 mb-3', // Adjust column size as needed
                    'required' => true,
                    'invalid_feedback' => 'More example invalid feedback text',
                ],
            ])

            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Submit form',
                'col' => 'col-12 mb-3',
                'class' => 'btn btn-primary',
            ])
            ->endRow()
            ->build();

        return view('backend.view-template.basic.index')->with(['formConfig' => $formConfig]);

    }




}
