<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQRA — Enterprise Educational Platform</title>
    <meta name="description" content="IQRA is an enterprise educational content management platform for OCR ingestion, question bank management, and paper generation.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="{{ asset('js/notification.js') }}"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        body {
            font-family: 'Outfit', sans-serif !important;
            background-color: #f4f7fb !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .pulse-dot { animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

        /* Navy colors from ZIWO */
        .text-navy-900 { color: #0a1c2f !important; }
        .bg-navy-900 { background-color: #0a1c2f !important; }
        .border-navy-900 { border-color: #0a1c2f !important; }
        .from-navy-900 { --tw-gradient-from: #0a1c2f; }

        /* ============================================
           SIDEBAR SYSTEM (100% Cloned from ZIWO layout)
           ============================================ */
        .sidebar-wrap {
            width: 260px;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: visible;
            flex-shrink: 0;
            position: relative;
        }

        .sidebar-wrap.collapsed {
            width: 68px;
        }

        .sidebar-inner {
            width: 100%;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Premium Floating Toggle Tab */
        .sidebar-toggle-tab {
            position: absolute;
            right: -20px;
            top: 50%;
            margin-top: -20px;
            z-index: 60;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ffffff, #f1f5f9);
            border: 3px solid #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            color: #64748b;
        }

        .sidebar-toggle-tab:hover {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-color: #3b82f6;
            color: white;
            transform: translate(2px, -2px) !important;
            box-shadow: -4px 4px 0px #1e40af, 0 10px 15px -3px rgba(37, 99, 235, 0.4);
        }

        .sidebar-toggle-tab:active {
            transform: translate(0px, 0px) !important;
            box-shadow: 0 2px 5px rgba(37, 99, 235, 0.3);
        }

        .sidebar-toggle-tab i {
            font-size: 0.85rem;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .sidebar-wrap.collapsed .sidebar-toggle-tab i {
            transform: rotate(180deg) scale(1.1);
        }

        /* Nav item base */
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.625rem 0.75rem;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 0.8rem;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            position: relative;
            text-decoration: none;
            gap: 0.75rem;
            -webkit-tap-highlight-color: transparent;
            -webkit-user-select: none;
            user-select: none;
            outline: none;
        }

        .nav-item:hover {
            background-color: #f1f5f9;
            color: #3b82f6;
        }

        .nav-item.active {
            background-color: #2563eb !important;
            color: white !important;
            box-shadow: -4px 4px 0px #1e40af, 0 10px 15px -3px rgba(37, 99, 235, 0.4) !important;
            transform: translate(3px, -3px) !important;
            font-weight: 950 !important;
            position: relative;
            z-index: 10;
        }

        .nav-item.active * {
            color: white !important;
        }

        .nav-item.active:hover {
            background-color: #1d4ed8 !important;
            transform: translate(1px, -1px) !important;
            box-shadow: -2px 2px 0px #1e40af, 0 10px 15px -3px rgba(37, 99, 235, 0.4) !important;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
            color: #94a3b8;
            transition: color 0.2s;
        }

        .nav-item.active .nav-icon {
            color: white !important;
        }

        .nav-label {
            transition: opacity 0.2s ease, transform 0.2s ease;
            opacity: 1;
            transform: translateX(0);
            overflow: hidden;
        }

        .collapsed .nav-label {
            opacity: 0;
            transform: translateX(-8px);
            pointer-events: none;
            width: 0;
        }

        /* Section headings */
        .nav-section {
            font-size: 0.6rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #cbd5e1;
            padding: 0.875rem 0.875rem 0.375rem;
            white-space: nowrap;
            transition: opacity 0.2s ease;
        }

        .collapsed .nav-section {
            opacity: 0;
            height: 0;
            padding: 0;
            overflow: hidden;
        }

        .collapsed .nav-section-divider {
            display: block !important;
        }

        .nav-section-divider {
            display: none;
            height: 1px;
            background: #e2e8f0;
            margin: 0.5rem 0.75rem;
        }

        /* Tooltip */
        .nav-tooltip {
            position: absolute;
            left: calc(100% + 12px);
            top: 50%;
            transform: translateY(-50%);
            background: #0f172a;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.15s ease, transform 0.15s ease;
            transform: translateY(-50%) translateX(-4px);
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .nav-tooltip::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: #0f172a;
        }

        .collapsed .nav-item:hover .nav-tooltip {
            opacity: 1;
            transform: translateY(-50%) translateX(0);
        }

        /* User footer */
        .sidebar-user-label {
            transition: opacity 0.2s ease, width 0.2s ease;
            overflow: hidden;
        }

        .collapsed .sidebar-user-label {
            opacity: 0;
            width: 0;
        }

        /* Brand area */
        .sidebar-brand-text {
            transition: opacity 0.2s ease, width 0.2s ease;
            overflow: hidden;
            white-space: nowrap;
        }

        .collapsed .sidebar-brand-text {
            opacity: 0;
            width: 0;
        }

        /* Primary badge pill */
        .nav-badge {
            margin-left: auto;
            font-size: 0.55rem;
            font-weight: 900;
            padding: 0.1rem 0.4rem;
            border-radius: 9999px;
            transition: opacity 0.2s ease;
        }

        .collapsed .nav-badge {
            opacity: 0;
        }

        /* Row Density Styles */
        .condensed-table td,
        .condensed-table th {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
            font-size: 0.7rem !important;
        }

        .spacious-table td,
        .spacious-table th {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
            font-size: 0.75rem !important;
        }

        /* 3D Active Card Indicators */
        .card-3d-active {
            transform: translate(2px, -2px) !important;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        }
        .card-3d-active.amber { box-shadow: -4px 4px 0px #b45309, 0 10px 15px -3px rgba(245, 158, 11, 0.4) !important; }
        .card-3d-active.blue  { box-shadow: -4px 4px 0px #1e40af, 0 10px 15px -3px rgba(37, 99, 235, 0.4) !important; }
        .card-3d-active.indigo { box-shadow: -4px 4px 0px #4338ca, 0 10px 15px -3px rgba(79, 70, 229, 0.4) !important; }
        .card-3d-active.purple { box-shadow: -4px 4px 0px #7e22ce, 0 10px 15px -3px rgba(147, 51, 234, 0.4) !important; }
        .card-3d-active.emerald { box-shadow: -4px 4px 0px #047857, 0 10px 15px -3px rgba(16, 185, 129, 0.4) !important; }
        .card-3d-active.rose { box-shadow: -4px 4px 0px #be123c, 0 10px 15px -3px rgba(225, 29, 72, 0.4) !important; }
        .card-3d-active.sky { box-shadow: -4px 4px 0px #0369a1, 0 10px 15px -3px rgba(14, 165, 233, 0.4) !important; }
        .card-3d-active.slate { box-shadow: -4px 4px 0px #334155, 0 10px 15px -3px rgba(71, 85, 105, 0.4) !important; }
    </style>
</head>
