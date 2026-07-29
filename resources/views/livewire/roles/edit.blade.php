<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Volt\Component;

new class extends Component {

    public $permissions = [];
    public $groupedPermissions = [];
    public $name;
    public $id;

    public $selectedPermissions = [];


    public function mount($id): Void
    {
        $this->id = $id;


        $role = Role::findOrFail($id);


        $this->name = $role->name;


        $this->permissions = Permission::all()->toArray();


        $this->groupedPermissions = collect($this->permissions)
            ->groupBy(function ($permission) {
                return explode('-', $permission['name'])[0];
            })
            ->toArray();



        $this->selectedPermissions = $role->permissions
            ->pluck('name')
            ->toArray();

    }



    public function selectAllPermissions($group)
    {
        $permissions = collect($this->groupedPermissions[$group])
            ->pluck('name')
            ->toArray();


        $this->selectedPermissions = array_unique(
            array_merge(
                $this->selectedPermissions,
                $permissions
            )
        );
    }



    public function updateRole()
    {

        $validated = $this->validate([
            'name' => ['string', 'required'],
        ]);



        $role = Role::findOrFail($this->id);



        $role->update([
            'name' => $this->name
        ]);



        $role->syncPermissions($this->selectedPermissions);



        $this->dispatch('role-updated');

    }

};

?>


<div>

    <div class="relative mb-6 w-full">

        <div class="flex justify-between items-center">

            <div>

                <div class="flex gap-2 items-center">

                    <a wire:navigate href="{{ route('roles.index') }}">
                        <flux:icon.arrow-left-circle />
                    </a>


                    <flux:heading size="xl" level="1">
                        {{ __('Edit Role - ') }} {{ $name }}
                    </flux:heading>

                </div>



                <flux:breadcrumbs class="mb-4 mt-2">

                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">
                        Home
                    </flux:breadcrumbs.item>


                    <flux:breadcrumbs.item href="{{ route('roles.index') }}">
                        Roles
                    </flux:breadcrumbs.item>


                    <flux:breadcrumbs.item>
                        Edit
                    </flux:breadcrumbs.item>


                    <flux:breadcrumbs.item>
                        {{ $name }}
                    </flux:breadcrumbs.item>

                </flux:breadcrumbs>


            </div>

        </div>


        <flux:separator variant="subtle" />

    </div>





    <form wire:submit.prevent="updateRole">



        <div class="flex gap-5 mb-8">


            <flux:input

                wire:model="name"

                :label="__('Name')"

                type="text"

                required

                placeholder="Role name"

            />


        </div>





        <div class="mb-6">


            <h2 class="text-xl font-semibold">
                Permissions
            </h2>


            <p class="text-sm text-gray-400 mt-2">

                Update the permissions this role should have.
                Permissions control what users can view, create, edit, or delete.

            </p>


        </div>






        <div class="space-y-6 mb-8">



            @foreach ($groupedPermissions as $group => $permissions)



                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">



                    <div class="flex justify-between items-center mb-5">



                        <h3 class="text-lg font-semibold capitalize">

                            {{ $group }}

                        </h3>



                        <button

                            type="button"

                            wire:click="selectAllPermissions('{{ $group }}')"

                            class="text-sm text-purple-400"

                        >

                            Select All

                        </button>



                    </div>






                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">



                        @foreach ($permissions as $permission)



                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-800 hover:border-purple-500 cursor-pointer">



                                <flux:checkbox

                                    value="{{ $permission['name'] }}"

                                    wire:model="selectedPermissions"

                                />



                                <flux:label>

                                    {{ ucwords(str_replace('-', ' ', $permission['name'])) }}

                                </flux:label>



                            </label>



                        @endforeach



                    </div>



                </div>



            @endforeach



        </div>





        <flux:error name="selectedPermissions" />






        <div class="flex items-center gap-4">



            <flux:button variant="primary" type="submit">

                Save

            </flux:button>




            <x-action-message on="role-updated">

                Saved.

            </x-action-message>



        </div>



    </form>


</div>