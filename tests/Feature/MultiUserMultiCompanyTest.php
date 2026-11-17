<?php

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ensure roles are seeded
    Role::firstOrCreate(['name' => 'super admin']);
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'manager']);
    Role::firstOrCreate(['name' => 'staff']);
});

test('user registration creates an account owner and their initial company', function () {
    $response = $this->post('/signup', [
        'first_name' => 'Alice',
        'last_name' => 'Wonderland',
        'company_name' => 'Wonderland Enterprises',
        'phone' => '1234567890',
        'email' => 'alice@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    $response->assertRedirect('/');

    $user = User::where('email', 'alice@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->creator_id)->toBeNull();

    $company = Company::where('name', 'Wonderland Enterprises')->first();
    expect($company)->not->toBeNull();
    expect($company->owner_id)->toBe($user->id);
    expect($user->belongsToCompany($company->id))->toBeTrue();
});

test('user can create more than one company', function () {
    $user = User::create([
        'first_name' => 'Bob',
        'last_name' => 'Builder',
        'email' => 'bob@example.com',
        'phone' => '0987654321',
        'password' => Hash::make('secret123'),
        'role_id' => 2,
    ]);

    $firstCompany = Company::create([
        'name' => 'Bob Construction',
        'slug' => 'bob-construction',
        'owner_id' => $user->id,
        'is_active' => true,
    ]);
    $user->update(['company_id' => $firstCompany->id]);
    $user->companies()->attach($firstCompany->id, ['role_id' => 2, 'status' => 'active', 'joined_at' => now()]);

    $this->actingAs($user);

    // Create a second company
    $response = $this->post('/companies', [
        'name' => 'Bob Logistics',
        'phone' => '0987654322',
        'currency' => 'USD',
    ]);

    $response->assertRedirect();

    $secondCompany = Company::where('name', 'Bob Logistics')->first();
    expect($secondCompany)->not->toBeNull();
    expect($secondCompany->owner_id)->toBe($user->id);
    expect($user->belongsToCompany($secondCompany->id))->toBeTrue();

    // Verify user now has 2 available companies
    $availableCompanies = $user->availableCompanies();
    expect($availableCompanies->count())->toBe(2);
    expect($availableCompanies->pluck('name')->all())->toContain('Bob Construction', 'Bob Logistics');
});

test('user can switch active company scope between their own companies but not another users company', function () {
    $userA = User::create([
        'first_name' => 'UserA',
        'last_name' => 'Owner',
        'email' => 'usera@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 2,
    ]);
    $companyA1 = Company::create(['name' => 'Company A1', 'slug' => 'comp-a1', 'owner_id' => $userA->id, 'is_active' => true]);
    $companyA2 = Company::create(['name' => 'Company A2', 'slug' => 'comp-a2', 'owner_id' => $userA->id, 'is_active' => true]);
    $userA->companies()->attach([$companyA1->id => ['role_id' => 2, 'status' => 'active'], $companyA2->id => ['role_id' => 2, 'status' => 'active']]);

    $userB = User::create([
        'first_name' => 'UserB',
        'last_name' => 'Owner',
        'email' => 'userb@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 2,
    ]);
    $companyB = Company::create(['name' => 'Company B', 'slug' => 'comp-b', 'owner_id' => $userB->id, 'is_active' => true]);
    $userB->companies()->attach([$companyB->id => ['role_id' => 2, 'status' => 'active']]);

    // User A switches to company A2 - should succeed
    $this->actingAs($userA);
    $response = $this->get('/companies/' . $companyA2->id);
    $response->assertRedirect();
    expect(session('company_id'))->toBe($companyA2->id);

    // User A attempts to switch to User B's company - should be 403 Forbidden
    $forbiddenResponse = $this->get('/companies/' . $companyB->id);
    $forbiddenResponse->assertStatus(403);
});

test('user tracks only their own staff and not other users staff', function () {
    // User A setup
    $userA = User::create([
        'first_name' => 'OwnerA',
        'last_name' => 'Alpha',
        'email' => 'ownera@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 2,
    ]);
    $companyA = Company::create(['name' => 'Alpha Corp', 'slug' => 'alpha-corp', 'owner_id' => $userA->id, 'is_active' => true]);
    $userA->update(['company_id' => $companyA->id]);
    $userA->companies()->attach($companyA->id, ['role_id' => 2, 'status' => 'active']);

    // Staff for User A
    $staffA = User::create([
        'creator_id' => $userA->id,
        'company_id' => $companyA->id,
        'first_name' => 'StaffA',
        'last_name' => 'Member',
        'email' => 'staffa@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 4,
    ]);
    $staffA->companies()->attach($companyA->id, ['role_id' => 4, 'status' => 'active']);

    // User B setup
    $userB = User::create([
        'first_name' => 'OwnerB',
        'last_name' => 'Beta',
        'email' => 'ownerb@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 2,
    ]);
    $companyB = Company::create(['name' => 'Beta Ltd', 'slug' => 'beta-ltd', 'owner_id' => $userB->id, 'is_active' => true]);
    $userB->update(['company_id' => $companyB->id]);
    $userB->companies()->attach($companyB->id, ['role_id' => 2, 'status' => 'active']);

    // Staff for User B
    $staffB = User::create([
        'creator_id' => $userB->id,
        'company_id' => $companyB->id,
        'first_name' => 'StaffB',
        'last_name' => 'Member',
        'email' => 'staffb@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 4,
    ]);
    $staffB->companies()->attach($companyB->id, ['role_id' => 4, 'status' => 'active']);

    // Check User A's staff list view
    $this->actingAs($userA)->withSession(['company_id' => $companyA->id]);
    $responseA = $this->get('/staff');
    $responseA->assertOk();
    $responseA->assertSee('StaffA');
    $responseA->assertDontSee('StaffB');

    // Check User B's staff list view
    $this->actingAs($userB)->withSession(['company_id' => $companyB->id]);
    $responseB = $this->get('/staff');
    $responseB->assertOk();
    $responseB->assertSee('StaffB');
    $responseB->assertDontSee('StaffA');
});

test('user can assign staff to any of their companies and reassign them', function () {
    $user = User::create([
        'first_name' => 'Manager',
        'last_name' => 'Boss',
        'email' => 'boss@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 2,
    ]);
    $company1 = Company::create(['name' => 'Branch One', 'slug' => 'branch-1', 'owner_id' => $user->id, 'is_active' => true]);
    $company2 = Company::create(['name' => 'Branch Two', 'slug' => 'branch-2', 'owner_id' => $user->id, 'is_active' => true]);
    $user->update(['company_id' => $company1->id]);
    $user->companies()->attach([$company1->id => ['role_id' => 2, 'status' => 'active'], $company2->id => ['role_id' => 2, 'status' => 'active']]);

    $this->actingAs($user)->withSession(['company_id' => $company1->id]);

    // Create staff assigned to Branch One
    $createResponse = $this->post('/staff', [
        'first_name' => 'Charlie',
        'last_name' => 'Brown',
        'email' => 'charlie@example.com',
        'phone' => '1122334455',
        'role_id' => 4,
        'company_id' => $company1->id,
    ]);
    $createResponse->assertRedirect();

    $staff = User::where('email', 'charlie@example.com')->first();
    expect($staff)->not->toBeNull();
    expect($staff->company_id)->toBe($company1->id);
    expect($staff->creator_id)->toBe($user->id);

    // Reassign Charlie to Branch Two
    $updateResponse = $this->put('/staff/' . $staff->id, [
        'first_name' => 'Charlie',
        'last_name' => 'Brown',
        'email' => 'charlie@example.com',
        'phone' => '1122334455',
        'role_id' => 4,
        'company_id' => $company2->id,
    ]);
    $updateResponse->assertRedirect();

    $staff->refresh();
    expect($staff->company_id)->toBe($company2->id);
    expect($staff->companies()->pluck('companies.id')->all())->toContain($company2->id);
});

test('user cannot assign staff to or manage another users company or staff', function () {
    $userA = User::create([
        'first_name' => 'UserA',
        'last_name' => 'Owner',
        'email' => 'ua@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 2,
    ]);
    $compA = Company::create(['name' => 'Comp A', 'slug' => 'c-a', 'owner_id' => $userA->id, 'is_active' => true]);
    $userA->companies()->attach($compA->id, ['role_id' => 2, 'status' => 'active']);

    $userB = User::create([
        'first_name' => 'UserB',
        'last_name' => 'Owner',
        'email' => 'ub@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 2,
    ]);
    $compB = Company::create(['name' => 'Comp B', 'slug' => 'c-b', 'owner_id' => $userB->id, 'is_active' => true]);
    $userB->companies()->attach($compB->id, ['role_id' => 2, 'status' => 'active']);

    $staffB = User::create([
        'creator_id' => $userB->id,
        'company_id' => $compB->id,
        'first_name' => 'StaffB',
        'last_name' => 'Person',
        'email' => 'staffb_person@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 4,
    ]);

    $this->actingAs($userA)->withSession(['company_id' => $compA->id]);

    // User A tries to create staff and assign to Comp B
    $createFail = $this->post('/staff', [
        'first_name' => 'Intruder',
        'last_name' => 'Staff',
        'email' => 'intruder@example.com',
        'role_id' => 4,
        'company_id' => $compB->id,
    ]);
    $createFail->assertStatus(403);

    // User A tries to edit User B's staff
    $editFail = $this->put('/staff/' . $staffB->id, [
        'first_name' => 'Hacked',
        'last_name' => 'Name',
        'email' => 'staffb_person@example.com',
        'role_id' => 4,
        'company_id' => $compA->id,
    ]);
    $editFail->assertStatus(403);

    // User A tries to delete User B's staff
    $deleteFail = $this->delete('/staff/' . $staffB->id);
    $deleteFail->assertStatus(403);
});


test('user can filter staff by company or view all their staff', function () {
    $user = User::create([
        'first_name' => 'Multi',
        'last_name' => 'Owner',
        'email' => 'multi@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 2,
    ]);

    $c1 = Company::create(['name' => 'Warehouse North', 'slug' => 'wh-north', 'owner_id' => $user->id, 'is_active' => true]);
    $c2 = Company::create(['name' => 'Warehouse South', 'slug' => 'wh-south', 'owner_id' => $user->id, 'is_active' => true]);
    $user->update(['company_id' => $c1->id]);
    $user->companies()->attach([$c1->id => ['role_id' => 2, 'status' => 'active'], $c2->id => ['role_id' => 2, 'status' => 'active']]);

    // Staff in North
    $staff1 = User::create([
        'creator_id' => $user->id,
        'company_id' => $c1->id,
        'first_name' => 'North',
        'last_name' => 'Worker',
        'email' => 'north@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 4,
    ]);
    $staff1->companies()->attach($c1->id, ['role_id' => 4, 'status' => 'active']);

    // Staff in South
    $staff2 = User::create([
        'creator_id' => $user->id,
        'company_id' => $c2->id,
        'first_name' => 'South',
        'last_name' => 'Worker',
        'email' => 'south@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 4,
    ]);
    $staff2->companies()->attach($c2->id, ['role_id' => 4, 'status' => 'active']);

    $this->actingAs($user);

    // Filter North only
    $resNorth = $this->get('/staff?company_id=' . $c1->id);
    $resNorth->assertOk();
    $resNorth->assertSee('North Worker');
    $resNorth->assertDontSee('South Worker');

    // Filter South only
    $resSouth = $this->get('/staff?company_id=' . $c2->id);
    $resSouth->assertOk();
    $resSouth->assertSee('South Worker');
    $resSouth->assertDontSee('North Worker');
});

test('user can delete their own staff but cannot delete another users staff or self', function () {
    $userA = User::create([
        'first_name' => 'UserA',
        'last_name' => 'Owner',
        'email' => 'user_del_a@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 2,
    ]);
    $companyA = Company::create(['name' => 'Company Del A', 'slug' => 'del-a', 'owner_id' => $userA->id, 'is_active' => true]);
    $userA->update(['company_id' => $companyA->id]);
    $userA->companies()->attach($companyA->id, ['role_id' => 2, 'status' => 'active']);

    $staffA = User::create([
        'creator_id' => $userA->id,
        'company_id' => $companyA->id,
        'first_name' => 'StaffA',
        'last_name' => 'ToDelete',
        'email' => 'staff_todelete@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 4,
    ]);
    $staffA->companies()->attach($companyA->id, ['role_id' => 4, 'status' => 'active']);

    $this->actingAs($userA)->withSession(['company_id' => $companyA->id]);

    // Attempt to delete self -> 403
    $selfDelete = $this->delete('/staff/' . $userA->id);
    $selfDelete->assertStatus(403);

    // Delete staffA -> success
    $deleteRes = $this->delete('/staff/' . $staffA->id);
    $deleteRes->assertRedirect();
    expect(User::find($staffA->id))->toBeNull();
});

test('set active company session middleware restricts user to their own companies', function () {
    $userA = User::create([
        'first_name' => 'OwnerA',
        'last_name' => 'CompanyOwner',
        'email' => 'middleware_a@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 2,
    ]);
    $companyA = Company::create(['name' => 'OwnerA Co', 'slug' => 'ownera-co', 'owner_id' => $userA->id, 'is_active' => true]);
    $userA->update(['company_id' => $companyA->id]);
    $userA->companies()->attach($companyA->id, ['role_id' => 2, 'status' => 'active']);

    $userB = User::create([
        'first_name' => 'OwnerB',
        'last_name' => 'CompanyOwner',
        'email' => 'middleware_b@example.com',
        'password' => Hash::make('secret123'),
        'role_id' => 2,
    ]);
    $companyB = Company::create(['name' => 'OwnerB Co', 'slug' => 'ownerb-co', 'owner_id' => $userB->id, 'is_active' => true]);
    $userB->update(['company_id' => $companyB->id]);
    $userB->companies()->attach($companyB->id, ['role_id' => 2, 'status' => 'active']);

    // Log in User A with User B's company in session
    $this->actingAs($userA)->withSession(['company_id' => $companyB->id]);

    // Make any web request through middleware
    $this->get('/home');

    // Middleware should have reset session to User A's company!
    expect(session('company_id'))->toBe($companyA->id);
});


test('user can switch language between English and Kiswahili', function () {
    // Switch to Kiswahili
    $responseSw = $this->get('/language/sw');
    $responseSw->assertRedirect();
    expect(session('locale'))->toBe('sw');

    // Make a request and verify translated Swahili text
    $responsePage = $this->withSession(['locale' => 'sw'])->get('/');
    $responsePage->assertOk();
    $responsePage->assertSee('Ingia');

    // Switch back to English
    $responseEn = $this->get('/language/en');
    $responseEn->assertRedirect();
    expect(session('locale'))->toBe('en');

    $responsePageEn = $this->withSession(['locale' => 'en'])->get('/');
    $responsePageEn->assertOk();
    $responsePageEn->assertSee('Sign in');
});
