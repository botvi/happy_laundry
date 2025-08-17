<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="kode-unik" content="{{ $kode_unik }}">
    <meta name="nama-link" content="{{ $nama_link }}">
    <link rel="shortcut icon" href="{{ asset('env/logo.jpg') }}" type="image/x-icon">
    <title>LinkSkuy - Editor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        
        .editor-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        
        .sidebar {
            width: 350px;
            background: white;
            border-right: 2px solid #e5e7eb;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            position: relative;
        }
        
        /* Drag and drop visual improvements */
        .sidebar.dragging {
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
        }
        
        .sidebar.dragging::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(34, 211, 153, 0.05));
            pointer-events: none;
            z-index: -1;
        }
        
        .preview-area {
            flex: 1;
            background: #f0f0f0;
            display: flex;
            flex-direction: column;
        }
        
        .preview-header {
            background: rgb(255, 255, 255);
            padding: 15px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .preview-iframe {
            flex: 1;
            border: none;
            background: white;
            width: 100%;
            margin: 0;
        }
        
        .element-item {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin: 8px;
            padding: 12px;
            cursor: grab;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            position: relative;
            overflow: hidden;
        }
        
        .element-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(16, 185, 129, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .element-item.dragging::before {
            left: 100%;
        }
        
        .element-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .element-item.drag-over {
            transform: translateY(2px);
            border-color: #10b981;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
            transition: all 0.2s ease;
        }
        
        .element-item.drag-over .drag-handle {
            color: #10b981;
            transform: scale(1.2);
        }
        
        /* Subtle pulse animation for drag over state */
        .element-item.drag-over {
            animation: dragOverPulse 1s ease-in-out infinite;
        }
        
        @keyframes dragOverPulse {
            0%, 100% { 
                box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
            }
            50% { 
                box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
            }
        }
        
        .element-item:active {
            cursor: grabbing;
        }
        
        .element-item.dragging {
            opacity: 0.9;
            transform: rotate(1deg) scale(1.02);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .element-item.drag-over {
            transform: translateY(2px);
            border-color: #10b981;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
            transition: all 0.2s ease;
        }
        
        .drop-zone {
            height: 6px;
            background: transparent;
            margin: 3px 0;
            border-radius: 3px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        .drop-zone.active {
            background: linear-gradient(90deg, #10b981, #34d399);
            height: 8px;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
            transform: scaleX(1.1);
        }
        
        .drop-zone.hover {
            background: linear-gradient(90deg, #34d399, #6ee7b7);
            height: 10px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
        
        .drop-zone::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 0;
            height: 0;
            background: #10b981;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .drop-zone.active::before {
            width: 4px;
            height: 4px;
        }
        
        .drop-zone.hover::before {
            width: 6px;
            height: 6px;
        }
        
        /* Success animation for reordered elements */
        @keyframes reorderSuccess {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .element-item.reorder-success {
            animation: reorderSuccess 0.6s ease-in-out;
        }
        
        /* Improved drop zone animations */
        .drop-zone {
            transform-origin: center;
        }
        
        .drop-zone.active {
            animation: dropZoneActive 0.3s ease-out;
        }
        
        @keyframes dropZoneActive {
            0% { transform: scaleX(1); }
            50% { transform: scaleX(1.3); }
            100% { transform: scaleX(1.1); }
        }
        
        .element-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .element-title {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }
        
        .element-controls {
            display: flex;
            gap: 8px;
        }
        
        .control-btn {
            padding: 4px 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s ease;
        }
        
        .btn-edit {
            background: #3b82f6;
            color: white;
        }
        
        .btn-edit:hover {
            background: #2563eb;
        }
        
        .btn-delete {
            background: #ef4444;
            color: white;
        }
        
        .btn-delete:hover {
            background: #dc2626;
        }
        
        .btn-toggle {
            background: #10b981;
            color: white;
        }
        
        .btn-toggle:hover {
            background: #059669;
        }
        
        .btn-toggle.hidden {
            background: #6b7280;
        }
        
        .drag-handle {
            color: #9ca3af;
            margin-right: 8px;
            cursor: grab;
            transition: all 0.2s ease;
            padding: 4px;
            border-radius: 4px;
        }
        
        .drag-handle:hover {
            color: #6b7280;
            background: rgba(107, 114, 128, 0.1);
        }
        
        .drag-handle:active {
            cursor: grabbing;
            color: #10b981;
            background: rgba(16, 185, 129, 0.1);
        }
        
        .drop-zone {
            height: 4px;
            background: transparent;
            margin: 2px 0;
            border-radius: 2px;
            transition: all 0.2s ease;
        }
        
        .drop-zone.active {
            background: #10b981;
            height: 8px;
        }
        
        .sidebar-header {
            background: #1f2937;
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .sidebar-content {
            padding: 20px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .section-title.text-gray-500 {
            color: #6b7280;
            border-bottom-color: #d1d5db;
        }
        
        .element-item.opacity-75 {
            opacity: 0.75;
            background: #f9fafb;
            border-color: #d1d5db;
        }
        
        .element-item.opacity-75:hover {
            opacity: 0.9;
            background: #f3f4f6;
        }
        
        .add-element-btn {
            width: 100%;
            padding: 12px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 20px;
        }
        
        .add-element-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }
        
        .element-preview {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 8px;
            margin: 4px 0;
            font-size: 12px;
            color: #6b7280;
        }
        
        .save-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.2s ease;
        }
        
        .save-btn:hover {
            background: #059669;
            transform: translateY(-2px);
        }
        
        .mobile-toggle-btn {
            display: none;
            padding: 8px 12px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
        }
        
        .mobile-close-btn {
            display: none;
        }

        /* Mobile Responsive Styles - Simplified */
        @media (max-width: 768px) {
            .editor-container {
                flex-direction: column;
                height: 100vh;
            }
            
            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100vh;
                max-height: 100vh;
                border-right: none;
                border-bottom: none;
                order: 0;
                z-index: 100000;
                transition: left 0.3s ease;
                background: white;
                overflow-y: auto;
            }
            
            .sidebar.mobile-open {
                left: 0;
            }
            
            .preview-area {
                order: 0;
                flex: 1;
                min-height: 100vh;
                width: 100%;
            }
            
            .preview-header {
                padding: 12px 16px;
                flex-direction: row;
                gap: 12px;
                align-items: center;
                justify-content: space-between;
            }
            
            .preview-header .flex {
                justify-content: flex-end;
                gap: 8px;
            }
            
            .mobile-toggle-btn {
                display: block;
            }
            
            .control-btn {
                padding: 8px 12px;
                font-size: 11px;
            }
            
            .sidebar-header {
                padding: 16px;
                position: relative;
            }
            
            .sidebar-header h1 {
                font-size: 18px;
            }
            
            .sidebar-header p {
                font-size: 12px;
            }
            
            .sidebar-content {
                padding: 16px;
            }
            
            .section-title {
                font-size: 16px;
                margin-bottom: 12px;
            }
            
            .add-element-btn {
                padding: 10px;
                font-size: 14px;
                margin-bottom: 16px;
            }
            
            .element-item {
                margin: 6px;
                padding: 10px;
            }
            
            .element-title {
                font-size: 13px;
            }
            
            .element-controls {
                gap: 6px;
            }
            
            .control-btn {
                padding: 6px 8px;
                font-size: 11px;
            }
            
            .element-preview {
                font-size: 11px;
                padding: 6px;
            }
            
            .save-btn {
                bottom: 16px;
                right: 16px;
                padding: 10px 20px;
                font-size: 14px;
            }
            
                    /* Mobile Modal */
        #addElementModal .bg-white {
            margin: 0;
            max-width: 100%;
            max-height: 85vh;
        }
        
        #addElementModal h3 {
            font-size: 16px;
        }
        
        /* Ensure modal content is responsive */
        #addElementModal .p-6 {
            padding: 1.5rem;
        }
        
        @media (max-width: 640px) {
            #addElementModal .p-6 {
                padding: 1rem;
            }
            
            #addElementModal .max-w-2xl {
                max-width: 95vw;
            }
        }
        
        /* Mobile responsive badges */
        @media (max-width: 768px) {
            #addElementModal .element-item .element-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            #addElementModal .element-item .element-header .flex-shrink-0 {
                align-self: flex-end;
                margin-left: 0 !important;
            }
            
            #addElementModal .element-item .element-title {
                font-size: 14px;
            }
            
            #addElementModal .element-item .element-preview {
                font-size: 12px;
            }
            
            #addElementModal .element-item {
                padding: 12px;
            }
            
            #addElementModal .element-item .w-12.h-12 {
                width: 40px;
                height: 40px;
            }
            
            #addElementModal .element-item .w-12.h-12 i {
                font-size: 16px;
            }
            
            #addElementModal .element-item .flex-1.min-w-0 {
                margin-right: 0;
            }
        }
            
            /* Mobile Preview */
            .preview-iframe {
                min-height: 300px;
            }
            
                                /* Mobile sidebar close button */
            .mobile-close-btn {
                display: flex;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                max-height: 100vh;
            }
            
            .preview-area {
                min-height: 100vh;
            }
            
            .preview-header {
                padding: 10px 12px;
            }
            
            .control-btn {
                padding: 6px 10px;
                font-size: 10px;
            }
            
            .sidebar-content {
                padding: 12px;
            }
            
            .element-item {
                margin: 4px;
                padding: 8px;
            }
            
            .element-title {
                font-size: 12px;
            }
            
            .element-controls {
                gap: 4px;
            }
            
            .control-btn {
                padding: 4px 6px;
                font-size: 10px;
            }
            
            .element-preview {
                font-size: 10px;
                padding: 4px;
            }
            
            .save-btn {
            
            /* Extra small screens */
            @media (max-width: 480px) {
                #addElementModal .element-item .element-header {
                    gap: 6px;
                }
                
                #addElementModal .element-item .element-title {
                    font-size: 13px;
                }
                
                #addElementModal .element-item .element-preview {
                    font-size: 11px;
                }
                
                #addElementModal .element-item {
                    padding: 10px;
                }
                
                #addElementModal .element-item .w-12.h-12 {
                    width: 36px;
                    height: 36px;
                }
                
                #addElementModal .element-item .w-12.h-12 i {
                    font-size: 14px;
                }
                
                #addElementModal .element-item .flex-shrink-0 span {
                    font-size: 10px;
                    padding: 4px 8px;
                }
                
                #addElementModal .element-item .flex-1.min-w-0 {
                    margin-right: 0;
                }
            }
        }
        
        /* Modal styling */
        #addElementModal .element-item {
            transition: all 0.3s ease;
            border: 2px solid transparent;
            margin-bottom: 12px;
        }
        
        #addElementModal .element-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: #3b82f6;
        }
        
        #addElementModal .element-item:active {
            transform: translateY(0);
        }
        
        #addElementModal .element-item .element-header {
            margin-bottom: 0;
        }
        
        #addElementModal .element-item .element-title {
            font-size: 16px;
            margin-bottom: 4px;
        }
        
        #addElementModal .element-item .element-preview {
            font-size: 14px;
            line-height: 1.4;
        }
        
        /* Line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Truncate utility */
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* Ensure badges are always visible */
        #addElementModal .element-item .flex-shrink-0 {
            position: relative;
            z-index: 10;
        }
        
        #addElementModal .element-item .flex-shrink-0 span {
            display: inline-block;
            max-width: 100%;
            word-wrap: break-word;
            white-space: nowrap;
        }
                bottom: 12px;
                right: 12px;
                padding: 8px 16px;
                font-size: 13px;
            }
            
            /* Mobile Preview */
            .preview-iframe {
                min-height: 250px;
            }
            
            /* Extra small mobile improvements */
            .sidebar-header {
                padding: 12px;
            }
            
            .sidebar-header h1 {
                font-size: 16px;
            }
            
            .sidebar-header p {
                font-size: 11px;
            }
            
            .section-title {
                font-size: 14px;
            }
            
            .add-element-btn {
                padding: 8px;
                font-size: 13px;
            }
            
            /* Mobile modal for extra small screens */
            #addElementModal .bg-white {
                margin: 0;
                max-width: 100%;
                max-height: 90vh;
            }
            
            #addElementModal h3 {
                font-size: 15px;
            }
        }

        /* Touch-friendly improvements for mobile */
        @media (max-width: 768px) {
            .element-item {
                min-height: 44px; /* Minimum touch target size */
            }
            
            .control-btn {
                min-height: 32px;
                min-width: 32px;
            }
            
            .add-element-btn {
                min-height: 44px;
            }
            
            .save-btn {
                min-height: 44px;
                min-width: 44px;
            }
            
            /* Mobile accessibility improvements */
            .element-item:focus,
            .control-btn:focus,
            .add-element-btn:focus,
            .save-btn:focus {
                outline: 2px solid #3b82f6;
                outline-offset: 2px;
            }
            
            /* Mobile focus visible for keyboard navigation */
            .element-item:focus-visible,
            .control-btn:focus-visible,
            .add-element-btn:focus-visible,
            .save-btn:focus-visible {
                outline: 2px solid #3b82f6;
                outline-offset: 2px;
            }
            
            /* Mobile reduced motion support */
            @media (prefers-reduced-motion: reduce) {
                .element-item,
                .control-btn,
                .add-element-btn,
                .save-btn {
                    transition: none;
                }
                
                #addElementModal .bg-white {
                    animation: none;
                }
            }
            
            /* Mobile performance optimizations */
            .sidebar,
            .preview-area,
            #elementList {
                will-change: scroll-position;
                transform: translateZ(0);
                -webkit-transform: translateZ(0);
            }
            
            /* Mobile smooth scrolling optimization */
            .sidebar,
            .preview-area,
            #elementList {
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
                overscroll-behavior: contain;
            }
            
            /* Mobile hardware acceleration */
            .element-item,
            .control-btn,
            .add-element-btn,
            .save-btn {
                transform: translateZ(0);
                -webkit-transform: translateZ(0);
                backface-visibility: hidden;
                -webkit-backface-visibility: hidden;
            }
        }

        /* Landscape mobile optimization */
        @media (max-width: 768px) and (orientation: landscape) {
            .sidebar {
                max-height: 100vh;
            }
            
            .preview-area {
                min-height: 100vh;
            }
            
            .preview-header {
                flex-direction: row;
                gap: 8px;
            }
            
            /* Landscape mobile specific improvements */
            .sidebar-content {
                padding: 12px;
            }
            
            .element-item {
                margin: 4px;
                padding: 8px;
            }
            
            .element-title {
                font-size: 12px;
            }
            
            .element-preview {
                font-size: 10px;
                padding: 4px;
            }
            
            .control-btn {
                padding: 4px 6px;
                font-size: 10px;
            }
            
            .add-element-btn {
                padding: 8px;
                font-size: 13px;
                margin-bottom: 12px;
            }
            
            .section-title {
                font-size: 14px;
                margin-bottom: 8px;
            }
        }

        /* Mobile mode specific styles */
        .mobile-mode .element-item {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        
        .mobile-mode .control-btn {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        
        .mobile-mode .add-element-btn {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        
        .mobile-mode .save-btn {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }

        /* Mobile scrollbar improvements */
        @media (max-width: 768px) {
            .sidebar::-webkit-scrollbar {
                width: 4px;
            }
            
            .sidebar::-webkit-scrollbar-track {
                background: #f1f1f1;
            }
            
            .sidebar::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 2px;
            }
            
            .sidebar::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8;
            }
        }

        /* Notification styles */
        .notification {
            animation: slideInRight 0.3s ease;
            z-index: 100000 !important;
        }
        
        /* Ensure all notifications and alerts are on top */
        .notification,
        .auto-save-indicator,
        .auto-save-success,
        .auto-save-error,
        .swal2-container,
        .modal-overlay {
            z-index: 100000 !important;
        }
        
        /* Drag and drop z-index management */
        .element-item.dragging {
            z-index: 100001 !important;
        }
        
        .drop-zone.active,
        .drop-zone.hover {
            z-index: 100002 !important;
        }
        
        /* Drag image styling */
        .drag-image {
            pointer-events: none !important;
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            background: white !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
            transform-origin: center !important;
            will-change: transform !important;
        }
        
        /* Smooth drag animation */
        .element-item.dragging {
            will-change: transform, opacity, box-shadow;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }
        
        /* Responsive drag image */
        @media (max-width: 768px) {
            .drag-image {
                width: 100px !important;
                transform: rotate(0.5deg) scale(0.6) !important;
            }
        }
        
        @media (max-width: 480px) {
            .drag-image {
                width: 80px !important;
                transform: rotate(0.3deg) scale(0.5) !important;
            }
        }
        
        /* Improve drag feedback */
        .element-item.dragging .drag-handle {
            color: #10b981;
            transform: scale(1.1);
        }
        
        /* Global z-index hierarchy */
        .modal-overlay {
            z-index: 100000 !important;
        }
        
        .modal-content {
            z-index: 100001 !important;
        }
        
        .notification-overlay {
            z-index: 100002 !important;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .notification button {
            transition: all 0.2s ease;
        }
        
        .notification button:hover {
            transform: scale(1.1);
        }

        /* Mobile modal improvements */
        @media (max-width: 768px) {
            #addElementModal {
                padding: 16px;
            }
            
                    /* Ensure modals are on top */
        #addElementModal,
        #editProfileModal,
        #editGridProdukModal,
        #editTombolLinkModal,
        #editYoutubeEmbedModal,
        #editSosialMediaModal,
        #editPortfolioProjectModal,
        #editGambarThumbnailModal,
        #editSpotifyEmbedModal,
        #editBackgroundCustomModal {
            z-index: 100000 !important;
        }
        
        /* Background Custom Modal Styles */
        #editBackgroundCustomModal input[type="radio"]:checked + div {
            border-color: #3b82f6 !important;
            background: linear-gradient(135deg, #eff6ff, #dbeafe) !important;
            transform: scale(1.02);
        }
        
        #editBackgroundCustomModal input[type="radio"]:checked + div::after {
            content: '✓';
            position: absolute;
            top: 8px;
            right: 8px;
            width: 20px;
            height: 20px;
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        
        #editBackgroundCustomModal .color-input {
            transition: all 0.2s ease;
        }
        
        #editBackgroundCustomModal .color-input:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        #editBackgroundCustomModal .preset-button {
            transition: all 0.2s ease;
        }
        
        #editBackgroundCustomModal .preset-button:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        
        /* Smooth transitions for modal sections */
        #backgroundImageSection,
        #backgroundColorSection,
        #backgroundGradientSection {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Color input improvements */
        #editBackgroundCustomModal input[type="color"] {
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s ease;
        }
        
        #editBackgroundCustomModal input[type="color"]:hover {
            border-color: #3b82f6;
            transform: scale(1.05);
        }
        
        /* Text input improvements */
        #editBackgroundCustomModal input[type="text"] {
            transition: all 0.2s ease;
        }
        
        #editBackgroundCustomModal input[type="text"]:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
            
            #addElementModal .bg-white {
                border-radius: 12px;
                max-height: 85vh;
                margin: 0;
            }
            
            #addElementModal .element-item {
                margin: 8px 0;
                padding: 12px;
            }
            
            /* Mobile modal backdrop */
            #addElementModal {
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }
            
            /* Mobile modal animations */
            #addElementModal .bg-white {
                animation: slideUpMobile 0.3s ease;
            }
            
            @keyframes slideUpMobile {
                from {
                    transform: translateY(100%);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        }

        /* Mobile preview improvements */
        @media (max-width: 768px) {
            .preview-iframe {
                border-radius: 8px;
                margin: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            
            /* Mobile sidebar improvements */
            .sidebar {
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
            }
            
            /* Mobile preview area improvements */
            .preview-area {
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
            }
            
            /* Mobile element list improvements */
            #elementList {
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
            }
            
            /* Mobile button improvements */
            .add-element-btn:active,
            .control-btn:active,
            .save-btn:active {
                transform: scale(0.95);
                transition: transform 0.1s ease;
            }
            
            /* Mobile element item improvements */
            .element-item:active {
                transform: scale(0.98);
                transition: transform 0.1s ease;
            }
        }

        /* Disabled state styles */
        .control-btn:disabled,
        .add-element-btn:disabled,
        .save-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
        
        .control-btn:disabled:hover,
        .add-element-btn:disabled:hover,
        .save-btn:disabled:hover {
            transform: none !important;
        }
        
        #hiddenElementsSection {
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        #showAllElementsBtn {
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        
        #showAllElementsBtn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        /* Auto Save Indicator Styles */
        .auto-save-indicator,
        .auto-save-success,
        .auto-save-error {
            animation: slideInLeft 0.3s ease-out;
            z-index: 100000 !important;
        }
        
        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .auto-save-indicator i,
        .auto-save-success i,
        .auto-save-error i {
            font-size: 16px;
        }
        
        .auto-save-indicator {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }
        
        .auto-save-success {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .auto-save-error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        
        /* SweetAlert2 z-index override */
        .swal2-container {
            z-index: 100000 !important;
        }
        
        .swal2-popup {
            z-index: 100001 !important;
        }
    </style>
</head>
<body>
    <div class="editor-container">
        <!-- Sidebar Editor -->
        <div class="sidebar">
            <div class="sidebar-header">
                <button class="mobile-close-btn" onclick="toggleSidebar()">
                    <i class="fas fa-times"></i>
                </button>
                <h1 class="text-xl font-bold">LinkSkuy Editor</h1>
                <p class="text-sm text-gray-300 mt-2">Atur susunan elemen halaman</p>
                <a href="/skuy" class="mt-4 inline-flex items-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors text-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali Skuy
                </a>
            </div>
            
            <div class="sidebar-content">
                <button class="add-element-btn" onclick="showAddElementModal()">
                    <i class="fas fa-plus mr-2"></i>Tambah Elemen Baru
                </button>
                
                <div class="text-center text-gray-500 py-4">
                    <i class="fas fa-info-circle text-blue-500 mb-2"></i>
                    <p class="text-xs">Layout akan dimuat dari server atau klik tombol untuk menambah elemen</p>
                </div>
                
                <div class="flex gap-2 mb-4">
                    <button onclick="loadLayoutFromServer()" class="flex-1 control-btn btn-edit">
                        <i class="fas fa-cloud-download-alt mr-2"></i>Load dari Server
                    </button>
                    
                </div>
                
                <div class="text-center text-gray-500 py-2">
                    <p class="text-xs">Layout akan dimuat dari server saat halaman dibuka</p>
                </div>
                
                <div class="section-title">
                    Elemen Halaman
                    <span id="elementCount" class="ml-2 text-sm bg-blue-100 text-blue-800 px-2 py-1" style="border-radius: 9999px; display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; text-align: center;">0</span>
                </div>
                <div id="elementList" class="space-y-2">
                    <!-- Elemen-elemen akan ditampilkan di sini -->
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-plus-circle text-4xl mb-4"></i>
                        <p class="text-sm">Layout akan dimuat dari server atau klik tombol untuk menambah elemen</p>
                    </div>
                </div>
                
                <!-- Hidden Elements Section -->
                <div id="hiddenElementsSection" class="mt-6" style="display: none;">
                    <div class="section-title text-gray-500">
                        <i class="fas fa-eye-slash mr-2"></i>Elemen Tersembunyi
                        <button id="showAllElementsBtn" onclick="showAllElements()" class="ml-auto text-sm bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition-colors">
                            <i class="fas fa-eye mr-1"></i>Tampilkan Semua
                        </button>
                    </div>
                    <div id="hiddenElementList" class="space-y-2">
                        <!-- Elemen tersembunyi akan di-generate di sini -->
                    </div>
                </div>
                
                <!-- Quick Add Button -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-blue-800 mb-3 text-center">Perlu elemen lain?</p>
                    <button onclick="showAddElementModal()" class="w-full control-btn btn-edit">
                        <i class="fas fa-plus mr-2"></i>Tambah Elemen Lagi
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Preview Area -->
        <div class="preview-area">
            <div class="preview-header">
                <button class="mobile-toggle-btn" onclick="toggleSidebar()">
                    <i class="fas fa-bars mr-2"></i>Editor
                </button>
                <div class="flex items-center gap-4">
                    <button onclick="refreshPreview()" class="control-btn btn-edit">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                    <button onclick="openPreview()" class="control-btn btn-edit">
                        <i class="fas fa-external-link-alt mr-2"></i>Buka di Tab Baru
                    </button>
                </div>
            </div>
            
            <iframe id="previewFrame" class="preview-iframe" src="{{ route('preview', $nama_link) }}"></iframe>
        </div>
    </div>
    
    <!-- Save Button -->
    <button class="save-btn" onclick="saveLayout()">
        <i class="fas fa-save mr-2"></i>Simpan Layout
    </button>
    
    <!-- Add Element Modal -->
    <div id="addElementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[100000] flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] flex flex-col shadow-2xl">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold">Pilih Elemen untuk Ditambahkan</h3>
                        <p class="text-sm text-gray-600 mt-1">Semua elemen tersedia untuk ditambahkan ke halaman Anda</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-blue-600" id="modalElementCount">0</div>
                        <div class="text-xs text-gray-500">elemen tersedia</div>
                    </div>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Elemen yang sudah ditambahkan -->
                <div id="addedElementsSection" class="mb-6" style="display: none;">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Elemen yang Sudah Ditambahkan (<span id="addedElementsCount">0</span>)
                    </h4>
                    <div id="addedElementsList" class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                        <!-- Elemen yang sudah ditambahkan akan di-generate di sini -->
                    </div>
                </div>
                
                <!-- Semua elemen yang tersedia -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-list text-blue-500 mr-2"></i>
                        Semua Elemen Tersedia
                    </h4>
                    <div id="availableElements" class="space-y-2">
                        <!-- Elemen yang tersedia akan di-generate di sini -->
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-500">Klik elemen untuk menambahkannya ke halaman</p>
                    <div class="flex gap-2">
                        <button onclick="hideAddElementModal()" class="control-btn btn-delete">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[100000] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-md w-full max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold">Edit Profil Pengguna</h3>
            </div>
            <div class="flex-1 overflow-y-auto p-6">
                <form id="profileForm" enctype="multipart/form-data">
                    @csrf
                    <!-- Foto Profil -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <img id="profilePreview" src="{{ asset('env/logo.jpg') }}" 
                                     alt="Preview Foto" 
                                     class="w-20 h-20 rounded-full object-cover border-2 border-gray-300"
                                     onerror="this.src='{{ asset('env/logo.jpg') }}'">
                                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full opacity-0 hover:opacity-100 transition-opacity">
                                    <i class="fas fa-camera text-white text-lg"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="file" id="profileImage" name="profile_image" 
                                       accept="image/*" class="hidden" onchange="previewProfileImage(this)">
                                <button type="button" onclick="document.getElementById('profileImage').click()" 
                                        class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                    <i class="fas fa-upload mr-2"></i>Pilih Foto
                                </button>
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maks: 2MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="mb-6">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" id="username" name="username" value="@username" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Masukkan username">
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Tulis deskripsi singkat tentang dirimu...">Ini adalah deskripsi singkat tentang pengguna. Tulis sesuatu yang menarik tentang dirimu di sini.</textarea>
                    </div>
                </form>
            </div>
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-end gap-2">
                    <button onclick="hideEditProfileModal()" class="control-btn btn-delete">Batal</button>
                    <button onclick="saveProfile()" class="control-btn btn-edit">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Grid Produk Modal -->
    <div id="editGridProdukModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[100000] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold">Edit Grid Produk</h3>
            </div>
            <div class="flex-1 overflow-y-auto p-6">
                <form id="gridProdukForm" enctype="multipart/form-data">
                    @csrf
                    <div id="productFields" class="space-y-6">
                        <!-- Product fields will be generated here -->
                    </div>
                    <button type="button" onclick="addProductField()" class="w-full mt-4 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Produk
                    </button>
                </form>
            </div>
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-end gap-2">
                    <button onclick="hideEditGridProdukModal()" class="control-btn btn-delete">Batal</button>
                    <button onclick="saveGridProduk()" class="control-btn btn-edit">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Tombol Link Modal -->
    <div id="editTombolLinkModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[100000] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold">Edit Tombol Link</h3>
            </div>
            <div class="flex-1 overflow-y-auto p-6">
                <form id="tombolLinkForm">
                    @csrf
                    <div id="linkFields" class="space-y-6">
                        <!-- Link fields will be generated here -->
                    </div>
                    <button type="button" onclick="addLinkField()" class="w-full mt-4 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Link
                    </button>
                </form>
            </div>
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-end gap-2">
                    <button onclick="hideEditTombolLinkModal()" class="control-btn btn-delete">Batal</button>
                    <button onclick="saveTombolLink()" class="control-btn btn-edit">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit YouTube Embed Modal -->
    <div id="editYoutubeEmbedModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[100000] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold">Edit YouTube Embed</h3>
            </div>
            <div class="flex-1 overflow-y-auto p-6">
                <form id="youtubeEmbedForm">
                    @csrf
                    <div class="mb-6">
                        <label for="header_youtube" class="block text-sm font-medium text-gray-700 mb-2">Header YouTube</label>
                        <input type="text" id="header_youtube" name="header_youtube" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Masukkan header YouTube">
                    </div>
                    <div class="mb-6">
                        <label for="deskripsi_header" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Header</label>
                        <textarea id="deskripsi_header" name="deskripsi_header" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Masukkan deskripsi header"></textarea>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Embed YouTube</label>
                        <div id="youtubeFields" class="space-y-4">
                            <!-- YouTube fields will be generated here -->
                        </div>
                        <button type="button" onclick="addYoutubeField()" class="w-full mt-4 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Tambah Embed YouTube
                        </button>
                    </div>
                </form>
            </div>
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-end gap-2">
                    <button onclick="hideEditYoutubeEmbedModal()" class="control-btn btn-delete">Batal</button>
                    <button onclick="saveYoutubeEmbed()" class="control-btn btn-edit">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Sosial Media Modal -->
    <div id="editSosialMediaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[100000] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold">Edit Sosial Media</h3>
            </div>
            <div class="flex-1 overflow-y-auto p-6">
                <form id="sosialMediaForm">
                    @csrf
                    <div id="socialMediaFields" class="space-y-4">
                        <!-- Social media fields will be generated here -->
                    </div>
                </form>
            </div>
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-end gap-2">
                    <button onclick="hideEditSosialMediaModal()" class="control-btn btn-delete">Batal</button>
                    <button onclick="saveSosialMedia()" class="control-btn btn-edit">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Portfolio Project Modal -->
    <div id="editPortfolioProjectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[100000] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold">Edit Portfolio Project</h3>
            </div>
            <div class="flex-1 overflow-y-auto p-6">
                <form id="portfolioProjectForm" enctype="multipart/form-data">
                    @csrf
                    <div id="portfolioFields" class="space-y-6">
                        <!-- Portfolio fields will be generated here -->
                    </div>
                    <button type="button" onclick="addPortfolioField()" class="w-full mt-4 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Project
                    </button>
                </form>
            </div>
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-end gap-2">
                    <button onclick="hideEditPortfolioProjectModal()" class="control-btn btn-delete">Batal</button>
                    <button onclick="savePortfolioProject()" class="control-btn btn-edit">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Gambar Thumbnail Modal -->
    <div id="editGambarThumbnailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[100000] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-md w-full max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold">Edit Gambar Thumbnail</h3>
            </div>
            <div class="flex-1 overflow-y-auto p-6">
                <form id="gambarThumbnailForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Thumbnail</label>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <img id="thumbnailPreview" src="{{ asset('env/bg.jpg') }}" 
                                     alt="Preview Thumbnail" 
                                     class="w-32 h-24 object-cover border-2 border-gray-300 rounded-lg"
                                     onerror="this.src='{{ asset('env/bg.jpg') }}'">
                                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-lg opacity-0 hover:opacity-100 transition-opacity">
                                    <i class="fas fa-image text-white text-lg"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="file" id="thumbnailImage" name="gambar_thumbnail" 
                                       accept="image/*" class="hidden" onchange="previewThumbnailImage(this)">
                                <button type="button" onclick="document.getElementById('thumbnailImage').click()" 
                                        class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                    <i class="fas fa-upload mr-2"></i>Pilih Gambar
                                </button>
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maks: 2MB</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-end gap-2">
                    <button onclick="hideEditGambarThumbnailModal()" class="control-btn btn-delete">Batal</button>
                    <button onclick="saveGambarThumbnail()" class="control-btn btn-edit">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Spotify Embed Modal -->
    <div id="editSpotifyEmbedModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[100000] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold">Edit Spotify Embed</h3>
            </div>
            <div class="flex-1 overflow-y-auto p-6">
                <form id="spotifyEmbedForm">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Embed Spotify</label>
                        <div id="spotifyFields" class="space-y-4">
                            <!-- Spotify fields will be generated here -->
                        </div>
                        <button type="button" onclick="addSpotifyField()" class="w-full mt-4 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Tambah Embed Spotify
                        </button>
                    </div>
                </form>
            </div>
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-end gap-2">
                    <button onclick="hideEditSpotifyEmbedModal()" class="control-btn btn-delete">Batal</button>
                    <button onclick="saveSpotifyEmbed()" class="control-btn btn-edit">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Background Custom Modal -->
    <div id="editBackgroundCustomModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[100000] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">🎨 Edit Background Custom</h3>
                        <p class="text-sm text-gray-600 mt-1">Pilih dan sesuaikan background halaman Anda</p>
                    </div>
                    <button onclick="hideEditBackgroundCustomModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto p-6">
                <form id="backgroundCustomForm" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Hidden inputs untuk memastikan data terkirim -->
                    <input type="hidden" name="background_type" id="hiddenBackgroundType" value="image">
                    
                    <!-- Tipe Background -->
                    <div class="mb-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
                        <label class="block text-lg font-semibold text-gray-800 mb-4">📋 Tipe Background</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="background_type" value="image" class="sr-only" checked onchange="toggleBackgroundOptions()">
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-300 transition-all duration-200 bg-white">
                                    <i class="fas fa-image text-2xl text-blue-500 mb-2"></i>
                                    <div class="font-medium text-gray-700">Gambar</div>
                                    <div class="text-xs text-gray-500">Upload foto custom</div>
                                </div>
                            </label>
                            
                            <label class="relative cursor-pointer">
                                <input type="radio" name="background_type" value="color" class="sr-only" onchange="toggleBackgroundOptions()">
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-300 transition-all duration-200 bg-white">
                                    <i class="fas fa-palette text-2xl text-green-500 mb-2"></i>
                                    <div class="font-medium text-gray-700">Warna Solid</div>
                                    <div class="text-xs text-gray-500">Pilih warna solid</div>
                                </div>
                            </label>
                            
                            <label class="relative cursor-pointer">
                                <input type="radio" name="background_type" value="gradient" class="sr-only" onchange="toggleBackgroundOptions()">
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-300 transition-all duration-200 bg-white">
                                    <i class="fas fa-palette text-2xl text-purple-500 mb-2"></i>
                                    <div class="font-medium text-gray-700">Gradient</div>
                                    <div class="text-xs text-gray-500">Kombinasi 2 warna</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Background Image Section -->
                    <div id="backgroundImageSection" class="mb-8 p-6 bg-blue-50 rounded-xl border border-blue-200">
                        <label class="block text-lg font-semibold text-blue-800 mb-4">🖼️ Upload Gambar Background</label>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="text-center">
                                <div class="relative inline-block">
                                    <img id="backgroundPreview" src="{{ asset('env/bg.jpg') }}" 
                                         alt="Preview Background" 
                                         class="w-48 h-36 object-cover border-4 border-white rounded-xl shadow-lg"
                                         onerror="this.src='{{ asset('env/bg.jpg') }}'">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-xl opacity-0 hover:opacity-100 transition-opacity duration-200">
                                        <i class="fas fa-image text-white text-2xl"></i>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">Preview Background</p>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <input type="file" id="backgroundImage" name="background_image" 
                                           accept="image/*" class="hidden" onchange="previewBackgroundImage(this)">
                                    <button type="button" onclick="document.getElementById('backgroundImage').click()" 
                                            class="w-full px-6 py-4 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-all duration-200 font-medium text-lg shadow-lg hover:shadow-xl">
                                        <i class="fas fa-cloud-upload-alt mr-3 text-xl"></i>Pilih Gambar
                                    </button>
                                </div>
                                
                                <div class="p-4 bg-white rounded-lg border border-gray-200">
                                    <h4 class="font-medium text-gray-800 mb-2">📋 Informasi File:</h4>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        <li>✅ Format: JPG, PNG, GIF</li>
                                        <li>✅ Ukuran maksimal: 5MB</li>
                                        <li>✅ Resolusi: Minimal 800x600px</li>
                                        <li>✅ Background akan otomatis di-crop</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Background Color Section -->
                    <div id="backgroundColorSection" class="mb-8 p-6 bg-green-50 rounded-xl border border-green-200 hidden">
                        <label class="block text-lg font-semibold text-green-800 mb-4">🎨 Pilih Warna Background</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">🎯 Warna Utama</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" id="backgroundColor" name="background_color" value="#ffffff" 
                                               class="w-16 h-16 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-colors">
                                        <div class="flex-1">
                                            <input type="text" value="#ffffff" class="w-full px-3 py-2 border border-gray-300 rounded-lg font-mono text-sm" 
                                                   onchange="document.getElementById('backgroundColor').value = this.value" 
                                                   placeholder="#ffffff">
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">🔄 Warna Sekunder (Opsional)</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" id="backgroundColorSecondary" name="background_color_secondary" value="#f3f4f6" 
                                               class="w-16 h-16 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-colors">
                                        <div class="flex-1">
                                            <input type="text" value="#f3f4f6" class="w-full px-3 py-2 border border-gray-300 rounded-lg font-mono text-sm" 
                                                   onchange="document.getElementById('backgroundColorSecondary').value = this.value" 
                                                   placeholder="#f3f4f6">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-6 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-medium text-gray-800 mb-3">💡 Tips Warna:</h4>
                                <ul class="text-sm text-gray-600 space-y-2">
                                    <li>• Pilih warna yang kontras dengan teks</li>
                                    <li>• Gunakan warna sekunder untuk gradient halus</li>
                                    <li>• Warna putih (#ffffff) untuk tampilan bersih</li>
                                    <li>• Warna gelap untuk tampilan elegan</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Background Gradient Section -->
                    <div id="backgroundGradientSection" class="mb-8 p-6 bg-purple-50 rounded-xl border border-purple-200 hidden">
                        <label class="block text-lg font-semibold text-purple-800 mb-4">🌈 Pilih Gradient Background</label>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">🎨 Warna Awal</label>
                                        <div class="flex items-center space-x-3">
                                            <input type="color" id="gradientColor1" name="gradient_color_1" value="#667eea" 
                                                   class="w-16 h-16 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-colors">
                                            <div class="flex-1">
                                                <input type="text" value="#667eea" class="w-full px-3 py-2 border border-gray-300 rounded-lg font-mono text-sm" 
                                                       onchange="document.getElementById('gradientColor1').value = this.value" 
                                                       placeholder="#667eea">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">🎨 Warna Akhir</label>
                                        <div class="flex items-center space-x-3">
                                            <input type="color" id="gradientColor2" name="gradient_color_2" value="#764ba2" 
                                                   class="w-16 h-16 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-colors">
                                            <div class="flex-1">
                                                <input type="text" value="#764ba2" class="w-full px-3 py-2 border border-gray-300 rounded-lg font-mono text-sm" 
                                                       onchange="document.getElementById('gradientColor2').value = this.value" 
                                                       placeholder="#764ba2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">🧭 Arah Gradient</label>
                                    <select id="gradientDirection" name="gradient_direction" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <option value="to bottom">⬇️ Ke Bawah</option>
                                        <option value="to top">⬆️ Ke Atas</option>
                                        <option value="to right">➡️ Ke Kanan</option>
                                        <option value="to left">⬅️ Ke Kiri</option>
                                        <option value="45deg">↗️ Diagonal (45°)</option>
                                        <option value="135deg">↘️ Diagonal (135°)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="p-6 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-medium text-gray-800 mb-3">🎯 Preview Gradient:</h4>
                                <div id="gradientPreview" class="w-full h-32 rounded-lg mb-4" style="background: linear-gradient(to bottom, #667eea, #764ba2);"></div>
                                <p class="text-xs text-gray-500">Gradient akan terlihat seperti ini di halaman Anda</p>
                            </div>
                        </div>
                    </div>

                    <!-- Preset Backgrounds -->
                    <div class="mb-8 p-6 bg-orange-50 rounded-xl border border-orange-200">
                        <label class="block text-lg font-semibold text-orange-800 mb-4">🚀 Background Preset</label>
                        <p class="text-sm text-gray-600 mb-4">Klik salah satu preset di bawah untuk mengatur gradient secara otomatis</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <button type="button" onclick="setPresetBackground('sunset')" class="h-24 rounded-xl bg-gradient-to-r from-orange-400 to-pink-600 hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <div class="h-full flex flex-col items-center justify-center text-white">
                                    <i class="fas fa-sun text-2xl mb-1"></i>
                                    <span class="font-medium">Sunset</span>
                                </div>
                            </button>
                            <button type="button" onclick="setPresetBackground('ocean')" class="h-24 rounded-xl bg-gradient-to-r from-blue-400 to-cyan-500 hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <div class="h-full flex flex-col items-center justify-center text-white">
                                    <i class="fas fa-water text-2xl mb-1"></i>
                                    <span class="font-medium">Ocean</span>
                                </div>
                            </button>
                            <button type="button" onclick="setPresetBackground('forest')" class="h-24 rounded-xl bg-gradient-to-r from-green-400 to-emerald-500 hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <div class="h-full flex flex-col items-center justify-center text-white">
                                    <i class="fas fa-tree text-2xl mb-1"></i>
                                    <span class="font-medium">Forest</span>
                                </div>
                            </button>
                            <button type="button" onclick="setPresetBackground('purple')" class="h-24 rounded-xl bg-gradient-to-r from-purple-400 to-indigo-500 hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <div class="h-full flex flex-col items-center justify-center text-white">
                                    <i class="fas fa-gem text-2xl mb-1"></i>
                                    <span class="text-white text-xs font-medium">Purple</span>
                                </div>
                            </button>
                            <button type="button" onclick="setPresetBackground('warm')" class="h-24 rounded-xl bg-gradient-to-r from-yellow-400 to-red-500 hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <div class="h-full flex flex-col items-center justify-center text-white">
                                    <i class="fas fa-fire text-2xl mb-1"></i>
                                    <span class="font-medium">Warm</span>
                                </div>
                            </button>
                            <button type="button" onclick="setPresetBackground('cool')" class="h-24 rounded-xl bg-gradient-to-r from-cyan-400 to-blue-500 hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <div class="h-full flex flex-col items-center justify-center text-white">
                                    <i class="fas fa-snowflake text-2xl mb-1"></i>
                                    <span class="font-medium">Cool</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="p-6 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Background akan langsung terupdate di preview
                    </div>
                    <div class="flex gap-3">
                        <button onclick="hideEditBackgroundCustomModal()" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-medium">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                        <button onclick="saveBackgroundCustom()" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium shadow-lg">
                            <i class="fas fa-save mr-2"></i>Simpan Background
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Helper function untuk mengkonversi path relatif menjadi URL lengkap
        function getAssetUrl(path) {
            if (!path) return '';
            
            // Jika sudah URL lengkap, return as is
            if (path.startsWith('http://') || path.startsWith('https://')) {
                return path;
            }
            
            // Jika path relatif, tambahkan base URL
            if (path.startsWith('/')) {
                return window.location.origin + path;
            }
            
            // Jika path tanpa slash, tambahkan slash dan base URL
            return window.location.origin + '/' + path;
        }
        
        // Helper function untuk menangani error loading gambar
        function handleImageError(img, fallbackUrl) {
            img.onerror = function() {
                console.warn('Gambar tidak dapat dimuat:', img.src);
                if (fallbackUrl) {
                    img.src = fallbackUrl;
                }
            };
        }
        
        // Helper function untuk membuat elemen gambar dengan error handling
        function createImageElement(src, className, alt, fallbackUrl) {
            const img = document.createElement('img');
            img.src = getAssetUrl(src);
            img.className = className;
            img.alt = alt || 'Preview';
            
            if (fallbackUrl) {
                handleImageError(img, fallbackUrl);
            }
            
            return img;
        }
        
        // Data elemen-elemen yang tersedia
        const availableElements = [
            {
                id: 'profil_pengguna',
                name: 'Profil Pengguna',
                description: 'Foto profil, username, dan deskripsi',
                icon: 'fas fa-user'
            },
            {
                id: 'grid_produk',
                name: 'Grid Produk',
                description: 'Tampilan produk dengan search bar',
                icon: 'fas fa-shopping-bag'
            },
            {
                id: 'tombol_link',
                name: 'Tombol Link',
                description: 'Tombol YouTube, TikTok, WhatsApp',
                icon: 'fas fa-link'
            },
            {
                id: 'youtube_embeded',
                name: 'YouTube Video',
                description: 'Embed video YouTube',
                icon: 'fab fa-youtube'
            },
            {
                id: 'sosial_media',
                name: 'Sosial Media',
                description: 'Icon sosial media',
                icon: 'fas fa-share-alt'
            },
            {
                id: 'portfolio_project',
                name: 'Portfolio Project',
                description: 'Tampilan project portfolio',
                icon: 'fas fa-briefcase'
            },
            {
                id: 'gambar_thumbnail',
                name: 'Gambar Thumbnail',
                description: 'Gambar thumbnail besar',
                icon: 'fas fa-image'
            },
            {
                id: 'spotify_embed',
                name: 'Spotify Embed',
                description: 'Embed playlist Spotify',
                icon: 'fab fa-spotify'
            },
            {
                id: 'background_custom',
                name: 'Background Custom',
                description: 'Ganti background halaman',
                icon: 'fas fa-palette'
            },

        ];

        // Urutan elemen saat ini (dimulai dengan kosong)
        let currentOrder = [];
        let hiddenElements = new Set(); // Elemen yang disembunyikan
        let draggedElement = null;

        // Cek layout dari server
        function checkServerLayout() {
            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/get-layout/${kodeUnik}/${namaLink}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data && data.data.order && data.data.order.length > 0) {
                    // Ada data dari server, isi sidebar
                    const layoutData = data.data;
                    currentOrder = layoutData.order;
                    hiddenElements = new Set(layoutData.hidden || []);
                    renderElementList();
                    setupDragAndDrop();
                    showNotification('Layout dari server berhasil dimuat!', 'success');
                } else {
                    // Tidak ada data dari server, sidebar tetap kosong
                    console.log('Tidak ada data layout dari server');
                }
            })
            .catch(error => {
                console.error('Error checking server layout:', error);
                // Jika error, sidebar tetap kosong
                console.log('Error saat memeriksa layout server');
            });
        }
        
        // Auto load layout dari server setiap 30 detik
        function startAutoLoadLayout() {
            setInterval(() => {
                if (currentOrder.length === 0) {
                    checkServerLayout();
                }
            }, 30000); // 30 detik
        }
        
        // Inisialisasi editor
        function initEditor() {
            renderElementList();
            setupDragAndDrop();
            initializeModals();
            
            // Set preview to desktop mode
            const iframe = document.getElementById('previewFrame');
            if (iframe) {
                iframe.style.width = '100%';
                iframe.style.margin = '0';
            }
            
            // Cek apakah ada data dari server terlebih dahulu
            checkServerLayout();
            
            // Mulai auto load layout dari server
            startAutoLoadLayout();
            
            // Tambahkan event listener untuk ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hideAddElementModal();
                }
            });
            
            // Tambahkan event listener untuk klik di luar modal
            document.getElementById('addElementModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    hideAddElementModal();
                }
            });
        }
        
        // Toggle sidebar untuk mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.classList.toggle('mobile-open');
            }
        }

        // Render daftar elemen
        function renderElementList() {
            const elementList = document.getElementById('elementList');
            const hiddenElementList = document.getElementById('hiddenElementList');
            const hiddenElementsSection = document.getElementById('hiddenElementsSection');
            const elementCount = document.getElementById('elementCount');
            
            // Update element count
            if (elementCount) {
                elementCount.textContent = currentOrder.length;
            }
            
            // Clear both lists
            elementList.innerHTML = '';
            hiddenElementList.innerHTML = '';
            
            // Log untuk debugging
            console.log(`Rendering element list. Current order: ${currentOrder.length}, Hidden: ${hiddenElements.size}`);
            
            // Separate visible and hidden elements
            const visibleElements = [];
            const hiddenElementsArray = [];
            
            currentOrder.forEach((elementId, index) => {
                const element = availableElements.find(el => el.id === elementId);
                if (element) {
                    if (hiddenElements.has(elementId)) {
                        hiddenElementsArray.push({ ...element, index });
                    } else {
                        visibleElements.push({ ...element, index });
                    }
                }
            });
            
            // Render visible elements with drop zones
            if (visibleElements.length === 0) {
                elementList.innerHTML = `
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-plus-circle text-4xl mb-4"></i>
                        <p class="text-sm mb-2">Belum ada elemen yang ditambahkan</p>
                        <p class="text-xs text-gray-400">Layout akan dimuat dari server atau klik tombol untuk menambah elemen</p>
                    </div>
                `;
                
                // Log untuk debugging
                console.log('Tidak ada elemen yang ditampilkan, sidebar dikosongkan');
                return;
            }
            
            visibleElements.forEach((element, listIndex) => {
                // Add drop zone before first element
                if (listIndex === 0) {
                    const dropZone = document.createElement('div');
                    dropZone.className = 'drop-zone';
                    dropZone.dataset.position = 'before';
                    dropZone.dataset.elementId = element.id;
                    elementList.appendChild(dropZone);
                }
                
                const elementItem = document.createElement('div');
                elementItem.className = 'element-item';
                elementItem.draggable = true;
                elementItem.dataset.id = element.id;
                elementItem.dataset.index = element.index;
                
                elementItem.innerHTML = `
                    <div class="element-header">
                        <div class="flex items-center">
                            <div class="drag-handle">
                                <i class="fas fa-grip-vertical"></i>
                            </div>
                            <div class="element-title">${element.name}</div>
                        </div>
                        <div class="element-controls">
                            <button class="control-btn btn-edit" onclick="editElement('${element.id}')" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="control-btn btn-toggle" onclick="toggleElement('${element.id}')" title="Sembunyikan">
                                <i class="fas fa-eye"></i>
                            </button>
                            ${visibleElements.length > 1 ? `<button class="control-btn btn-delete" onclick="removeElement('${element.id}')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>` : ''}
                        </div>
                    </div>
                    <div class="element-preview">
                        <i class="${element.icon} mr-2"></i>${element.description}
                    </div>
                `;
                
                elementList.appendChild(elementItem);
                
                // Add drop zone after element
                const dropZone = document.createElement('div');
                dropZone.className = 'drop-zone';
                dropZone.dataset.position = 'after';
                dropZone.dataset.elementId = element.id;
                elementList.appendChild(dropZone);
            });
            
            // Render hidden elements
            hiddenElementsArray.forEach((element) => {
                const elementItem = document.createElement('div');
                elementItem.className = 'element-item opacity-75';
                elementItem.dataset.id = element.id;
                elementItem.dataset.index = element.index;
                
                elementItem.innerHTML = `
                    <div class="element-header">
                        <div class="flex items-center">
                            <div class="element-title text-gray-500">${element.name}</div>
                        </div>
                        <div class="element-controls">
                            <button class="control-btn btn-toggle" onclick="toggleElement('${element.id}')" title="Tampilkan">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="element-preview text-gray-400">
                        <i class="${element.icon} mr-2"></i>${element.description}
                        <span class="text-red-500 ml-2">(Tersembunyi)</span>
                    </div>
                `;
                
                hiddenElementList.appendChild(elementItem);
            });
            
            // Show/hide hidden elements section
            if (hiddenElementsArray.length > 0) {
                hiddenElementsSection.style.display = 'block';
            } else {
                hiddenElementsSection.style.display = 'none';
            }
        }

        // Setup drag and drop with improved accuracy
        function setupDragAndDrop() {
            const elementItems = document.querySelectorAll('#elementList .element-item');
            const dropZones = document.querySelectorAll('#elementList .drop-zone');
            
            // Remove existing event listeners
            elementItems.forEach(item => {
                item.removeEventListener('dragstart', handleDragStart);
                item.removeEventListener('dragend', handleDragEnd);
                item.removeEventListener('dragover', handleDragOver);
                item.removeEventListener('drop', handleDrop);
                item.removeEventListener('dragenter', handleDragEnter);
                item.removeEventListener('dragleave', handleDragLeave);
            });
            
            dropZones.forEach(zone => {
                zone.removeEventListener('dragover', handleDropZoneDragOver);
                zone.removeEventListener('dragenter', handleDropZoneDragEnter);
                zone.removeEventListener('dragleave', handleDropZoneDragLeave);
                zone.removeEventListener('drop', handleDropZoneDrop);
            });
            
            // Add event listeners to elements
            elementItems.forEach(item => {
                item.addEventListener('dragstart', handleDragStart);
                item.addEventListener('dragend', handleDragEnd);
                item.addEventListener('dragover', handleDragOver);
                item.addEventListener('drop', handleDrop);
                item.addEventListener('dragenter', handleDragEnter);
                item.addEventListener('dragleave', handleDragLeave);
            });
            
            // Add event listeners to drop zones
            dropZones.forEach(zone => {
                zone.addEventListener('dragover', handleDropZoneDragOver);
                zone.addEventListener('dragenter', handleDropZoneDragEnter);
                zone.addEventListener('dragleave', handleDropZoneDragLeave);
                zone.addEventListener('drop', handleDropZoneDrop);
            });
            
            // Mobile touch events
            if (window.innerWidth <= 768) {
                elementItems.forEach(item => setupTouchEvents(item));
            }
            
            // Log untuk debugging
            console.log(`Drag and drop setup selesai. Elemen: ${elementItems.length}, Drop zones: ${dropZones.length}`);
        }
        
        // Setup touch events for mobile
        function setupTouchEvents(item) {
            let startY = 0;
            let currentY = 0;
            let isDragging = false;
            let startTime = 0;
            
            item.addEventListener('touchstart', (e) => {
                startY = e.touches[0].clientY;
                startTime = Date.now();
                isDragging = false;
            }, { passive: true });
            
            item.addEventListener('touchmove', (e) => {
                if (!isDragging) {
                    currentY = e.touches[0].clientY;
                    const diff = Math.abs(currentY - startY);
                    
                    if (diff > 15) {
                        isDragging = true;
                        item.classList.add('dragging');
                        // Add haptic feedback if available
                        if (navigator.vibrate) {
                            navigator.vibrate(50);
                        }
                    }
                }
            }, { passive: true });
            
            item.addEventListener('touchend', (e) => {
                if (isDragging) {
                    const endTime = Date.now();
                    const duration = endTime - startTime;
                    
                    // Only trigger if drag was long enough
                    if (duration > 200) {
                        item.classList.remove('dragging');
                        // Handle reordering logic here if needed
                    }
                }
            }, { passive: true });
        }

        function handleDragStart(e) {
            draggedElement = e.target;
            e.target.classList.add('dragging');
            
            // Add smooth animation
            e.target.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            
            // Add sidebar dragging state
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.classList.add('dragging');
            }
            
            // Set drag image dengan posisi cursor yang tepat
            if (e.dataTransfer.setDragImage) {
                const dragImage = e.target.cloneNode(true);
                dragImage.className = 'drag-image';
                dragImage.style.opacity = '0.8';
                dragImage.style.position = 'absolute';
                dragImage.style.top = '-9999px';
                dragImage.style.left = '-9999px';
                dragImage.style.pointerEvents = 'none';
                dragImage.style.zIndex = '-1';
                dragImage.style.borderRadius = '6px';
                dragImage.style.boxShadow = '0 2px 6px rgba(0,0,0,0.12)';
                dragImage.style.overflow = 'hidden';
                dragImage.style.height = 'auto';
                
                // Hapus elemen yang tidak perlu dari drag image
                const controls = dragImage.querySelector('.element-controls');
                if (controls) controls.remove();
                
                // Set ukuran responsif berdasarkan layar
                if (window.innerWidth <= 480) {
                    dragImage.style.width = '80px';
                    dragImage.style.transform = 'rotate(0.3deg) scale(0.5)';
                } else if (window.innerWidth <= 768) {
                    dragImage.style.width = '100px';
                    dragImage.style.transform = 'rotate(0.5deg) scale(0.6)';
                } else {
                    dragImage.style.width = '120px';
                    dragImage.style.transform = 'rotate(1deg) scale(0.7)';
                }
                
                document.body.appendChild(dragImage);
                
                // Hitung offset cursor yang tepat (tengah elemen)
                const rect = e.target.getBoundingClientRect();
                let offsetX, offsetY;
                
                if (window.innerWidth <= 480) {
                    offsetX = Math.min(rect.width / 2, 40);
                    offsetY = Math.min(rect.height / 2, 30);
                } else if (window.innerWidth <= 768) {
                    offsetX = Math.min(rect.width / 2, 50);
                    offsetY = Math.min(rect.height / 2, 35);
                } else {
                    offsetX = Math.min(rect.width / 2, 60);
                    offsetY = Math.min(rect.height / 2, 40);
                }
                
                e.dataTransfer.setDragImage(dragImage, offsetX, offsetY);
                
                // Hapus drag image setelah digunakan
                setTimeout(() => {
                    if (dragImage.parentElement) {
                        dragImage.parentElement.removeChild(dragImage);
                    }
                }, 0);
            }
        }

        function handleDragEnd(e) {
            e.target.classList.remove('dragging');
            e.target.style.transition = '';
            draggedElement = null;
            
            // Remove sidebar dragging state
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.classList.remove('dragging');
            }
            
            // Clear all drop zone states
            document.querySelectorAll('.drop-zone').forEach(zone => {
                zone.classList.remove('active', 'hover');
            });
        }

        function handleDragOver(e) {
            e.preventDefault();
        }

        function handleDragEnter(e) {
            e.preventDefault();
            if (e.target.classList.contains('element-item')) {
                e.target.classList.add('drag-over');
            }
        }

        function handleDragLeave(e) {
            if (e.target.classList.contains('element-item')) {
                e.target.classList.remove('drag-over');
            }
        }

        function handleDrop(e) {
            e.preventDefault();
            if (e.target.classList.contains('element-item')) {
                e.target.classList.remove('drag-over');
                
                if (draggedElement) {
                    const draggedId = draggedElement.dataset.id;
                    const targetId = e.target.dataset.id;
                    
                    // Update urutan
                    const draggedIndex = currentOrder.indexOf(draggedId);
                    const targetIndex = currentOrder.indexOf(targetId);
                    
                    const newOrder = [...currentOrder];
                    const [removed] = newOrder.splice(draggedIndex, 1);
                    newOrder.splice(targetIndex, 0, removed);
                    
                    currentOrder = newOrder;
                    
                    // Re-render dan update preview
                    renderElementList();
                    setupDragAndDrop();
                    
                    // Add success animation to the moved element
                    setTimeout(() => {
                        const movedElement = document.querySelector(`[data-id="${draggedId}"]`);
                        if (movedElement) {
                            movedElement.classList.add('reorder-success');
                            setTimeout(() => {
                                movedElement.classList.remove('reorder-success');
                            }, 600);
                        }
                    }, 100);
                    
                    // Auto simpan layout dan refresh preview
                    autoSaveLayout();
                }
            }
        }
        
        // New drop zone handlers for better accuracy
        function handleDropZoneDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        }
        
        function handleDropZoneDragEnter(e) {
            e.preventDefault();
            e.target.classList.add('hover');
        }
        
        function handleDropZoneDragLeave(e) {
            e.preventDefault();
            e.target.classList.remove('hover');
        }
        
        function handleDropZoneDrop(e) {
            e.preventDefault();
            e.target.classList.remove('hover', 'active');
            
            if (draggedElement) {
                const draggedId = draggedElement.dataset.id;
                const targetElementId = e.target.dataset.elementId;
                const position = e.target.dataset.position;
                
                // Calculate new position
                const draggedIndex = currentOrder.indexOf(draggedId);
                const targetIndex = currentOrder.indexOf(targetElementId);
                
                let newIndex;
                if (position === 'before') {
                    newIndex = targetIndex;
                } else {
                    newIndex = targetIndex + 1;
                }
                
                // Don't drop on itself
                if (draggedIndex === newIndex || (draggedIndex === newIndex - 1 && position === 'after')) {
                    return;
                }
                
                // Update order with smooth animation
                const newOrder = [...currentOrder];
                const [removed] = newOrder.splice(draggedIndex, 1);
                newOrder.splice(newIndex, 0, removed);
                
                currentOrder = newOrder;
                
                // Add success animation
                e.target.classList.add('active');
                setTimeout(() => {
                    e.target.classList.remove('active');
                }, 300);
                
                // Re-render dan update preview
                renderElementList();
                setupDragAndDrop();
                
                // Add success animation to the moved element
                setTimeout(() => {
                    const movedElement = document.querySelector(`[data-id="${draggedId}"]`);
                    if (movedElement) {
                        movedElement.classList.add('reorder-success');
                        setTimeout(() => {
                            movedElement.classList.remove('reorder-success');
                        }, 600);
                    }
                }, 100);
                
                // Auto simpan layout dan refresh preview
                autoSaveLayout();
            }
        }

        // Toggle visibility elemen
        function toggleElement(elementId) {
            const elementName = availableElements.find(el => el.id === elementId)?.name;
            const isCurrentlyHidden = hiddenElements.has(elementId);
            
            if (isCurrentlyHidden) {
                // Show element
                Swal.fire({
                    title: 'Tampilkan Elemen',
                    text: `Apakah Anda yakin ingin menampilkan elemen "${elementName}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Tampilkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        hiddenElements.delete(elementId);
                        showNotification(`Elemen "${elementName}" ditampilkan!`, 'success');
                        renderElementList();
                        
                        // Auto simpan layout dan refresh preview
                        autoSaveLayout();
                    }
                });
            } else {
                // Hide element
                Swal.fire({
                    title: 'Sembunyikan Elemen',
                    text: `Apakah Anda yakin ingin menyembunyikan elemen "${elementName}"?\n\nElemen ini akan tetap tersimpan tetapi tidak akan ditampilkan di halaman.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Sembunyikan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        hiddenElements.add(elementId);
                        showNotification(`Elemen "${elementName}" disembunyikan!`, 'info');
                        renderElementList();
                        
                        // Auto simpan layout dan refresh preview
                        autoSaveLayout();
                    }
                });
            }
        }

                // Edit elemen
        function editElement(elementId) {
            if (elementId === 'profil_pengguna') {
                showEditProfileModal();
            } else if (elementId === 'grid_produk') {
                showEditGridProdukModal();
            } else if (elementId === 'tombol_link') {
                showEditTombolLinkModal();
            } else if (elementId === 'youtube_embeded') {
                showEditYoutubeEmbedModal();
            } else if (elementId === 'sosial_media') {
                showEditSosialMediaModal();
            } else if (elementId === 'portfolio_project') {
                showEditPortfolioProjectModal();
            } else if (elementId === 'gambar_thumbnail') {
                showEditGambarThumbnailModal();
            } else if (elementId === 'spotify_embed') {
                showEditSpotifyEmbedModal();
            } else if (elementId === 'background_custom') {
                showEditBackgroundCustomModal();
            } else {
                // Implementasi untuk edit elemen lainnya
            console.log('Edit element:', elementId);
                showNotification(`Fitur edit untuk elemen "${availableElements.find(el => el.id === elementId)?.name}" akan segera tersedia!`, 'info');
            }
        }

        // Hapus elemen
        function removeElement(elementId) {
            const elementName = availableElements.find(el => el.id === elementId)?.name;
            
            // Validasi bahwa elemen ada dalam currentOrder
            if (!currentOrder.includes(elementId)) {
                showNotification(`Elemen "${elementName}" tidak ditemukan dalam layout!`, 'error');
                return;
            }
            
            Swal.fire({
                title: 'Hapus Elemen',
                text: `Apakah Anda yakin ingin menghapus elemen "${elementName}"?\n\nTindakan ini tidak dapat dibatalkan dan elemen akan dihapus dari layout beserta semua datanya.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const index = currentOrder.indexOf(elementId);
                    if (index > -1) {
                        currentOrder.splice(index, 1);
                        hiddenElements.delete(elementId); // Hapus dari hidden elements juga
                        
                        // Bersihkan data elemen yang dihapus dari server
                        cleanElementData(elementId);
                        
                        // Update UI
                        renderElementList();
                        setupDragAndDrop();
                        
                        // Auto simpan layout dan refresh preview
                        autoSaveLayout();
                        
                        // Update preview setelah penghapusan
                        setTimeout(() => {
                            updatePreview();
                        }, 100);
                        
                        // Log untuk debugging
                        console.log(`Elemen "${elementName}" berhasil dihapus. Sisa elemen: ${currentOrder.length}`);
                        
                        showNotification(`Elemen "${elementName}" berhasil dihapus beserta semua datanya!`, 'success');
                    }
                }
            });
        }
        
        // Bersihkan data elemen yang dihapus dari server
        function cleanElementData(elementId) {
            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            // Buat data kosong untuk elemen yang dihapus
            const cleanData = {};
            
            switch(elementId) {
                case 'profil_pengguna':
                    cleanData.profil_pengguna = null;
                    break;
                case 'grid_produk':
                    cleanData.grid_produk = null;
                    break;
                case 'tombol_link':
                    cleanData.tombol_link = null;
                    break;
                case 'youtube_embeded':
                    cleanData.youtube_embeded = null;
                    break;
                case 'sosial_media':
                    cleanData.sosial_media = null;
                    break;
                case 'portfolio_project':
                    cleanData.portfolio_project = null;
                    break;
                case 'gambar_thumbnail':
                    cleanData.gambar_thumbnail = null;
                    break;
                case 'spotify_embed':
                    cleanData.spotify_embed = null;
                    break;
                case 'background_custom':
                    cleanData.background_custom = null;
                    break;
            }
            
            // Kirim request ke server untuk membersihkan data
            if (Object.keys(cleanData).length > 0) {
                fetch(`/clean-element-data/${kodeUnik}/${namaLink}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(cleanData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(`Data elemen ${elementId} berhasil dibersihkan dari server`);
                    } else {
                        console.warn(`Gagal membersihkan data elemen ${elementId}:`, data.message);
                    }
                })
                .catch(error => {
                    console.error(`Error membersihkan data elemen ${elementId}:`, error);
                });
            }
        }
        
        // Bersihkan semua data elemen yang tidak ada dalam currentOrder
        function cleanAllRemovedElementData() {
            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            // Dapatkan semua elemen yang tersedia
            const allAvailableElements = availableElements.map(el => el.id);
            
            // Dapatkan elemen yang tidak ada dalam currentOrder
            const removedElements = allAvailableElements.filter(elementId => !currentOrder.includes(elementId));
            
            if (removedElements.length === 0) {
                return; // Tidak ada elemen yang dihapus
            }
            
            // Buat data kosong untuk semua elemen yang dihapus
            const cleanData = {};
            removedElements.forEach(elementId => {
                cleanData[elementId] = null;
            });
            
            // Kirim request ke server untuk membersihkan data
            fetch(`/clean-element-data/${kodeUnik}/${namaLink}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(cleanData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(`Data ${removedElements.length} elemen berhasil dibersihkan dari server:`, removedElements);
                } else {
                    console.warn(`Gagal membersihkan data elemen:`, data.message);
                }
            })
            .catch(error => {
                console.error(`Error membersihkan data elemen:`, error);
            });
        }

        // Update preview
        function updatePreview() {
            const iframe = document.getElementById('previewFrame');
            if (iframe.contentWindow) {
                try {
                    // Log untuk debugging
                    console.log('Updating preview with order:', currentOrder);
                    
                    // Coba kirim pesan ke iframe untuk update layout
                    iframe.contentWindow.postMessage({
                        type: 'UPDATE_LAYOUT',
                        order: currentOrder
                    }, '*');
                    
                    // Jika iframe sudah load, update langsung
                    if (iframe.contentDocument && iframe.contentDocument.readyState === 'complete') {
                        updateIframeContent();
                    } else {
                        // Tunggu iframe selesai loading
                        iframe.addEventListener('load', updateIframeContent);
                    }
                } catch (e) {
                    console.log('Iframe belum siap, refresh preview...');
                    refreshPreview();
                }
            }
        }

        // Update konten iframe secara langsung
        function updateIframeContent() {
            const iframe = document.getElementById('previewFrame');
            if (!iframe.contentDocument) return;

            try {
                const mainContent = iframe.contentDocument.querySelector('.main-content');
                if (!mainContent) return;

                // Jika tidak ada elemen, kosongkan konten dan return
                if (currentOrder.length === 0) {
                    const sections = mainContent.querySelectorAll('section');
                    sections.forEach(section => {
                        if (section.parentNode) {
                            section.parentNode.removeChild(section);
                        }
                    });
                    console.log('Preview dikosongkan karena tidak ada elemen');
                    return;
                }

                // Dapatkan semua section yang ada
                const sections = mainContent.querySelectorAll('section');
                const sectionMap = new Map();

                // Map section berdasarkan ID atau class yang unik
                sections.forEach((section, index) => {
                    let sectionId = '';
                    
                    // Identifikasi section berdasarkan kontennya
                    if (section.querySelector('#profil_pengguna')) {
                        sectionId = 'profil_pengguna';
                    } else if (section.querySelector('#grid_produk')) {
                        sectionId = 'grid_produk';
                    } else if (section.querySelector('#tombol_link')) {
                        sectionId = 'tombol_link';
                    } else if (section.querySelector('#youtube_embeded')) {
                        sectionId = 'youtube_embeded';
                    } else if (section.querySelector('#sosial_media')) {
                        sectionId = 'sosial_media';
                    } else if (section.querySelector('#portfolio_project')) {
                        sectionId = 'portfolio_project';
                    } else if (section.querySelector('#gambar_thumbnail')) {
                        sectionId = 'gambar_thumbnail';
                    } else if (section.querySelector('#spotify_embed')) {
                        sectionId = 'spotify_embed';
                    }
                    
                    if (sectionId) {
                        sectionMap.set(sectionId, section);
                    }
                });

                // Hapus semua section yang ada
                sections.forEach(section => {
                    if (section.parentNode) {
                        section.parentNode.removeChild(section);
                    }
                });

                // Tambahkan section sesuai urutan baru dan visibility
                currentOrder.forEach(elementId => {
                    if (!hiddenElements.has(elementId)) { // Hanya tampilkan elemen yang tidak tersembunyi
                        const section = sectionMap.get(elementId);
                        if (section) {
                            mainContent.appendChild(section.cloneNode(true));
                        }
                    }
                });

                console.log('Preview berhasil diupdate!');
            } catch (e) {
                console.error('Error updating iframe content:', e);
                // Fallback: refresh preview
                refreshPreview();
            }
        }

        // Refresh preview
        function refreshPreview() {
            const iframe = document.getElementById('previewFrame');
            const currentSrc = iframe.src;
            iframe.src = '';
            setTimeout(() => {
                iframe.src = currentSrc;
                iframe.addEventListener('load', updateIframeContent);
            }, 100);
        }

        // Buka preview di tab baru
        function openPreview() {
            window.open('{{ route('preview', $nama_link) }}', '_blank');
        }

        // Show add element modal
        function showAddElementModal() {
            const modal = document.getElementById('addElementModal');
            const availableElementsDiv = document.getElementById('availableElements');
            const addedElementsSection = document.getElementById('addedElementsSection');
            const addedElementsCount = document.getElementById('addedElementsCount');
            const addedElementsList = document.getElementById('addedElementsList');
            
            // Update counter di modal
            const modalElementCount = document.getElementById('modalElementCount');
            if (modalElementCount) {
                modalElementCount.textContent = availableElements.length;
            }
            
            // Update bagian elemen yang sudah ditambahkan
            if (currentOrder.length > 0 && addedElementsSection && addedElementsCount && addedElementsList) {
                addedElementsCount.textContent = currentOrder.length;
                addedElementsList.innerHTML = currentOrder.map(elementId => {
                    const element = availableElements.find(el => el.id === elementId);
                    return element ? `
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="${element.icon} text-green-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-medium text-green-800">${element.name}</div>
                                <div class="text-xs text-green-600">Sudah aktif</div>
                            </div>
                        </div>
                    ` : '';
                }).join('');
                addedElementsSection.style.display = 'block';
            } else if (addedElementsSection) {
                addedElementsSection.style.display = 'none';
            }
            
            // Tampilkan semua elemen yang tersedia
            availableElementsDiv.innerHTML = availableElements.map(el => `
                <div class="element-item cursor-pointer hover:bg-blue-50 transition-all duration-200 border-2 border-transparent hover:border-blue-200 rounded-lg p-4" onclick="addElement('${el.id}')">
                    <div class="element-header">
                        <div class="flex items-center flex-1 min-w-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="${el.icon} text-blue-600 text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="element-title font-semibold text-gray-800 truncate">${el.name}</div>
                                <div class="element-preview text-sm text-gray-600 mt-1 line-clamp-2">${el.description}</div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ml-3">
                            ${currentOrder.includes(el.id) ? '<span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full font-medium whitespace-nowrap">✓ Sudah Ditambahkan</span>' : '<span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium whitespace-nowrap">+ Tambahkan</span>'}
                        </div>
                    </div>
                </div>
            `).join('');
            
            modal.classList.remove('hidden');
            
            // Tampilkan notifikasi informatif
            if (currentOrder.length === 0) {
                showNotification('Pilih elemen pertama untuk memulai halaman Anda!', 'info');
            } else {
                showNotification(`Halaman Anda memiliki ${currentOrder.length} elemen aktif`, 'info');
            }
            
            // Mobile-specific modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = 'hidden';
                modal.style.padding = '16px';
            }
        }

        // Hide add element modal
        function hideAddElementModal() {
            document.getElementById('addElementModal').classList.add('hidden');
            
            // Reset mobile modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = '';
            }
        }

        // Show edit profile modal
        function showEditProfileModal() {
            const modal = document.getElementById('editProfileModal');
            modal.classList.remove('hidden');
            
            // Load existing data
            loadProfileData();
            
            // Mobile-specific modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = 'hidden';
            }
        }
        
        // Load profile data from server
        function loadProfileData() {
            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/get-layout/${kodeUnik}/${namaLink}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data && data.data.profil_pengguna) {
                        const profile = data.data.profil_pengguna;
                        
                        // Update form fields
                        document.getElementById('username').value = profile.username || '';
                        document.getElementById('description').value = profile.deskripsi || '';
                        
                        // Update profile image preview
                        if (profile.foto_profil) {
                            document.getElementById('profilePreview').src = getAssetUrl(profile.foto_profil);
                        } else {
                            // Fallback ke default image
                            document.getElementById('profilePreview').src = 'https://pandekakode.com/env/saya.jpeg';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading profile data:', error);
                });
        }

        // Hide edit profile modal
        function hideEditProfileModal() {
            document.getElementById('editProfileModal').classList.add('hidden');
            
            // Reset mobile modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = '';
            }
        }

        // Show edit grid produk modal
        function showEditGridProdukModal() {
            const modal = document.getElementById('editGridProdukModal');
            modal.classList.remove('hidden');
            
            // Load existing data
            loadGridProdukData();
            
            // Mobile-specific modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = 'hidden';
            }
        }
        
        // Load grid produk data from server
        function loadGridProdukData() {
            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/get-layout/${kodeUnik}/${namaLink}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data && data.data.grid_produk && data.data.grid_produk.length > 0) {
                        const products = data.data.grid_produk;
                        
                        // Clear existing fields
                        document.getElementById('productFields').innerHTML = '';
                        
                        // Add fields for each product
                        products.forEach((product, index) => {
                            addProductField(product);
                        });
                    } else {
                        // If no data, add one empty field
                        addProductField();
                    }
                })
                .catch(error => {
                    console.error('Error loading grid produk data:', error);
                    // Add one empty field as fallback
                    addProductField();
                });
        }

        // Hide edit grid produk modal
        function hideEditGridProdukModal() {
            document.getElementById('editGridProdukModal').classList.add('hidden');
            
            // Reset mobile modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = '';
            }
        }

        // Show edit tombol link modal
        function showEditTombolLinkModal() {
            const modal = document.getElementById('editTombolLinkModal');
            modal.classList.remove('hidden');
            
            // Load existing data
            loadTombolLinkData();
            
            // Mobile-specific modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = 'hidden';
            }
        }
        
        // Load tombol link data from server
        function loadTombolLinkData() {
            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/get-layout/${kodeUnik}/${namaLink}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data && data.data.tombol_link && data.data.tombol_link.length > 0) {
                        const links = data.data.tombol_link;
                        
                        // Clear existing fields
                        document.getElementById('linkFields').innerHTML = '';
                        
                        // Add fields for each link
                        links.forEach((link, index) => {
                            addLinkField(link);
                        });
                    } else {
                        // If no data, add one empty field
                        addLinkField();
                    }
                })
                .catch(error => {
                    console.error('Error loading tombol link data:', error);
                    // Add one empty field as fallback
                    addLinkField();
                });
        }

        // Hide edit tombol link modal
        function hideEditTombolLinkModal() {
            document.getElementById('editTombolLinkModal').classList.add('hidden');
            
            // Reset mobile modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = '';
            }
        }

        // Show edit youtube embed modal
        function showEditYoutubeEmbedModal() {
            const modal = document.getElementById('editYoutubeEmbedModal');
            modal.classList.remove('hidden');
            
            // Load existing data
            loadYoutubeEmbedData();
            
            // Mobile-specific modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = 'hidden';
            }
        }
        
        // Load YouTube embed data from server
        function loadYoutubeEmbedData() {
            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/get-layout/${kodeUnik}/${namaLink}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data && data.data.youtube_embeded) {
                        const youtubeData = data.data.youtube_embeded;
                        
                        // Update form fields
                        document.getElementById('header_youtube').value = youtubeData.header_youtube || '';
                        document.getElementById('deskripsi_header').value = youtubeData.deskripsi_header || '';
                        
                        // Clear existing fields and add new ones
                        document.getElementById('youtubeFields').innerHTML = '';
                        
                        if (youtubeData.embeded_youtube && youtubeData.embeded_youtube.length > 0) {
                            youtubeData.embeded_youtube.forEach((embed, index) => {
                                addYoutubeField(embed);
                            });
                        } else {
                            addYoutubeField();
                        }
                    } else {
                        // If no data, add one empty field
                        addYoutubeField();
                    }
                })
                .catch(error => {
                    console.error('Error loading YouTube embed data:', error);
                    // Add one empty field as fallback
                    addYoutubeField();
                });
        }

        // Hide edit youtube embed modal
        function hideEditYoutubeEmbedModal() {
            document.getElementById('editYoutubeEmbedModal').classList.add('hidden');
            
            // Reset mobile modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = '';
            }
        }

        // Show edit sosial media modal
        function showEditSosialMediaModal() {
            const modal = document.getElementById('editSosialMediaModal');
            modal.classList.remove('hidden');
            
            // Load existing data
            loadSosialMediaData();
            
            // Mobile-specific modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = 'hidden';
            }
        }
        
        // Load sosial media data from server
        function loadSosialMediaData() {
            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/get-layout/${kodeUnik}/${namaLink}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data && data.data.sosial_media && data.data.sosial_media.length > 0) {
                        const socialMediaData = data.data.sosial_media;
                        
                        // Update form fields based on existing data
                        socialMediaData.forEach(social => {
                            const platform = social.platform.toLowerCase();
                            const checkbox = document.getElementById(`active_${platform}`);
                            const linkInput = document.querySelector(`input[name="sosial_media[${platform}][link]"]`);
                            
                            if (checkbox && linkInput) {
                                checkbox.checked = social.active;
                                linkInput.value = social.link || '';
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading sosial media data:', error);
                });
        }

        // Hide edit sosial media modal
        function hideEditSosialMediaModal() {
            document.getElementById('editSosialMediaModal').classList.add('hidden');
            
            // Reset mobile modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = '';
            }
        }

        // Show edit portfolio project modal
        function showEditPortfolioProjectModal() {
            const modal = document.getElementById('editPortfolioProjectModal');
            modal.classList.remove('hidden');
            
            // Load existing data
            loadPortfolioProjectData();
            
            // Mobile-specific modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = 'hidden';
            }
        }
        
        // Load portfolio project data from server
        function loadPortfolioProjectData() {
            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/get-layout/${kodeUnik}/${namaLink}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data && data.data.portfolio_project && data.data.portfolio_project.length > 0) {
                        const projects = data.data.portfolio_project;
                        
                        // Clear existing fields
                        document.getElementById('portfolioFields').innerHTML = '';
                        
                        // Add fields for each project
                        projects.forEach((project, index) => {
                            addPortfolioField(project);
                        });
                    } else {
                        // If no data, add one empty field
                        addPortfolioField();
                    }
                })
                .catch(error => {
                    console.error('Error loading portfolio project data:', error);
                    // Add one empty field as fallback
                    addPortfolioField();
                });
        }

        // Hide edit portfolio project modal
        function hideEditPortfolioProjectModal() {
            document.getElementById('editPortfolioProjectModal').classList.add('hidden');
            
            // Reset mobile modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = '';
            }
        }

        // Show edit gambar thumbnail modal
        function showEditGambarThumbnailModal() {
            const modal = document.getElementById('editGambarThumbnailModal');
            modal.classList.remove('hidden');
            
            // Load existing data
            loadGambarThumbnailData();
            
            // Mobile-specific modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = 'hidden';
            }
        }
        
        // Load gambar thumbnail data from server
        function loadGambarThumbnailData() {
            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/get-layout/${kodeUnik}/${namaLink}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data && data.data.gambar_thumbnail && data.data.gambar_thumbnail.gambar_thumbnail) {
                        const thumbnail = data.data.gambar_thumbnail.gambar_thumbnail;
                        
                        // Update thumbnail preview
                        document.getElementById('thumbnailPreview').src = getAssetUrl(thumbnail);
                    }
                })
                .catch(error => {
                    console.error('Error loading gambar thumbnail data:', error);
                });
        }

        // Hide edit gambar thumbnail modal
        function hideEditGambarThumbnailModal() {
            document.getElementById('editGambarThumbnailModal').classList.add('hidden');
            
            // Reset mobile modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = '';
            }
        }

        // Show edit spotify embed modal
        function showEditSpotifyEmbedModal() {
            const modal = document.getElementById('editSpotifyEmbedModal');
            modal.classList.remove('hidden');
            
            // Load existing data
            loadSpotifyEmbedData();
            
            // Mobile-specific modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = 'hidden';
            }
        }
        
        // Load Spotify embed data from server
        function loadSpotifyEmbedData() {
            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/get-layout/${kodeUnik}/${namaLink}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data && data.data.spotify_embed && data.data.spotify_embed.embeded_spotify && data.data.spotify_embed.embeded_spotify.length > 0) {
                        const spotifyEmbeds = data.data.spotify_embed.embeded_spotify;
                        
                        // Clear existing fields
                        document.getElementById('spotifyFields').innerHTML = '';
                        
                        // Add fields for each embed
                        spotifyEmbeds.forEach((embed, index) => {
                            addSpotifyField(embed);
                        });
                    } else {
                        // If no data, add one empty field
                        addSpotifyField();
                    }
                })
                .catch(error => {
                    console.error('Error loading Spotify embed data:', error);
                    // Add one empty field as fallback
                    addSpotifyField();
                });
        }

        // Hide edit spotify embed modal
        function hideEditSpotifyEmbedModal() {
            document.getElementById('editSpotifyEmbedModal').classList.add('hidden');
            
            // Reset mobile modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = '';
            }
        }

        // Show edit background custom modal
        function showEditBackgroundCustomModal() {
            const modal = document.getElementById('editBackgroundCustomModal');
            modal.classList.remove('hidden');
            
            // Load existing data
            loadBackgroundCustomData();
            
            // Mobile-specific modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = 'hidden';
            }
        }
        
        // Load background custom data from server
        function loadBackgroundCustomData() {
            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/get-layout/${kodeUnik}/${namaLink}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data && data.data.background_custom) {
                        const backgroundData = data.data.background_custom;
                        
                        // Set background type
                        const backgroundTypeRadio = document.querySelector(`input[name="background_type"][value="${backgroundData.type}"]`);
                        if (backgroundTypeRadio) {
                            backgroundTypeRadio.checked = true;
                            toggleBackgroundOptions();
                        }
                        
                        // Update fields based on type
                        if (backgroundData.type === 'image' && backgroundData.image) {
                            document.getElementById('backgroundPreview').src = getAssetUrl(backgroundData.image);
                        } else {
                            // Fallback ke default background image
                            document.getElementById('backgroundPreview').src = 'https://images.pexels.com/photos/1037992/pexels-photo-1037992.jpeg?cs=srgb&dl=pexels-moose-photos-170195-1037992.jpg&fm=jpg';
                        }
                        
                        if (backgroundData.type === 'color') {
                            if (backgroundData.color) {
                                document.getElementById('backgroundColor').value = backgroundData.color;
                                document.querySelector('input[onchange*="backgroundColor"]').value = backgroundData.color;
                            }
                            if (backgroundData.color_secondary) {
                                document.getElementById('backgroundColorSecondary').value = backgroundData.color_secondary;
                                document.querySelector('input[onchange*="backgroundColorSecondary"]').value = backgroundData.color_secondary;
                            }
                        } else if (backgroundData.type === 'gradient' && backgroundData.gradient) {
                            if (backgroundData.gradient.color1) {
                                document.getElementById('gradientColor1').value = backgroundData.gradient.color1;
                                document.querySelector('input[onchange*="gradientColor1"]').value = backgroundData.gradient.color1;
                            }
                            if (backgroundData.gradient.color2) {
                                document.getElementById('gradientColor2').value = backgroundData.gradient.color2;
                                document.querySelector('input[onchange*="gradientColor2"]').value = backgroundData.gradient.color2;
                            }
                            if (backgroundData.gradient.direction) {
                                document.getElementById('gradientDirection').value = backgroundData.gradient.direction;
                            }
                            
                            // Update gradient preview
                            updateGradientPreview();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading background custom data:', error);
                });
        }

        // Hide edit background custom modal
        function hideEditBackgroundCustomModal() {
            document.getElementById('editBackgroundCustomModal').classList.add('hidden');
            
            // Reset mobile modal behavior
            if (window.innerWidth <= 768) {
                document.body.style.overflow = '';
            }
        }

        // Preview profile image
        function previewProfileImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Save profile
        function saveProfile() {
            const formData = new FormData();
            const profileImage = document.getElementById('profileImage').files[0];
            const username = document.getElementById('username').value;
            const description = document.getElementById('description').value;

            // Validasi input
            if (!username.trim()) {
                showNotification('Username tidak boleh kosong!', 'error');
                return;
            }

            if (!description.trim()) {
                showNotification('Deskripsi tidak boleh kosong!', 'error');
                return;
            }

            // Debug: log data yang akan dikirim
            console.log('Sending profile data:', {
                username: username,
                description: description,
                hasImage: !!profileImage,
                imageName: profileImage ? profileImage.name : 'No image'
            });

            if (profileImage) {
                formData.append('profile_image', profileImage);
            }
            formData.append('username', username);
            formData.append('description', description);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Tampilkan loading state
            const saveBtn = document.querySelector('#editProfileModal .control-btn.btn-edit');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            saveBtn.disabled = true;

            // Debug: log FormData
            for (let [key, value] of formData.entries()) {
                console.log('FormData entry:', key, value);
            }

            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/update-profile/${kodeUnik}/${namaLink}`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showNotification('Profil berhasil diupdate!', 'success');
                    hideEditProfileModal();
                    
                    // Auto refresh preview setelah save
                    setTimeout(() => {
                        refreshPreview();
                    }, 500);
                } else {
                    showNotification(data.message || 'Gagal mengupdate profil!', 'error');
                }
            })
            .catch(error => {
                console.error('Error updating profile:', error);
                showNotification('Gagal mengupdate profil! Error: ' + error.message, 'error');
            })
            .finally(() => {
                // Reset button state
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        // Add element
        function addElement(elementId) {
            if (!currentOrder.includes(elementId)) {
                const element = availableElements.find(el => el.id === elementId);
                currentOrder.push(elementId);
                renderElementList();
                setupDragAndDrop();
                
                // Auto simpan layout dan refresh preview
                autoSaveLayout();
                
                hideAddElementModal();
                showNotification(`Elemen "${element.name}" berhasil ditambahkan!`, 'success');
                
                // Refresh modal untuk menampilkan status "Sudah Ditambahkan"
                setTimeout(() => {
                    showAddElementModal();
                }, 1000);
            } else {
                const element = availableElements.find(el => el.id === elementId);
                showNotification(`Elemen "${element.name}" sudah ada di halaman!`, 'info');
            }
        }

        // Grid Produk Functions
        function addProductField(existingProduct = null) {
            const container = document.getElementById('productFields');
            const fieldId = Date.now();
            
            const fieldHtml = `
                <div class="product-field border border-gray-200 rounded-lg p-4" data-field-id="${fieldId}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-medium text-gray-700">Produk ${container.children.length + 1}</h4>
                        <button type="button" onclick="removeProductField(${fieldId})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Produk</label>
                            <div class="flex items-center space-x-4">
                                ${
                                    existingProduct && existingProduct.foto_produk
                                        ? `<img src="${getAssetUrl(existingProduct.foto_produk)}" class="w-16 h-16 object-cover border-2 border-gray-300 rounded-lg" alt="Preview" onerror="this.src='https://via.placeholder.com/64x64?text=No+Image'">`
                                        : ''
                                }
                                <input
                                    type="file"
                                    name="foto_produk[]"
                                    accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    ${existingProduct && existingProduct.foto_produk ? '' : 'required'}
                                    data-old-foto="${existingProduct && existingProduct.foto_produk ? existingProduct.foto_produk : ''}"
                                    onchange="this.setAttribute('data-has-changed', 'true')"
                                >
                                ${
                                    existingProduct && existingProduct.foto_produk
                                        ? `<input type="hidden" name="old_foto_produk[]" value="${existingProduct.foto_produk}">`
                                        : `<input type="hidden" name="old_foto_produk[]" value="">`
                                }
                            </div>
                            <small class="text-gray-500">Jika tidak ingin mengganti foto, biarkan kosong.</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                            <input
                                type="text"
                                name="nama_produk[]"
                                required
                                maxlength="20"
                                value="${existingProduct ? existingProduct.nama_produk : ''}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Maks 20 karakter"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Link Produk</label>
                            <input
                                type="url"
                                name="link_produk[]"
                                required
                                value="${existingProduct ? existingProduct.link_produk : ''}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="https://example.com"
                            >
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                            <input
                                type="text"
                                name="harga[]"
                                required
                                value="${existingProduct ? existingProduct.harga : ''}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Rp 100.000"
                            >
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', fieldHtml);
        }

        function removeProductField(fieldId) {
            const field = document.querySelector(`[data-field-id="${fieldId}"]`);
            if (field) {
                field.remove();
            }
        }

        function saveGridProduk() {
            const form = document.getElementById('gridProdukForm');
            const formData = new FormData(form);
            
            // Validasi minimal 1 produk
            const productFields = document.querySelectorAll('.product-field');
            if (productFields.length === 0) {
                showNotification('Minimal harus ada 1 produk!', 'error');
                return;
            }

            // Tampilkan loading state
            const saveBtn = document.querySelector('#editGridProdukModal .control-btn.btn-edit');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            saveBtn.disabled = true;

            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/update-grid-produk/${kodeUnik}/${namaLink}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    hideEditGridProdukModal();
                    
                    // Auto refresh preview setelah save
                    setTimeout(() => {
                        refreshPreview();
                    }, 500);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error saving grid produk:', error);
                showNotification('Gagal menyimpan grid produk!', 'error');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        // Tombol Link Functions
        function addLinkField(existingLink = null) {
            const container = document.getElementById('linkFields');
            const fieldId = Date.now();
            
            const fieldHtml = `
                <div class="link-field border border-gray-200 rounded-lg p-4" data-field-id="${fieldId}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-medium text-gray-700">Link ${container.children.length + 1}</h4>
                        <button type="button" onclick="removeLinkField(${fieldId})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Link</label>
                            <input type="text" name="nama_link[]" required 
                                   value="${existingLink ? existingLink.nama_link : ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="YouTube">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Link Tombol</label>
                            <input type="url" name="link_tombol[]" required 
                                   value="${existingLink ? existingLink.link_tombol : ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="https://youtube.com">
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', fieldHtml);
        }

        function removeLinkField(fieldId) {
            const field = document.querySelector(`[data-field-id="${fieldId}"]`);
            if (field) {
                field.remove();
            }
        }

        function saveTombolLink() {
            const form = document.getElementById('tombolLinkForm');
            const formData = new FormData(form);
            
            // Validasi minimal 1 link
            const linkFields = document.querySelectorAll('.link-field');
            if (linkFields.length === 0) {
                showNotification('Minimal harus ada 1 link!', 'error');
                return;
            }

            // Debug: log data yang akan dikirim
            console.log('Sending tombol link data:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            // Tampilkan loading state
            const saveBtn = document.querySelector('#editTombolLinkModal .control-btn.btn-edit');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            saveBtn.disabled = true;

            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/update-tombol-link/${kodeUnik}/${namaLink}`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showNotification(data.message, 'success');
                    hideEditTombolLinkModal();
                    
                    // Auto refresh preview setelah save
                    setTimeout(() => {
                        refreshPreview();
                    }, 500);
                } else {
                    showNotification(data.message || 'Gagal menyimpan tombol link!', 'error');
                }
            })
            .catch(error => {
                console.error('Error saving tombol link:', error);
                showNotification('Gagal menyimpan tombol link! Error: ' + error.message, 'error');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        // YouTube Embed Functions
        function addYoutubeField(existingEmbed = null) {
            const container = document.getElementById('youtubeFields');
            const fieldId = Date.now();
            
            const fieldHtml = `
                <div class="youtube-field border border-gray-200 rounded-lg p-4" data-field-id="${fieldId}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-medium text-gray-700">Embed YouTube ${container.children.length + 1}</h4>
                        <button type="button" onclick="removeYoutubeField(${fieldId})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Embed Code</label>
                        <textarea name="embeded_youtube[]" rows="3" required 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="<iframe src='...'></iframe>">${existingEmbed || ''}</textarea>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', fieldHtml);
        }

        function removeYoutubeField(fieldId) {
            const field = document.querySelector(`[data-field-id="${fieldId}"]`);
            if (field) {
                field.remove();
            }
        }

        function saveYoutubeEmbed() {
            const form = document.getElementById('youtubeEmbedForm');
            const formData = new FormData(form);
            
            // Validasi minimal 1 embed
            const youtubeFields = document.querySelectorAll('.youtube-field');
            if (youtubeFields.length === 0) {
                showNotification('Minimal harus ada 1 embed YouTube!', 'error');
                return;
            }

            // Tampilkan loading state
            const saveBtn = document.querySelector('#editYoutubeEmbedModal .control-btn.btn-edit');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            saveBtn.disabled = true;

            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/update-youtube-embed/${kodeUnik}/${namaLink}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    hideEditYoutubeEmbedModal();
                    
                    // Auto refresh preview setelah save
                    setTimeout(() => {
                        refreshPreview();
                    }, 500);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error saving YouTube embed:', error);
                showNotification('Gagal menyimpan YouTube embed!', 'error');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        // Sosial Media Functions
        function initializeSocialMediaFields() {
            const container = document.getElementById('socialMediaFields');
            const platforms = [
                { name: 'YouTube', icon: 'fab fa-youtube', color: 'bg-red-500' },
                { name: 'Facebook', icon: 'fab fa-facebook-f', color: 'bg-blue-600' },
                { name: 'Instagram', icon: 'fab fa-instagram', color: 'bg-pink-500' },
                { name: 'Spotify', icon: 'fab fa-spotify', color: 'bg-green-500' },
                { name: 'LinkedIn', icon: 'fab fa-linkedin-in', color: 'bg-blue-700' },
                { name: 'TikTok', icon: 'fab fa-tiktok', color: 'bg-black' },
                { name: 'Telegram', icon: 'fab fa-telegram-plane', color: 'bg-blue-500' },
                { name: 'WhatsApp', icon: 'fab fa-whatsapp', color: 'bg-green-600' }
            ];
            
            container.innerHTML = platforms.map(platform => `
                <div class="social-media-item flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" id="active_${platform.name.toLowerCase()}" name="sosial_media[${platform.name.toLowerCase()}][active]" 
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="active_${platform.name.toLowerCase()}" class="flex items-center space-x-2">
                            <div class="w-8 h-8 ${platform.color} rounded-full flex items-center justify-center">
                                <i class="${platform.icon} text-white text-sm"></i>
                            </div>
                            <span class="font-medium text-gray-700">${platform.name}</span>
                        </label>
                    </div>
                    <div class="flex-1">
                        <input type="url" name="sosial_media[${platform.name.toLowerCase()}][link]" 
                               placeholder="https://${platform.name.toLowerCase()}.com/username"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <input type="hidden" name="sosial_media[${platform.name.toLowerCase()}][platform]" value="${platform.name}">
                    </div>
                </div>
            `).join('');
            
            // Debug: log the generated HTML
            console.log('Social media fields initialized:', container.innerHTML);
        }

        function saveSosialMedia() {
            const form = document.getElementById('sosialMediaForm');
            const formData = new FormData(form);
            
            // Debug: log data yang akan dikirim
            console.log('Raw FormData entries:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            
            // Convert FormData to JSON structure
            const socialMediaData = {};
            for (let [key, value] of formData.entries()) {
                if (key.includes('[')) {
                    // Parse array notation
                    const matches = key.match(/sosial_media\[([^\]]+)\]\[([^\]]+)\]/);
                    if (matches) {
                        const platform = matches[1];
                        const field = matches[2];
                        if (!socialMediaData[platform]) {
                            socialMediaData[platform] = {};
                        }
                        socialMediaData[platform][field] = value;
                    }
                }
            }
            
            console.log('Parsed social media data:', socialMediaData);
            
            // Convert to array format and filter active platforms
            const socialMediaArray = [];
            for (const [platform, data] of Object.entries(socialMediaData)) {
                console.log(`Processing platform: ${platform}`, data);
                if (data.active === 'on' && data.link && data.link.trim() !== '') {
                    socialMediaArray.push({
                        platform: data.platform || platform,
                        link: data.link,
                        active: true
                    });
                }
            }
            
            console.log('Final processed social media data:', socialMediaArray);

            // Validasi minimal 1 platform aktif
            if (socialMediaArray.length === 0) {
                showNotification('Minimal harus ada 1 platform sosial media yang aktif dengan link!', 'error');
                return;
            }

            // Tampilkan loading state
            const saveBtn = document.querySelector('#editSosialMediaModal .control-btn.btn-edit');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            saveBtn.disabled = true;

            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/update-sosial-media/${kodeUnik}/${namaLink}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ sosial_media: socialMediaArray })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showNotification(data.message, 'success');
                    hideEditSosialMediaModal();
                    
                    // Auto refresh preview setelah save
                    setTimeout(() => {
                        refreshPreview();
                    }, 500);
                } else {
                    showNotification(data.message || 'Gagal menyimpan sosial media!', 'error');
                }
            })
            .catch(error => {
                console.error('Error saving sosial media:', error);
                showNotification('Gagal menyimpan sosial media! Error: ' + error.message, 'error');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        // Portfolio Project Functions
        function addPortfolioField(existingProject = null) {
            const container = document.getElementById('portfolioFields');
            const fieldId = Date.now();
            
            const fieldHtml = `
                <div class="portfolio-field border border-gray-200 rounded-lg p-4" data-field-id="${fieldId}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-medium text-gray-700">Project ${container.children.length + 1}</h4>
                        <button type="button" onclick="removePortfolioField(${fieldId})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Project</label>
                            <div class="flex items-center space-x-4">
                                ${existingProject && existingProject.gambar_project ? 
                                    `<img src="${getAssetUrl(existingProject.gambar_project)}" class="w-16 h-16 object-cover border-2 border-gray-300 rounded-lg" alt="Preview" onerror="this.src='https://via.placeholder.com/64x64?text=No+Image'">` : 
                                    ''
                                }
                                <input 
                                    type="file" 
                                    name="gambar_project[]" 
                                    accept="image/*" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    ${existingProject && existingProject.gambar_project ? '' : 'required'}
                                    data-old-gambar="${existingProject && existingProject.gambar_project ? existingProject.gambar_project : ''}"
                                    onchange="this.setAttribute('data-has-changed', 'true')"
                                >
                                ${existingProject && existingProject.gambar_project 
                                    ? `<input type="hidden" name="old_gambar_project[]" value="${existingProject.gambar_project}">`
                                    : `<input type="hidden" name="old_gambar_project[]" value="">`
                                }
                            </div>
                            <small class="text-gray-500">Jika tidak ingin mengganti gambar, biarkan field ini kosong. Jika ingin mengganti, pilih file baru.</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Project</label>
                            <input type="text" name="judul_project[]" required 
                                   value="${existingProject ? existingProject.judul_project : ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="E-Commerce Platform">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Project <span class="text-xs text-gray-400">(maks 60 karakter)</span></label>
                            <textarea name="deskripsi_project[]" rows="2" required maxlength="60"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Maksimal 60 karakter">${existingProject ? existingProject.deskripsi_project : ''}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Link Project</label>
                            <input type="url" name="link_project[]" required 
                                   value="${existingProject ? existingProject.link_project : ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="https://example.com">
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', fieldHtml);
        }

        function removePortfolioField(fieldId) {
            const field = document.querySelector(`[data-field-id="${fieldId}"]`);
            if (field) {
                field.remove();
            }
        }

        function savePortfolioProject() {
            const form = document.getElementById('portfolioProjectForm');
            const formData = new FormData(form);
            
            // Validasi minimal 1 project
            const portfolioFields = document.querySelectorAll('.portfolio-field');
            if (portfolioFields.length === 0) {
                showNotification('Minimal harus ada 1 project!', 'error');
                return;
            }

            // Tampilkan loading state
            const saveBtn = document.querySelector('#editPortfolioProjectModal .control-btn.btn-edit');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            saveBtn.disabled = true;

            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/update-portfolio-project/${kodeUnik}/${namaLink}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    hideEditPortfolioProjectModal();
                    
                    // Auto refresh preview setelah save
                    setTimeout(() => {
                        refreshPreview();
                    }, 500);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error saving portfolio project:', error);
                showNotification('Gagal menyimpan portfolio project!', 'error');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        // Gambar Thumbnail Functions
        function saveGambarThumbnail() {
            const form = document.getElementById('gambarThumbnailForm');
            const formData = new FormData(form);
            
            // Validasi file
            const fileInput = document.getElementById('thumbnailImage');
            if (!fileInput.files[0]) {
                showNotification('Pilih gambar thumbnail terlebih dahulu!', 'error');
                return;
            }

            // Tampilkan loading state
            const saveBtn = document.querySelector('#editGambarThumbnailModal .control-btn.btn-edit');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            saveBtn.disabled = true;

            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/update-gambar-thumbnail/${kodeUnik}/${namaLink}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    hideEditGambarThumbnailModal();
                    
                    // Auto refresh preview setelah save
                    setTimeout(() => {
                        refreshPreview();
                    }, 500);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error saving gambar thumbnail:', error);
                showNotification('Gagal menyimpan gambar thumbnail!', 'error');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        // Spotify Embed Functions
        function addSpotifyField(existingEmbed = null) {
            const container = document.getElementById('spotifyFields');
            const fieldId = Date.now();
            
            const fieldHtml = `
                <div class="spotify-field border border-gray-200 rounded-lg p-4" data-field-id="${fieldId}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-medium text-gray-700">Embed Spotify ${container.children.length + 1}</h4>
                        <button type="button" onclick="removeSpotifyField(${fieldId})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Embed Code</label>
                        <textarea name="embeded_spotify[]" rows="3" required 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="<iframe src='...'></iframe>">${existingEmbed || ''}</textarea>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', fieldHtml);
        }

        // Background Custom Functions
        function toggleBackgroundOptions() {
            const backgroundType = document.querySelector('input[name="background_type"]:checked').value;
            const imageSection = document.getElementById('backgroundImageSection');
            const colorSection = document.getElementById('backgroundColorSection');
            const gradientSection = document.getElementById('backgroundGradientSection');
            const hiddenBackgroundType = document.getElementById('hiddenBackgroundType');
            
            // Update hidden input
            if (hiddenBackgroundType) {
                hiddenBackgroundType.value = backgroundType;
            }
            
            // Hide all sections first with smooth animation
            [imageSection, colorSection, gradientSection].forEach(section => {
                if (section) {
                    section.style.opacity = '0';
                    section.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        section.style.display = 'none';
                    }, 200);
                }
            });
            
            // Show selected section with smooth animation
            setTimeout(() => {
                let targetSection;
                if (backgroundType === 'image') {
                    targetSection = imageSection;
                } else if (backgroundType === 'color') {
                    targetSection = colorSection;
                } else if (backgroundType === 'gradient') {
                    targetSection = gradientSection;
                }
                
                if (targetSection) {
                    targetSection.style.display = 'block';
                    setTimeout(() => {
                        targetSection.style.opacity = '1';
                        targetSection.style.transform = 'translateY(0)';
                    }, 50);
                }
            }, 200);
        }

        function previewBackgroundImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('backgroundPreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function setPresetBackground(preset) {
            const backgroundType = document.querySelector('input[name="background_type"]:checked');
            const gradientColor1 = document.getElementById('gradientColor1');
            const gradientColor2 = document.getElementById('gradientColor2');
            const gradientDirection = document.getElementById('gradientDirection');
            
            // Set radio button to gradient
            backgroundType.value = 'gradient';
            backgroundType.checked = true;
            toggleBackgroundOptions();
            
            // Set preset colors and direction
            switch(preset) {
                case 'sunset':
                    gradientColor1.value = '#f093fb';
                    gradientColor2.value = '#f5576c';
                    gradientDirection.value = '45deg';
                    break;
                case 'ocean':
                    gradientColor1.value = '#4facfe';
                    gradientColor2.value = '#00f2fe';
                    gradientDirection.value = 'to bottom';
                    break;
                case 'forest':
                    gradientColor1.value = '#43e97b';
                    gradientColor2.value = '#38f9d7';
                    gradientDirection.value = 'to bottom';
                    break;
                case 'purple':
                    gradientColor1.value = '#667eea';
                    gradientColor2.value = '#764ba2';
                    gradientDirection.value = 'to bottom';
                    break;
                case 'warm':
                    gradientColor1.value = '#fa709a';
                    gradientColor2.value = '#fee140';
                    gradientDirection.value = '45deg';
                    break;
                case 'cool':
                    gradientColor1.value = '#a8edea';
                    gradientColor2.value = '#fed6e3';
                    gradientDirection.value = 'to bottom';
                    break;
            }
            
            // Update gradient preview
            updateGradientPreview();
        }
        
        function updateGradientPreview() {
            const gradientPreview = document.getElementById('gradientPreview');
            const color1 = document.getElementById('gradientColor1').value;
            const color2 = document.getElementById('gradientColor2').value;
            const direction = document.getElementById('gradientDirection').value;
            
            if (gradientPreview) {
                gradientPreview.style.background = `linear-gradient(${direction}, ${color1}, ${color2})`;
            }
        }

        function saveBackgroundCustom() {
            try {
                const form = document.getElementById('backgroundCustomForm');
                if (!form) {
                    console.error('Form not found');
                    showNotification('Error: Form tidak ditemukan!', 'error');
                    return;
                }
                
                const formData = new FormData(form);
                
                // Get background type
                const backgroundTypeRadio = document.querySelector('input[name="background_type"]:checked');
                if (!backgroundTypeRadio) {
                    showNotification('Pilih tipe background terlebih dahulu!', 'error');
                    return;
                }
                
                const backgroundType = backgroundTypeRadio.value;
                console.log('Selected background type:', backgroundType);
                
                // Validate based on type
                if (backgroundType === 'image') {
                    const backgroundImage = document.getElementById('backgroundImage');
                    if (!backgroundImage || !backgroundImage.files[0]) {
                        showNotification('Pilih gambar background terlebih dahulu!', 'error');
                        return;
                    }
                    console.log('Image file selected:', backgroundImage.files[0].name);
                } else if (backgroundType === 'color') {
                    const backgroundColor = document.getElementById('backgroundColor');
                    if (!backgroundColor || !backgroundColor.value) {
                        showNotification('Pilih warna background terlebih dahulu!', 'error');
                        return;
                    }
                    console.log('Color selected:', backgroundColor.value);
                } else if (backgroundType === 'gradient') {
                    const gradientColor1 = document.getElementById('gradientColor1');
                    const gradientColor2 = document.getElementById('gradientColor2');
                    if (!gradientColor1 || !gradientColor2 || !gradientColor1.value || !gradientColor2.value) {
                        showNotification('Pilih kedua warna gradient terlebih dahulu!', 'error');
                        return;
                    }
                    console.log('Gradient colors:', gradientColor1.value, gradientColor2.value);
                }

                // Tampilkan loading state
                const saveBtn = document.querySelector('#editBackgroundCustomModal button[onclick="saveBackgroundCustom()"]');
                if (!saveBtn) {
                    console.error('Save button not found');
                    showNotification('Error: Button simpan tidak ditemukan!', 'error');
                    return;
                }
                
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
                saveBtn.disabled = true;

                // Debug: log data yang akan dikirim
                console.log('Sending background custom data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }

                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                formData.append('_token', csrfToken);

                const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
                const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
                
                fetch(`/update-background-custom/${kodeUnik}/${namaLink}`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        showNotification(data.message, 'success');
                        hideEditBackgroundCustomModal();
                        
                        // Auto refresh preview setelah save
                        setTimeout(() => {
                            refreshPreview();
                        }, 500);
                    } else {
                        showNotification(data.message || 'Gagal menyimpan background custom!', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error saving background custom:', error);
                    showNotification('Gagal menyimpan background custom! Error: ' + error.message, 'error');
                })
                .finally(() => {
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                });
                
            } catch (error) {
                console.error('Unexpected error in saveBackgroundCustom:', error);
                showNotification('Terjadi kesalahan yang tidak terduga: ' + error.message, 'error');
            }
        }

        function removeSpotifyField(fieldId) {
            const field = document.querySelector(`[data-field-id="${fieldId}"]`);
            if (field) {
                field.remove();
            }
        }

        function saveSpotifyEmbed() {
            const form = document.getElementById('spotifyEmbedForm');
            const formData = new FormData(form);
            
            // Validasi minimal 1 embed
            const spotifyFields = document.querySelectorAll('.spotify-field');
            if (spotifyFields.length === 0) {
                showNotification('Minimal harus ada 1 embed Spotify!', 'error');
                return;
            }

            // Tampilkan loading state
            const saveBtn = document.querySelector('#editSpotifyEmbedModal .control-btn.btn-edit');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            saveBtn.disabled = true;

            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            fetch(`/update-spotify-embed/${kodeUnik}/${namaLink}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    hideEditSpotifyEmbedModal();
                    
                    // Auto refresh preview setelah save
                    setTimeout(() => {
                        refreshPreview();
                    }, 500);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error saving Spotify embed:', error);
                showNotification('Gagal menyimpan Spotify embed!', 'error');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        // Initialize modals with default fields
        function initializeModals() {
            // Initialize social media fields only (no default content)
            initializeSocialMediaFields();
            
            // Add default fields to other modals
            addProductField();
            addLinkField();
            addYoutubeField();
            addPortfolioField();
            addSpotifyField();
            
            // Initialize background custom modal
            toggleBackgroundOptions();
            
            // Add event listeners for gradient preview
            setTimeout(() => {
                const gradientColor1 = document.getElementById('gradientColor1');
                const gradientColor2 = document.getElementById('gradientColor2');
                const gradientDirection = document.getElementById('gradientDirection');
                
                if (gradientColor1) gradientColor1.addEventListener('input', updateGradientPreview);
                if (gradientColor2) gradientColor2.addEventListener('input', updateGradientPreview);
                if (gradientDirection) gradientDirection.addEventListener('change', updateGradientPreview);
            }, 1000);
        }

        // Save layout
        function saveLayout() {
            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            const layoutData = {
                order: currentOrder,
                hidden: Array.from(hiddenElements),
                timestamp: new Date().toISOString()
            };
            
            // Simpan ke localStorage sebagai backup
            localStorage.setItem('bioKerenLayout', JSON.stringify(layoutData));
            
            // Bersihkan data elemen yang tidak ada dalam currentOrder
            cleanAllRemovedElementData();
            
            // Tampilkan loading state
            const saveBtn = document.querySelector('.save-btn');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            saveBtn.disabled = true;
            
            // Kirim ke server via API
            fetch(`/store-layout/${kodeUnik}/${namaLink}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(layoutData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Tampilkan notifikasi sukses
                    showNotification(data.message, 'success');
                    
                    // Auto refresh preview setelah save layout
                    setTimeout(() => {
                        refreshPreview();
                    }, 500);
                } else {
                    showNotification('Gagal menyimpan layout ke server!', 'error');
                }
            })
            .catch(error => {
                console.error('Error saving layout:', error);
                showNotification('Gagal menyimpan layout ke server!', 'error');
            })
            .finally(() => {
                // Reset button state
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        // Auto save layout dengan debouncing untuk performa yang lebih baik
        let autoSaveTimeout;
        function autoSaveLayout() {
            // Clear timeout yang ada
            if (autoSaveTimeout) {
                clearTimeout(autoSaveTimeout);
            }
            
            // Tampilkan indikator auto save yang sedang berjalan
            showAutoSaveIndicator();
            
            // Set timeout baru untuk debouncing (500ms)
            autoSaveTimeout = setTimeout(() => {
                const layoutData = {
                    order: currentOrder,
                    hidden: Array.from(hiddenElements),
                    timestamp: new Date().toISOString()
                };
                
                // Log untuk debugging
                console.log('Auto saving layout:', layoutData);
                
                // Simpan ke localStorage sebagai backup
                localStorage.setItem('bioKerenLayout', JSON.stringify(layoutData));
                
                // Bersihkan data elemen yang tidak ada dalam currentOrder
                cleanAllRemovedElementData();
                
                // Kirim ke server via API tanpa loading state
                const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
                const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
                
                fetch(`/store-layout/${kodeUnik}/${namaLink}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(layoutData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Auto refresh preview setelah auto save
                        setTimeout(() => {
                            refreshPreview();
                        }, 300);
                        
                        // Tampilkan notifikasi auto save berhasil
                        showAutoSaveSuccess();
                    } else {
                        console.error('Auto save layout gagal:', data.message);
                        showAutoSaveError();
                    }
                })
                .catch(error => {
                    console.error('Error auto saving layout:', error);
                    showAutoSaveError();
                });
            }, 500);
        }

        // Tampilkan indikator auto save sedang berjalan
        function showAutoSaveIndicator() {
            // Hapus notifikasi auto save yang sudah ada
            const existingIndicator = document.querySelector('.auto-save-indicator');
            if (existingIndicator) {
                existingIndicator.remove();
            }
            
            const indicator = document.createElement('div');
            indicator.className = 'auto-save-indicator fixed top-4 left-4 p-3 bg-blue-500 text-white rounded-lg shadow-lg z-[100000] flex items-center space-x-2';
            indicator.innerHTML = `
                <i class="fas fa-save fa-spin"></i>
                <span class="text-sm">Auto saving layout...</span>
            `;
            
            document.body.appendChild(indicator);
            
            // Auto hide setelah 3 detik
            setTimeout(() => {
                if (indicator.parentElement) {
                    indicator.remove();
                }
            }, 3000);
        }

        // Tampilkan notifikasi auto save berhasil
        function showAutoSaveSuccess() {
            // Hapus indikator yang sedang berjalan
            const existingIndicator = document.querySelector('.auto-save-indicator');
            if (existingIndicator) {
                existingIndicator.remove();
            }
            
            const successIndicator = document.createElement('div');
            successIndicator.className = 'auto-save-success fixed top-4 left-4 p-3 bg-green-500 text-white rounded-lg shadow-lg z-[100000] flex items-center space-x-2';
            successIndicator.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle"></i>
                    <span class="text-sm">Layout auto saved!</span>
                </div>
            `;
            
            document.body.appendChild(successIndicator);
            
            // Auto hide setelah 2 detik
            setTimeout(() => {
                if (successIndicator.parentElement) {
                    successIndicator.remove();
                }
            }, 2000);
        }

        // Tampilkan notifikasi auto save error
        function showAutoSaveError() {
            // Hapus indikator yang sedang berjalan
            const existingIndicator = document.querySelector('.auto-save-indicator');
            if (existingIndicator) {
                existingIndicator.remove();
            }
            
            const errorIndicator = document.createElement('div');
            errorIndicator.className = 'auto-save-error fixed top-4 left-4 p-3 bg-red-500 text-white rounded-lg shadow-lg z-[100000] flex items-center space-x-2';
            errorIndicator.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="text-sm">Auto save gagal!</span>
                </div>
            `;
                
            document.body.appendChild(errorIndicator);
            
            // Auto hide setelah 3 detik
            setTimeout(() => {
                if (errorIndicator.parentElement) {
                    errorIndicator.remove();
                }
            }, 3000);
        }

        // Load layout dari localStorage atau server
        function loadLayout() {
            // Coba load dari server terlebih dahulu
            loadLayoutFromServer();
            
            // Fallback ke localStorage
            const savedLayout = localStorage.getItem('bioKerenLayout');
            if (savedLayout) {
                try {
                    const layoutData = JSON.parse(savedLayout);
                    currentOrder = layoutData.order || currentOrder;
                    hiddenElements = new Set(layoutData.hidden || []);
                    renderElementList();
                    setupDragAndDrop();
                } catch (e) {
                    console.error('Error loading layout from localStorage:', e);
                }
            }
        }

        // Load layout dari server
        function loadLayoutFromServer() {
            const kodeUnik = document.querySelector('meta[name="kode-unik"]').getAttribute('content');
            const namaLink = document.querySelector('meta[name="nama-link"]').getAttribute('content');
            
            // Tampilkan loading state
            const loadBtn = document.querySelector('button[onclick="loadLayoutFromServer()"]');
            const originalText = loadBtn.innerHTML;
            loadBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
            loadBtn.disabled = true;
            
            fetch(`/get-layout/${kodeUnik}/${namaLink}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data && data.data.order && data.data.order.length > 0) {
                    const layoutData = data.data;
                    currentOrder = layoutData.order;
                    hiddenElements = new Set(layoutData.hidden || []);
                    renderElementList();
                    setupDragAndDrop();
                    
                    // Auto refresh preview setelah load layout
                    setTimeout(() => {
                        refreshPreview();
                    }, 500);
                    
                    showNotification('Layout berhasil dimuat dari server!', 'success');
                    console.log('Layout berhasil dimuat dari server');
                } else {
                    // Jika tidak ada data dari server, kosongkan sidebar
                    currentOrder = [];
                    hiddenElements.clear();
                    renderElementList();
                    showNotification('Tidak ada layout tersimpan, sidebar dikosongkan', 'info');
                }
            })
            .catch(error => {
                console.error('Error loading layout from server:', error);
                showNotification('Gagal memuat layout dari server!', 'error');
                // Jika error, kosongkan sidebar
                currentOrder = [];
                hiddenElements.clear();
                renderElementList();
            })
            .finally(() => {
                // Reset button state
                loadBtn.innerHTML = originalText;
                loadBtn.disabled = false;
            });
        }

        // Tampilkan notifikasi
        function showNotification(message, type = 'info') {
            // Hapus notifikasi yang sudah ada
            const existingNotification = document.querySelector('.notification');
            if (existingNotification) {
                existingNotification.remove();
            }
            
            // Buat notifikasi baru
            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-[100000] max-w-sm ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto hide setelah 5 detik
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

                // Reset layout ke default
        function resetLayout() {
            Swal.fire({
                title: 'Reset Layout',
                text: 'Yakin ingin mereset layout ke pengaturan default?\n\nSemua perubahan yang belum disimpan akan hilang.\nTindakan ini tidak dapat dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kosongkan sidebar
                    currentOrder = [];
                    hiddenElements.clear();
                renderElementList();
                setupDragAndDrop();
                    
                    // Auto simpan layout dan refresh preview
                    autoSaveLayout();
                    
                    showNotification('Layout berhasil direset!', 'success');
                }
            });
        }

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            initEditor();
            loadLayout();
            
            // Update preview setelah iframe load
            const iframe = document.getElementById('previewFrame');
            iframe.addEventListener('load', () => {
                setTimeout(() => {
                    updateIframeContent();
                }, 500);
            });
        });

        // Handle pesan dari iframe
        window.addEventListener('message', (event) => {
            if (event.data.type === 'LAYOUT_UPDATED') {
                console.log('Layout updated in iframe');
            }
        });

        // Show all elements
        function showAllElements() {
            Swal.fire({
                title: 'Tampilkan Semua Elemen',
                text: 'Apakah Anda yakin ingin menampilkan semua elemen yang tersembunyi?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Tampilkan Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    hiddenElements.clear();
                    renderElementList();
                    setupDragAndDrop();
                    
                    // Auto simpan layout dan refresh preview
                    autoSaveLayout();
                    
                    showNotification('Semua elemen telah ditampilkan!', 'success');
                }
            });
        }
    </script>
</body>
</html>
