export interface Patient {
  id: string
  name: string
  dob: string
  region: string
  city: string
  condition: string
  severity: "Low" | "Medium" | "High"
  contact: string
  admissionDate: string
  timestamp: string
}

export interface Resource {
  id: string
  name: string
  category: string
  stock: number
  unit: string
  threshold: number
  usageRate: number
  timestamp: string
}

export interface Alert {
  id: string
  title: string
  description: string
  priority: "high" | "medium" | "low"
  actionLabel?: string
  timestamp: string
}

export interface DiseaseData {
  id: string
  location: string
  disease: string
  severity: "Low" | "Medium" | "High"
  coordinates?: [number, number]
  timestamp: string
}

export interface User {
  email: string
  isHealthcare: boolean
}
