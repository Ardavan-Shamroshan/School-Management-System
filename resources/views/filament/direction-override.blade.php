@if(app()->getLocale() === 'fa')
    <style>
        [dir="rtl"] {
            direction: ltr !important;
        }
        .rtl\:text-right {
            text-align: left !important;
        }
        .rtl\:text-left {
            text-align: right !important;
        }
        .rtl\:ml-2 {
            margin-right: 0.5rem !important;
            margin-left: 0 !important;
        }
        .rtl\:mr-2 {
            margin-left: 0.5rem !important;
            margin-right: 0 !important;
        }
    </style>
@endif 
