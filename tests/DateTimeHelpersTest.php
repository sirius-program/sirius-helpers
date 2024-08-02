<?php

it('can be converted into php\'s datetime instance', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius;

    $datetime = $sirius->dateTime('2024-01-01 01:01:01')
        ->toDateTime()
        ->get();

    expect($datetime)
        ->toBeInstanceOf(\DateTime::class);
});

it('can be converted into nesbot\'s carbon instance', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius;

    $datetime = $sirius->dateTime('2024-01-01 01:01:01')
        ->toCarbon()
        ->get();

    expect($datetime)
        ->toBeInstanceOf(\Carbon\Carbon::class);
});

it('can be formatted', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius;

    $datetime = $sirius->dateTime('2024-01-01 01:01:01')
        ->toDateTime()
        ->format('Y-m-d')
        ->get();

    expect($datetime)
        ->toBe('2024-01-01');
});

it('can be converted into long month', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius;

    $datetime = $sirius->dateTime(1)
        ->toLongMonth()
        ->get();

    expect($datetime)
        ->toBe('January');

    $datetime = $sirius->dateTime('2024-02-01')
        ->toLongMonth()
        ->get();

    expect($datetime)
        ->toBe('February');

    $datetime = $sirius->dateTime('2024-03-01')
        ->toDateTime('Y-m-d')
        ->toLongMonth()
        ->get();

    expect($datetime)
        ->toBe('March');

    $datetime = $sirius->dateTime('2024-04-01')
        ->toCarbon('Y-m-d')
        ->toLongMonth()
        ->get();

    expect($datetime)
        ->toBe('April');
});

it('can be converted into short month', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius;

    $datetime = $sirius->dateTime(1)
        ->toShortMonth()
        ->get();

    expect($datetime)
        ->toBe('Jan');

    $datetime = $sirius->dateTime('2024-02-01')
        ->toShortMonth()
        ->get();

    expect($datetime)
        ->toBe('Feb');

    $datetime = $sirius->dateTime('2024-03-01')
        ->toDateTime('Y-m-d')
        ->toShortMonth()
        ->get();

    expect($datetime)
        ->toBe('Mar');

    $datetime = $sirius->dateTime('2024-04-01')
        ->toCarbon('Y-m-d')
        ->toShortMonth()
        ->get();

    expect($datetime)
        ->toBe('Apr');
});

it('can be converted into long day', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius;

    $datetime = $sirius->dateTime(1)
        ->toLongDay()
        ->get();

    expect($datetime)
        ->toBe('Monday');

    $datetime = $sirius->dateTime('2024-01-02')
        ->toLongDay()
        ->get();

    expect($datetime)
        ->toBe('Tuesday');

    $datetime = $sirius->dateTime('2024-01-03')
        ->toDateTime('Y-m-d')
        ->toLongDay()
        ->get();

    expect($datetime)
        ->toBe('Wednesday');

    $datetime = $sirius->dateTime('2024-01-04')
        ->toCarbon('Y-m-d')
        ->toLongDay()
        ->get();

    expect($datetime)
        ->toBe('Thursday');
});

it('can be converted into short day', function () {
    $sirius = new SiriusProgram\SiriusHelpers\Sirius;

    $datetime = $sirius->dateTime(1)
        ->toShortDay()
        ->get();

    expect($datetime)
        ->toBe('Mon');

    $datetime = $sirius->dateTime('2024-01-02')
        ->toShortDay()
        ->get();

    expect($datetime)
        ->toBe('Tue');

    $datetime = $sirius->dateTime('2024-01-03')
        ->toDateTime('Y-m-d')
        ->toShortDay()
        ->get();

    expect($datetime)
        ->toBe('Wed');

    $datetime = $sirius->dateTime('2024-01-04')
        ->toCarbon('Y-m-d')
        ->toShortDay()
        ->get();

    expect($datetime)
        ->toBe('Thu');
});

it('can retrieve list on months', function () {
    $months = SiriusProgram\SiriusHelpers\DateTimeHelpers::getAllMonths();

    expect($months)
        ->toBeArray();

    expect($months)
        ->toHaveCount(12);

    expect($months)
        ->toBe([
            1  => 'January',
            2  => 'February',
            3  => 'March',
            4  => 'April',
            5  => 'May',
            6  => 'June',
            7  => 'July',
            8  => 'August',
            9  => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ]);

    $months = SiriusProgram\SiriusHelpers\DateTimeHelpers::getAllMonths(format: 'MMM');

    expect($months)
        ->toBe([
            1  => 'Jan',
            2  => 'Feb',
            3  => 'Mar',
            4  => 'Apr',
            5  => 'May',
            6  => 'Jun',
            7  => 'Jul',
            8  => 'Aug',
            9  => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec',
        ]);
});

it('can retrieve list on days', function () {
    $days = SiriusProgram\SiriusHelpers\DateTimeHelpers::getAllDays();

    expect($days)
        ->toBeArray();

    expect($days)
        ->toHaveCount(7);

    expect($days)
        ->toBe([
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ]);

    $days = SiriusProgram\SiriusHelpers\DateTimeHelpers::getAllDays(format: 'EE', startingDay: SiriusProgram\SiriusHelpers\DateTimeHelpers::START_WITH_MONDAY);

    expect($days)
        ->toBe([
            1 => 'Mon',
            2 => 'Tue',
            3 => 'Wed',
            4 => 'Thu',
            5 => 'Fri',
            6 => 'Sat',
            7 => 'Sun',
        ]);
});
