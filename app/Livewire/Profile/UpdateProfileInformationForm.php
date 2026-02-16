<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Laravel\Jetstream\InteractsWithBanner;
use Laravel\Jetstream\WithProfilePhotoFileUploads;
use Livewire\Component;

class UpdateProfileInformationForm extends Component
{
    use WithProfilePhotoFileUploads;    

    public $state = [];
    public $photo;
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
        $this->state = $this->user->withoutRelations()->toArray();
    }

    public function updateProfileInformation(UpdatesUserProfileInformation $updater)
    {
        $this->resetErrorBag();

        $updater->update($this->user, array_merge($this->state, [
            'photo' => $this->photo,
        ]));

        if (isset($this->photo)) {
            $this->photo = null;
        }

        $this->emit('saved');
    }

    public function deleteProfilePhoto()
    {
        $this->user->deleteProfilePhoto();
    }

    public function render()
    {
        return view('profile.update-profile-information-form');
    }
}
