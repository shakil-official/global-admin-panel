<?php

namespace App\Http\Controllers\TermsAndCondition;

use App\Engine\TermsAndCondition\Services\Contracts\TermsAndConditionServiceInterface;
use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use App\Models\TermsAndCondition;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;

class TermsAndConditionController extends Controller
{
    private TermsAndConditionServiceInterface $service;

    public function __construct(TermsAndConditionServiceInterface $service)
    {
        $this->service = $service;
    }

    public function edit(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find(1);

        $formConfig = (new FormMaking())
            ->action(route('section.term.update', ['id' => 1]))
            ->method('POST')
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
                                <div class=\"snow-editor\"  data-name=\"{$input['name']}\" style=\"min-height: 300px;\">$value</div>
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
                'value' => 'Update',
                'col' => 'col-6 mb-3',
                'class' => 'btn btn-sm btn-primary',
            ])
            ->endRow()
            ->build();

        return view('backend.terms_and_condition.edit')->with([
            'title' => 'Terms & Condition Edit',
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
            'description' => 'required',
        ]);

        $response = $this->service->update($id, $request->all());

        if ($response) {
            return redirect()->route('section_term.edit')->with('success', 'Terms And Condition updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }
}
