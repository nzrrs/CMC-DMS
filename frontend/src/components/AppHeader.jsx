import BrandHeader from './BrandHeader'

function AppHeader({ isHistoryActive, user, onHistory, onHome, onLogout }) {
  return (
    <header className="topbar">
      <BrandHeader />
      <div className="user-box">
        <span>{user?.name}</span>
        <small>{user?.role}</small>
        <button type="button" className="ghost-button" onClick={onHome}>
          Documents
        </button>
        <button
          type="button"
          className={isHistoryActive ? 'ghost-button active' : 'ghost-button'}
          onClick={onHistory}
        >
          Historique
        </button>
        <button type="button" className="ghost-button" onClick={onLogout}>
          Logout
        </button>
      </div>
    </header>
  )
}

export default AppHeader
