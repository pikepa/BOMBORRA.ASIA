<?php

namespace App\Http\Livewire\Forms;

use App\Models\Category;
use Livewire\Component;

class CategorySelect extends Component
{
    public $categories;

    public $cat_id;

    public $category_id = 0;

    public function mount($cat_id = null)
    {
        if ($cat_id != null) {
            $this->category_id = $cat_id;
        }
        $this->categories = Category::get();
    }

    public function render()
    {
        return view('livewire.forms.category-select', $this->categories);
    }

    public function updatedCategoryId()
    {
        $this->emitUp('category_selected', $this->category_id);
    }
}
