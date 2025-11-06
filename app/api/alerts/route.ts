import { type NextRequest, NextResponse } from "next/server"

export async function GET(request: NextRequest) {
  try {
    return NextResponse.json({ success: true, message: "Use client-side localStorage or connect to backend database" })
  } catch (error) {
    return NextResponse.json({ error: "Failed to fetch alerts" }, { status: 500 })
  }
}

export async function POST(request: NextRequest) {
  try {
    const body = await request.json()

    if (!body.title || !body.description || !body.priority) {
      return NextResponse.json({ error: "Missing required fields" }, { status: 400 })
    }

    return NextResponse.json({ success: true, data: body }, { status: 201 })
  } catch (error) {
    return NextResponse.json({ error: "Failed to create alert" }, { status: 500 })
  }
}
