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

        /* Row Density Styles from ZIWO */
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

        /* 3D Active Card Indicators from ZIWO */
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
