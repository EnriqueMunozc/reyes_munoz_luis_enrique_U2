<?php

return [
    'between' => [
        'numeric' => 'El campo :attribute debe estar entre :min y :max.',
    ],
    'confirmed' => 'La confirmacion de :attribute no coincide.',
    'decimal' => 'El campo :attribute debe tener como maximo :decimal decimales.',
    'email' => 'El campo :attribute debe ser una direccion de correo valida.',
    'exists' => 'El valor seleccionado para :attribute no es valido.',
    'image' => 'El archivo de :attribute debe ser una imagen valida.',
    'integer' => 'El campo :attribute debe ser un numero entero.',
    'max' => [
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
        'string' => 'El campo :attribute no debe superar :max caracteres.',
    ],
    'min' => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'prohibited' => 'El campo :attribute no esta permitido.',
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser texto.',
    'size' => [
        'string' => 'El campo :attribute debe tener :size caracteres.',
    ],
    'unique' => 'El campo :attribute ya esta registrado.',

    'attributes' => [
        'email' => 'correo electronico',
        'category_id' => 'categoria',
        'confirmation_key' => 'clave de confirmacion',
        'description' => 'descripcion',
        'image' => 'imagen',
        'name' => 'nombre',
        'password' => 'contrasena',
        'price' => 'precio',
        'role' => 'rol',
        'stock' => 'existencias',
        'quantity' => 'cantidad',
    ],
];
