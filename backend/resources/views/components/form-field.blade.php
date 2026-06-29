<div class="flex flex-col gap-1">
    @if ($field->field_type !== 'AUTO' && $field->field_type !== 'SYSTEM')
        <label for="{{ $field->field_key }}" class="text-sm font-medium text-gray-700">
            {{ ucwords(str_replace('_', ' ', $field->field_key)) }}
        </label>
    @else
        <label for="{{ $field->field_key }}" class="text-sm font-medium text-gray-700">
            {{ ucwords(str_replace('_', ' ', $field->field_key)) }} (Auto-generated)
        </label>
    @endif

    @if ($field->field_type === 'INPUT')
        <input type="{{ in_array($field->field_key, ['heure_depart', 'heure_retour']) ? 'time' : 'text' }}"
            id="{{ $field->field_key }}" name="{{ $field->field_key }}" value="{{ old($field->field_key) }}"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    @elseif($field->field_type === 'DATE')
        <input type="date" id="{{ $field->field_key }}" name="{{ $field->field_key }}"
            value="{{ old($field->field_key) }}"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    @elseif($field->field_type === 'NUMBER')
        <input type="number" id="{{ $field->field_key }}" name="{{ $field->field_key }}"
            value="{{ old($field->field_key) }}"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    @elseif($field->field_type === 'SELECT')
        <select id="{{ $field->field_key }}" name="{{ $field->field_key }}"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

            @php
                $config = is_array($field->config_json)
                    ? $field->config_json
                    : json_decode($field->config_json, true) ?? [];

                if (isset($config['min'], $config['max'])) {
                    $max = $config['max'] === 'current_year' ? now()->year : $config['max'];

                    $options = range($max, $config['min']);
                } else {
                    $options = $config['options'] ?? [];
                }
            @endphp

            @foreach ($options as $option)
                <option value="{{ $option }}" {{ old($field->field_key) == $option ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach

        </select>
    @endif
</div>
