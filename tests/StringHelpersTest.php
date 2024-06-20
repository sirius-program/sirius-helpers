<?php

it('can encrypt', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $string = $sirius
        ->string('text you want to encrypt @ 123')
        ->encrypt(salt: 'sirius')
        ->get();

    expect($string)
        ->toBe('MwTMh2laUQDG09O9ZsVCv2c8pON/3IlIHf+8Dq55gkg=');
});

it('can decrypt', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $string = $sirius
        ->string('MwTMh2laUQDG09O9ZsVCv2c8pON/3IlIHf+8Dq55gkg=')
        ->decrypt(salt: 'sirius')
        ->get();

    expect($string)
        ->toBe('text you want to encrypt @ 123');
});

it('can make url-safe string', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $string = $sirius
        ->string('MwTMh2laUQDG09O9ZsVCv2c8pON/3IlIHf+8Dq55gkg=')
        ->urlSafe()
        ->get();

    expect($string)
        ->toBe('MwTMh2laUQDG09O9ZsVCv2c8pON_3IlIHf.8Dq55gkg-');
});

it('can make url-safe string unsafe again', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $string = $sirius
        ->string('MwTMh2laUQDG09O9ZsVCv2c8pON_3IlIHf.8Dq55gkg-')
        ->urlUnsafe()
        ->get();

    expect($string)
        ->toBe('MwTMh2laUQDG09O9ZsVCv2c8pON/3IlIHf+8Dq55gkg=');
});

it('can convert string to readable phone number', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $string = $sirius
        ->string('081234567890')
        ->toPhoneNumber()
        ->get();

    expect($string)
        ->toBe('+62 812-3456-7890');

    $string = $sirius
        ->string('081234567890')
        ->toPhoneNumber(zeroPrefix: true)
        ->get();

    expect($string)
        ->toBe('0812-3456-7890');
});

it('can sanitize phone number', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $string = $sirius
        ->string('+62 812-3456-7890')
        ->sanitizePhoneNumber()
        ->get();

    expect($string)
        ->toBe('+6281234567890');

    $string = $sirius
        ->string('+62 812-3456-7890')
        ->sanitizePhoneNumber(zeroPrefix: true)
        ->get();

    expect($string)
        ->toBe('081234567890');
});

it('can be coverted into laravel\'s stringable instance', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $str = str('this is sirius helper');

    $string = $sirius
        ->string('this is sirius helper')
        ->toStr();

    expect($string)
        ->toBeInstanceOf(\Illuminate\Support\Stringable::class);

    expect($string->toString())
        ->toBe($str->toString());
});

it('can chain the methods', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $string = $sirius->string('this is sirius helper');
    expect($string->get())->toBe('this is sirius helper');

    $string = $string->encrypt(salt: 'sirius');
    expect($string->get())->toBe('Pz6V7+WfFiloAMYqp66K/rbgtm0Z8Op7bcKK9Dabs18=');

    $string = $string->urlSafe();
    expect($string->get())->toBe('Pz6V7.WfFiloAMYqp66K_rbgtm0Z8Op7bcKK9Dabs18-');

    $string = $string->urlUnsafe();
    expect($string->get())->toBe('Pz6V7+WfFiloAMYqp66K/rbgtm0Z8Op7bcKK9Dabs18=');

    $string = $string->decrypt(salt: 'sirius');
    expect($string->get())->toBe('this is sirius helper');
});
