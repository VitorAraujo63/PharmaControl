<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.app')]
class ManageUsers extends Component
{
    use WithPagination;

    public $search = '';

    public $user_id_editing = null;
    public $name;
    public $email;
    public $password;
    public $role = 'cashier';
    public $status = 'ativo';

    public $showModal = false;

    public function updatedSearch() { $this->resetPage(); }

    public function create()
    {
        $this->reset(['user_id_editing', 'name', 'email', 'password', 'role', 'status']);
        $this->role = 'cashier';
        $this->status = 'ativo';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->user_id_editing = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->status = $user->status;
        $this->password = '';

        $this->showModal = true;
    }

    public function save()
{
    $rules = [
        'name' => 'required|min:3',
        'email' => ['required', 'email', Rule::unique('users')->ignore($this->user_id_editing)],
    ];

    if ($this->user_id_editing !== auth()->id()) {
        $rules['role'] = 'required|in:admin,manager,cashier';
        $rules['status'] = 'required|in:ativo,inativo';
    }

    if ($this->user_id_editing) {
        $rules['password'] = 'nullable|min:6';
    } else {
        $rules['password'] = 'required|min:6';
    }

    $this->validate($rules);

    $data = [
        'name' => $this->name,
        'email' => $this->email,
    ];

    if ($this->user_id_editing === auth()->id()) {
    } else {

        $data['role'] = $this->role;
        $data['status'] = $this->status;
    }

    if (!empty($this->password)) {
        $data['password'] = Hash::make($this->password);
    }

    if ($this->user_id_editing) {
        $user = User::find($this->user_id_editing);
        $user->update($data);
        session()->flash('success', 'Usuário atualizado com sucesso!');
    } else {
        $data['role'] = $this->role;
        $data['status'] = $this->status;

        User::create($data);
        session()->flash('success', 'Novo funcionário cadastrado!');
    }

    $this->showModal = false;
}

    public function delete($id)
    {

        if ($id == auth()->id()) {
            session()->flash('error', 'Você não pode excluir sua própria conta!');
            return;
        }

        User::find($id)->delete();
        session()->flash('success', 'Funcionário removido.');
    }

    public function render()
    {
        $users = User::query()
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('email', 'like', '%'.$this->search.'%')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.manage-users', [
            'users' => $users
        ]);
    }
}
