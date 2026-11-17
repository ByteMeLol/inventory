<?php

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'super admin']);
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'manager']);
    Role::firstOrCreate(['name' => 'staff']);
});

function createTestUserAndCompany(): array
{
    $user = User::create([
        'first_name' => 'Juma',
        'last_name' => 'Rashid',
        'email' => 'juma@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 1,
    ]);

    $company = Company::create([
        'name' => 'Kilimanjaro Traders',
        'slug' => 'kilimanjaro-traders',
        'owner_id' => $user->id,
        'is_active' => true,
    ]);

    $user->update(['company_id' => $company->id]);
    $user->companies()->attach($company->id, ['role_id' => 1, 'status' => 'active']);

    return [$user, $company];
}

test('language switcher endpoint sets session locale and redirects', function () {
    // Switch to Kiswahili
    $responseSw = $this->get('/language/sw');
    $responseSw->assertRedirect();
    expect(session('locale'))->toBe('sw');

    // Switch to English
    $responseEn = $this->get('/language/en');
    $responseEn->assertRedirect();
    expect(session('locale'))->toBe('en');

    // Switch with invalid locale does not alter session
    $this->withSession(['locale' => 'en'])->get('/language/fr');
    expect(session('locale'))->toBe('en');
});

test('guest pages render correctly in Kiswahili and English', function () {
    // English Login
    $responseLoginEn = $this->withSession(['locale' => 'en'])->get('/');
    $responseLoginEn->assertOk();
    $responseLoginEn->assertSee('Sign in');
    $responseLoginEn->assertSee('Email address');
    $responseLoginEn->assertSee('Password');

    // Kiswahili Login
    $responseLoginSw = $this->withSession(['locale' => 'sw'])->get('/');
    $responseLoginSw->assertOk();
    $responseLoginSw->assertSee('Ingia');
    $responseLoginSw->assertSee('Barua pepe');
    $responseLoginSw->assertSee('Nenosiri');
    $responseLoginSw->assertSee('Nikumbuke');

    // English Signup
    $responseSignupEn = $this->withSession(['locale' => 'en'])->get('/signup');
    $responseSignupEn->assertOk();
    $responseSignupEn->assertSee('Create account');
    $responseSignupEn->assertSee('Business name');
    $responseSignupEn->assertSee('First name');
    $responseSignupEn->assertSee('Last name');

    // Kiswahili Signup
    $responseSignupSw = $this->withSession(['locale' => 'sw'])->get('/signup');
    $responseSignupSw->assertOk();
    $responseSignupSw->assertSee('Fungua Akaunti');
    $responseSignupSw->assertSee('Jina la biashara');
    $responseSignupSw->assertSee('Jina la kwanza');
    $responseSignupSw->assertSee('Jina la mwisho');
});

test('authenticated pages render fully in Kiswahili', function () {
    [$user, $company] = createTestUserAndCompany();

    $session = [
        'locale' => 'sw',
        'company_id' => $company->id,
        'company_name' => $company->name,
    ];

    // Home / Dashboard
    $resp = $this->actingAs($user)->withSession($session)->get('/home');
    $resp->assertOk();
    $resp->assertSee('Habari za leo');
    $resp->assertSee('Maagizo ya Mauzo');
    $resp->assertSee('Matumizi na Gharama');
    $resp->assertSee('Manunuzi na Wasambazaji');
    $resp->assertSee('Leja ya Fedha');
    $resp->assertSee('Bidhaa Ghalani');

    // Dashboard Overview
    $resp = $this->actingAs($user)->withSession($session)->get('/dashboard');
    $resp->assertOk();
    $resp->assertSee('Muhtasari');
    $resp->assertSee('Bidhaa Zinazopatikana');
    $resp->assertSee('Tahadhari ya Bidhaa Zinazokaribia Kuisha');
    $resp->assertSee('Mwezi huu');

    // Inventory
    $resp = $this->actingAs($user)->withSession($session)->get('/inventory');
    $resp->assertOk();
    $resp->assertSee('Muhtasari wa Bidhaa Ghalani');
    $resp->assertSee('Sajili SKU Mpya');
    $resp->assertSee('Jumla ya Bidhaa za Orodha');
    $resp->assertSee('Makundi Yote');

    // Sales
    $resp = $this->actingAs($user)->withSession($session)->get('/sales');
    $resp->assertOk();
    $resp->assertSee('Leja ya Maagizo ya Mauzo');
    $resp->assertSee('Agizo Jipya la Mauzo');
    $resp->assertSee('Maagizo Yasiyokamilika');
    $resp->assertSee('Mauzo Yaliyokamilika');

    // Purchases
    $resp = $this->actingAs($user)->withSession($session)->get('/purchases');
    $resp->assertOk();
    $resp->assertSee('Manunuzi na Wasambazaji');
    $resp->assertSee('Ongeza msambazaji');
    $resp->assertSee('Rekodi ununuzi');
    $resp->assertSee('Wasambazaji wanaofanya kazi');

    // Customers
    $resp = $this->actingAs($user)->withSession($session)->get('/customers');
    $resp->assertOk();
    $resp->assertSee('Orodha ya Wateja');
    $resp->assertSee('Ongeza Wasifu wa Mteja');
    $resp->assertSee('Jumla ya Wateja');

    // Finance
    $resp = $this->actingAs($user)->withSession($session)->get('/finance');
    $resp->assertOk();
    $resp->assertSee('Pesa zinazoingia na kutoka');
    $resp->assertSee('Kundi la fedha');
    $resp->assertSee('Rekodi muamala');

    // Expenses
    $resp = $this->actingAs($user)->withSession($session)->get('/expenses');
    $resp->assertOk();
    $resp->assertSee('Matumizi na Gharama');
    $resp->assertSee('Rekodi Matumizi');
    $resp->assertSee('Daftari la Matumizi');

    // Reports
    $resp = $this->actingAs($user)->withSession($session)->get('/reports');
    $resp->assertOk();
    $resp->assertSee('Kitovu cha Ripoti');
    $resp->assertSee('Vigezo vya Kuchagua Ripoti');
    $resp->assertSee('Kusanya Leja');

    // Staff
    $resp = $this->actingAs($user)->withSession($session)->get('/staff');
    $resp->assertOk();
    $resp->assertSee('Simamia Wafanyakazi na Kampuni');
    $resp->assertSee('Orodha ya Wafanyakazi');
    $resp->assertSee('Ongeza Mfanyakazi');

    // Settings
    $resp = $this->actingAs($user)->withSession($session)->get('/settings');
    $resp->assertOk();
    $resp->assertSee('Mipangilio ya Mfumo');
    $resp->assertSee('Hifadhi Mipangilio');
    $resp->assertSee('Mapendeleo ya Lugha');

    // Profile
    $resp = $this->actingAs($user)->withSession($session)->get('/profile');
    $resp->assertOk();
    $resp->assertSee('Taarifa za Akaunti');
    $resp->assertSee('Jina Kamili la Kisheria');
    $resp->assertSee('Hatua za Usalama');
});

test('validation and authentication errors are translated to Kiswahili', function () {
    // Authentication error in Kiswahili
    $response = $this->withSession(['locale' => 'sw'])->post('/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'wrongpassword',
    ]);
    $response->assertSessionHasErrors('email');
    $errors = session('errors')->get('email');
    expect($errors[0])->toBe('Taarifa hizi za kuingia hazilingani na rekodi zetu.');

    // Required field validation in Kiswahili
    $responseSignup = $this->withSession(['locale' => 'sw'])->post('/signup', []);
    $responseSignup->assertSessionHasErrors(['first_name', 'email']);
    $firstNameError = session('errors')->get('first_name')[0];
    expect($firstNameError)->toContain('kinahitajika');
});
