<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AutenticacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_las_rutas_del_panel_exigen_login(): void
    {
        foreach (['/dashboard', '/vehiculos', '/marcas', '/perfil', '/cliente-api', '/vehiculos/reporte/pdf'] as $ruta) {
            $this->get($ruta)->assertRedirect('/login');
        }
    }

    public function test_un_usuario_se_registra_y_entra_al_dashboard(): void
    {
        $this->post('/registro', [
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'password' => 'Clave12345',
            'password_confirmation' => 'Clave12345',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticated();
        $this->get('/dashboard')->assertOk()->assertSee('Ana Pérez');
    }

    public function test_login_y_logout(): void
    {
        $user = User::factory()->create(['password' => 'Clave12345']);

        $this->post('/login', ['email' => $user->email, 'password' => 'incorrecta'])->assertSessionHasErrors('email');
        $this->post('/login', ['email' => $user->email, 'password' => 'Clave12345'])->assertRedirect('/dashboard');
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_recuperar_contrasena_envia_enlace_y_permite_cambiarla(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post('/olvide-contrasena', ['email' => $user->email])->assertSessionHas('success');

        Notification::assertSentTo($user, ResetPassword::class, function ($notificacion) use ($user) {
            $this->post('/restablecer-contrasena', [
                'token' => $notificacion->token,
                'email' => $user->email,
                'password' => 'NuevaClave123',
                'password_confirmation' => 'NuevaClave123',
            ])->assertRedirect('/login');

            return true;
        });

        $this->assertTrue(Hash::check('NuevaClave123', $user->fresh()->password));
    }

    public function test_el_usuario_edita_su_perfil_y_sube_avatar(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->put('/perfil', [
            'name' => 'Nombre Nuevo',
            'email' => $user->email,
            'telefono' => '3001234567',
            'avatar' => UploadedFile::fake()->image('yo.jpg'),
        ])->assertSessionHas('success');

        $user->refresh();
        $this->assertSame('Nombre Nuevo', $user->name);
        Storage::disk('public')->assertExists($user->avatar);
    }
}
