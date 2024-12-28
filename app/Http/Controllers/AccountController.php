<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use Carbon\Carbon;

class AccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read account', ['only' => ['index']]);
        $this->middleware('permission:create account', ['only' => ['create']]);
        $this->middleware('permission:update account', ['only' => ['update','edit']]);
        $this->middleware('permission:delete account', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        

        // Define the filters
        $filters = [
            'user_name' => $request->user_name,
            'platform' => $request->platform,
            'is_active' => $request->is_active,
        ];
        // Start the Account query
        $AccountQuery = Account::with('roles')->latest();

        // Apply the filters if they exist
        $AccountQuery->when($filters['user_name'], function ($query, $user_name) {
            return $query->where('user_name', 'LIKE', "%{$user_name}%");
        });

        $AccountQuery->when($filters['platform'], function ($query, $platform) {
            return $query->where('platform', 'LIKE', "%{$platform}%");
        });


        if (isset($filters['is_active'])) {
            $AccountQuery->where('is_active', $filters['is_active']);
        }
        // Paginate the filtered account
        $account = $AccountQuery->paginate(10);

        return Inertia('Accounts/index', [
            'translations' => __('messages'),
            'filters' => $filters,
            'accounts' => $account,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return Inertia('Accounts/Create', [ 'translations' => __('messages'),'roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        // Create account instance and assign validated data
        $account = new Account([
            'user_name' => $request->user_name,
            'platform'=>$request->platform,
            'note'=>$request->note,
            'last_check_date'=>Carbon::now()->format('Y-m-d'),
            'created' =>Carbon::now()->format('Y-m-d'),
            'times_of_check'=>1
            // 'image' => $request->image ? $request->image : 'accounts/default_account.png',
        ]);
    
        // Handle account upload if a file is provided
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('accounts', 'public');
            $account->image = $path;
        }
    
        // Save the account
        $account->save();
    
        // Sync roles if any selected
        if ($request->has('selectedRoles')) {
            $account->syncRoles($request->selectedRoles);
        }
    
        // Redirect with success message
        return redirect()->route('accounts.index')
            ->with('success', __('messages.data_saved_successfully'));
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        return Inertia('Accounts/Edit', [
            'translations' => __('messages'),
            'account' => $account,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        // The request is automatically validated using the UpdateAccountRequest rules
        // Check if an avatar file is uploaded and store it
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('accounts', 'public');
            $account->image = $path; // Update the account's avatar path
        }
    
        // Increment the times_of_check field by 1
        $account->times_of_check = $account->times_of_check + 1;

        // Update account information, including avatar and other fields, in a single save operation
        $account->update(array_merge($request->validated(), [
            'times_of_check' => $account->times_of_check,
        ]));
    
        // Sync roles if any
        //$account->syncRoles($request->selectedRoles);
    
        return redirect()->route('accounts.index')
            ->with('success', __('messages.data_updated_successfully'));
    }
    

    public function activate(Account $account)
    {
        $account->update(
            [
                'is_active' => ($account->is_active) ? 0 : 1
            ]
        );
        return redirect()->route('accounts.index')
            ->with('success', 'account Status Updated successfully!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        // تحقق ما إذا كان المنتج محذوفًا بالفعل
        if ($account->trashed()) {
            return redirect()->route('accounts.index')
                ->with('error', __('messages.account_already_deleted'));
        }
    
        // حذف المنتج حذفًا ناعمًا
        $account->delete();
    
        return redirect()->route('accounts.index')
            ->with('success', __('messages.data_deleted_successfully'));
    }
    public function trashed()
    {
        $trashedAccounts = Account::onlyTrashed()->get();
        return view('accounts.trashed', compact('trashedAccounts'));
    }
    public function restore($id)
    {
        $account = Account::onlyTrashed()->findOrFail($id);
        $account->restore();

        return redirect()->route('accounts.index')
            ->with('success', __('messages.account_restored_successfully'));
    }
}
