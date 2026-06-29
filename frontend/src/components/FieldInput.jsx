function FieldInput({ field, value, onChange }) {
  const inputId = `field-${field.key}`
  const label = makeLabel(field.key)

  if (field.type === 'SELECT') {
    return (
      <label htmlFor={inputId}>
        {label}
        <select
          id={inputId}
          value={value}
          required={field.required}
          onChange={(event) => onChange(field.key, event.target.value)}
        >
          <option value="">Choose an option</option>
          {(field.options || []).map((option) => (
            <option value={option} key={option}>
              {option}
            </option>
          ))}
        </select>
      </label>
    )
  }

  return (
    <label htmlFor={inputId}>
      {label}
      <input
        id={inputId}
        type={getInputType(field)}
        value={value}
        required={field.required}
        onChange={(event) => onChange(field.key, event.target.value)}
      />
    </label>
  )
}

function getInputType(field) {
  if (['heure_depart', 'heure_retour'].includes(field.key)) {
    return 'time'
  }

  if (field.type === 'DATE') {
    return 'date'
  }

  if (field.type === 'NUMBER') {
    return 'number'
  }

  return 'text'
}

function makeLabel(key) {
  return key
    .replace(/[_-]+/g, ' ')
    .replace(/\b\w/g, (letter) => letter.toUpperCase())
}

export default FieldInput
