import logoUrl from '../assets/CMC-logo.png'

function BrandHeader() {
  return (
    <div className="brand">
      <div className="logo-slot">
        <img src={logoUrl} alt="CMC logo" />
      </div>
      <div>
        <strong>CMC-DMS</strong>
        <span>Document Management System</span>
      </div>
    </div>
  )
}

export default BrandHeader
