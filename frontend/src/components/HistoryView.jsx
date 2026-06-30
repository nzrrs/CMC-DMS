import AlertMessage from './AlertMessage'

function HistoryView({
  error,
  historyItems,
  isLoading,
  message,
  onDownloadHistory,
  onRegenerateHistory,
  onRefreshHistory,
}) {
  return (
    <section className="history-view">
      <div className="history-heading">
        <div>
          <h1>Historique</h1>
          <p>Generated documents, versions, status, and downloads.</p>
        </div>
        <button type="button" className="secondary-button" onClick={onRefreshHistory}>
          Refresh
        </button>
      </div>

      <AlertMessage message={error} type="error" />
      <AlertMessage message={message} type="success" />

      <div className="history-table-wrap">
        <table className="history-table">
          <thead>
            <tr>
              <th>Document</th>
              <th>Version</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {historyItems.map((item) => (
              <tr key={item.id}>
                <td>
                  <strong>{item.document}</strong>
                  <span className="history-file-name">{item.generated_name}</span>
                </td>
                <td>v{item.version}</td>
                <td>{formatDate(item.created_at)}</td>
                <td>
                  <span className={`status-badge ${item.status.toLowerCase()}`}>
                    {item.status}
                  </span>
                </td>
                <td>
                  <div className="table-actions">
                    <button
                      type="button"
                      className="secondary-button compact-button"
                      disabled={!item.can_download}
                      onClick={() => onDownloadHistory(item)}
                    >
                      Download
                    </button>
                    <button
                      type="button"
                      className="ghost-button compact-button"
                      onClick={() => onRegenerateHistory(item)}
                    >
                      Regenerate
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>

        {!isLoading && historyItems.length === 0 && (
          <p className="history-empty">No documents generated yet.</p>
        )}

        {isLoading && <p className="history-empty">Loading history...</p>}
      </div>
    </section>
  )
}

function formatDate(value) {
  if (!value) {
    return '-'
  }

  return new Intl.DateTimeFormat('fr-MA', {
    dateStyle: 'short',
    timeStyle: 'short',
  }).format(new Date(value))
}

export default HistoryView
