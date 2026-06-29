function AlertMessage({ message, type }) {
  if (!message) {
    return null
  }

  return <p className={`alert ${type}`}>{message}</p>
}

export default AlertMessage
