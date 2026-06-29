import AlertMessage from './AlertMessage'
import BrandHeader from './BrandHeader'

function LoginView({
  error,
  isLoading,
  loginForm,
  onLogin,
  onUpdateLoginForm,
}) {
  return (
    <main className="app-shell login-screen">
      <section className="login-panel">
        <BrandHeader />
        <form className="login-form" onSubmit={onLogin}>
          <div>
            <h1>Sign in</h1>
            <p>Access documents and grouped document packages.</p>
          </div>

          <label>
            Email
            <input
              type="email"
              value={loginForm.email}
              onChange={(event) =>
                onUpdateLoginForm({ ...loginForm, email: event.target.value })
              }
              placeholder="ahmed@cmc.ma"
              required
            />
          </label>

          <label>
            Password
            <input
              type="password"
              value={loginForm.password}
              onChange={(event) =>
                onUpdateLoginForm({ ...loginForm, password: event.target.value })
              }
              placeholder="Enter your password"
              required
            />
          </label>

          <AlertMessage message={error} type="error" />

          <button type="submit" className="primary-button" disabled={isLoading}>
            {isLoading ? 'Signing in...' : 'Login'}
          </button>
        </form>
      </section>
    </main>
  )
}

export default LoginView
