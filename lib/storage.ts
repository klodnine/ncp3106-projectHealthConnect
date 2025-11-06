import type { Patient, Resource, Alert, DiseaseData } from "./types"

const STORAGE_KEYS = {
  PATIENTS: "healthcare_patients",
  RESOURCES: "healthcare_resources",
  ALERTS: "healthcare_alerts",
  DISEASE_DATA: "healthcare_disease_data",
}

export const patientStorage = {
  getAll: (): Patient[] => {
    try {
      const data = localStorage.getItem(STORAGE_KEYS.PATIENTS)
      return data ? JSON.parse(data) : []
    } catch {
      return []
    }
  },

  add: (patient: Patient): void => {
    const patients = patientStorage.getAll()
    patients.push(patient)
    localStorage.setItem(STORAGE_KEYS.PATIENTS, JSON.stringify(patients))
  },

  update: (id: string, updates: Partial<Patient>): void => {
    const patients = patientStorage.getAll()
    const index = patients.findIndex((p) => p.id === id)
    if (index !== -1) {
      patients[index] = { ...patients[index], ...updates }
      localStorage.setItem(STORAGE_KEYS.PATIENTS, JSON.stringify(patients))
    }
  },

  delete: (id: string): void => {
    const patients = patientStorage.getAll().filter((p) => p.id !== id)
    localStorage.setItem(STORAGE_KEYS.PATIENTS, JSON.stringify(patients))
  },

  clear: (): void => {
    localStorage.removeItem(STORAGE_KEYS.PATIENTS)
  },
}

export const resourceStorage = {
  getAll: (): Resource[] => {
    try {
      const data = localStorage.getItem(STORAGE_KEYS.RESOURCES)
      return data ? JSON.parse(data) : []
    } catch {
      return []
    }
  },

  add: (resource: Resource): void => {
    const resources = resourceStorage.getAll()
    resources.push(resource)
    localStorage.setItem(STORAGE_KEYS.RESOURCES, JSON.stringify(resources))
  },

  update: (id: string, updates: Partial<Resource>): void => {
    const resources = resourceStorage.getAll()
    const index = resources.findIndex((r) => r.id === id)
    if (index !== -1) {
      resources[index] = { ...resources[index], ...updates }
      localStorage.setItem(STORAGE_KEYS.RESOURCES, JSON.stringify(resources))
    }
  },

  delete: (id: string): void => {
    const resources = resourceStorage.getAll().filter((r) => r.id !== id)
    localStorage.setItem(STORAGE_KEYS.RESOURCES, JSON.stringify(resources))
  },

  clear: (): void => {
    localStorage.removeItem(STORAGE_KEYS.RESOURCES)
  },
}

export const alertStorage = {
  getAll: (): Alert[] => {
    try {
      const data = localStorage.getItem(STORAGE_KEYS.ALERTS)
      return data ? JSON.parse(data) : []
    } catch {
      return []
    }
  },

  add: (alert: Alert): void => {
    const alerts = alertStorage.getAll()
    alerts.push(alert)
    localStorage.setItem(STORAGE_KEYS.ALERTS, JSON.stringify(alerts))
  },

  delete: (id: string): void => {
    const alerts = alertStorage.getAll().filter((a) => a.id !== id)
    localStorage.setItem(STORAGE_KEYS.ALERTS, JSON.stringify(alerts))
  },

  clear: (): void => {
    localStorage.removeItem(STORAGE_KEYS.ALERTS)
  },
}

export const diseaseStorage = {
  getAll: (): DiseaseData[] => {
    try {
      const data = localStorage.getItem(STORAGE_KEYS.DISEASE_DATA)
      return data ? JSON.parse(data) : []
    } catch {
      return []
    }
  },

  add: (data: DiseaseData): void => {
    const diseaseData = diseaseStorage.getAll()
    diseaseData.push(data)
    localStorage.setItem(STORAGE_KEYS.DISEASE_DATA, JSON.stringify(diseaseData))
  },

  clear: (): void => {
    localStorage.removeItem(STORAGE_KEYS.DISEASE_DATA)
  },
}
