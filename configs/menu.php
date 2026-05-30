<?php

return [

    'admin' => [
        [
            'title' => 'Dashboard',
            'icon'  => 'bi bi-grid-1x2-fill',
            'url'   => 'admin/dashboard',
            'pages' => ['dashboard.php']
        ],

        [
            'title' => 'Users',
            'icon'  => 'bi bi-person-gear',
            'url'   => 'admin/users',
            'pages' => [
                'index.php',
                'create.php',
                'edit.php',
                'delete.php'
            ]
        ],

        [
            'title' => 'Patients',
            'icon'  => 'bi bi-people-fill',
            'url'   => 'admin/patients',
            'pages' => [
                'index.php',
                'create.php',
                'edit.php',
            ]
        ],

        [
            'title' => 'Dentists',
            'icon'  => 'bi bi-person-badge-fill',
            'url'   => 'admin/dentists',
            'pages' => [
                'index.php',
                'create.php',
                'edit.php',
            ]
        ],
        
        [
            'title' => 'Services',
            'icon'  => 'bi bi-briefcase-fill',
            'url'   => 'admin/services',
            'pages' => [
                'index.php',
                'create.php',
                'edit.php',
            ]
        ],

        [
            'title' => 'Appointments',
            'icon'  => 'bi bi-calendar2-check-fill',
            'url'   => 'admin/appointments',
            'pages' => ['appointments.php']
        ],

        [
            'title' => 'Payments',
            'icon'  => 'bi bi-cash-stack',
            'url'   => 'admin/payments',
            'pages' => ['payments.php']
        ],

        [
            'title' => 'Feedbacks',
            'icon'  => 'bi bi-chat-dots-fill',
            'url'   => 'admin/feedbacks',
            'pages' => ['feedbacks.php']
        ]

    ],

    'staff' => [

        [
            'title' => 'Dashboard',
            'icon'  => 'bi bi-grid-1x2-fill',
            'url'   => 'staff/dashboard',
            'pages' => ['dashboard.php']
        ],

        [
            'title' => 'Appointments',
            'icon'  => 'bi bi-calendar-event-fill',
            'url'   => 'staff/appointments',
            'pages' => ['appointments.php']
        ],

        [
            'title' => 'Payments',
            'icon'  => 'bi bi-wallet2',
            'url'   => 'staff/payments',
            'pages' => ['payments.php']
        ]

    ],

    'dentist' => [

        [
            'title' => 'Dashboard',
            'icon'  => 'bi bi-grid-1x2-fill',
            'url'   => 'dentist/dashboard',
            'pages' => ['dashboard.php']
        ],

        [
            'title' => 'My Schedule',
            'icon'  => 'bi bi-calendar-week-fill',
            'url'   => 'dentist/schedule',
            'pages' => ['schedule.php']
        ],

        [
            'title' => 'My Patients',
            'icon'  => 'bi bi-person-lines-fill',
            'url'   => 'dentist/patients',
            'pages' => ['patients.php']
        ]

    ]

];
