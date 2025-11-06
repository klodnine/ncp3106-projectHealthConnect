"use client"

import type React from "react"
import { useState, useEffect, useRef } from "react"
import { Plus, LogOut, Info, AlertTriangle, AlertCircle, TrendingUp, X, ArrowLeft, Eye, UserCheck } from "lucide-react"

// Disease map imports
declare global {
  interface Window {
    L: any
  }
}

export default function HealthcareDashboard() {
  // View states
  const [currentView, setCurrentView] = useState<"role" | "login" | "dashboard">("role")
  const [activeTab, setActiveTab] = useState("dashboard")
  const [isHealthcareWorker, setIsHealthcareWorker] = useState(false)
  const [userEmail, setUserEmail] = useState("")

  // Form states
  const [loginForm, setLoginForm] = useState({ email: "", password: "" })
  const [loginError, setLoginError] = useState("")

  // Data states
  const [patientsData, setPatientsData] = useState<any[]>([])
  const [resourcesData, setResourcesData] = useState<any[]>([])
  const [alertsData, setAlertsData] = useState<any[]>([])
  const [diseaseData, setDiseaseData] = useState<any[]>([])

  // Form visibility states
  const [showPatientForm, setShowPatientForm] = useState(false)
  const [showResourceForm, setShowResourceForm] = useState(false)
  const [showAlertForm, setShowAlertForm] = useState(false)

  // Map refs
  const mapContainerRef = useRef<any>(null)
  const diseaseMapRef = useRef<any>(null)

  // Load data from localStorage on mount
  useEffect(() => {
    const savedUser = localStorage.getItem("healthcare_user")
    if (savedUser) {
      const user = JSON.parse(savedUser)
      setUserEmail(user.email)
      setIsHealthcareWorker(user.isHealthcare)
      setCurrentView("dashboard")
      setPatientsData(JSON.parse(localStorage.getItem("patients_data") || "[]"))
      setResourcesData(JSON.parse(localStorage.getItem("resources_data") || "[]"))
      setAlertsData(JSON.parse(localStorage.getItem("alerts_data") || "[]"))
      setDiseaseData(JSON.parse(localStorage.getItem("disease_data") || "[]"))
    }
  }, [])

  // Role selection handlers
  const handleYesHealthcare = () => {
    setCurrentView("login")
  }

  const handleNoHealthcare = () => {
    setIsHealthcareWorker(false)
    setCurrentView("dashboard")
    setPatientsData(JSON.parse(localStorage.getItem("patients_data") || "[]"))
    setResourcesData(JSON.parse(localStorage.getItem("resources_data") || "[]"))
    setAlertsData(JSON.parse(localStorage.getItem("alerts_data") || "[]"))
    setDiseaseData(JSON.parse(localStorage.getItem("disease_data") || "[]"))
  }

  // Login handler
  const handleLogin = (e: React.FormEvent) => {
    e.preventDefault()
    if (!loginForm.email || !loginForm.password) {
      setLoginError("Please fill in all fields")
      return
    }

    // Simple demo authentication
    const user = { email: loginForm.email, isHealthcare: true }
    localStorage.setItem("healthcare_user", JSON.stringify(user))
    setUserEmail(loginForm.email)
    setIsHealthcareWorker(true)
    setCurrentView("dashboard")
    setLoginForm({ email: "", password: "" })
    setLoginError("")

    setPatientsData(JSON.parse(localStorage.getItem("patients_data") || "[]"))
    setResourcesData(JSON.parse(localStorage.getItem("resources_data") || "[]"))
    setAlertsData(JSON.parse(localStorage.getItem("alerts_data") || "[]"))
    setDiseaseData(JSON.parse(localStorage.getItem("disease_data") || "[]"))
  }

  // Logout handler
  const handleLogout = () => {
    localStorage.removeItem("healthcare_user")
    setUserEmail("")
    setIsHealthcareWorker(false)
    setCurrentView("role")
    setActiveTab("dashboard")
  }

  // Patient handlers
  const handleAddPatient = (e: React.FormEvent) => {
    e.preventDefault()
    const formData = new FormData(e.target as HTMLFormElement)
    const patient = {
      id: `MED-${Date.now()}`,
      name: formData.get("patientName"),
      dob: formData.get("patientDOB"),
      region: formData.get("patientRegion"),
      city: formData.get("patientCity"),
      condition: formData.get("patientCondition"),
      severity: formData.get("patientSeverity"),
      contact: formData.get("patientContact"),
      admissionDate: formData.get("patientAdmission"),
      timestamp: new Date().toISOString(),
    }

    const updatedPatients = [...patientsData, patient]
    setPatientsData(updatedPatients)
    localStorage.setItem("patients_data", JSON.stringify(updatedPatients))
    setShowPatientForm(false)
    ;(e.target as HTMLFormElement).reset()
  }

  // Resource handlers
  const handleAddResource = (e: React.FormEvent) => {
    e.preventDefault()
    const formData = new FormData(e.target as HTMLFormElement)
    const resource = {
      id: `RES-${Date.now()}`,
      name: formData.get("resourceName"),
      category: formData.get("resourceCategory"),
      stock: Number.parseInt(formData.get("resourceStock") as string),
      unit: formData.get("resourceUnit"),
      threshold: Number.parseInt(formData.get("resourceThreshold") as string),
      usageRate: Number.parseFloat(formData.get("resourceUsageRate") as string),
      timestamp: new Date().toISOString(),
    }

    const updatedResources = [...resourcesData, resource]
    setResourcesData(updatedResources)
    localStorage.setItem("resources_data", JSON.stringify(updatedResources))
    setShowResourceForm(false)
    ;(e.target as HTMLFormElement).reset()
  }

  // Alert handlers
  const handleAddAlert = (e: React.FormEvent) => {
    e.preventDefault()
    const formData = new FormData(e.target as HTMLFormElement)
    const alert = {
      id: `ALT-${Date.now()}`,
      title: formData.get("alertTitle"),
      description: formData.get("alertDescription"),
      priority: formData.get("alertPriority"),
      actionLabel: formData.get("alertActionLabel") || "Take Action",
      timestamp: new Date().toISOString(),
    }

    const updatedAlerts = [...alertsData, alert]
    setAlertsData(updatedAlerts)
    localStorage.setItem("alerts_data", JSON.stringify(updatedAlerts))
    setShowAlertForm(false)
    ;(e.target as HTMLFormElement).reset()
  }

  const handleDismissAlert = (id: string) => {
    const updated = alertsData.filter((a) => a.id !== id)
    setAlertsData(updated)
    localStorage.setItem("alerts_data", JSON.stringify(updated))
  }

  // Role selection view
  if (currentView === "role") {
    return (
      <div className="min-h-screen flex items-center justify-center bg-white relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-b from-white via-white/95 to-white/90 pointer-events-none"></div>
        <div className="relative z-10 w-full max-w-md px-6">
          <div className="text-center mb-12">
            <h1 className="text-5xl font-bold text-[#E50914] mb-2 tracking-tight">HEALTHCARE</h1>
            <p className="text-[#666666] text-sm">Community Dashboard</p>
          </div>

          <div className="bg-white/60 backdrop-blur-sm border border-[#E0E0E0] rounded-lg p-8 shadow-2xl">
            <h2 className="text-3xl font-semibold text-black mb-4 text-center">Welcome</h2>
            <p className="text-lg text-[#666666] mb-8 text-center">Are you a healthcare worker?</p>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <button
                onClick={handleYesHealthcare}
                className="h-32 bg-[#E50914] hover:bg-[#CC0812] text-white font-semibold text-xl rounded-lg transition-all hover:scale-105 flex flex-col items-center justify-center gap-3"
              >
                <UserCheck className="w-12 h-12" />
                <span>Yes, I am</span>
              </button>

              <button
                onClick={handleNoHealthcare}
                className="h-32 bg-[#FAFAFA] hover:bg-[#F0F0F0] border-2 border-[#E0E0E0] hover:border-[#E50914]/50 text-black font-semibold text-xl rounded-lg transition-all hover:scale-105 flex flex-col items-center justify-center gap-3"
              >
                <Eye className="w-12 h-12" />
                <span>No, just viewing</span>
              </button>
            </div>

            <div className="mt-8 pt-6 border-t border-[#E0E0E0]">
              <p className="text-[#666666] text-sm text-center leading-relaxed">
                Healthcare workers can input and manage data after authentication.
                <br />
                Viewers can access all dashboard metrics without an account.
              </p>
            </div>
          </div>
        </div>
      </div>
    )
  }

  // Login view
  if (currentView === "login") {
    return (
      <div className="min-h-screen flex items-center justify-center bg-white relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-b from-white via-white/95 to-white/90 pointer-events-none"></div>
        <div className="relative z-10 w-full max-w-md px-6">
          <div className="text-center mb-12">
            <h1 className="text-5xl font-bold text-[#E50914] mb-2 tracking-tight">HEALTHCARE</h1>
            <p className="text-[#666666] text-sm">Community Dashboard</p>
          </div>

          <div className="bg-white/60 backdrop-blur-sm border border-[#E0E0E0] rounded-lg p-8 shadow-2xl">
            <div className="flex items-center justify-between mb-6">
              <h2 className="text-2xl font-semibold text-black">Healthcare Worker Sign In</h2>
              <button
                onClick={() => setCurrentView("role")}
                className="text-[#666666] hover:text-black transition-colors"
              >
                <ArrowLeft className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleLogin} className="space-y-5">
              <div className="space-y-2">
                <label className="block text-sm font-medium text-black">Email</label>
                <input
                  type="email"
                  value={loginForm.email}
                  onChange={(e) => setLoginForm({ ...loginForm, email: e.target.value })}
                  className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black placeholder:text-[#666666] h-12 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                  placeholder="Enter your email"
                  required
                />
              </div>

              <div className="space-y-2">
                <label className="block text-sm font-medium text-black">Password</label>
                <input
                  type="password"
                  value={loginForm.password}
                  onChange={(e) => setLoginForm({ ...loginForm, password: e.target.value })}
                  className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black placeholder:text-[#666666] h-12 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                  placeholder="Enter your password"
                />
              </div>

              {loginError && (
                <div className="text-sm text-red-700 bg-red-50 border border-red-200 rounded px-3 py-2">
                  {loginError}
                </div>
              )}

              <button
                type="submit"
                className="w-full h-12 bg-[#E50914] hover:bg-[#CC0812] text-white font-semibold text-base rounded-md transition-colors"
              >
                Sign In
              </button>
            </form>

            <div className="mt-6 flex items-center justify-between text-sm">
              <label className="flex items-center gap-2 text-[#666666] cursor-pointer">
                <input type="checkbox" className="rounded border-[#E0E0E0]" />
                Remember me
              </label>
              <a href="#" className="text-[#666666] hover:text-black transition-colors">
                Need help?
              </a>
            </div>

            <div className="mt-8 pt-6 border-t border-[#E0E0E0]">
              <p className="text-[#666666] text-sm text-center">
                New healthcare worker?
                <a href="#" className="text-black hover:underline font-medium ml-1">
                  Sign up now
                </a>
              </p>
            </div>
          </div>

          <p className="text-center text-[#666666] text-xs mt-8 leading-relaxed">
            This page is protected by authentication to ensure the security of your healthcare data.
          </p>
        </div>
      </div>
    )
  }

  // Dashboard view
  return (
    <div className="min-h-screen bg-white">
      {/* Header */}
      <header className="border-b border-[#E0E0E0] bg-[#F5F5F5]/50 backdrop-blur-sm sticky top-0 z-50">
        <div className="container mx-auto px-6 py-4 flex items-center justify-between">
          <h1 className="text-2xl font-bold text-[#E50914]">Healthcare Community Dashboard</h1>
          <div className="flex items-center gap-4">
            <span className="px-3 py-1 text-sm rounded-full bg-green-500/10 text-green-500 border border-green-500/20">
              {isHealthcareWorker ? "Healthcare Worker" : "Viewer"}
            </span>
            <span className="px-3 py-1 text-sm rounded-full bg-green-500/10 text-green-500 border border-green-500/20">
              System Online
            </span>
            {isHealthcareWorker && (
              <button
                onClick={handleLogout}
                className="flex items-center gap-2 px-4 py-2 text-sm text-[#666666] hover:text-black transition-colors rounded-md hover:bg-[#FAFAFA]"
              >
                <LogOut className="w-4 h-4" />
                <span>Log Out</span>
              </button>
            )}
          </div>
        </div>
      </header>

      {/* Navigation tabs */}
      <nav className="border-b border-[#E0E0E0] bg-[#F5F5F5]/30">
        <div className="container mx-auto px-6">
          <div className="flex gap-1">
            {["dashboard", "patients", "resources", "training"].map((tab) => (
              <button
                key={tab}
                onClick={() => setActiveTab(tab)}
                className={`px-6 py-3 text-sm font-medium capitalize transition-colors relative ${
                  activeTab === tab ? "text-[#E50914] border-b-2 border-[#E50914]" : "text-[#666666] hover:text-black"
                }`}
              >
                {tab}
              </button>
            ))}
          </div>
        </div>
      </nav>

      {/* Main content */}
      <main className="container mx-auto px-6 py-8">
        {!isHealthcareWorker && (
          <div className="mb-6 p-4 bg-blue-500/10 border border-blue-500/20 rounded-lg">
            <div className="flex items-center gap-3">
              <Info className="w-5 h-5 text-blue-500" />
              <div>
                <h3 className="text-sm font-semibold text-blue-500">Viewing Mode</h3>
                <p className="text-xs text-[#666666] mt-1">
                  You are viewing the dashboard in read-only mode. To input or edit data, please sign in as a healthcare
                  worker.
                </p>
              </div>
            </div>
          </div>
        )}

        {/* Dashboard Tab */}
        {activeTab === "dashboard" && (
          <div className="space-y-8">
            {/* Stats cards */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
                <h3 className="text-sm text-[#666666] mb-4">Total Patients</h3>
                <div className="text-3xl font-bold text-black">{patientsData.length}</div>
                <p className="text-sm text-green-500 flex items-center gap-1 mt-2">
                  <TrendingUp className="w-4 h-4" />
                  <span>Active records</span>
                </p>
              </div>

              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
                <h3 className="text-sm text-[#666666] mb-4">Priority Alerts</h3>
                <div className="text-3xl font-bold text-black">{alertsData.length}</div>
                <p className="text-sm text-red-500 flex items-center gap-1 mt-2">
                  <AlertTriangle className="w-4 h-4" />
                  <span>Requires attention</span>
                </p>
              </div>

              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
                <h3 className="text-sm text-[#666666] mb-4">Resources</h3>
                <div className="text-3xl font-bold text-black">{resourcesData.length}</div>
                <p className="text-sm text-[#666666] mt-2">Items monitored</p>
              </div>

              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
                <h3 className="text-sm text-[#666666] mb-4">Low Stock</h3>
                <div className="text-3xl font-bold text-black">
                  {resourcesData.filter((r) => r.stock < r.threshold).length}
                </div>
                <p className="text-sm text-red-500 flex items-center gap-1 mt-2">
                  <AlertCircle className="w-4 h-4" />
                  <span>Needs attention</span>
                </p>
              </div>
            </div>

            {/* Priority Alerts Section */}
            <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
              <div className="flex items-center justify-between mb-6">
                <h2 className="text-xl font-semibold text-black">Priority Alerts</h2>
                {isHealthcareWorker && (
                  <button
                    onClick={() => setShowAlertForm(!showAlertForm)}
                    className="px-4 py-2 text-sm bg-[#E50914] hover:bg-[#CC0812] text-white rounded-md transition-colors flex items-center gap-2"
                  >
                    <Plus className="w-4 h-4" />
                    Add Alert
                  </button>
                )}
              </div>

              {showAlertForm && isHealthcareWorker && (
                <form
                  onSubmit={handleAddAlert}
                  className="mb-6 bg-[#FAFAFA]/30 border border-[#E0E0E0] rounded-lg p-6 space-y-4"
                >
                  <h3 className="text-lg font-semibold text-black">New Priority Alert</h3>

                  <div className="space-y-2">
                    <label className="block text-sm font-medium text-black">Alert Title</label>
                    <input
                      type="text"
                      name="alertTitle"
                      placeholder="e.g., Medication Stock Low"
                      className="w-full bg-white border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                      required
                    />
                  </div>

                  <div className="space-y-2">
                    <label className="block text-sm font-medium text-black">Description</label>
                    <textarea
                      name="alertDescription"
                      rows={2}
                      placeholder="Provide details about this alert..."
                      className="w-full bg-white border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                      required
                    ></textarea>
                  </div>

                  <div className="space-y-2">
                    <label className="block text-sm font-medium text-black">Priority Level</label>
                    <select
                      name="alertPriority"
                      className="w-full bg-white border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                      required
                    >
                      <option value="high">High - Requires immediate action</option>
                      <option value="medium">Medium - Needs attention soon</option>
                      <option value="low">Low - General reminder</option>
                    </select>
                  </div>

                  <div className="flex items-center gap-3">
                    <button
                      type="submit"
                      className="px-6 py-2 bg-[#E50914] hover:bg-[#CC0812] text-white rounded-md transition-colors"
                    >
                      Add Alert
                    </button>
                    <button
                      type="button"
                      onClick={() => setShowAlertForm(false)}
                      className="px-6 py-2 bg-[#FAFAFA] hover:bg-[#F0F0F0] text-black rounded-md transition-colors"
                    >
                      Cancel
                    </button>
                  </div>
                </form>
              )}

              {alertsData.length === 0 ? (
                <p className="text-sm text-[#666666] text-center py-8">
                  No priority alerts. Healthcare workers can add new alerts using the button above.
                </p>
              ) : (
                <div className="space-y-4">
                  {alertsData.map((alert) => {
                    const priorityConfig = {
                      high: {
                        bg: "bg-red-500/10",
                        border: "border-red-500",
                        text: "text-red-500",
                        icon: AlertTriangle,
                      },
                      medium: {
                        bg: "bg-yellow-500/10",
                        border: "border-yellow-500",
                        text: "text-yellow-500",
                        icon: AlertCircle,
                      },
                      low: { bg: "bg-blue-500/10", border: "border-blue-500", text: "text-blue-500", icon: Info },
                    }
                    const config = priorityConfig[alert.priority as keyof typeof priorityConfig]
                    const IconComponent = config.icon

                    return (
                      <div
                        key={alert.id}
                        className={`flex items-start justify-between p-4 ${config.bg} rounded-lg border-l-4 ${config.border}`}
                      >
                        <div className="flex items-start gap-3 flex-1">
                          <IconComponent className={`w-5 h-5 ${config.text} flex-shrink-0 mt-0.5`} />
                          <div className="flex-1">
                            <h4 className="font-semibold text-black mb-1">{alert.title}</h4>
                            <p className="text-sm text-[#666666] mb-2">{alert.description}</p>
                            <div className="flex items-center gap-3 text-xs text-[#666666]">
                              <span className={`uppercase font-medium ${config.text}`}>{alert.priority} Priority</span>
                              <span>•</span>
                              <span>{new Date(alert.timestamp).toLocaleString()}</span>
                            </div>
                          </div>
                        </div>
                        <div className="flex items-center gap-2 ml-4">
                          <button className="px-4 py-2 text-sm bg-[#E50914] hover:bg-[#CC0812] text-white rounded-md transition-colors whitespace-nowrap">
                            {alert.actionLabel || "Take Action"}
                          </button>
                          {isHealthcareWorker && (
                            <button
                              onClick={() => handleDismissAlert(alert.id)}
                              className="p-2 text-[#666666] hover:text-black transition-colors"
                            >
                              <X className="w-4 h-4" />
                            </button>
                          )}
                        </div>
                      </div>
                    )
                  })}
                </div>
              )}
            </div>
          </div>
        )}

        {/* Patients Tab */}
        {activeTab === "patients" && (
          <div className="space-y-8">
            {isHealthcareWorker && (
              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
                <h2 className="text-xl font-semibold text-black mb-6">Add New Patient</h2>
                <form onSubmit={handleAddPatient} className="space-y-6">
                  <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">Patient Name</label>
                      <input
                        type="text"
                        name="patientName"
                        placeholder="Enter full name"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                        required
                      />
                    </div>
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">Date of Birth</label>
                      <input
                        type="date"
                        name="patientDOB"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                        required
                      />
                    </div>
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">Medical ID</label>
                      <input
                        type="text"
                        placeholder="Auto-generated"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md"
                        disabled
                      />
                    </div>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-[#E0E0E0] pt-6">
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">Region</label>
                      <select
                        name="patientRegion"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                        required
                      >
                        <option value="">Select region</option>
                        <option>Metro Manila</option>
                        <option>Cavite</option>
                        <option>Laguna</option>
                      </select>
                    </div>
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">City</label>
                      <input
                        type="text"
                        name="patientCity"
                        placeholder="City"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                      />
                    </div>
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">Condition</label>
                      <select
                        name="patientCondition"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                        required
                      >
                        <option value="">Select condition</option>
                        <option>Dengue Fever</option>
                        <option>Influenza Type A</option>
                        <option>Tuberculosis</option>
                        <option>COVID-19</option>
                      </select>
                    </div>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">Severity</label>
                      <select
                        name="patientSeverity"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                        required
                      >
                        <option value="">Select severity</option>
                        <option value="Low">Low - Mild</option>
                        <option value="Medium">Medium - Moderate</option>
                        <option value="High">High - Critical</option>
                      </select>
                    </div>
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">Contact</label>
                      <input
                        type="tel"
                        name="patientContact"
                        placeholder="Phone number"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                        required
                      />
                    </div>
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">Admission Date</label>
                      <input
                        type="date"
                        name="patientAdmission"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                        required
                      />
                    </div>
                  </div>

                  <button
                    type="submit"
                    className="px-6 py-2 bg-[#E50914] hover:bg-[#CC0812] text-white rounded-md transition-colors"
                  >
                    Add Patient
                  </button>
                </form>
              </div>
            )}

            {patientsData.length === 0 ? (
              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-12">
                <p className="text-center text-[#666666]">
                  No patient records yet.{" "}
                  {isHealthcareWorker ? "Add a new patient above." : "Healthcare workers can add patients."}
                </p>
              </div>
            ) : (
              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6 overflow-x-auto">
                <table className="w-full">
                  <thead>
                    <tr className="border-b border-[#E0E0E0]">
                      <th className="text-left py-3 px-4 text-sm font-medium text-[#666666]">Patient ID</th>
                      <th className="text-left py-3 px-4 text-sm font-medium text-[#666666]">Name</th>
                      <th className="text-left py-3 px-4 text-sm font-medium text-[#666666]">Condition</th>
                      <th className="text-left py-3 px-4 text-sm font-medium text-[#666666]">Severity</th>
                      <th className="text-left py-3 px-4 text-sm font-medium text-[#666666]">Admission Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    {patientsData.map((patient) => (
                      <tr key={patient.id} className="border-b border-[#E0E0E0] hover:bg-white/50">
                        <td className="py-3 px-4 text-sm text-black">{patient.id}</td>
                        <td className="py-3 px-4 text-sm text-black">{patient.name}</td>
                        <td className="py-3 px-4 text-sm text-black">{patient.condition}</td>
                        <td className="py-3 px-4 text-sm">
                          <span
                            className={`px-2 py-1 rounded text-xs font-medium ${
                              patient.severity === "High"
                                ? "bg-red-100 text-red-800"
                                : patient.severity === "Medium"
                                  ? "bg-yellow-100 text-yellow-800"
                                  : "bg-green-100 text-green-800"
                            }`}
                          >
                            {patient.severity}
                          </span>
                        </td>
                        <td className="py-3 px-4 text-sm text-black">{patient.admissionDate}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        )}

        {/* Resources Tab */}
        {activeTab === "resources" && (
          <div className="space-y-8">
            {isHealthcareWorker && (
              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
                <h2 className="text-xl font-semibold text-black mb-6">Add New Resource</h2>
                <form onSubmit={handleAddResource} className="space-y-6">
                  <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">Resource Name</label>
                      <input
                        type="text"
                        name="resourceName"
                        placeholder="e.g., Insulin, Face Masks"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                        required
                      />
                    </div>
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">Category</label>
                      <select
                        name="resourceCategory"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                        required
                      >
                        <option value="">Select category</option>
                        <option>Medication</option>
                        <option>Medical Supplies</option>
                        <option>Equipment</option>
                        <option>PPE</option>
                      </select>
                    </div>
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">Current Stock</label>
                      <input
                        type="number"
                        name="resourceStock"
                        placeholder="Quantity"
                        min="0"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                        required
                      />
                    </div>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">Unit</label>
                      <input
                        type="text"
                        name="resourceUnit"
                        placeholder="e.g., boxes, bottles"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                        required
                      />
                    </div>
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">Minimum Threshold</label>
                      <input
                        type="number"
                        name="resourceThreshold"
                        placeholder="Alert level"
                        min="0"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                        required
                      />
                    </div>
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-black">Daily Usage</label>
                      <input
                        type="number"
                        name="resourceUsageRate"
                        placeholder="Units per day"
                        min="0"
                        step="0.1"
                        className="w-full bg-[#FAFAFA] border border-[#E0E0E0] text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E50914]"
                        required
                      />
                    </div>
                  </div>

                  <button
                    type="submit"
                    className="px-6 py-2 bg-[#E50914] hover:bg-[#CC0812] text-white rounded-md transition-colors"
                  >
                    Add Resource
                  </button>
                </form>
              </div>
            )}

            <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
                <h3 className="text-sm text-[#666666] mb-4">Total Resources</h3>
                <div className="text-3xl font-bold text-black">{resourcesData.length}</div>
                <p className="text-sm text-[#666666] mt-2">Items monitored</p>
              </div>
              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
                <h3 className="text-sm text-[#666666] mb-4">Low Stock Items</h3>
                <div className="text-3xl font-bold text-black">
                  {resourcesData.filter((r) => r.stock < r.threshold).length}
                </div>
                <p className="text-sm text-red-500 flex items-center gap-1 mt-2">
                  <AlertCircle className="w-4 h-4" />
                  <span>Needs attention</span>
                </p>
              </div>
              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
                <h3 className="text-sm text-[#666666] mb-4">Total Items</h3>
                <div className="text-3xl font-bold text-black">
                  {resourcesData.reduce((sum, r) => sum + r.stock, 0)}
                </div>
                <p className="text-sm text-[#666666] mt-2">In stock</p>
              </div>
              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
                <h3 className="text-sm text-[#666666] mb-4">Categories</h3>
                <div className="text-3xl font-bold text-black">
                  {new Set(resourcesData.map((r) => r.category)).size}
                </div>
                <p className="text-sm text-[#666666] mt-2">Active types</p>
              </div>
            </div>

            {resourcesData.length === 0 ? (
              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-12">
                <p className="text-center text-[#666666]">
                  No resources yet.{" "}
                  {isHealthcareWorker ? "Add a new resource above." : "Healthcare workers can add resources."}
                </p>
              </div>
            ) : (
              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6 overflow-x-auto">
                <table className="w-full">
                  <thead>
                    <tr className="border-b border-[#E0E0E0]">
                      <th className="text-left py-3 px-4 text-sm font-medium text-[#666666]">Resource ID</th>
                      <th className="text-left py-3 px-4 text-sm font-medium text-[#666666]">Name</th>
                      <th className="text-left py-3 px-4 text-sm font-medium text-[#666666]">Category</th>
                      <th className="text-left py-3 px-4 text-sm font-medium text-[#666666]">Stock</th>
                      <th className="text-left py-3 px-4 text-sm font-medium text-[#666666]">Threshold</th>
                      <th className="text-left py-3 px-4 text-sm font-medium text-[#666666]">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    {resourcesData.map((resource) => (
                      <tr key={resource.id} className="border-b border-[#E0E0E0] hover:bg-white/50">
                        <td className="py-3 px-4 text-sm text-black">{resource.id}</td>
                        <td className="py-3 px-4 text-sm text-black">{resource.name}</td>
                        <td className="py-3 px-4 text-sm text-black">{resource.category}</td>
                        <td className="py-3 px-4 text-sm text-black">
                          {resource.stock} {resource.unit}
                        </td>
                        <td className="py-3 px-4 text-sm text-black">{resource.threshold}</td>
                        <td className="py-3 px-4 text-sm">
                          <span
                            className={`px-2 py-1 rounded text-xs font-medium ${
                              resource.stock < resource.threshold
                                ? "bg-red-100 text-red-800"
                                : "bg-green-100 text-green-800"
                            }`}
                          >
                            {resource.stock < resource.threshold ? "Low Stock" : "OK"}
                          </span>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        )}

        {/* Training Tab */}
        {activeTab === "training" && (
          <div className="space-y-8">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
                <h3 className="text-sm text-[#666666] mb-4">Available Modules</h3>
                <div className="text-3xl font-bold text-black">12</div>
                <p className="text-sm text-[#666666] mt-2">Online training resources</p>
              </div>
              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
                <h3 className="text-sm text-[#666666] mb-4">Free Resources</h3>
                <div className="text-3xl font-bold text-black">8</div>
                <p className="text-sm text-green-500 flex items-center gap-1 mt-2">No cost access</p>
              </div>
              <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
                <h3 className="text-sm text-[#666666] mb-4">With Certificates</h3>
                <div className="text-3xl font-bold text-black">4</div>
                <p className="text-sm text-[#666666] mt-2">Certificate available</p>
              </div>
            </div>

            <div className="bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg p-6">
              <h2 className="text-xl font-semibold text-black mb-6">Healthcare Training Modules</h2>
              <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                {[
                  {
                    title: "WHO COVID-19 Clinical Management",
                    provider: "WHO",
                    duration: "4-6 hours",
                    isFree: true,
                    hasCert: false,
                  },
                  {
                    title: "Basic Life Support (BLS)",
                    provider: "American Heart Association",
                    duration: "2-3 hours",
                    isFree: false,
                    hasCert: true,
                  },
                  {
                    title: "Infection Prevention and Control",
                    provider: "CDC",
                    duration: "3-4 hours",
                    isFree: true,
                    hasCert: false,
                  },
                  {
                    title: "Patient Communication Skills",
                    provider: "Healthcare Institute",
                    duration: "2 hours",
                    isFree: true,
                    hasCert: true,
                  },
                  {
                    title: "Electronic Health Records (EHR)",
                    provider: "Medical Training Group",
                    duration: "3 hours",
                    isFree: false,
                    hasCert: true,
                  },
                  {
                    title: "Healthcare Data Security",
                    provider: "Security Council",
                    duration: "2.5 hours",
                    isFree: true,
                    hasCert: false,
                  },
                ].map((module, idx) => (
                  <div
                    key={idx}
                    className="bg-white border border-[#E0E0E0] rounded-lg p-6 hover:shadow-lg transition-shadow"
                  >
                    <div className="flex items-start justify-between mb-3">
                      <h3 className="font-semibold text-black text-sm flex-1">{module.title}</h3>
                      {module.isFree && (
                        <span className="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Free</span>
                      )}
                    </div>
                    <p className="text-xs text-[#666666] mb-4">{module.provider}</p>
                    <div className="flex items-center gap-4 text-xs text-[#666666]">
                      <span>Duration: {module.duration}</span>
                      {module.hasCert && <span className="text-blue-600">Certificate</span>}
                    </div>
                    <button className="mt-4 w-full px-3 py-2 bg-[#E50914] hover:bg-[#CC0812] text-white rounded text-sm transition-colors">
                      Enroll
                    </button>
                  </div>
                ))}
              </div>
            </div>
          </div>
        )}
      </main>
    </div>
  )
}
