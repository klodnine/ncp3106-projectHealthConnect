import { Button } from "@/components/ui/button"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"

export default function Home() {
  return (
    <main className="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
      <div className="container mx-auto px-4 py-16 md:py-24">
        {/* Hero Section */}
        <div className="max-w-3xl mx-auto text-center space-y-8 mb-20">
          <div className="space-y-4">
            <h1 className="text-5xl md:text-6xl font-bold text-slate-900 text-balance">Healthcare Connect</h1>
            <p className="text-xl text-slate-600 text-pretty">
              Your unified platform for patient care, resource management, and real-time alerts
            </p>
          </div>

          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Button size="lg" className="bg-blue-600 hover:bg-blue-700 text-white">
              Get Started
            </Button>
            <Button size="lg" variant="outline">
              Learn More
            </Button>
          </div>
        </div>

        {/* Features Grid */}
        <div className="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
          <Card>
            <CardHeader>
              <CardTitle>Patient Management</CardTitle>
              <CardDescription>Track and manage patient information with ease</CardDescription>
            </CardHeader>
            <CardContent>
              <p className="text-sm text-slate-600">
                Comprehensive patient records with location tracking and medical history
              </p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Resource Tracking</CardTitle>
              <CardDescription>Monitor medical supplies and equipment</CardDescription>
            </CardHeader>
            <CardContent>
              <p className="text-sm text-slate-600">Real-time inventory management and stock status alerts</p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Priority Alerts</CardTitle>
              <CardDescription>Stay informed with instant notifications</CardDescription>
            </CardHeader>
            <CardContent>
              <p className="text-sm text-slate-600">Critical updates and priority-based alert system</p>
            </CardContent>
          </Card>
        </div>
      </div>
    </main>
  )
}
