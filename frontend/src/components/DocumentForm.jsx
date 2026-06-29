import AlertMessage from './AlertMessage'
import FieldInput from './FieldInput'

function DocumentForm({
  activeLabel,
  downloadUrl,
  error,
  fields,
  fieldTitle,
  formValues,
  isGenerating,
  message,
  onDownload,
  onGenerate,
  onUpdateField,
}) {
  return (
    <form className="document-form" onSubmit={onGenerate}>
      <div className="form-heading">
        <div>
          <h1>{fieldTitle}</h1>
          <p>Fill the required information, then generate your file.</p>
        </div>
        <span className="type-badge">{activeLabel}</span>
      </div>

      {fields.length === 0 && <p className="muted">No fields required.</p>}

      <div className="field-grid">
        {fields.map((field) => (
          <FieldInput
            field={field}
            key={field.key}
            value={formValues[field.key] || ''}
            onChange={onUpdateField}
          />
        ))}
      </div>

      <AlertMessage message={error} type="error" />
      <AlertMessage message={message} type="success" />

      <div className="actions">
        <button type="submit" className="primary-button" disabled={isGenerating}>
          {isGenerating ? 'Generating...' : 'Generate'}
        </button>
        <button
          type="button"
          className="secondary-button"
          onClick={onDownload}
          disabled={!downloadUrl}
        >
          Download
        </button>
      </div>
    </form>
  )
}

export default DocumentForm
