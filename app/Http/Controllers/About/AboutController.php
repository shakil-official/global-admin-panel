<?php

namespace App\Http\Controllers\About;

use App\Engine\About\Services\Contracts\AboutServiceInterface;
use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AboutController extends Controller
{
    private AboutServiceInterface $service;

    public function __construct(AboutServiceInterface $service)
    {
        $this->service = $service;
    }



    public function edit(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find(1);

        $formConfig = (new FormMaking())
            ->action(route('about.update', ['id' => 1]))
            ->method('POST')
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Title',
                    'col' => 'col-md-12 mb-3',
                    'value' => $data->title,
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                ]
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'custom',
                    'name' => 'description',
                    'value' => $data->description,
                    'col' => 'col-md-12 mb-3',
                    'callback' => function ($input) {
                        $value = htmlspecialchars($input['value'], ENT_QUOTES, 'UTF-8'); // Escape value for safety

                        return "<div class=\"form-group\">
                                <label for=\"{$input['name']}\">Description</label>
                                <div class=\"snow-editor\"  data-name=\"{$input['name']}\" style=\"height: 300px;\">$value</div>
                                <input type=\"hidden\" name=\"{$input['name']}\" id=\"{$input['name']}\" value=\"$value\">
                                   </div>
                            ";
                    }

                ]
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Update Privacy Policy',
                'col' => 'col-6 mb-3',
                'class' => 'btn btn-sm btn-primary',
            ])
            ->endRow()
            ->build();

        return view('backend.about.edit')->with([
            'title' => 'About Edit',
            'buttons' => [],
            'formConfig' => $formConfig,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'title' => [
                'required',
                Rule::unique((new About)->getTable())->ignore($id),
            ],
            'description' => 'required',
        ]);

        $response = $this->service->update($id, $request->all());

        if ($response) {
            return redirect()->route('about.edit')->with('success', 'About updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }
}
