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
        <input type="text" id="{{ $field->field_key }}" name="{{ $field->field_key }}"
            value="{{ old($field->field_key) }}"
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
            @foreach ($field->config_json['options'] as $option)
                <option value="{{ $option }}" {{ old($field->field_key) == $option ? 'selected' : '' }}>
                    {{ $option }}</option>
            @endforeach
        </select>
    @endif
</div>
