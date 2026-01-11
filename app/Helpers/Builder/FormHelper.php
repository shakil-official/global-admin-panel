<?php

namespace App\Helpers\Builder;

class FormHelper
{
    public static function renderForm(array $formConfig): string
    {
        $formHtml = "<form action=\"{$formConfig['action']}\" method=\"{$formConfig['method']}\" class=\"row g-3 needs-validation\" novalidate ";

        if (isset($formConfig['enctype'])) {
            $formHtml .= " enctype=\"{$formConfig['enctype']}\"";
        }

        $formHtml .= ">\n";
        $formHtml .= csrf_field() . "\n";

        foreach ($formConfig['inputs'] as $inputGroup) {
            $formHtml .= "<div class=\"row\">\n";

            foreach ($inputGroup['row'] as $input) {
                $formHtml .= "<div class=\"{$input['col']}\">\n";

                if (isset($input['callback']) && is_callable($input['callback'])) {
                    $formHtml .= call_user_func($input['callback'], $input);
                } else {
                    $formHtml .= self::renderInput($input);
                }

                $formHtml .= "</div>\n";
            }

            $formHtml .= "</div>\n";
        }

        $formHtml .= "</form>\n";
        return $formHtml;
    }

    private static function renderInput(array $input): string
    {
        $required = $input['required'] ?? false ? 'required' : '';
        $invalidFeedback = $input['invalid_feedback'] ?? '';
        $validFeedback = $input['validation_feedback'] ?? '';
        $value = $input['value'] ?? old($input['name'], '');
        $placeholder = $input['placeholder'] ?? '';
        $html = "";
        $icon = $input['icon'] ?? '';



        switch ($input['type']) {

            case 'textarea':
                $html .= "<label for=\"{$input['name']}\" class=\"form-label\">{$input['label']}</label>\n";
                $html .= "<textarea class=\"form-control\" id=\"{$input['name']}\" name=\"{$input['name']}\" $required>{$value}</textarea>\n";
                if ($validFeedback) {
                    $html .= "<div class=\"valid-feedback\">$validFeedback</div>\n";
                }
                if ($invalidFeedback) {
                    $html .= "<div class=\"invalid-feedback\">$invalidFeedback</div>\n";
                }
                break;

            case 'number':
                $html .= "<label for=\"{$input['name']}\" class=\"form-label\">{$input['label']}</label>\n";
                $html .= "<input type=\"number\" class=\"form-control\" id=\"{$input['name']}\" name=\"{$input['name']}\"
              value=\"$value\"";

                if (isset($input['min'])) {
                    $html .= " min=\"{$input['min']}\"";
                }
                if (isset($input['max'])) {
                    $html .= " max=\"{$input['max']}\"";
                }
                if (isset($input['step'])) {
                    $html .= " step=\"{$input['step']}\"";
                }
                if (isset($input['placeholder'])) {
                    $html .= " placeholder=\"{$placeholder}\"";
                }

                $html .= " $required>\n";

                if ($validFeedback) {
                    $html .= "<div class=\"valid-feedback\">$validFeedback</div>\n";
                }
                if ($invalidFeedback) {
                    $html .= "<div class=\"invalid-feedback\">$invalidFeedback</div>\n";
                }
                break;

            case 'select':
                $multiple = isset($input['multiple']) && $input['multiple'] ? 'multiple' : '';
                $inputClass = $input['class'] ?? '';

                $name = $multiple ? "{$input['name']}[]" : $input['name'];
                $value = is_array($value) ? $value : [$value]; // Ensure $value is an array for multi-select
                $html .= "<label for=\"{$input['name']}\" class=\"form-label\">{$input['label']}</label>\n";
                $html .= "<select class=\"form-select {$inputClass}\" id=\"{$input['name']}\" name=\"$name\" $required $multiple>\n";
                foreach ($input['options'] as $optionValue => $optionLabel) {
                    $selected = in_array($optionValue, $value) ? 'selected' : '';
                    $html .= "<option value=\"$optionValue\" $selected>$optionLabel</option>\n";
                }
                $html .= "</select>\n";
                if ($invalidFeedback) {
                    $html .= "<div class=\"invalid-feedback\">$invalidFeedback</div>\n";
                }
                break;


            case 'radio':
                $html .= "<label class=\"form-label\">{$input['label']}</label>\n";
                $html .= "<div class=\"\">\n";

                foreach ($input['options'] as $optionValue => $optionLabel) {
                    $optionSetLabel = $optionLabel['label'];
                    $optionSetValue = $optionLabel['value'];
                    $checked = $optionLabel['checked'] ? 'checked' : '';
                    $html .= "<div class=\"form-check form-check-inline\">\n";
                    $html .= "<input type=\"radio\" class=\"form-check-input\" id=\"{$input['name']}_{$optionValue}\"
                            name=\"{$input['name']}\" value=\"$optionSetValue\" $checked $required>\n";
                    $html .= "<label class=\"form-check-label\" for=\"{$input['name']}_{$optionValue}\">$optionSetLabel</label>\n";
                    $html .= "</div>\n";
                }

                if ($invalidFeedback) {
                    $html .= "<div class=\"invalid-feedback\">$invalidFeedback</div>\n";
                }

                $html .= "</div>\n";
                break;

            case 'checkbox':
                $html .= "<div class=\"form-check\">\n";
                $html .= "<input type=\"checkbox\" class=\"form-check-input\" id=\"{$input['name']}\" name=\"{$input['name']}\" $required>\n";
                $html .= "<label class=\"form-check-label\" for=\"{$input['name']}\">{$input['label']}</label>\n";
                if ($invalidFeedback) {
                    $html .= "<div class=\"invalid-feedback\">$invalidFeedback</div>\n";
                }
                $html .= "</div>\n";
                break;

            case 'submit':
            case 'button':
                $html .= "<button type=\"{$input['type']}\" class=\"{$input['class']}\"><i class=\"{$icon}\"></i> {$input['value']}</button>\n";
                break;

            default:
                $html .= "<label for=\"{$input['name']}\" class=\"form-label\">{$input['label']}</label>\n";
                $html .= "<input type=\"{$input['type']}\" class=\"form-control\"  placeholder=\"{$placeholder}\"   id=\"{$input['name']}\" name=\"{$input['name']}\" value=\"$value\" $required>\n";
                if ($validFeedback) {
                    $html .= "<div class=\"valid-feedback\">$validFeedback</div>\n";
                }
                if ($invalidFeedback) {
                    $html .= "<div class=\"invalid-feedback\">$invalidFeedback</div>\n";
                }
                break;
        }

        return $html;
    }
}
