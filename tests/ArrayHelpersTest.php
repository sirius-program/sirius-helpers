<?php

declare(strict_types=1);

use SiriusProgram\SiriusHelpers\Sirius;

it('can sort keys using list of the given keys', function (): void {
    $sirius = new Sirius;

    $array = [
        'name'  => 'Fathul Husnan',
        'phone' => '+62 812 3456 7890',
        'email' => 'fathulhusnan@example.com',
    ];

    $sortedArray = $sirius->array($array)->sortKeyByListOf(['email', 'name', 'phone'])->get();

    expect($sortedArray)->toBe([
        'email' => 'fathulhusnan@example.com',
        'name'  => 'Fathul Husnan',
        'phone' => '+62 812 3456 7890',
    ]);
});
