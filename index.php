<?php
session_start();
require_once 'config.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare Community Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
    <style>
        tailwind.config = { 
            theme: { 
                extend: { 
                    colors: { 
                        primary: '#E50914', 
                        background: '#FFFFFF', 
                        foreground: '#000000', 
                        card: '#F5F5F5', 
                        border: '#E0E0E0', 
                        secondary: '#FAFAFA', 
                        muted: '#666666' 
                    } 
                } 
            } 
        }
        
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; 
        }
        
        .tab-active { 
            color: #E50914; 
            border-bottom: 2px solid #E50914; 
        }
        
        .tab-content { 
            display: none; 
        }
        
        .tab-content.active { 
            display: block; 
        }
        
        .view { 
            display: none; 
        }
        
        .view.active { 
            display: block; 
        }
        
        .disease-marker { 
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.15)); 
            animation: pulse 2s infinite; 
        }
        
        @keyframes pulse { 
            0%, 100% { opacity: 1; } 
            50% { opacity: 0.8; } 
        }
        
        .leaflet-popup-content { 
            font-size: 12px !important; 
            padding: 8px !important; 
        }
        
        .leaflet-popup-content-wrapper { 
            border-radius: 8px !important; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; 
        }
        
        .map-loading { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            height: 100%; 
            color: #666; 
            font-size: 14px; 
        }
        
        #locationPickerMap { 
            height: 400px; 
            width: 100%; 
            border-radius: 8px; 
            border: 1px solid #E0E0E0; 
        }
        
        .module-card { 
            transition: all 0.3s ease; 
            cursor: pointer; 
        }
        
        .module-card:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12); 
        }
        
        .badge { 
            display: inline-flex; 
            align-items: center; 
            padding: 4px 12px; 
            border-radius: 12px; 
            font-size: 11px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
        }
        
        .badge-free { 
            background-color: #22c55e20; 
            color: #22c55e; 
            border: 1px solid #22c55e40; 
        }
        
        .badge-certificate { 
            background-color: #3b82f620; 
            color: #3b82f6; 
            border: 1px solid #3b82f640; 
        }
        
        .badge-beginner { 
            background-color: #eab30820; 
            color: #eab308; 
            border: 1px solid #eab30840; 
        }
        
        .badge-intermediate { 
            background-color: #f9731620; 
            color: #f97316; 
            border: 1px solid #f9731640; 
        }
        
        .badge-advanced { 
            background-color: #ef444420; 
            color: #ef4444; 
            border: 1px solid #ef444440; 
        }
        
        .alert-item { 
            transition: all 0.3s ease; 
        }
        
        .alert-item:hover { 
            transform: translateX(4px); 
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Main Container -->
    <div class="container">
        <!-- Welcome View (Before Login) -->
        <div id="welcomeView" class="view <?php echo !$isLoggedIn ? 'active' : ''; ?>">
            <div style="text-align: center; padding: 4rem 1rem; max-width: 600px; margin: 0 auto;">
                <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; color: #E50914;">
                    <i class="fas fa-heartbeat" style="margin-right: 0.75rem;"></i>HEALTHCARE
                </h1>
                <h2 style="font-size: 1.75rem; font-weight: 600; margin-bottom: 2rem; color: #000;">Community Dashboard</h2>
                
                <p style="font-size: 1.1rem; color: #666; margin-bottom: 2rem;">Welcome</p>
                <p style="font-size: 1.05rem; margin-bottom: 2rem; color: #333;">Are you a healthcare worker?</p>
                
                <div style="display: flex; gap: 1rem; justify-content: center; margin-bottom: 2rem; flex-wrap: wrap;">
                    <button onclick="showLoginView()" style="padding: 0.75rem 2rem; background: #E50914; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 1rem;">
                        Yes, I am
                    </button>
                    <button onclick="showDashboardView()" style="padding: 0.75rem 2rem; background: #F5F5F5; color: #000; border: 2px solid #E0E0E0; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 1rem;">
                        No, just viewing
                    </button>
                </div>
                
                <p style="font-size: 0.95rem; color: #666; margin-top: 2rem;">
                    Healthcare workers can input and manage data after authentication.<br/>
                    Viewers can access all dashboard metrics without an account.
                </p>
            </div>
        </div>

        <!-- Login View -->
        <div id="loginView" class="view">
            <div style="max-width: 450px; margin: 4rem auto; padding: 0 1rem;">
                <div class="card">
                    <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem;">
                        <i class="fas fa-heartbeat" style="margin-right: 0.5rem; color: #E50914;"></i>HEALTHCARE
                    </h1>
                    <p style="color: #666; margin-bottom: 2rem;">Community Dashboard</p>
                    
                    <h2 style="font-size: 1.35rem; font-weight: 600; margin-bottom: 1.5rem;">Healthcare Worker Sign In</h2>
                    
                    <form id="loginForm" style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #E0E0E0; border-radius: 6px; font-size: 0.95rem;">
                        </div>
                        
                        <div>
                            <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Password</label>
                            <input type="password" id="password" name="password" placeholder="Enter your password" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #E0E0E0; border-radius: 6px; font-size: 0.95rem;">
                        </div>
                        
                        <div id="errorMessage" class="alert-box alert-error hidden" style="margin: 1rem 0;"></div>
                        
                        <button type="submit" style="width: 100%; padding: 0.75rem; background: #E50914; color: white; border: none; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; margin-top: 1rem;">
                            Sign In
                        </button>
                    </form>
                    
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #E0E0E0; text-align: center;">
                        <p style="font-size: 0.9rem; color: #666; margin-bottom: 1rem;">Remember me</p>
                        <p style="font-size: 0.9rem; color: #666; margin-bottom: 1rem;">Need help?</p>
                        <p style="font-size: 0.9rem;"><span style="color: #666;">New healthcare worker? </span><a href="#" style="color: #E50914; text-decoration: none; font-weight: 600;">Sign up now</a></p>
                    </div>
                </div>
                
                <p style="text-align: center; margin-top: 2rem; color: #999; font-size: 0.85rem;">This page is protected by authentication to ensure the security of your healthcare data.</p>
            </div>
        </div>

        <!-- Dashboard View -->
        <div id="dashboardView" class="view">
            <!-- Dashboard Header -->
            <div class="card" style="margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <h1 style="font-size: 1.75rem; font-weight: 700;">Healthcare Community Dashboard</h1>
                    <?php if ($isLoggedIn): ?>
                        <span class="badge badge-info" style="font-size: 0.85rem;">
                            <i class="fas fa-user-md"></i> <?php echo htmlspecialchars($_SESSION['user_role']); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Viewing Mode Alert -->
            <?php if (!$isLoggedIn): ?>
                <div class="alert-box alert-info" style="margin-bottom: 2rem;">
                    <i class="fas fa-info-circle"></i> <strong>Viewing Mode</strong> - You are viewing the dashboard in read-only mode. To input or edit data, please sign in as a healthcare worker.
                </div>
            <?php endif; ?>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab-button tab-active" onclick="switchTab('dashboard')">
                    <i class="fas fa-chart-line"></i> Dashboard
                </button>
                <?php if ($isLoggedIn && $userRole === 'healthcare_worker'): ?>
                    <button class="tab-button" onclick="switchTab('patients')">
                        <i class="fas fa-user-injured"></i> Patients
                    </button>
                    <button class="tab-button" onclick="switchTab('resources')">
                        <i class="fas fa-boxes"></i> Resources
                    </button>
                    <button class="tab-button" onclick="switchTab('alerts')">
                        <i class="fas fa-exclamation-triangle"></i> Alerts
                    </button>
                <?php endif; ?>
            </div>

            <!-- Dashboard Tab Content -->
            <div id="dashboard" class="tab-content active">
                <!-- Stats Cards -->
                <div class="grid grid-4" style="margin-bottom: 2rem;">
                    <div class="stat-card">
                        <i class="fas fa-users" style="font-size: 2rem; color: #E50914;"></i>
                        <div class="stat-value" id="totalPatients">0</div>
                        <div class="stat-label">Total Patients</div>
                        <div class="stat-description">Active cases</div>
                    </div>
                    
                    <div class="stat-card">
                        <i class="fas fa-heartbeat" style="font-size: 2rem; color: #ef4444;"></i>
                        <div class="stat-value" id="criticalCases">0</div>
                        <div class="stat-label">Critical Cases</div>
                        <div class="stat-description">Monitor closely</div>
                    </div>
                    
                    <div class="stat-card">
                        <i class="fas fa-boxes" style="font-size: 2rem; color: #f59e0b;"></i>
                        <div class="stat-value" id="healthyStock">0</div>
                        <div class="stat-label">Healthy Stock</div>
                        <div class="stat-description">Above threshold</div>
                    </div>
                    
                    <div class="stat-card">
                        <i class="fas fa-exclamation" style="font-size: 2rem; color: #ef4444;"></i>
                        <div class="stat-value" id="lowStock">0</div>
                        <div class="stat-label">Low Stock</div>
                        <div class="stat-description">Needs attention</div>
                    </div>
                </div>

                <!-- Resource Table -->
                <div class="card" style="margin-bottom: 2rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">Resource Stock Monitoring</h2>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Resource Name</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Threshold</th>
                                    <th>Days Remaining</th>
                                    <th>Status</th>
                                    <th>Utilization %</th>
                                </tr>
                            </thead>
                            <tbody id="resourceTable">
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">No resources added yet. Healthcare workers can add resources in the Resources tab.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Critical Alerts -->
                <div class="card" style="margin-bottom: 2rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">
                        <i class="fas fa-bell" style="margin-right: 0.5rem; color: #E50914;"></i> Critical Alerts
                    </h2>
                    <div id="criticalAlertsList" style="space-y: 1rem;">
                        <p style="color: #666; text-align: center; padding: 2rem;">No critical alerts at this time.</p>
                    </div>
                </div>

                <!-- Disease Map -->
                <div class="card">
                    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Disease Map Indicator</h2>
                    <div style="margin-bottom: 1rem;">
                        <label style="font-weight: 500; margin-right: 1rem;">Severity:</label>
                        <select id="severityFilter" style="padding: 0.5rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                            <option value="">All</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div id="map" style="height: 400px; border-radius: 8px; border: 1px solid #E0E0E0; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #666;">
                        Loading map...
                    </div>
                </div>
            </div>

            <!-- Patients Tab Content -->
            <?php if ($isLoggedIn && $userRole === 'healthcare_worker'): ?>
            <div id="patients" class="tab-content">
                <div class="card" style="margin-bottom: 2rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">Add New Patient</h2>
                    
                    <form id="patientForm" style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div class="grid grid-3">
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Patient Name</label>
                                <input type="text" id="patientName" placeholder="Enter full name" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                            </div>
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Date of Birth</label>
                                <input type="date" id="patientDOB" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                            </div>
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Medical ID</label>
                                <input type="text" id="patientID" placeholder="Auto-generated" readonly style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px; background-color: #F5F5F5;">
                            </div>
                        </div>

                        <div style="border-top: 1px solid #E0E0E0; padding-top: 1.5rem;">
                            <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">Patient Location</h3>
                            
                            <div class="grid grid-3">
                                <div>
                                    <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Region/Province</label>
                                    <select id="patientRegion" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                                        <option value="">Select region</option>
                                        <option value="Metro Manila">Metro Manila</option>
                                        <option value="Cavite">Cavite</option>
                                        <option value="Laguna">Laguna</option>
                                        <option value="Bulacan">Bulacan</option>
                                        <option value="Rizal">Rizal</option>
                                        <option value="Batangas">Batangas</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">City/Municipality</label>
                                    <select id="patientCity" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                                        <option value="">Select city first</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Barangay</label>
                                    <select id="patientBarangay" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                                        <option value="">Select barangay</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div style="margin-top: 1rem;">
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Street Address</label>
                                <input type="text" id="patientStreet" placeholder="e.g., 123 Main Street, Building A" style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                            </div>
                        </div>

                        <div class="grid grid-3">
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Primary Condition</label>
                                <select id="patientCondition" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                                    <option value="">Select condition</option>
                                    <option>Dengue Fever</option>
                                    <option>Influenza Type A</option>
                                    <option>Tuberculosis</option>
                                    <option>Hypertension</option>
                                    <option>COVID-19</option>
                                    <option>Diabetes</option>
                                    <option>Heart Disease</option>
                                    <option>Respiratory Disease</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Severity Level</label>
                                <select id="patientSeverity" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                                    <option value="">Select severity</option>
                                    <option value="Low">Low - Mild symptoms</option>
                                    <option value="Medium">Medium - Moderate symptoms</option>
                                    <option value="High">High - Critical condition</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Emergency Contact</label>
                                <input type="tel" id="patientContact" placeholder="Phone number" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                            </div>
                        </div>

                        <div class="grid grid-3">
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Insurance Provider</label>
                                <input type="text" id="patientInsurance" placeholder="Insurance company" style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                            </div>
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Admission Date</label>
                                <input type="date" id="patientAdmission" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                            </div>
                        </div>

                        <div>
                            <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Symptoms/Notes</label>
                            <textarea id="patientNotes" placeholder="Describe symptoms and any additional information..." style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px; min-height: 100px; font-family: inherit;"></textarea>
                        </div>

                        <button type="submit" style="padding: 0.75rem 1.5rem; background: #E50914; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; align-self: flex-start;">
                            Add Patient
                        </button>
                    </form>
                </div>

                <!-- Patients List -->
                <div class="card">
                    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">Patients List</h2>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Medical ID</th>
                                    <th>Name</th>
                                    <th>DOB</th>
                                    <th>Condition</th>
                                    <th>Severity</th>
                                    <th>Contact</th>
                                    <th>Date Added</th>
                                </tr>
                            </thead>
                            <tbody id="patientsList">
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">No patients added yet.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resources Tab Content -->
            <div id="resources" class="tab-content">
                <div class="card" style="margin-bottom: 2rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">Add New Resource</h2>
                    
                    <form id="resourceForm" style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div class="grid grid-3">
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Resource Name</label>
                                <input type="text" id="resourceName" placeholder="e.g., Insulin, Face Masks" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                            </div>
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Category</label>
                                <select id="resourceCategory" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                                    <option value="">Select category</option>
                                    <option>Medication</option>
                                    <option>Medical Supplies</option>
                                    <option>Equipment</option>
                                    <option>PPE</option>
                                    <option>Laboratory</option>
                                    <option>Emergency</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Current Stock</label>
                                <input type="number" id="resourceStock" placeholder="Quantity" min="0" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                            </div>
                        </div>

                        <div class="grid grid-3">
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Unit</label>
                                <input type="text" id="resourceUnit" placeholder="e.g., boxes, bottles, pieces" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                            </div>
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Minimum Threshold</label>
                                <input type="number" id="resourceThreshold" placeholder="Alert level" min="0" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                            </div>
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Daily Usage Rate</label>
                                <input type="number" id="resourceUsageRate" placeholder="Units per day" min="0" step="0.1" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                            </div>
                        </div>

                        <button type="submit" style="padding: 0.75rem 1.5rem; background: #E50914; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; align-self: flex-start;">
                            Add Resource
                        </button>
                    </form>
                </div>

                <!-- Resources List -->
                <div class="card">
                    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">Resources List</h2>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Resource Name</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Unit</th>
                                    <th>Threshold</th>
                                    <th>Daily Usage</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="resourcesList">
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">No resources added yet.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Alerts Tab Content -->
            <div id="alerts" class="tab-content">
                <div class="card" style="margin-bottom: 2rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">Add New Priority Alert</h2>
                    
                    <form id="alertForm" style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div>
                            <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Alert Title</label>
                            <input type="text" id="alertTitle" placeholder="e.g., Medication Stock Low" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Description</label>
                            <textarea id="alertDescription" placeholder="Provide details about this alert..." rows="3" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px; font-family: inherit;"></textarea>
                        </div>
                        <div class="grid grid-2">
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Priority Level</label>
                                <select id="alertPriority" required style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                                    <option value="high">High - Requires immediate action</option>
                                    <option value="medium">Medium - Needs attention soon</option>
                                    <option value="low">Low - General reminder</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Action Button Label (Optional)</label>
                                <input type="text" id="alertActionLabel" placeholder="e.g., Take Action, Acknowledge" style="width: 100%; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 6px;">
                            </div>
                        </div>

                        <button type="submit" style="padding: 0.75rem 1.5rem; background: #E50914; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; align-self: flex-start;">
                            Add Alert
                        </button>
                    </form>
                </div>

                <!-- Alerts List -->
                <div class="card">
                    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">Priority Alerts</h2>
                    <div id="alertsList" style="display: flex; flex-direction: column; gap: 1rem;">
                        <p style="color: #666; text-align: center; padding: 2rem;">No priority alerts yet.</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        // Tab switching
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-button').forEach(el => el.classList.remove('tab-active'));
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('tab-active');
        }

        // View switching
        function showLoginView() {
            document.getElementById('welcomeView').classList.remove('active');
            document.getElementById('loginView').classList.add('active');
        }

        function showDashboardView() {
            document.getElementById('welcomeView').classList.remove('active');
            document.getElementById('dashboardView').classList.add('active');
            loadDashboardData();
        }

        // Login handler
        document.getElementById('loginForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('api/auth.php?action=login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    document.getElementById('errorMessage').textContent = data.message;
                    document.getElementById('errorMessage').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Login error:', error);
            }
        });

        // Logout handler
        document.getElementById('logoutBtn')?.addEventListener('click', async () => {
            try {
                await fetch('api/auth.php?action=logout', { method: 'POST' });
                window.location.reload();
            } catch (error) {
                console.error('Logout error:', error);
            }
        });

        // Load dashboard data
        async function loadDashboardData() {
            try {
                const response = await fetch('api/dashboard.php');
                const data = await response.json();
                if (data.success) {
                    document.getElementById('totalPatients').textContent = data.stats.total_patients;
                    document.getElementById('criticalCases').textContent = data.stats.critical_cases;
                    document.getElementById('healthyStock').textContent = data.stats.resources.healthy || 0;
                    document.getElementById('lowStock').textContent = data.stats.resources.low || 0;
                }
            } catch (error) {
                console.error('Dashboard error:', error);
            }
        }

        // Patient form handler
        document.getElementById('patientForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = {
                name: document.getElementById('patientName').value,
                dob: document.getElementById('patientDOB').value,
                region: document.getElementById('patientRegion').value,
                city: document.getElementById('patientCity').value,
                barangay: document.getElementById('patientBarangay').value,
                street: document.getElementById('patientStreet').value,
                condition: document.getElementById('patientCondition').value,
                severity: document.getElementById('patientSeverity').value,
                contact: document.getElementById('patientContact').value,
                insurance: document.getElementById('patientInsurance').value,
                admission_date: document.getElementById('patientAdmission').value,
                notes: document.getElementById('patientNotes').value,
                lat: 0,
                lng: 0
            };

            try {
                const response = await fetch('api/patients.php?action=add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                if (data.success) {
                    alert('Patient added successfully! Medical ID: ' + data.medical_id);
                    document.getElementById('patientForm').reset();
                    loadPatientsList();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Patient error:', error);
            }
        });

        // Resource form handler
        document.getElementById('resourceForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = {
                name: document.getElementById('resourceName').value,
                category: document.getElementById('resourceCategory').value,
                stock: document.getElementById('resourceStock').value,
                unit: document.getElementById('resourceUnit').value,
                threshold: document.getElementById('resourceThreshold').value,
                usage_rate: document.getElementById('resourceUsageRate').value
            };

            try {
                const response = await fetch('api/resources.php?action=add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                if (data.success) {
                    alert('Resource added successfully!');
                    document.getElementById('resourceForm').reset();
                    loadResourcesList();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Resource error:', error);
            }
        });

        // Alert form handler
        document.getElementById('alertForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = {
                title: document.getElementById('alertTitle').value,
                description: document.getElementById('alertDescription').value,
                priority: document.getElementById('alertPriority').value,
                action_label: document.getElementById('alertActionLabel').value
            };

            try {
                const response = await fetch('api/alerts.php?action=add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                if (data.success) {
                    alert('Alert added successfully!');
                    document.getElementById('alertForm').reset();
                    loadAlertsList();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Alert error:', error);
            }
        });

        // Load patients list
        async function loadPatientsList() {
            try {
                const response = await fetch('api/patients.php?action=list');
                const data = await response.json();
                if (data.success) {
                    const tbody = document.getElementById('patientsList');
                    tbody.innerHTML = data.patients.length > 0 
                        ? data.patients.map(p => `
                            <tr>
                                <td>${p.medical_id}</td>
                                <td>${p.name}</td>
                                <td>${p.dob}</td>
                                <td>${p.condition}</td>
                                <td><span class="badge badge-${p.severity === 'High' ? 'danger' : 'warning'}">${p.severity}</span></td>
                                <td>${p.contact}</td>
                                <td>${new Date(p.created_at).toLocaleDateString()}</td>
                            </tr>
                        `).join('')
                        : '<tr><td colspan="7" style="text-align: center; padding: 2rem; color: #666;">No patients added yet.</td></tr>';
                }
            } catch (error) {
                console.error('Load patients error:', error);
            }
        }

        // Load resources list
        async function loadResourcesList() {
            try {
                const response = await fetch('api/resources.php?action=list');
                const data = await response.json();
                if (data.success) {
                    const tbody = document.getElementById('resourcesList');
                    tbody.innerHTML = data.resources.length > 0 
                        ? data.resources.map(r => {
                            const status = r.current_stock > r.minimum_threshold ? 'Healthy' : (r.current_stock > 0 ? 'Low' : 'Critical');
                            const statusClass = status === 'Healthy' ? 'success' : (status === 'Low' ? 'warning' : 'danger');
                            return `
                                <tr>
                                    <td>${r.name}</td>
                                    <td>${r.category}</td>
                                    <td>${r.current_stock}</td>
                                    <td>${r.unit}</td>
                                    <td>${r.minimum_threshold}</td>
                                    <td>${r.daily_usage_rate}</td>
                                    <td><span class="badge badge-${statusClass}">${status}</span></td>
                                </tr>
                            `;
                        }).join('')
                        : '<tr><td colspan="7" style="text-align: center; padding: 2rem; color: #666;">No resources added yet.</td></tr>';
                }
            } catch (error) {
                console.error('Load resources error:', error);
            }
        }

        // Load alerts list
        async function loadAlertsList() {
            try {
                const response = await fetch('api/alerts.php?action=list');
                const data = await response.json();
                if (data.success) {
                    const container = document.getElementById('alertsList');
                    container.innerHTML = data.alerts.length > 0 
                        ? data.alerts.map(a => `
                            <div class="card" style="border-left: 4px solid ${a.priority === 'high' ? '#ef4444' : (a.priority === 'medium' ? '#f59e0b' : '#3b82f6')};">
                                <div style="display: flex; justify-content: space-between; align-items: start; gap: 1rem; flex-wrap: wrap;">
                                    <div>
                                        <h3 style="font-weight: 600; margin-bottom: 0.25rem;">${a.title}</h3>
                                        <p style="color: #666; margin-bottom: 0.5rem;">${a.description}</p>
                                        <small style="color: #999;">${new Date(a.created_at).toLocaleString()}</small>
                                    </div>
                                    <span class="badge badge-${a.priority === 'high' ? 'danger' : (a.priority === 'medium' ? 'warning' : 'info')}">${a.priority}</span>
                                </div>
                            </div>
                        `).join('')
                        : '<p style="color: #666; text-align: center; padding: 2rem;">No priority alerts yet.</p>';
                }
            } catch (error) {
                console.error('Load alerts error:', error);
            }
        }

        // Initialize on page load
        window.addEventListener('load', () => {
            <?php if ($isLoggedIn && $userRole === 'healthcare_worker'): ?>
            loadPatientsList();
            loadResourcesList();
            loadAlertsList();
            <?php else: ?>
            loadDashboardData();
            <?php endif; ?>
        });
    </script>
</body>
</html>
