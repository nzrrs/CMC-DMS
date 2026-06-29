export const API_BASE_URL =
  import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000/api'

export async function apiRequest(path, options = {}, token = '') {
  const response = await fetch(`${API_BASE_URL}${path}`, {
    ...options,
    headers: {
      Accept: 'application/json',
      ...(options.body ? { 'Content-Type': 'application/json' } : {}),
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...options.headers,
    },
  })

  if (!response.ok) {
    throw new Error(await getErrorMessage(response))
  }

  return response.json()
}

export async function downloadFromApi(path, fileName, token) {
  const response = await fetch(`${API_BASE_URL}${path}`, {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  })

  if (!response.ok) {
    throw new Error(`Download failed with status ${response.status}`)
  }

  const blob = await response.blob()
  const fileUrl = URL.createObjectURL(blob)
  const link = document.createElement('a')

  link.href = fileUrl
  link.download = getDownloadName(response, fileName)
  document.body.appendChild(link)
  link.click()
  link.remove()
  URL.revokeObjectURL(fileUrl)
}

async function getErrorMessage(response) {
  let errorMessage = 'Something went wrong. Please try again.'

  try {
    const errorData = await response.json()
    errorMessage = errorData.message || errorMessage

    if (errorData.errors) {
      errorMessage = Object.values(errorData.errors).flat().join(' ')
    }
  } catch {
    errorMessage = `Request failed with status ${response.status}`
  }

  return errorMessage
}

function getDownloadName(response, fallbackName) {
  const disposition = response.headers.get('content-disposition')
  const match = disposition?.match(/filename="?([^"]+)"?/)

  return match?.[1] || fallbackName
}
