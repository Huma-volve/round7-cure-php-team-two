<?php
// lang/en/validation.php


return [
// ...
// ... باقي الرسايل الجاهزة
// ...

/*
|--------------------------------------------------------------------------
| Custom Validation Attributes
|--------------------------------------------------------------------------
|
| The following language lines are used to swap our attribute placeholder
| with something more reader friendly such as "E-Mail Address" instead
| of "email". This simply helps us make our message more expressive.
|
*/

'attributes' => [
'name' => 'Name',
'email' => 'Email Address',
'password' => 'Password',
'image' => 'Image',
],

/*
|--------------------------------------------------------------------------
| Custom Validation Language Lines
|--------------------------------------------------------------------------
|
| Here you may specify custom validation messages for attributes using the
| convention "attribute.rule" to name the lines. This makes it quick to
| specify a specific custom language line for a given attribute rule.
|
*/

'custom' => [


    'name.required' => 'The name field is required.',
    'name.max' => 'The name must not exceed 255 characters.',

    'email.required' => 'The email field is required.',
    'email.exists' => 'The email address does not exist.',
    'email.email' => 'Please provide a valid email address.',
    'email.unique' => 'This email is already taken.',
    'password.min' => 'The password must be at least 8 characters.',
    'password.confirmed' => 'The password confirmation does not match.',
    'image.image' => 'The file must be an image.',
    'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
    'image.max' => 'The image must not be larger than 2MB.',
    'password.letters' => 'The password must contain at least one letter.',
    'password.numbers' => 'The password must contain at least one number.',
    'password.symbols' => 'The password must contain at least one symbol.',
    'latitude.required' => 'The latitude field is required.',
    'latitude.numeric' => 'The latitude must be a number.',
    'latitude.between' => 'The latitude must be between -90 and 90.',
    'longitude.required' => 'The longitude field is required.',
    'longitude.numeric' => 'The longitude must be a number.',
    'longitude.between' => 'The longitude must be between -180 and 180.',
    'image.required' => 'The image field is required.',
    'phone_number.required' => 'The phone number field is required.',
    'phone_number.unique' => 'This phone number is already taken.',

],

];
