import AppHeader from './AppHeader'
import DocumentForm from './DocumentForm'
import Sidebar from './Sidebar'

function DashboardView({
  activeLabel,
  activeTab,
  currentItems,
  downloadUrl,
  error,
  fields,
  fieldTitle,
  formValues,
  isGenerating,
  isLoading,
  message,
  selectedItem,
  user,
  onChangeTab,
  onDownload,
  onGenerate,
  onHome,
  onLoadFields,
  onLogout,
  onRefresh,
  onUpdateField,
}) {
  return (
    <main className="app-shell">
      <AppHeader user={user} onHome={onHome} onLogout={onLogout} />

      <section className="dashboard">
        <Sidebar
          activeTab={activeTab}
          currentItems={currentItems}
          isLoading={isLoading}
          selectedItem={selectedItem}
          onChangeTab={onChangeTab}
          onLoadFields={onLoadFields}
          onRefresh={onRefresh}
        />

        <section className="workspace">
          {!selectedItem ? (
            <div className="empty-state">
              <h1>Choose a {activeLabel}</h1>
              <p>
                Select an item from the list to load its fields, generate the
                file, and download the result.
              </p>
            </div>
          ) : (
            <DocumentForm
              activeLabel={activeLabel}
              downloadUrl={downloadUrl}
              error={error}
              fields={fields}
              fieldTitle={fieldTitle}
              formValues={formValues}
              isGenerating={isGenerating}
              message={message}
              onDownload={onDownload}
              onGenerate={onGenerate}
              onUpdateField={onUpdateField}
            />
          )}
        </section>
      </section>
    </main>
  )
}

export default DashboardView
