<?php

return [
'show_custom_fields' => false,
    'custom_fields' => [
        'username' => [
            'type' => 'text',
            'label' => 'Username',
            'placeholder' => 'Masukan username',
            'required' => true,
            'rules' => 'required|string|max:255|unique:users|alpha_dash|min:3',
        ],
        'phone_number' => [
            'type' => 'text',
            'label' => 'Nomor Telepon',
            'placeholder' => 'Masukan nomor telepon',
            'required' => true,
            'rules' => 'required|string|max:22|unique:users|alpha_dash|min:3',
        ],
    ]
];
