<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - WKG</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f7fafc;
            color: #2d3748;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Banner Styles */
        .banner {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }
        
        .school-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .school-logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            color: #1e40af;
        }
        
        .school-name {
            font-weight: 700;
            font-size: 20px;
            letter-spacing: 0.5px;
        }
        
        .portal-tag {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 16px;
            backdrop-filter: blur(10px);
        }
        
        /* Updated CSS for your working code structure */
        .bg-white {
            background: white;
        }
        
        .shadow {
            box-shadow: 0 4px 12px rgba(235, 45, 45, 0.08);
        }
        
        .rounded-lg {
            border-radius: 12px;
        }
        
        .p-6 {
            padding: 25px;
        }
        
        .mb-6 {
            margin-bottom: 25px;
        }
        
        .flex {
            display: flex;
        }
        
        .flex-col {
            flex-direction: column;
        }
        
        .items-center {
            align-items: center;
        }
        
        .space-y-4 > * + * {
            margin-top: 16px;
        }
        
        .border {
            border: 1px solid #e2e8f0;
        }
        
        .border-blue-100 {
            border-color: #e2e8f0;
        }
        
        .w-28 {
            width: 112px;
        }
        
        .h-28 {
            height: 112px;
        }
        
        .rounded-full {
            border-radius: 50%;
        }
        
        .object-cover {
            object-fit: cover;
        }
        
        .ring-4 {
            box-shadow: 0 0 0 4px;
        }
        
        .ring-blue-500 {
            box-shadow-color: #3b82f6;
        }
        
        .text-center {
            text-align: center;
        }
        
        .md\:text-left {
            text-align: left;
        }
        
        .text-lg {
            font-size: 18px;
        }
        
        .font-bold {
            font-weight: 700;
        }
        
        .text-gray-800 {
            color: #01050fff;
        }
        
        .text-sm {
            font-size: 15px;
        }
        
        .text-gray-600 {
            color: #060606ff;
        }
        
        /* Responsive styles */
        @media (min-width: 768px) {
            .md\:flex-row {
                flex-direction: row;
            }
            
            .md\:space-y-0 > * + * {
                margin-top: 0;
            }
            
            .md\:space-x-6 > * + * {
                margin-left: 24px;
            }
        }
        
        /* Announcements Section */
        .announcements {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-bottom: 25px;
            border-left: 4px solid #ed8936;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #ed8936;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .announcement-list {
            list-style: none;
        }
        
        .announcement-item {
            padding: 15px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .announcement-item:last-child {
            border-bottom: none;
        }
        
        .announcement-text {
            color: #2d3748;
            margin-bottom: 8px;
            line-height: 1.5;
        }
        
        .announcement-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #718096;
            font-size: 14px;
        }
        
        .announcement-title {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 5px;
            font-size: 1.1rem;
        }
        
        .read-more {
            color: #6366f1;
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 5px;
            display: inline-block;
        }
        
        .read-more:hover {
            text-decoration: underline;
        }
        
        .attachment-link {
            display: inline-flex;
            align-items: center;
            color: #6366f1;
            text-decoration: none;
            font-size: 0.9rem;
            margin-top: 8px;
        }
        
        .attachment-link:hover {
            text-decoration: underline;
        }
        
        .announcement-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .badge-urgent {
            background-color: #fee2e2;
            color: #dc2626;
        }
        
        .badge-payment {
            background-color: #fef3c7;
            color: #d97706;
        }
        
        .badge-general {
            background-color: #dbeafe;
            color: #2563eb;
        }
        
        .hidden {
            display: none;
        }
        
        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-top: 30px;
        }
        
        @media (min-width: 768px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        .dashboard-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(12, 216, 94, 0.08);
            padding: 20px;
            transition: transform 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-content {
            color: #085ceeff;
        }
        
        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            margin-top: 40px;
            color: #718096;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
        }
        
        /* Utility Classes */
        .text-blue {
            color: #3b82f6;
        }
        
        .text-bold {
            font-weight: 700;
        }
        
        .mb-4 {
            margin-bottom: 16px;
        }
        
        .student-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #3b82f6;
            flex-shrink: 0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-expired {
            background-color: #fee2e2;
            color: #991b1b;
        }

        
        








        /* Logout Button Styles */
.student-info-container {
    /* Remove positioning and padding for absolute positioning */
    position: static;
    padding-right: 0;
    min-height: auto;
}

form.button {
    /* Use normal flow instead of absolute positioning */
    position: static;
    display: flex;
    justify-content: flex-end; /* Align button to the right */
    margin-top: 20px; /* Add space between student info and button */
    width: 100%;
}

.logout-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 3px 6px rgba(220, 38, 38, 0.2);
    white-space: nowrap; /* Prevent text wrapping */
}

.logout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(220, 38, 38, 0.3);
    background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
}

.logout-btn:active {
    transform: translateY(0);
}

/* Responsive adjustments - keep existing mobile styles */
@media (max-width: 768px) {
    form.button {
        justify-content: center; /* Center button on mobile */
    }
}
    </style>
</head>
<body>
    <div class="container">
        <!-- Banner Container -->
        <div class="banner">
            <div class="school-info">
                <div class="school-logo">EDMOL</div>
                <div class="school-name">EDMOL HIGH SCHOOL</div>
            </div>
            <div class="portal-tag">Student Portal</div>
        </div>

        

        <!-- Your Original Working Code - Student Info Card -->
        <div class="bg-white shadow rounded-lg p-6 mb-6 flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6 border border-blue-100">
            <!-- Student Image -->    
            
        @php
            $initials = implode('', array_map(function($word) {
                return strtoupper(substr(trim($word), 0, 1));
            }, array_slice(explode(' ', $user->name), 0, 2)));
            
            $svgFallback = 'data:image/svg+xml;utf8,'.rawurlencode(
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">'
                .'<rect width="100" height="100" fill="#0c61ebff"/>'
                .'<text x="50" y="60" font-size="40" text-anchor="middle" fill="white" font-weight="bold">'
                .($initials ?: '?')
                .'</text></svg>'
            );
        @endphp

        <img src="{{ $user->image ? asset('storage/'.$user->image).'?v='.time() : $svgFallback }}" 
             class="student-image"
             onerror="this.onerror=null;this.src='{{ $svgFallback }}'"
             alt="{{ $user->name }} avatar">

        
         <!-- Student Info Container -->
       <div class="student-info-container text-center md:text-left">
           <div class="text-center md:text-left">
            <h2 class="text-lg font-bold text-gray-800">{{ $user->name }}</h2>
            <p class="text-sm text-gray-600">ID: {{ $user->registration_id }}</p>
            <p class="text-sm text-gray-600">Grade: 
                @if($user->grade)
                    {{ $user->grade->level }}{{ $user->grade->section }}
                @else
                    Not assigned
                @endif
            </p>
            <p class="text-sm text-gray-600">Email: {{ $user->email }}</p>
            <p class="text-sm text-gray-600">Role: {{ ucfirst($user->role) }}</p>
            <form class="button" method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </button>
    </form>
              
    </div>
        </div>
        </div>
        
        

        <!-- Dashboard Content -->
        <h3 class="mb-4 text-xl font-bold text-gray-800">Recent Activity</h3>
        <p class="mb-6 text-gray-600">This is your student dashboard. More features coming soon!</p>

        <!-- Updated Announcements Section -->
        <div class="announcements">
            <h4 class="section-title">
                <i class="fas fa-bullhorn"></i>
                Announcements
            </h4>
            <ul class="announcement-list">
                @foreach($announcements as $announcement)
                <li class="announcement-item">
                    <div class="announcement-title">
                        {{ $announcement->title }}
                        <span class="announcement-badge {{ 
                            $announcement->type === 'urgent' ? 'badge-urgent' : 
                            ($announcement->type === 'payment' ? 'badge-payment' : 'badge-general') 
                        }}">
                            {{ ucfirst($announcement->type) }}
                        </span>
                        
                        @if($announcement->end_date && now()->gt($announcement->end_date))
                            <span class="status-badge status-expired">Expired</span>
                        @else
                            <span class="status-badge status-active">Active</span>
                        @endif
                    </div>
                    
                    <p class="announcement-text">
                        <div id="short-{{ $announcement->id }}">
                            {!! Str::limit(strip_tags($announcement->content), 150) !!}
                            @if(strlen(strip_tags($announcement->content)) > 150)
                                <span class="read-more" onclick="toggleContent({{ $announcement->id }})">...read more</span>
                            @endif
                        </div>
                        <div id="full-{{ $announcement->id }}" class="hidden">
                            {!! strip_tags($announcement->content) !!}
                            <span class="read-more" onclick="toggleContent({{ $announcement->id }})">...show less</span>
                        </div>
                    </p>
                    
                    @if($announcement->attachment)
                    <a href="{{ asset('storage/'.$announcement->attachment) }}" target="_blank" class="attachment-link">
                        <i class="fas fa-paperclip mr-1"></i> View attachment
                    </a>
                    @endif
                    
                    <div class="announcement-meta">
                        <i class="far fa-clock"></i>
                        <span>Posted on {{ $announcement->start_date->format('M d, Y') }}</span>
                        @if($announcement->end_date)
                        <span>to {{ $announcement->end_date->format('M d, Y') }}</span>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Dashboard Cards Grid -->
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h5 class="card-title">
                    <i class="fas fa-book text-blue"></i>
                    Courses
                </h5>
                <div class="card-content">
                    <p>View your current courses, assignments, and progress.</p>
                </div>
            </div>
            <div class="dashboard-card">
                <h5 class="card-title">
                    <i class="fas fa-chart-line text-blue"></i>
                    Grades
                </h5>
                <div class="card-content">
                    <p>Check your latest grades and performance reports.</p>
                </div>
            </div>
            <div class="dashboard-card">
                <h5 class="card-title">
                    <i class="fas fa-calendar-alt text-blue"></i>
                    Schedule
                </h5>
                <div class="card-content">
                    <p>View your class schedule and upcoming events.</p>
                </div>
            </div>
        </div>
    </div>

    
    
    <footer>
         <div class="text-sm text-white">
    <p>&copy; <span id="currentYear"></span> EDMOL SCHOOL. All rights reserved.</p>
    <p>Developed by <a href="#" class="text-cyan-300 hover:text-cyan-200 transition-colors font-medium"> ||Potiphar G Vaye||</a></p>
</div>
    <script>
    // Display current year
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>
    
    </footer>



    <script>
        // Simple JavaScript to demonstrate interactivity
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.dashboard-card');
            
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    const title = this.querySelector('.card-title').textContent;
                    alert(`You clicked on ${title}`);
                });
            });
            
            // Update the banner with a greeting based on time of day
            const updateBannerGreeting = () => {
                const hour = new Date().getHours();
                let greeting = "Welcome";
                
                if (hour < 12) greeting = "Good Morning";
                else if (hour < 18) greeting = "Good Afternoon";
                else greeting = "Good Evening";
                
                const portalTag = document.querySelector('.portal-tag');
                portalTag.textContent = `${greeting}, Student!`;
            };
            
            updateBannerGreeting();
        });
        
        // Function to toggle announcement content
        function toggleContent(id) {
            const shortContent = document.getElementById('short-' + id);
            const fullContent = document.getElementById('full-' + id);
            
            if (shortContent.classList.contains('hidden')) {
                shortContent.classList.remove('hidden');
                fullContent.classList.add('hidden');
            } else {
                shortContent.classList.add('hidden');
                fullContent.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>