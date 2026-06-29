import { useCallback, useEffect, useState } from 'react'
import { apiRequest, downloadFromApi } from './api'
import DashboardView from './components/DashboardView'
import LoginView from './components/LoginView'
import './App.css'

const emptyLoginForm = {
  email: '',
  password: '',
}

function App() {
  const [routePath, setRoutePath] = useState(() => window.location.pathname)
  const [token, setToken] = useState(() => localStorage.getItem('cmc_token') || '')
  const [user, setUser] = useState(() => {
    const storedUser = localStorage.getItem('cmc_user')
    return storedUser ? JSON.parse(storedUser) : null
  })
  const [loginForm, setLoginForm] = useState(emptyLoginForm)
  const [activeTab, setActiveTab] = useState('documents')
  const [documents, setDocuments] = useState([])
  const [groups, setGroups] = useState([])
  const [selectedItem, setSelectedItem] = useState(null)
  const [fieldTitle, setFieldTitle] = useState('')
  const [fields, setFields] = useState([])
  const [formValues, setFormValues] = useState({})
  const [downloadUrl, setDownloadUrl] = useState('')
  const [message, setMessage] = useState('')
  const [error, setError] = useState('')
  const [isLoading, setIsLoading] = useState(false)
  const [isGenerating, setIsGenerating] = useState(false)

  const currentItems = activeTab === 'documents' ? documents : groups
  const activeLabel = activeTab === 'documents' ? 'document' : 'group'

  const navigate = useCallback((path) => {
    window.history.pushState({}, '', path)
    setRoutePath(path)
  }, [])

  const loadLists = useCallback(async () => {
    setError('')
    setIsLoading(true)

    try {
      const [documentList, groupList] = await Promise.all([
        apiRequest('/documents', {}, token),
        apiRequest('/groups', {}, token),
      ])

      setDocuments(documentList)
      setGroups(groupList)
    } catch (requestError) {
      setError(requestError.message)
    } finally {
      setIsLoading(false)
    }
  }, [token])

  useEffect(() => {
    if (token) {
      const timer = window.setTimeout(() => {
        loadLists()
      }, 0)

      return () => window.clearTimeout(timer)
    }
  }, [loadLists, token])

  useEffect(() => {
    function handlePopState() {
      setRoutePath(window.location.pathname)
    }

    window.addEventListener('popstate', handlePopState)

    return () => window.removeEventListener('popstate', handlePopState)
  }, [])

  useEffect(() => {
    if (!token && routePath !== '/login') {
      const timer = window.setTimeout(() => {
        navigate('/login')
      }, 0)

      return () => window.clearTimeout(timer)
    }

    if (token && (routePath === '/' || routePath === '/login')) {
      const timer = window.setTimeout(() => {
        navigate('/documents')
      }, 0)

      return () => window.clearTimeout(timer)
    }
  }, [navigate, routePath, token])

  const loadFields = useCallback(
    async (item, section = activeTab, shouldNavigate = true) => {
      setError('')
      setMessage('')
      setDownloadUrl('')
      setSelectedItem(item)
      setActiveTab(section)
      setIsLoading(true)

      if (shouldNavigate) {
        navigate(`/${section}/${item.id}`)
      }

      try {
        const data = await apiRequest(`/${section}/${item.id}/fields`, {}, token)
        const loadedFields = data.fields || []

        setFields(loadedFields)
        setFieldTitle(data.document || data.group || item.name)
        setFormValues(createInitialValues(loadedFields))
      } catch (requestError) {
        setError(requestError.message)
      } finally {
        setIsLoading(false)
      }
    },
    [activeTab, navigate, token],
  )

  useEffect(() => {
    if (!token) {
      return
    }

    const routeMatch = routePath.match(/^\/(documents|groups)\/(\d+)$/)

    if (!routeMatch) {
      return
    }

    const section = routeMatch[1]
    const itemId = Number(routeMatch[2])
    const items = section === 'documents' ? documents : groups
    const routeItem = items.find((item) => item.id === itemId)

    if (routeItem && (selectedItem?.id !== itemId || activeTab !== section)) {
      const timer = window.setTimeout(() => {
        loadFields(routeItem, section, false)
      }, 0)

      return () => window.clearTimeout(timer)
    }
  }, [
    activeTab,
    documents,
    groups,
    loadFields,
    routePath,
    selectedItem?.id,
    token,
  ])

  async function handleLogin(event) {
    event.preventDefault()
    setError('')
    setMessage('')
    setIsLoading(true)

    try {
      const data = await apiRequest('/auth/login', {
        method: 'POST',
        body: JSON.stringify(loginForm),
      })

      localStorage.setItem('cmc_token', data.token)
      localStorage.setItem('cmc_user', JSON.stringify(data.user))
      setToken(data.token)
      setUser(data.user)
      setLoginForm(emptyLoginForm)
      setMessage(`Welcome, ${data.user.name}.`)
      navigate('/documents')
    } catch (requestError) {
      setError(requestError.message)
    } finally {
      setIsLoading(false)
    }
  }

  async function handleLogout() {
    setError('')
    setMessage('')

    try {
      await apiRequest('/auth/logout', { method: 'POST' }, token)
    } catch {
      // The local session should still be cleared if the token is already invalid.
    }

    localStorage.removeItem('cmc_token')
    localStorage.removeItem('cmc_user')
    setToken('')
    setUser(null)
    setDocuments([])
    setGroups([])
    clearSelection()
    navigate('/login')
  }

  async function handleGenerate(event) {
    event.preventDefault()

    if (!selectedItem) {
      setError(`Choose a ${activeLabel} first.`)
      return
    }

    setError('')
    setMessage('')
    setDownloadUrl('')
    setIsGenerating(true)

    try {
      const data = await apiRequest(
        `/${activeTab}/${selectedItem.id}/generate`,
        {
          method: 'POST',
          body: JSON.stringify(formValues),
        },
        token,
      )

      setDownloadUrl(data.download_url || '')
      setMessage(data.message || 'Generated successfully.')
    } catch (requestError) {
      setError(requestError.message)
    } finally {
      setIsGenerating(false)
    }
  }

  async function handleDownload() {
    if (!selectedItem) {
      return
    }

    setError('')

    try {
      await downloadFromApi(
        `/${activeTab}/${selectedItem.id}/download`,
        getFallbackFileName(selectedItem.name, activeTab),
        token,
      )
    } catch (requestError) {
      setError(requestError.message)
    }
  }

  function clearSelection() {
    setSelectedItem(null)
    setFieldTitle('')
    setFields([])
    setFormValues({})
    setDownloadUrl('')
  }

  function changeTab(nextTab) {
    setActiveTab(nextTab)
    setError('')
    setMessage('')
    clearSelection()
    navigate(`/${nextTab}`)
  }

  function updateFieldValue(key, value) {
    setFormValues((currentValues) => ({
      ...currentValues,
      [key]: value,
    }))
  }

  if (!token) {
    return (
      <LoginView
        error={error}
        isLoading={isLoading}
        loginForm={loginForm}
        onLogin={handleLogin}
        onUpdateLoginForm={setLoginForm}
      />
    )
  }

  return (
    <DashboardView
      activeLabel={activeLabel}
      activeTab={activeTab}
      currentItems={currentItems}
      downloadUrl={downloadUrl}
      error={error}
      fields={fields}
      fieldTitle={fieldTitle}
      formValues={formValues}
      isGenerating={isGenerating}
      isLoading={isLoading}
      message={message}
      selectedItem={selectedItem}
      user={user}
      onChangeTab={changeTab}
      onDownload={handleDownload}
      onGenerate={handleGenerate}
      onHome={() => changeTab('documents')}
      onLoadFields={loadFields}
      onLogout={handleLogout}
      onRefresh={loadLists}
      onUpdateField={updateFieldValue}
    />
  )
}

function createInitialValues(fields) {
  return fields.reduce((values, field) => {
    values[field.key] = ''
    return values
  }, {})
}

function getFallbackFileName(itemName, activeTab) {
  const safeName = itemName.toLowerCase().replace(/[^a-z0-9]+/g, '-')
  return activeTab === 'groups' ? `${safeName}.zip` : `${safeName}.docx`
}

export default App
