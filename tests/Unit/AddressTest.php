<?php
use App\Models\Address\{Country, PhilippineBarangay, PhilippineCity, PhilippineProvince, PhilippineRegion};

test('address records persisted', function () {
    expect(Country::all()->count())->toBe(203);
    expect(PhilippineRegion::all()->count())->toBe(17);
    expect(PhilippineProvince::all()->count())->toBe(88);
    expect(PhilippineCity::all()->count())->toBe(1647);
    expect(PhilippineBarangay::all()->count())->toBe(42031);
});

test('default country is the Philippines', function () {
    expect(app(Country::class)->default()->code)->toBe('PH');
    expect(app(PhilippineRegion::class)->default()->identifier)->toBe('13');
    expect(app(PhilippineProvince::class)->default()->identifier)->toBe('1339');
    expect(app(PhilippineCity::class)->default()->identifier)->toBe('133901');
    expect(app(PhilippineBarangay::class)->default()->identifier)->toBe('133901060');
});

