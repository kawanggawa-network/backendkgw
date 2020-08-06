<?php

/**
 * Method to generate field input
 * @param $type string
 * @param $name string
 * @return string
 */
function getFieldInput($key, $field, $value = '', $options = [], $dataAttributes = [])
{
    $checked = $value ? ' checked' : '';

    switch($field['type']) {
        case 'text':
        case 'file':
        case 'email':
        case 'number':
            return '<input type="' . $field['type'] . '" name="' . $key . '" class="form-control" placeholder="' . $field['label'] . '" value="' . $value . '" />';
            break;
        case 'password':
            return '<input type="' . $field['type'] . '" name="' . $key . '" class="form-control" placeholder="' . $field['label'] . '"  />';
            break;
        case 'checkbox':
            return '<input type="' . $field['type'] . '" name="' . $key . '" class="form-control" placeholder="' . $field['label'] . '" value="1" '. $checked .' />';
            break;
        case 'textarea':
            return '<textarea name="' . $key . '" class="form-control" rows="10"  placeholder="' . $field['label'] . '">' . $value . '</textarea>';
            break;
        case 'date':
            return '
            <div class="input-group input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                </div>
                <input class="form-control datepicker" placeholder="Select date" type="text" data-format="yyyy-dd-mm" value="' . $value .'" name="' . $key . '">
            </div>
            ';
            break;
        case 'wysiwyg':
            return '
                <div data-key="'.$key.'" id="text-'. $key .'" class="editor" rows="3"  placeholder="' . $field['label'] . '" style="height:200px;">
                    ' . $value . '
                </div>
                <textarea name="' . $key . '" style="display:none;">
                    ' . $value . '
                </textarea>
                <!-- Initialize Quill editor -->
                <script>
                var quill = new Quill("#text-' . $key . '", {
                    theme: "snow",
                    placeholder: "' . $field['label'] . '",
                });
                </script>
            ';
            break;
        case 'select':
            $optionsHTML = '<option value="">Select ' . $field['label'] . '</option>';
            $options = !empty($option) 
                ? $option 
                : isset($field['options']) ? $field['options'] : [];

            foreach($options as $option) {
                $dataAttributes = '';
                if (isset($option['data'])) {    
                    foreach ($option['data'] as $dataKey => $dataValue) {
                        $dataAttributes .= 'data-' . $dataKey . '="' . $dataValue . '"';
                    }
                }

                $selected = !is_object($value) && $option['value'] == $value ? ' selected' : '';
                $optionsHTML .= '<option value="' . $option['value'] . '"' . $selected . ' ' . $dataAttributes . '>' . $option['text'] . '</option>';
            }

            return '
                <select class="form-control" name="' . $key . '">
                    ' . $optionsHTML . '
                </select>
            ';
            break;
        case 'datetime':
        return '
        <div class="input-group input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="material-icons">event</i></span>
            </div>
            <input class="form-control" type="datetime" value="' . $value .'" name="' . $key . '">
        </div>
        ';
        break;
    }

}

/**
 * Currency format.
 *
 * @param integer $amount
 *
 * @return string
 */
function currencyFormat($amount = 0)
{
    return "Rp " . number_format($amount, 0, ',', '.') . ',-';
}
