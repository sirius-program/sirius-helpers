<?php

it('can calculate distance in meters', function () {
    $distance = SiriusProgram\SiriusHelpers\Sirius::calculateDistanceInMeters(-7.3197956, 112.765537, -7.3231706, 112.7578611);

    expect($distance)->toBe(926.0155020636498);
});

it('can set null if blank', function () {
    $data = SiriusProgram\SiriusHelpers\Sirius::setNullIfBlank(null);

    expect($data)->toBeNull();

    $data = SiriusProgram\SiriusHelpers\Sirius::setNullIfBlank(0);

    expect($data)->toBeNull();

    $data = SiriusProgram\SiriusHelpers\Sirius::setNullIfBlank('');

    expect($data)->toBeNull();

    $data = SiriusProgram\SiriusHelpers\Sirius::setNullIfBlank([]);

    expect($data)->toBeNull();

    $data = SiriusProgram\SiriusHelpers\Sirius::setNullIfBlank([0, 1, '', 2, null, 3, []]);

    expect($data)->toBe([null, 1, null, 2, null, 3, null]);

    $data = SiriusProgram\SiriusHelpers\Sirius::setNullIfBlank([0, 1, '', 2, null, 3, []], keepZero: true, keepEmptyArray: true, keepEmptyString: true);

    expect($data)->toBe([0, 1, '', 2, null, 3, []]);
});

it('can can get a country detail', function () {
    $detail = SiriusProgram\SiriusHelpers\Sirius::getCountryDetail('ID');

    expect($detail)->toBe(['code' => 'ID', 'name' => 'Indonesia', 'dailingCode' => '+62']);
});
