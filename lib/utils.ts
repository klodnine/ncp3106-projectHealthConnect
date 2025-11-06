import { clsx, type ClassValue } from "clsx"
import { twMerge } from "tailwind-merge"

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs))
}

export function generateMedicalId(): string {
  return `MED-${Date.now().toString(36).toUpperCase()}-${Math.random().toString(36).substring(2, 8).toUpperCase()}`
}

export function formatDate(dateString: string): string {
  return new Date(dateString).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  })
}

export function getStockStatus(current: number, threshold: number): "low" | "warning" | "ok" {
  if (current < threshold) return "low"
  if (current < threshold * 1.5) return "warning"
  return "ok"
}

export function calculateDaysUntilStockout(current: number, usageRate: number): number {
  if (usageRate === 0) return Number.POSITIVE_INFINITY
  return Math.ceil(current / usageRate)
}

export function validateEmail(email: string): boolean {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return regex.test(email)
}

export function validatePatientData(data: any): { valid: boolean; errors: string[] } {
  const errors: string[] = []

  if (!data.name?.trim()) errors.push("Patient name is required")
  if (!data.dob) errors.push("Date of birth is required")
  if (!data.region) errors.push("Region is required")
  if (!data.condition) errors.push("Condition is required")
  if (!data.severity) errors.push("Severity level is required")
  if (!data.contact?.trim()) errors.push("Contact is required")
  if (!data.admissionDate) errors.push("Admission date is required")

  return { valid: errors.length === 0, errors }
}

export function validateResourceData(data: any): { valid: boolean; errors: string[] } {
  const errors: string[] = []

  if (!data.name?.trim()) errors.push("Resource name is required")
  if (!data.category) errors.push("Category is required")
  if (data.stock === undefined || data.stock < 0) errors.push("Stock must be a positive number")
  if (!data.unit?.trim()) errors.push("Unit is required")
  if (data.threshold === undefined || data.threshold < 0) errors.push("Threshold must be a positive number")
  if (data.usageRate === undefined || data.usageRate < 0) errors.push("Usage rate must be a positive number")

  return { valid: errors.length === 0, errors }
}
