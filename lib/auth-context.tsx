"use client"

import type React from "react"
import { createContext, useContext, useState, useEffect } from "react"

interface User {
  email: string
  isHealthcare: boolean
}

interface AuthContextType {
  user: User | null
  isLoading: boolean
  login: (email: string, password: string) => Promise<void>
  logout: () => void
  setAsViewer: () => void
}

const AuthContext = createContext<AuthContextType | undefined>(undefined)

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null)
  const [isLoading, setIsLoading] = useState(true)

  useEffect(() => {
    const savedUser = localStorage.getItem("healthcare_user")
    if (savedUser) {
      try {
        setUser(JSON.parse(savedUser))
      } catch (error) {
        console.error("Failed to parse user data:", error)
        localStorage.removeItem("healthcare_user")
      }
    }
    setIsLoading(false)
  }, [])

  const login = async (email: string, password: string) => {
    if (!email || !password) {
      throw new Error("Email and password are required")
    }

    // Simulate authentication - in production, call your backend
    const userData: User = { email, isHealthcare: true }
    localStorage.setItem("healthcare_user", JSON.stringify(userData))
    setUser(userData)
  }

  const logout = () => {
    localStorage.removeItem("healthcare_user")
    setUser(null)
  }

  const setAsViewer = () => {
    const viewerData: User = { email: "viewer@healthcare.local", isHealthcare: false }
    localStorage.setItem("healthcare_user", JSON.stringify(viewerData))
    setUser(viewerData)
  }

  return <AuthContext.Provider value={{ user, isLoading, login, logout, setAsViewer }}>{children}</AuthContext.Provider>
}

export const useAuth = () => {
  const context = useContext(AuthContext)
  if (context === undefined) {
    throw new Error("useAuth must be used within an AuthProvider")
  }
  return context
}
