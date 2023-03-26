<div>
    <x-forms.card title="Add Post">
        <x-forms.errors :errors="$errors"></x-forms.errors>
        <form wire:submit.prevent="save">
 
            @include('livewire.posts.form')
      
        </form>
    </x-forms.card>
</div>
