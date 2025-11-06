<?php
// Serve the frontend HTML (converted from index copy.html)
// Start session for future auth use
session_start();
// Optional: include config or db here if you want server-side rendering or DB access
// include_once __DIR__ . '/api/db.php';
?>
<!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.1/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster@1.5.1/dist/leaflet.markercluster.js"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {/* Lines 20-29 omitted */}
        }
      }
    </script>
    <style>
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
  <body class="bg-background">
    <div id="roleSelectionView" class="view active">
      <div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-white">
        <div class="absolute inset-0 bg-gradient-to-b from-white via-white/95 to-white/90 pointer-events-none"></div>
        <div class="absolute inset-0 opacity-[0.02] pointer-events-none" style="background-image: radial-gradient(circle at 1px 1px, black 1px, transparent 0); background-size: 40px 40px;"></div>

        <div class="relative z-10 w-full max-w-2xl px-6">
          <div class="text-center mb-12">
            /* Lines 142-144 omitted */
          </div>

          <div class="bg-white/60 backdrop-blur-sm border border-border rounded-lg p-8 shadow-2xl">
            /* Lines 147-174 omitted */
          </div>
        </div>
      </div>
    </div>
    
    <div id="loginView" class="view">
      <div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-white">
        <div class="absolute inset-0 bg-gradient-to-b from-white via-white/95 to-white/90 pointer-events-none"></div>
        <div class="absolute inset-0 opacity-[0.02] pointer-events-none" style="background-image: radial-gradient(circle at 1px 1px, black 1px, transparent 0); background-size: 40px 40px;"></div>

        <div class="relative z-10 w-full max-w-md px-6">
          <div class="text-center mb-12">
            /* Lines 186-188 omitted */
          </div>

          <div class="bg-white/60 backdrop-blur-sm border border-border rounded-lg p-8 shadow-2xl">
            /* Lines 191-253 omitted */
          </div>

          <p class="text-center text-muted text-xs mt-8 leading-relaxed">
            /* Lines 256-257 omitted */
          </p>
        </div>
      </div>
    </div>

    <div id="dashboardView" class="view">
      <header class="border-b border-border bg-card/50 backdrop-blur-sm sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">
          <h1 class="text-2xl font-bold text-primary">Healthcare Community Dashboard</h1>
          <div class="flex items-center gap-4">
            /* Lines 267-277 omitted */
          </div>
        </div>
      </header>

      <nav class="border-b border-border bg-card/30">
        <div class="container mx-auto px-6">
          <div class="flex gap-1">
            /* Lines 284-296 omitted */
          </div>
        </div>
      </nav>

      <main class="container mx-auto px-6 py-8">
        <div id="readOnlyNotice" class="hidden mb-6 p-4 bg-blue-500/10 border border-blue-500/20 rounded-lg">
          <div class="flex items-center gap-3">
            /* Lines 303-308 omitted */
          </div>
        </div>
    
        <div id="dashboard" class="tab-content active">
          <div class="space-y-8">
            /* Lines 313-544 omitted */
          </div>
        </div>

        <div id="patients" class="tab-content">
          <div class="space-y-8">
            /* Lines 549-716 omitted */
          </div>
        </div>

        <div id="resources" class="tab-content">
          <div class="space-y-8">
            /* Lines 721-790 omitted */
          </div>
        </div>

        <div id="training" class="tab-content">
          <div class="space-y-8">
            /* Lines 795-838 omitted */
          </div>
        </div>
      </main>
    </div>

    <script>
      let resourcesData = [];
      let patientsData = [];
      let diseaseData = [];
      let alertsData = [];
      let chartsMap = {};
      let diseaseMap = null;
      let locationPickerMap = null;
      let locationMarker = null;
      let heatmapLayer = null;
      let markerClusterGroup = null;
      let isProcessingPatient = false;
      let patientQueue = [];
      let processedCount = 0;

      const trainingModules = [
        {
          id: 1,
          title: 'WHO COVID-19 Clinical Management',
          provider: 'World Health Organization',
          category: 'clinical',
          level: 'intermediate',
          duration: '4-6 hours',
          description: 'Comprehensive guide to clinical management of COVID-19 patients, including diagnosis, treatment protocols, and monitoring.',
          url: 'https://www.who.int/publications/i/item/WHO-2019-nCoV-clinical-2021-2',
          isFree: true,
          hasCertificate: false,
          tags: ['COVID-19', 'Clinical Care', 'Treatment']
        },
        {
          id: 2,
          title: 'Basic Life Support (BLS)',
          provider: 'American Heart Association',
          category: 'emergency',
          level: 'beginner',
          duration: '2-3 hours',
          description: 'Learn essential CPR skills, use of AED, and relief of choking in adults, children, and infants.',
          url: 'https://cpr.heart.org/en/courses/basic-life-support-bls-training',
          isFree: false,
          hasCertificate: true,
          tags: ['CPR', 'Emergency', 'Life Support']
        },
        {
          id: 3,
          title: 'Infection Prevention and Control',
          provider: 'CDC',
          category: 'safety',
          level: 'intermediate',
          duration: '3-4 hours',
          description: 'Evidence-based strategies for preventing healthcare-associated infections and implementing control measures.',
          url: 'https://www.cdc.gov/infection-control',
          isFree: true,
          hasCertificate: false,
          tags: ['Infection Control', 'Safety', 'Prevention']
        },
        {
          id: 4,
          title: 'Patient Safety Fundamentals',
          provider: 'Institute for Healthcare Improvement',
          category: 'safety',
          level: 'beginner',
          duration: '2 hours',
          description: 'Introduction to patient safety principles, error prevention, and creating a culture of safety.',
          url: 'https://www.ihi.org/education/InPersonTraining/patient-safety',
          isFree: true,
          hasCertificate: true,
          tags: ['Patient Safety', 'Quality', 'Healthcare']
        },
        {
          id: 5,
          title: 'Advanced Cardiac Life Support (ACLS)',
          provider: 'American Heart Association',
          category: 'emergency',
          level: 'advanced',
          duration: '8-10 hours',
          description: 'Advanced cardiovascular emergency care including cardiac arrest, stroke, and acute coronary syndromes.',
          url: 'https://cpr.heart.org/en/courses/acls',
          isFree: false,
          hasCertificate: true,
          tags: ['ACLS', 'Emergency', 'Cardiac']
        },
        {
          id: 6,
          title: 'Pediatric Advanced Life Support (PALS)',
          provider: 'American Heart Association',
          category: 'emergency',
          level: 'advanced',
          duration: '7-9 hours',
          description: 'Systematic approach to pediatric assessment, basic life support, PALS algorithms, and effective resuscitation.',
          url: 'https://cpr.heart.org/en/courses/pals',
          isFree: false,
          hasCertificate: true,
          tags: ['PALS', 'Pediatrics', 'Emergency']
        },
        {
          id: 7,
          title: 'Wound Care Management',
          provider: 'Wound Care Education Institute',
          category: 'clinical',
          level: 'intermediate',
          duration: '4-5 hours',
          description: 'Comprehensive wound assessment, treatment options, and evidence-based wound care practices.',
          url: 'https://wcei.net',
          isFree: true,
          hasCertificate: true,
          tags: ['Wound Care', 'Clinical', 'Treatment']
        },
        {
          id: 8,
          title: 'Medication Administration Safety',
          provider: 'Institute for Safe Medication Practices',
          category: 'safety',
          level: 'beginner',
          duration: '2-3 hours',
          description: 'Best practices for safe medication administration and error prevention strategies.',
          url: 'https://www.ismp.org/resources',
          isFree: true,
          hasCertificate: false,
          tags: ['Medication', 'Safety', 'Administration']
        },
        {
          id: 9,
          title: 'Healthcare Communication Skills',
          provider: 'Health Communication Partners',
          category: 'communication',
          level: 'beginner',
          duration: '3 hours',
          description: 'Effective communication techniques for patient interactions, family conferences, and team collaboration.',
          url: 'https://healthcommunicationpartners.com',
          isFree: true,
          hasCertificate: true,
          tags: ['Communication', 'Patient Care', 'Teamwork']
        },
        {
          id: 10,
          title: 'Critical Care Nursing Essentials',
          provider: 'American Association of Critical-Care Nurses',
          category: 'clinical',
          level: 'advanced',
          duration: '6-8 hours',
          description: 'Advanced critical care concepts including hemodynamic monitoring, ventilator management, and complex patient care.',
          url: 'https://www.aacn.org/education',
          isFree: false,
          hasCertificate: true,
          tags: ['Critical Care', 'ICU', 'Advanced']
        },
        {
          id: 11,
          title: 'Diabetes Management for Healthcare Providers',
          provider: 'American Diabetes Association',
          category: 'clinical',
          level: 'intermediate',
          duration: '4 hours',
          description: 'Comprehensive diabetes care including diagnosis, treatment plans, medication management, and patient education.',
          url: 'https://professional.diabetes.org',
          isFree: true,
          hasCertificate: true,
          tags: ['Diabetes', 'Chronic Disease', 'Management']
        },
        {
          id: 12,
          title: 'Mental Health First Aid',
          provider: 'National Council for Mental Wellbeing',
          category: 'clinical',
          level: 'beginner',
          duration: '3-4 hours',
          description: 'Recognize and respond to signs of mental illness and substance use disorders in healthcare settings.',
          url: 'https://www.mentalhealthfirstaid.org',
          isFree: true,
          hasCertificate: true,
          tags: ['Mental Health', 'Crisis', 'First Aid']
        },
        {
          id: 13,
          title: 'Surgical Asepsis and Sterile Technique',
          provider: 'Association of periOperative Registered Nurses',
          category: 'clinical',
          level: 'intermediate',
          duration: '3-4 hours',
          description: 'Principles of surgical asepsis, sterile field maintenance, and infection prevention in surgical settings.',
          url: 'https://www.aorn.org/education',
          isFree: false,
          hasCertificate: true,
          tags: ['Surgery', 'Sterile Technique', 'Infection Control']
        },
        {
          id: 14,
          title: 'Neonatal Resuscitation Program (NRP)',
          provider: 'American Academy of Pediatrics',
          category: 'emergency',
          level: 'advanced',
          duration: '5-6 hours',
          description: 'Evidence-based approach to newborn resuscitation and stabilization at birth.',
          url: 'https://www.aap.org/nrp',
          isFree: false,
          hasCertificate: true,
          tags: ['Neonatal', 'Resuscitation', 'Pediatrics']
        },
        {
          id: 15,
          title: 'Pain Management Strategies',
          provider: 'American Pain Society',
          category: 'clinical',
          level: 'intermediate',
          duration: '4 hours',
          description: 'Multimodal pain assessment and management strategies for acute and chronic pain conditions.',
          url: 'https://americanpainsociety.org',
          isFree: true,
          hasCertificate: false,
          tags: ['Pain Management', 'Patient Care', 'Treatment']
        },
        {
          id: 16,
          title: 'Healthcare Leadership Fundamentals',
          provider: 'Healthcare Leadership Alliance',
          category: 'management',
          level: 'intermediate',
          duration: '5-6 hours',
          description: 'Essential leadership skills for healthcare professionals including team management and decision-making.',
          url: 'https://healthcareleadershipalliance.org',
          isFree: true,
          hasCertificate: true,
          tags: ['Leadership', 'Management', 'Administration']
        },
        {
          id: 17,
          title: 'Electronic Health Records Training',
          provider: 'Office of the National Coordinator for Health IT',
          category: 'management',
          level: 'beginner',
          duration: '2-3 hours',
          description: 'Navigate and utilize electronic health record systems efficiently and securely.',
          url: 'https://www.healthit.gov/topic/certification-ehrs',
          isFree: true,
          hasCertificate: false,
          tags: ['EHR', 'Technology', 'Documentation']
        },
        {
          id: 18,
          title: 'Trauma Nursing Core Course (TNCC)',
          provider: 'Emergency Nurses Association',
          category: 'emergency',
          level: 'advanced',
          duration: '8-12 hours',
          description: 'Systematic approach to trauma assessment and evidence-based interventions for trauma patients.',
          url: 'https://www.ena.org/education/tncc',
          isFree: false,
          hasCertificate: true,
          tags: ['Trauma', 'Emergency', 'Advanced']
        },
        {
          id: 19,
          title: 'Pharmacology Basics for Nurses',
          provider: 'Nursing Pharmacology Education',
          category: 'clinical',
          level: 'beginner',
          duration: '4-5 hours',
          description: 'Foundational pharmacology principles, drug classifications, and safe medication practices.',
          url: 'https://nursingpharmacology.com',
          isFree: true,
          hasCertificate: true,
          tags: ['Pharmacology', 'Medication', 'Education']
        },
        {
          id: 20,
          title: 'Geriatric Care Essentials',
          provider: 'American Geriatrics Society',
          category: 'clinical',
          level: 'intermediate',
          duration: '4 hours',
          description: 'Specialized care for elderly patients including age-related conditions and comprehensive assessment.',
          url: 'https://www.americangeriatrics.org/education',
          isFree: true,
          hasCertificate: true,
          tags: ['Geriatrics', 'Elderly Care', 'Specialized']
        },
        {
          id: 21,
          title: 'Healthcare Quality Improvement',
          provider: 'Institute for Healthcare Improvement',
          category: 'management',
          level: 'intermediate',
          duration: '3-4 hours',
          description: 'Quality improvement methodologies including PDSA cycles and data-driven healthcare improvements.',
          url: 'https://www.ihi.org/education/IHIOpenSchool',
          isFree: true,
          hasCertificate: true,
          tags: ['Quality', 'Improvement', 'Management']
        },
        {
          id: 22,
          title: 'Cultural Competence in Healthcare',
          provider: 'National Center for Cultural Competence',
          category: 'communication',
          level: 'beginner',
          duration: '2-3 hours',
          description: 'Culturally responsive care practices and strategies for diverse patient populations.',
          url: 'https://nccc.georgetown.edu',
          isFree: true,
          hasCertificate: false,
          tags: ['Cultural Competence', 'Diversity', 'Patient Care']
        },
        {
          id: 23,
          title: 'Sepsis Recognition and Management',
          provider: 'Sepsis Alliance',
          category: 'clinical',
          level: 'intermediate',
          duration: '3 hours',
          description: 'Early recognition, assessment, and evidence-based management of sepsis and septic shock.',
          url: 'https://www.sepsis.org/education',
          isFree: true,
          hasCertificate: false,
          tags: ['Sepsis', 'Critical Care', 'Emergency']
        },
        {
          id: 24,
          title: 'Oncology Nursing Certification Prep',
          provider: 'Oncology Nursing Society',
          category: 'clinical',
          level: 'advanced',
          duration: '10-15 hours',
          description: 'Comprehensive preparation for oncology nursing certification including cancer treatments and symptom management.',
          url: 'https://www.ons.org/certification',
          isFree: false,
          hasCertificate: true,
          tags: ['Oncology', 'Cancer', 'Certification']
        },
        {
          id: 25,
          title: 'Healthcare Ethics and Legal Issues',
          provider: 'American Nurses Association',
          category: 'management',
          level: 'intermediate',
          duration: '3-4 hours',
          description: 'Ethical decision-making, patient rights, legal responsibilities, and professional boundaries.',
          url: 'https://www.nursingworld.org/education',
          isFree: true,
          hasCertificate: false,
          tags: ['Ethics', 'Legal', 'Professional']
        }
      ];

      const philippineLocations = {
        'Metro Manila': {
          coords: [14.5995, 120.9842],
          cities: {/* Lines 1190-1202 omitted */}
        },
        'Cavite': {
          coords: [14.4791, 120.8970],
          cities: {/* Lines 1207-1219 omitted */}
        },
        'Laguna': {
          coords: [14.1686, 121.3244],
          cities: {/* Lines 1224-1236 omitted */}
        },
        'Bulacan': {
          coords: [14.7928, 120.9783],
          cities: {/* Lines 1241-1253 omitted */}
        },
        'Rizal': {
          coords: [14.6760, 121.3890],
          cities: {/* Lines 1258-1270 omitted */}
        },
        'Batangas': {
          coords: [13.7567, 121.0589],
          cities: {/* Lines 1275-1287 omitted */}
        }
      };


          function updateTrainingMetrics() {
        const totalModules = trainingModules.length;
        const freeModules = trainingModules.filter(m => m.isFree).length;
        const certificateModules = trainingModules.filter(m => m.hasCertificate).length;

        document.getElementById('availableModulesCount').textContent = totalModules;
        document.getElementById('freeResourcesCount').textContent = freeModules;
        document.getElementById('certificatesCount').textContent = certificateModules;
      }

      function renderTrainingModules(filter = 'all') {
        updateTrainingMetrics();
        const grid = document.getElementById('trainingModulesGrid');
        const filteredModules = filter === 'all' 
          ? trainingModules 
          : trainingModules.filter(m => m.category === filter);

        if (filteredModules.length === 0) {
          grid.innerHTML = '<p class="col-span-full text-center text-muted py-8">No modules found for this category.</p>';
          return;
        }

        grid.innerHTML = filteredModules.map(module => {
          const levelClass = module.level === 'beginner' ? 'badge-beginner' : 
                  /* Lines 1316-1317 omitted */
                    
          return `
          `;
        }).join('');

        lucide.createIcons();
      }


      // Auto-dismiss alerts after 24 hours
      function checkAndDismissExpiredAlerts() {
        const now = new Date().getTime();
        const twentyFourHoursInMs = 24 * 60 * 60 * 1000;

        const originalCount = alertsData.length;
        alertsData = alertsData.filter(alert => {
          const alertTime = new Date(alert.timestamp).getTime();
          const timeElapsed = now - alertTime;
          return timeElapsed < twentyFourHoursInMs;
        });

        if (alertsData.length !== originalCount) {
          renderAlerts();
          console.log(`Auto-dismissed ${originalCount - alertsData.length} expired alert(s)`);
        }
      }

      function renderAlerts() {
        const alertsList = document.getElementById('alertsList');
        const isHealthcareWorker = checkHealthcareWorkerStatus();
                
        if (alertsData.length === 0) {
          alertsList.innerHTML = `<p class="text-sm text-muted text-center py-8">No priority alerts. Healthcare workers can add new alerts using the button above.</p>`;
          return;
        }

        alertsList.innerHTML = alertsData.map(alert => {
          const priorityColors = {
          high: { bg: 'bg-red-500/10', border: 'border-red-500', text: 'text-red-500', icon: 'alert-triangle' },
          medium: { bg: 'bg-yellow-500/10', border: 'border-yellow-500', text: 'text-yellow-500', icon: 'alert-circle' },
          low: { bg: 'bg-blue-500/10', border: 'border-blue-500', text: 'text-blue-500', icon: 'info' }
          };
                    
          const colors = priorityColors[alert.priority];
          const actionLabel = alert.actionLabel || 'Take Action';
                    
          // Conditionally render dismiss button only for healthcare workers
          const dismissButton = isHealthcareWorker 
          ? `<button onclick="dismissAlert('${alert.id}')" 
            /* Lines 1403-1406 omitted */
            </button>`
          : '';

          return `
          <div class="alert-item flex items-start justify-between p-4 ${colors.bg} rounded-lg border-l-4 ${colors.border}">
            /* Lines 1411-1430 omitted */
          </div>
          `;
        }).join('');
                
        lucide.createIcons();
        }


      function handleAlertAction(alertId) {
        const alert = alertsData.find(a => a.id === alertId);
        if (alert) {
          alert(`Action taken for: ${alert.title}`);
        }
      }

      function dismissAlert(alertId) {
        // Check if user is a healthcare worker
        const isHealthcareWorker = checkHealthcareWorkerStatus();
                
        if (!isHealthcareWorker) {
          alert('Only healthcare workers can dismiss priority alerts.');
          return;
        }
                
        // Proceed with dismissing the alert
        alertsData = alertsData.filter(a => a.id !== alertId);
        renderAlerts();
        }

      // Add this helper function to check user role
        function checkHealthcareWorkerStatus() {
          const userRoleBadge = document.getElementById('userRoleBadge');
          const roleText = userRoleBadge.textContent.trim();
          return roleText === 'Healthcare Worker';
          }

      function showView(viewId) {
        const views = document.querySelectorAll('.view');
        views.forEach(view => view.classList.remove('active'));
        document.getElementById(viewId).classList.add('active');
      }

      function calculateDaysRemaining(currentStock, dailyUsageRate) {
        if (dailyUsageRate === 0 || !dailyUsageRate) return Math.round(currentStock);
        return Math.round(currentStock / dailyUsageRate);
      }

      function getStockStatus(currentStock, threshold) {
        if (currentStock <= 0) return { status: 'Out of Stock', color: 'text-red-600', bgColor: 'bg-red-500/10', borderColor: 'border-red-500/20' };
        if (currentStock <= threshold * 0.25) return { status: 'Critical', color: 'text-red-600', bgColor: 'bg-red-500/10', borderColor: 'border-red-500/20' };
        if (currentStock <= threshold) return { status: 'Low Stock', color: 'text-yellow-600', bgColor: 'bg-yellow-500/10', borderColor: 'border-yellow-500/20' };
        return { status: 'Healthy', color: 'text-green-600', bgColor: 'bg-green-500/10', borderColor: 'border-green-500/20' };
      }

      function updateResourceDashboard() {
        const resourceTableBody = document.getElementById('resourceTableBody');
        const stockAlertsContainer = document.getElementById('stockAlertsContainer');
        const stockAlertsList = document.getElementById('stockAlertsList');

        let healthyCount = 0, lowCount = 0, criticalCount = 0;
        const alerts = [];

        if (resourcesData.length === 0) {
          resourceTableBody.innerHTML = `
          `;
          stockAlertsContainer.classList.add('hidden');
          document.getElementById('totalResourceItems').textContent = '0';
          document.getElementById('healthyStockCount').textContent = '0';
          document.getElementById('lowStockCount').textContent = '0';
          document.getElementById('criticalStockCount').textContent = '0';
          return;
        }

        resourceTableBody.innerHTML = resourcesData.map(resource => {
          const daysRemaining = calculateDaysRemaining(resource.stock, resource.usageRate);
          const statusInfo = getStockStatus(resource.stock, resource.threshold);
          const utilizationPercent = Math.min(Math.round((resource.stock / (resource.threshold * 4)) * 100), 100);

          if (statusInfo.status === 'Healthy') healthyCount++;
          else if (statusInfo.status === 'Low Stock') {/* Lines 1513-1515 omitted */}
          else {/* Lines 1517-1519 omitted */}

          return `
          `;
        }).join('');

        document.getElementById('totalResourceItems').textContent = resourcesData.length;
        document.getElementById('healthyStockCount').textContent = healthyCount;
        document.getElementById('lowStockCount').textContent = lowCount;
        document.getElementById('criticalStockCount').textContent = criticalCount;
        document.getElementById('lowStockCount2').textContent = lowCount + criticalCount;

        if (alerts.length > 0) {
          stockAlertsContainer.classList.remove('hidden');
          stockAlertsList.innerHTML = alerts.map(alert => `
          `).join('');
          lucide.createIcons();
        } else {
          stockAlertsContainer.classList.add('hidden');
        }

        updateResourceTrendChart();
      }

      function updateResourceTrendChart() {
        if (!chartsMap.resourceTrendChart) return;
                
        const categories = [...new Set(resourcesData.map(r => r.category))];
        const categoryData = categories.map(cat => {
          const resources = resourcesData.filter(r => r.category === cat);
          return resources.reduce((sum, r) => sum + r.stock, 0);
        });

        chartsMap.resourceTrendChart.data.labels = categories.length > 0 ? categories : ['No Data'];
        chartsMap.resourceTrendChart.data.datasets[0].data = categoryData.length > 0 ? categoryData : [0];
        chartsMap.resourceTrendChart.update();
      }

      function initializeLocationPicker() {
        if (locationPickerMap) {
          locationPickerMap.remove();
        }

        locationPickerMap = L.map('locationPickerMap').setView([14.5995, 120.9842], 12);
                
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '© OpenStreetMap contributors',
          maxZoom: 19
        }).addTo(locationPickerMap);

        locationPickerMap.on('click', function(e) {
          const lat = e.latlng.lat.toFixed(6);
          const lng = e.latlng.lng.toFixed(6);
                    
          if (locationMarker) {/* Lines 1600-1601 omitted */}
                    
          locationMarker = L.marker([lat, lng]).addTo(locationPickerMap);
                    
          document.getElementById('patientLat').value = lat;
          document.getElementById('patientLng').value = lng;
          document.getElementById('coordsDisplay').textContent = `${lat}, ${lng}`;
          document.getElementById('selectedCoordinates').classList.remove('hidden');
        });

        setTimeout(() => {
          locationPickerMap.invalidateSize();
        }, 100);
      }

      function updateCityOptions(region) {
        const citySelect = document.getElementById('patientCity');
        const barangaySelect = document.getElementById('patientBarangay');
                
        citySelect.innerHTML = '<option value="">Select city</option>';
        barangaySelect.innerHTML = '<option value="">Select barangay</option>';
                
        if (region && philippineLocations[region]) {
          const cities = Object.keys(philippineLocations[region].cities);
          cities.forEach(city => {
            /* Lines 1626-1629 omitted */
            citySelect.appendChild(option);
          });
        }
      }

      function updateBarangayOptions(region, city) {
        const barangaySelect = document.getElementById('patientBarangay');
        barangaySelect.innerHTML = '<option value="">Select barangay</option>';
                
        if (region && city && philippineLocations[region] && philippineLocations[region].cities[city]) {
          const barangays = philippineLocations[region].cities[city].barangays;
          barangays.forEach(barangay => {
            /* Lines 1641-1644 omitted */
            barangaySelect.appendChild(option);
          });

          const coords = philippineLocations[region].cities[city].coords;
          if (locationPickerMap) {/* Lines 1649-1650 omitted */}
        }
      }

      function initializeDiseaseMap() {
        if (diseaseMap) return;
                
        diseaseMap = L.map('diseaseMapContainer').setView([14.5995, 120.9842], 12);
                
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '© OpenStreetMap contributors',
          maxZoom: 19
        }).addTo(diseaseMap);

        markerClusterGroup = L.markerClusterGroup({
          maxClusterRadius: 50,
          disableClusteringAtZoom: 16
        }).addTo(diseaseMap);
      }

      async function processPatientQueue() {
        if (isProcessingPatient || patientQueue.length === 0) return;
                
        isProcessingPatient = true;
        const patientData = patientQueue.shift();
                
        try {
          await new Promise(resolve => setTimeout(resolve, 500));
          updateDashboardWithPatientData(patientData);
          processedCount++;
          updatePendingUpdates();
        } catch (error) {
          console.error('Error processing patient:', error);
          patientQueue.unshift(patientData);
        } finally {
          isProcessingPatient = false;
          if (patientQueue.length > 0) {/* Lines 1687-1688 omitted */}
        }
      }

      function updatePendingUpdates() {
        document.getElementById('pendingUpdates').textContent = patientQueue.length;
      }

      function validatePatientData(patientData) {
        const errors = [];
        if (!patientData.name || patientData.name.trim() === '') errors.push('Patient name is required');
        if (!patientData.location || patientData.location.trim() === '') errors.push('Location is required');
        if (!patientData.condition || patientData.condition === '') errors.push('Condition is required');
        if (!patientData.severity || patientData.severity === '') errors.push('Severity level is required');
        if (!patientData.admissionDate || patientData.admissionDate === '') errors.push('Admission date is required');
        if (!patientData.contact || patientData.contact.trim() === '') errors.push('Emergency contact is required');
        return { isValid: errors.length === 0, errors };
      }

      function updateDiseaseMap() {
        if (!diseaseMap) initializeDiseaseMap();

        markerClusterGroup.clearLayers();
        if (heatmapLayer) diseaseMap.removeLayer(heatmapLayer);

        const locationMap = {};
        const heatmapData = [];
                
        diseaseData.forEach(entry => {
          const location = entry.location;
          if (!locationMap[location]) {/* Lines 1719-1725 omitted */}
                    
          const existingDisease = locationMap[location].diseases.find(d => d.name === entry.disease);
          if (existingDisease) {
            existingDisease.cases++;
          } else {/* Lines 1731-1732 omitted */}
          locationMap[location].totalCases++;
                    
          if (entry.severity === 'High' || locationMap[location].maxSeverity === 'Low') {
            locationMap[location].maxSeverity = entry.severity;
          } else if (entry.severity === 'Medium' && locationMap[location].maxSeverity !== 'High') {/* Lines 1738-1739 omitted */}

          if (locationMap[location].coordinates) {/* Lines 1742-1744 omitted */}
        });

        const getMarkerColor = (severity, cases) => {
          if (severity === 'High' || cases > 10) return '#ef4444';
          if (severity === 'Medium' || cases > 5) return '#eab308';
          return '#22c55e';
        };

        Object.entries(locationMap).forEach(([location, data]) => {
          if (!data.coordinates) return;

          const color = getMarkerColor(data.maxSeverity, data.totalCases);
                    
          const customIcon = L.divIcon({
            /* Lines 1759-1766 omitted */
            className: 'disease-marker'
          });

          const marker = L.marker(data.coordinates, { icon: customIcon });
                    
          const popupContent = `
          `;
                    
          marker.bindPopup(popupContent);
          markerClusterGroup.addLayer(marker);
        });

        if (heatmapData.length > 0) {
          heatmapLayer = L.heatLayer(heatmapData, {
            /* Lines 1788-1791 omitted */
            gradient: {0.0: '#22c55e', 0.5: '#eab308', 1.0: '#ef4444'}
          }).addTo(diseaseMap);
        }

        if (markerClusterGroup.getLayers().length > 0) {
          diseaseMap.fitBounds(markerClusterGroup.getBounds().pad(0.1));
        }

        const topLocations = document.getElementById('topLocations');
        const sortedLocations = Object.entries(locationMap)
          .sort((a, b) => b[1].totalCases - a[1].totalCases)
          .slice(0, 5);

        if (sortedLocations.length > 0) {
          topLocations.innerHTML = sortedLocations.map(([location, data]) => `
          `).join('');
        } else {
          topLocations.innerHTML = '<p class="text-xs text-muted">No data available yet</p>';
        }

        const diseaseDistribution = document.getElementById('diseaseDistribution');
        const diseaseCount = {};
        Object.values(locationMap).forEach(data => {
          data.diseases.forEach(d => {
            diseaseCount[d.name] = (diseaseCount[d.name] || 0) + d.cases;
          });
        });

        const sortedDiseases = Object.entries(diseaseCount)
          .sort((a, b) => b[1] - a[1])
          .slice(0, 5);

        if (sortedDiseases.length > 0) {
          diseaseDistribution.innerHTML = sortedDiseases.map(([disease, count]) => `
          `).join('');
        } else {
          diseaseDistribution.innerHTML = '<p class="text-xs text-muted">No data available yet</p>';
        }
      }


      // Update patient chart with actual admission dates (one dot per patient)
      function updatePatientChartWithDates() {
        // Group patients by admission date
        const dateCount = {};
        patientsData.forEach(patient => {
          const admissionDate = patient.admissionDate;
          if (admissionDate) {/* Lines 1850-1857 omitted */}
        });

        // Convert to chart data format
        const labels = Object.keys(dateCount);
        const data = Object.values(dateCount);

        // Update chart to show only dots (scatter points)
        chartsMap.patientsChart.data.labels = labels.length > 0 ? labels : [];
        chartsMap.patientsChart.data.datasets[0].data = data.length > 0 ? data : [];
        chartsMap.patientsChart.update();
      }

      function updateDashboardMetrics() {
        const totalPatients = patientsData.length;
        const criticalCases = patientsData.filter(p => p.severity === 'High').length;

        document.getElementById('totalPatientsCount').textContent = totalPatients;
        document.getElementById('totalPatientsStatus').textContent = totalPatients > 0 ? `+${totalPatients} this week` : 'Awaiting input';
        document.getElementById('totalPatientsAvg').textContent = `Daily Avg: ${Math.ceil(totalPatients / 7)} patients`;

        document.getElementById('criticalCasesCount').textContent = criticalCases;
        document.getElementById('criticalCasesStatus').textContent = criticalCases > 0 ? `${criticalCases} requiring attention` : 'Awaiting input';
        document.getElementById('criticalCasesCapacity').textContent = `ICU Capacity: ${Math.min(criticalCases * 10, 100)}%`;

        const today = new Date().toISOString().split('T')[0];
        const newPatientsToday = patientsData.filter(p => p.admissionDate === today).length;
        document.getElementById('newPatientsToday').textContent = newPatientsToday;
        document.getElementById('newPatientsTodayStatus').textContent = newPatientsToday > 0 ? `+${newPatientsToday} today` : 'Awaiting input';

        if (chartsMap.patientsChart) {
          chartsMap.patientsChart.data.datasets[0].data = [totalPatients, totalPatients, totalPatients, totalPatients, totalPatients, totalPatients, totalPatients];
          chartsMap.patientsChart.update();
        }

        if (chartsMap.criticalChart) {
          chartsMap.criticalChart.data.datasets[0].data = [criticalCases, criticalCases, criticalCases, criticalCases, criticalCases, criticalCases, criticalCases];
          chartsMap.criticalChart.update();
        }
      }

      function updateDashboardWithPatientData(patientData) {
        patientsData.push(patientData);

        const patientTableBody = document.getElementById('patientTableBody');
        const newRow = document.createElement('tr');
        newRow.className = 'border-b border-border hover:bg-secondary/50 transition-colors';
        newRow.innerHTML = `
          <td class="py-3 px-4 text-sm text-foreground">${patientData.id}</td>
          <td class="py-3 px-4 text-sm text-foreground">${patientData.name}</td>
          <td class="py-3 px-4 text-sm text-foreground">${patientData.location}</td>
          <td class="py-3 px-4 text-sm text-foreground">${patientData.condition}</td>
          <td class="py-3 px-4 text-sm">
            /* Lines 1910-1917 omitted */
          </td>
          <td class="py-3 px-4 text-sm text-foreground">${patientData.admissionDate}</td>
        `;
        patientTableBody.appendChild(newRow);

        if (patientData.updateDiseaseTracking) {
          addDiseaseTrackingEntry(patientData);
        }

        updateDashboardMetrics();
      }

      function addDiseaseTrackingEntry(patientData) {
        const diseaseEntry = {
          location: patientData.location,
          disease: patientData.condition,
          severity: patientData.severity,
          coordinates: patientData.coordinates,
          timestamp: new Date().toLocaleString()
        };
        diseaseData.push(diseaseEntry);
        updateDiseaseMap();
      }

      document.addEventListener('DOMContentLoaded', () => {
        const yesHealthcareBtn = document.getElementById('yesHealthcareBtn');
        const noHealthcareBtn = document.getElementById('noHealthcareBtn');
        const backToRoleBtn = document.getElementById('backToRoleBtn');
        const logoutBtn = document.getElementById('logoutBtn');
        const loginForm = document.getElementById('loginForm');
        const errorMessage = document.getElementById('errorMessage');
        const userRoleBadge = document.getElementById('userRoleBadge');
        const logoutBtnText = document.getElementById('logoutBtnText');
        const readOnlyNotice = document.getElementById('readOnlyNotice');
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        const showAlertFormBtn = document.getElementById('showAlertFormBtn');
        const alertFormContainer = document.getElementById('alertFormContainer');
        const cancelAlertFormBtn = document.getElementById('cancelAlertFormBtn');
        const alertForm = document.getElementById('alertForm');

        const patientRegionSelect = document.getElementById('patientRegion');
        const patientCitySelect = document.getElementById('patientCity');
        const patientBarangaySelect = document.getElementById('patientBarangay');
        const moduleFilter = document.getElementById('moduleFilter');

        if (patientRegionSelect) {
          patientRegionSelect.addEventListener('change', (e) => {
            updateCityOptions(e.target.value);
          });
        }

        if (patientCitySelect) {
          patientCitySelect.addEventListener('change', (e) => {
            /* Lines 1972-1973 omitted */
            updateBarangayOptions(region, e.target.value);
          });
        }

        if (moduleFilter) {
          moduleFilter.addEventListener('change', (e) => {
            renderTrainingModules(e.target.value);
          });
        }

        if (showAlertFormBtn) {
          showAlertFormBtn.addEventListener('click', (e) => {
          });
        }

        if (cancelAlertFormBtn) {
          cancelAlertFormBtn.addEventListener('click', (e) => {
            /* Lines 1996-1999 omitted */
            alertForm.reset();
          });
        }

        if (alertForm) {
          alertForm.addEventListener('submit', (e) => {
            /* Lines 2005-2027 omitted */                        
            alert('Alert added successfully!');
          });
        }

        yesHealthcareBtn.addEventListener('click', () => {
          showView('loginView');
          errorMessage.classList.add('hidden');
          errorMessage.textContent = '';
        });

        noHealthcareBtn.addEventListener('click', () => {
          userRoleBadge.textContent = 'Viewer';
          userRoleBadge.className = 'px-3 py-1 text-sm rounded-full bg-blue-500/10 text-blue-500 border border-blue-500/20';
          logoutBtnText.textContent = 'Go Back';
          readOnlyNotice.classList.remove('hidden');

          document.querySelectorAll('.healthcare-only').forEach(el => {
            el.style.display = 'none';
          });

          showView('dashboardView');
          lucide.createIcons();
          setTimeout(() => {
            /* Lines 2050-2052 omitted */
            renderTrainingModules();
          }, 100);
        });

        backToRoleBtn.addEventListener('click', () => {
          showView('roleSelectionView');
        });

        logoutBtn.addEventListener('click', () => {
          showView('roleSelectionView');
          loginForm.reset();
          errorMessage.classList.add('hidden');
        });

        loginForm.addEventListener('submit', (e) => {
          e.preventDefault();

          const emailInput = document.getElementById('email');
          const passwordInput = document.getElementById('password');
          const email = (emailInput.value || '').trim();
          const password = (passwordInput.value || '').trim();

          // Basic checks
          if (email === '' || password === '') {/* Lines 2076-2079 omitted */}

          // Ensure email contains '@'
          if (!email.includes('@')) {/* Lines 2083-2087 omitted */}

          // Optional: stronger but simple email pattern (user can decide to use it)
          const simpleEmailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!simpleEmailRegex.test(email)) {/* Lines 2092-2096 omitted */}

          // Optional: password minimum (recommended)
          if (password.length < 6) {/* Lines 2100-2104 omitted */}

          // Clear any previous error
          errorMessage.classList.add('hidden');
          errorMessage.textContent = '';

          // Proceed with the existing post-login UI updates (no change to logic)
          userRoleBadge.textContent = 'Healthcare Worker';
          userRoleBadge.className = 'px-3 py-1 text-sm rounded-full bg-green-500/10 text-green-500 border border-green-500/20';
          logoutBtnText.textContent = 'Log Out';
          readOnlyNotice.classList.add('hidden');

          document.querySelectorAll('.healthcare-only').forEach(el => {
            el.style.display = '';
          });

          loginForm.reset();

          showView('dashboardView');
          lucide.createIcons();
          setTimeout(() => {
            /* Lines 2125-2127 omitted */
            renderTrainingModules();
          }, 100);
          });

        tabButtons.forEach(button => {
          button.addEventListener('click', () => {
          });
        });

        const patientForm = document.getElementById('patientForm');
        if (patientForm) {/* Lines 2159-2228 omitted */}

        const resourceForm = document.getElementById('resourceForm');
        if (resourceForm) {/* Lines 2232-2256 omitted */}

        lucide.createIcons();
                
        const chartConfig = {/* Lines 2261-2269 omitted */};

        chartsMap.patientsChart = new Chart(document.getElementById('patientsChart'), {
          /* Lines 2272-2284 omitted */
          options: chartConfig
        });

        chartsMap.criticalChart = new Chart(document.getElementById('criticalChart'), {
          /* Lines 2288-2300 omitted */
          options: chartConfig
        });

        chartsMap.resourceTrendChart = new Chart(document.getElementById('resourceTrendChart'), {
        });

        renderAlerts();
        renderTrainingModules();

        // Check for expired alerts every 5 minutes
        setInterval(checkAndDismissExpiredAlerts, 5 * 60 * 1000);
        checkAndDismissExpiredAlerts();
      });
    </script>
  </body>
  </html>
