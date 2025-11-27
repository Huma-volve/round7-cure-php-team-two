<?php
// config/menu.php

return [

    /*
    |--------------------------------------------------------------------------
    | Sidebar Menu Configuration
    |--------------------------------------------------------------------------
    |
    | Each item:
    | - 'route' => named route OR url (if 'external' => true)
    | - 'label' => translation key or plain string
    | - 'icon'  => css class for icon (optional)
    | - 'role'  => string role required to see item (optional)
    | - 'permission' => ability name for Gate::allows / $user->can() (optional)
    | - 'children' => array of sub-items (optional)
    | - 'badge' => ['text'=> 'New', 'class'=>'bg-danger'] optional
    |
    */

    [
        'route' => 'doctor.dashboard',
        'label' => 'doctor Dashboard',
        'icon' => 'ri ri-home-smile-line',
        'role' => 'doctor',
    ],
    [
        'route' => 'admin.dashboard',
        'label' => 'Admin Dashboard',
        'icon' => 'ri ri-home-smile-line',
        'role' => 'admin',
    ],
    [
        'route' => 'doctors.index',
        'label' => 'Doctors',
        'icon' => 'ri ri-user-3-line',
        'role' => 'admin',
        'children' => [
            [
                'route' => 'admin.doctor.index',
                'label' => 'All Doctors',
                'icon' => 'ri ri-user-3-line',
                'role' => 'admin'

            ],
            [
                'route' => 'admin.doctor.add-page',
                'label' => 'Create Doctor',
                'icon' => 'ri ri-user-3-line',
                'role' => 'admin'

            ],

        ],
    ],

    [
        'route' => 'bookings.index',
        'label' => 'Bookings',
        'icon' => 'ri ri-calendar-2-line',
        'role' => 'admin',
        //'badge' => ['text' => '3', 'class' => 'badge bg-label-danger rounded-pill ms-2']
    ],
    [
        'route' => 'doctor.bookings.index',
        'label' => 'Bookings',
        'icon' => 'ri ri-calendar-2-line',
        'role' => 'doctor',
        //'badge' => ['text' => '3', 'class' => 'badge bg-label-danger rounded-pill ms-2']
    ],
    [
        'route' => 'doctor.available-time',
        'label' => 'All Available Times',
        'icon' => 'ri ri-alarm-line',
        'role' => 'doctor'

    ],

    [
        'route' => 'questions.index',
        'label' => 'Questions',
        'icon' => 'ri ri-question-line',
        'role' => 'admin',
        'children' => [
            [
                'route' => 'questions.index',
                'label' => 'All Questions',
                'icon' => 'ri ri-question-line',
                'role' => 'admin'

            ],
            [
                'route' => 'questions.create',
                'label' => 'Create Question',
                'icon' => 'ri ri-question-line',
                'role' => 'admin'

            ],

        ],

        // 'badge' => ['text' => '3', 'class' => 'badge bg-label-danger rounded-pill ms-2']
    ],
     [
        'route' => 'messenger',
        'label' => 'Chats',
         'icon' => 'ri-chat-3-line',
         'role' => 'doctor',
        // 'badge' => ['text' => '3', 'class' => 'badge bg-label-danger rounded-pill ms-2']
    ],
    [
        'route' => 'settings.index',
        'label' => 'Settings',
        'icon' => 'ri ri-settings-4-line',
        'role' => 'admin'
        // 'badge' => ['text' => '3', 'class' => 'badge bg-label-danger rounded-pill ms-2']
    ]



];
