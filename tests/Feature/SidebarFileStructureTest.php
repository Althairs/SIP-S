<?php

it('sidebar master blade file exists', function () {
    $path = resource_path('views/components/navigation/sidebar.blade.php');
    expect(file_exists($path))->toBeTrue();
});

it('old sidebar files have been deleted', function () {
    $oldFiles = [
        'sidebar-kajur.blade.php',
        'sidebar-sekjur.blade.php',
        'sidebar-mahasiswa.blade.php',
        'sidebar-dosen.blade.php',
        'sidebar-panitia-verifikasi.blade.php',
        'sidebar-panitia-penjadwalan.blade.php',
        'sidebar-panitia-administrasi.blade.php',
    ];

    $navDir = resource_path('views/components/navigation');

    foreach ($oldFiles as $file) {
        $path = $navDir . '/' . $file;
        expect(file_exists($path))->toBeFalse();
    }
});

it('no old sidebar component references in blade files', function () {
    $oldReferences = [
        'x-navigation.sidebar-kajur',
        'x-navigation.sidebar-sekjur',
        'x-navigation.sidebar-mahasiswa',
        'x-navigation.sidebar-dosen',
        'x-navigation.sidebar-panitia-verifikasi',
        'x-navigation.sidebar-panitia-penjadwalan',
        'x-navigation.sidebar-panitia-administrasi',
    ];

    $bladeFiles = glob(resource_path('views/**/*.blade.php'));

    foreach ($bladeFiles as $file) {
        $content = file_get_contents($file);
        foreach ($oldReferences as $ref) {
            expect($content)
                ->not->toContain($ref, "Found old reference '{$ref}' in " . basename($file));
        }
    }
});

it('app-auth uses single sidebar component', function () {
    $path = resource_path('views/components/layouts/app-auth.blade.php');
    $content = file_get_contents($path);

    // Should contain the single sidebar component
    expect($content)->toContain('<x-navigation.sidebar />');

    // Should NOT contain old role-based dispatch logic
    expect($content)->not->toContain('sidebar-kajur');
    expect($content)->not->toContain('sidebar-sekjur');
    expect($content)->not->toContain('sidebar-mahasiswa');
    expect($content)->not->toContain('sidebar-dosen');
    expect($content)->not->toContain('sidebar-panitia');
});

it('sidebar uses spatie role directives', function () {
    $path = resource_path('views/components/navigation/sidebar.blade.php');
    $content = file_get_contents($path);

    expect($content)->toContain('@role(');
    expect($content)->toContain('@endrole');
    expect($content)->toContain('@hasanyrole(');
    expect($content)->toContain('@endhasanyrole');
});

it('sidebar uses match true for home route', function () {
    $path = resource_path('views/components/navigation/sidebar.blade.php');
    $content = file_get_contents($path);

    expect($content)->toContain('match(true)');
    expect($content)->toContain('$homeRoute');
});
