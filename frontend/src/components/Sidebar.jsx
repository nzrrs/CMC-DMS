import { useMemo, useState } from 'react'

function Sidebar({
  activeTab,
  currentItems,
  isLoading,
  selectedItem,
  onChangeTab,
  onLoadFields,
  onRefresh,
}) {
  const [searchTerm, setSearchTerm] = useState('')
  const filteredItems = useMemo(() => {
    const normalizedSearch = searchTerm.trim().toLowerCase()

    if (!normalizedSearch) {
      return currentItems
    }

    return currentItems.filter((item) => {
      const searchableText = [
        item.name,
        getItemMeta(item, activeTab),
        ...(item.groups || []),
      ]
        .join(' ')
        .toLowerCase()

      return searchableText.includes(normalizedSearch)
    })
  }, [activeTab, currentItems, searchTerm])

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

      <label className="search-field" htmlFor="sidebar-search">
        Search
        <input
          id="sidebar-search"
          type="search"
          value={searchTerm}
          onChange={(event) => setSearchTerm(event.target.value)}
          placeholder={`Search ${activeTab}`}
        />
      </label>

      {isLoading && !selectedItem && <p className="muted">Loading...</p>}

      <div className="item-list">
        {filteredItems.map((item) => (
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

      {!isLoading && filteredItems.length === 0 && (
        <p className="muted">No {activeTab} match your search.</p>
      )}
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
