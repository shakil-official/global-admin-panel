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

        // Repeatable Field Script
        $formHtml .= "
                        <script>
                        document.addEventListener('click', function(e) {

                            // ADD
                            if (e.target.classList.contains('repeatable-add')) {

                                let wrapper = e.target.closest('.repeatable-wrapper');
                                if (!wrapper) return;

                                let baseName = wrapper.getAttribute('data-name');
                                let inputClass = wrapper.getAttribute('data-class');
                                let placeholder = wrapper.getAttribute('data-placeholder');

                                let row = document.createElement('div');
                                row.className = 'd-flex mb-2 align-items-center repeatable-row';

                                row.innerHTML =
                                    '<button type=\"button\" class=\"btn btn-sm btn-success me-2 repeatable-add\">+</button>' +
                                    '<input type=\"text\" name=\"' + baseName + '[]\" class=\"' + inputClass + ' form-control me-2\" placeholder=\"' + placeholder + '\">' +
                                    '<button type=\"button\" class=\"btn btn-sm btn-danger repeatable-remove\">×</button>';

                                wrapper.appendChild(row);
                            }

                            // REMOVE
                            if (e.target.classList.contains('repeatable-remove')) {

                                let row = e.target.closest('.repeatable-row');
                                if (!row) return;

                                let wrapper = row.closest('.repeatable-wrapper');

                                if (wrapper.querySelectorAll('.repeatable-row').length > 1) {
                                    row.remove();
                                }
                            }

                        });
                        </script>
                        ";

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
                $label = $input['label'] ?? '';
                $html .= "<label for=\"{$input['name']}\" class=\"form-label\">{$label}</label>\n";
                $html .= "<textarea class=\"form-control\" id=\"{$input['name']}\" name=\"{$input['name']}\" $required>{$value}</textarea>\n";
                if ($validFeedback) {
                    $html .= "<div class=\"valid-feedback\">$validFeedback</div>\n";
                }
                if ($invalidFeedback) {
                    $html .= "<div class=\"invalid-feedback\">$invalidFeedback</div>\n";
                }
                break;

            case 'number':
                $label = $input['label'] ?? '';
                $html .= "<label for=\"{$input['name']}\" class=\"form-label\">{$label}</label>\n";
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
                $label = $input['label'] ?? '';

                $name = $multiple ? "{$input['name']}[]" : $input['name'];
                $value = is_array($value) ? $value : [$value]; // Ensure $value is an array for multi-select
                $html .= "<label for=\"{$input['name']}\" class=\"form-label\">{$label}</label>\n";
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

            case 'select_advance':

                $multiple = !empty($input['multiple']) ? 'multiple' : '';
                $inputClass = $input['class'] ?? '';
                $required = !empty($input['required']) ? 'required' : '';
                $invalidFeedback = $input['invalid_feedback'] ?? null;
                $label = $input['label'] ?? '';

                $name = $multiple ? "{$input['name']}[]" : $input['name'];

                // Normalize value to array (important for edit & multi-select)
                $value = $input['value'] ?? null;
                $selectedValues = is_array($value) ? $value : [$value];

                $html .= "<label for=\"{$input['name']}\" class=\"form-label\">{$label}</label>\n";
                $html .= "<select class=\"form-select {$inputClass}\" id=\"{$input['name']}\" name=\"{$name}\" {$required} {$multiple}>\n";

                foreach ($input['options'] as $option) {

                    // Support both formats
                    if (is_array($option)) {
                        $optionValue = $option['value'];
                        $optionLabel = $option['label'];
                    } else {
                        $optionValue = $option;
                        $optionLabel = $option;
                    }

                    $selected = in_array($optionValue, $selectedValues, true) ? 'selected' : '';

                    $html .= "<option value=\"{$optionValue}\" {$selected}>{$optionLabel}</option>\n";
                }

                $html .= "</select>\n";

                if ($invalidFeedback) {
                    $html .= "<div class=\"invalid-feedback\">{$invalidFeedback}</div>\n";
                }

                break;

            case 'radio':
                $label = $input['label'] ?? '';
                $html .= "<label class=\"form-label\">{$label}</label>\n";
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
                $checked = isset($input['checked']) && $input['checked'] ? 'checked' : '';
                $label = $input['label'] ?? '';
                $html .= "<div class=\"form-check\">\n";
                $html .= "<input type=\"hidden\" name=\"{$input['name']}\" value=\"0\">\n";
                $html .= "<input type=\"checkbox\" class=\"form-check-input\" id=\"{$input['name']}\" name=\"{$input['name']}\" value=\"1\" $checked $required>\n";
                $html .= "<label class=\"form-check-label\" for=\"{$input['name']}\">{$label}</label>\n";
                if ($invalidFeedback) {
                    $html .= "<div class=\"invalid-feedback\">$invalidFeedback</div>\n";
                }
                $html .= "</div>\n";
                break;

            case 'repeatable':

                $label = $input['label'] ?? '';
                $name = $input['name'];
                $class = $input['class'] ?? 'form-control';
                $placeholder = $input['placeholder'] ?? '';
                $values = $input['value'] ?? [''];

                $values = is_array($values) ? $values : [$values];

                if (empty($values)) {
                    $values = [''];
                }

                $html .= "<label class='form-label'>{$label}</label>";
                $html .= "<div class='repeatable-wrapper'
                                data-name='{$name}'
                                data-class='{$class}'
                                data-placeholder='{$placeholder}'>";

                foreach ($values as $val) {

                    $val = htmlspecialchars($val ?? '', ENT_QUOTES, 'UTF-8');

                    $html .= "
                    <div class='d-flex mb-2 align-items-center repeatable-row'>
                        <button type='button' class='btn btn-sm btn-success me-2 repeatable-add'>+</button>
                        <input type='text'
                               name='{$name}[]'
                               class='{$class} me-2'
                               value='{$val}'
                               placeholder='{$placeholder}'>
                        <button type='button' class='btn btn-sm btn-danger repeatable-remove'>×</button>
                    </div>";
                }

                $html .= "</div>";
                break;

            case 'submit':

            case 'button':
                $icon = isset($input['icon']) ? "<i class=\"{$input['icon']}\"></i> " : '';
                $html .= "<button type=\"{$input['type']}\" class=\"{$input['class']}\">{$icon}{$input['value']}</button>\n";
                break;

            default:
                $label = $input['label'] ?? '';
                $html .= "<label for=\"{$input['name']}\" class=\"form-label\">{$label}</label>\n";
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
