function Sidebar({
  activeTab,
  currentItems,
  isLoading,
  selectedItem,
  onChangeTab,
  onLoadFields,
  onRefresh,
}) {
  return (
    <aside className="sidebar">
      <div className="tabs" aria-label="Document type">
        <button
          type="button"
          className={activeTab === 'documents' ? 'active' : ''}
          onClick={() => onChangeTab('documents')}
        >
          Documents
        </button>
        <button
          type="button"
          className={activeTab === 'groups' ? 'active' : ''}
          onClick={() => onChangeTab('groups')}
        >
          Groups
        </button>
      </div>

      <div className="list-header">
        <h2>{activeTab === 'documents' ? 'Documents' : 'Groups'}</h2>
        <button type="button" className="text-button" onClick={onRefresh}>
          Refresh
        </button>
      </div>

      {isLoading && !selectedItem && <p className="muted">Loading...</p>}

      <div className="item-list">
        {currentItems.map((item) => (
          <button
            type="button"
            key={item.id}
            className={selectedItem?.id === item.id ? 'item active' : 'item'}
            onClick={() => onLoadFields(item)}
          >
            <strong>{item.name}</strong>
            <span>{getItemMeta(item, activeTab)}</span>
          </button>
        ))}
      </div>
    </aside>
  )
}

function getItemMeta(item, activeTab) {
  if (activeTab === 'groups') {
    return `${item.doc_count || 0} documents`
  }

  if (item.groups?.length) {
    return item.groups.join(', ')
  }

  return 'Single document'
}

export default Sidebar
