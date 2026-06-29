import logoUrl from '../assets/CMC-logo.png'

function BrandHeader() {
  return (
    <div className="brand">
      <div className="logo-slot">
        <img src={logoUrl} alt="CMC logo" />
      </div>
      <div>
        <strong>cmc-bmk</strong>
        <span>Document management system</span>
      </div>
    </div>
  )
}

export default BrandHeader
