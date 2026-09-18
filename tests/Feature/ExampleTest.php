<?php

/**
 * ==============================================================================
 * Tujuan: Feature Test dasar pengujian respons halaman utama dan pengalihan ke login.
 * Dipakai Oleh: PHPUnit / php artisan test
 * Dependensi: Tests\TestCase
 * Daftar Fungsi: test_the_application_redirects_unauthenticated_to_login()
 * Side Effect: HTTP call simulasi ke route /
 * ==============================================================================
 */

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Pastikan pengunjung yang belum login dialihkan ke halaman /login.
     */
    public function test_the_application_redirects_unauthenticated_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }
}
