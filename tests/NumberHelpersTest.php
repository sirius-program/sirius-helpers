<?php

it('can be converted into int', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $number = $sirius
        ->number('123.45')
        ->toInt()
        ->get();

    expect($number)
        ->toBe(123);
});

it('can be converted into float', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $number = $sirius
        ->number('123.45')
        ->toFloat()
        ->get();

    expect($number)
        ->toBe(123.45);
});

it('can be formatted', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $number = $sirius
        ->number('1234567.89')
        ->format()
        ->get();

    expect($number)
        ->toBe('1.234.567,89');

    $number = $sirius
        ->number('1234567.89')
        ->format('en_US')
        ->get();

    expect($number)
        ->toBe('1,234,567.89');
});

it('can be converted into roman numerals', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $number = $sirius
        ->number('1234')
        ->toRoman()
        ->get();

    expect($number)
        ->toBe('MCCXXXIV');
});

it('can be converted into currency', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $number = $sirius
        ->number('1234567.89')
        ->toCurrency()
        ->get();

    expect($number)
        ->toBe('Rp1.234.567,89');

    $number = $sirius
        ->number('1234567.89')
        ->toCurrency('en_US')
        ->get();

    expect($number)
        ->toBe('$1,234,567.89');
});

it('can be converted into cent', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $number = $sirius
        ->number('1234567.89')
        ->toCent()
        ->get();

    expect($number)
        ->toBe('123.456.789');
});

it('can be converted from cent', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $number = $sirius
        ->number(123456789)
        ->fromCent();

    expect($number->get())->toBe('1.234.567,89');
    expect($number->getOriginal())->toBe(1234567.89);

    $number = $sirius
        ->number(123456789)
        ->fromCent(impactOriginalNumber: false);

    expect($number->get())->toBe('1.234.567,89');
    expect($number->getOriginal())->toBe(123456789);
});

it('can spell out the number', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $number = $sirius
        ->number('1234567.89')
        ->spell()
        ->get();

    expect($number)
        ->toBe('satu juta dua ratus tiga puluh empat ribu lima ratus enam puluh tujuh koma delapan sembilan');

    $number = $sirius
        ->number('1234567.89')
        ->spell('en_US')
        ->get();

    expect($number)
        ->toBe('one million two hundred thirty-four thousand five hundred sixty-seven point eight nine');
});

it('can spell out the currency with it\'s symbol', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $number = $sirius
        ->number('1234567.89')
        ->toCurrency()
        ->spell()
        ->get();

    expect($number)
        ->toBe('satu juta dua ratus tiga puluh empat ribu lima ratus enam puluh tujuh koma delapan sembilan rupiah');

    $number = $sirius
        ->number('1234567.89')
        ->toCurrency('en_US')
        ->spell()
        ->get();

    expect($number)
        ->toBe('one million two hundred thirty-four thousand five hundred sixty-seven point eight nine dollars');
});

it('can get the original number', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $number = $sirius
        ->number('1234567.89')
        ->toCurrency()
        ->getOriginal();

    expect($number)
        ->toBe(1234567.89);
});

it('can chain the methods', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius();

    $number = $sirius->number('1234567.89');
    expect($number->get())->toBe(1234567.89);

    $number = $number->format();
    expect($number->get())->toBe('1.234.567,89');

    $number = $number->toCurrency();
    expect($number->get())->toBe('Rp1.234.567,89');

    $number = $number->spell();
    expect($number->get())->toBe('satu juta dua ratus tiga puluh empat ribu lima ratus enam puluh tujuh koma delapan sembilan rupiah');

    $number = $number->getOriginal();
    expect($number)->toBe(1234567.89);
});
