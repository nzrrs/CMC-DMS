import BrandHeader from './BrandHeader'

function AppHeader({ user, onHome, onLogout }) {
  return (
    <header className="topbar">
      <BrandHeader />
      <div className="user-box">
        <span>{user?.name}</span>
        <small>{user?.role}</small>
        <button type="button" className="ghost-button" onClick={onHome}>
          Documents
        </button>
        <button type="button" className="ghost-button" onClick={onLogout}>
          Logout
        </button>
      </div>
    </header>
  )
}

export default AppHeader
