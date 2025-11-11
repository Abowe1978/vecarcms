<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tag;
use App\Services\TagSelectorService;
use Illuminate\Support\Str;

class TagSelector extends Component
{
    public $tags = '';
    public $selectedTags = [];
    public $popularTags = [];
    public $message = '';
    public $messageType = '';
    public $showNewTagInput = false;
    public $newTagName = '';
    
    /**
     * @var TagSelectorService
     */
    protected $tagService;

    /**
     * Create a new component instance.
     */
    public function boot(TagSelectorService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function mount($tags = '')
    {
        $this->tags = $tags;
        $this->selectedTags = $this->tagService->parseTagsString($tags);
        $this->loadPopularTags();
    }

    public function loadPopularTags()
    {
        $this->popularTags = $this->tagService->getPopularTagsWithVisualization();
    }

    public function toggleNewTagInput()
    {
        $this->showNewTagInput = !$this->showNewTagInput;
        $this->newTagName = '';
        $this->message = '';
    }

    public function addNewTag()
    {
        $errors = $this->tagService->validateTagName($this->newTagName);
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->addError('newTagName', $error);
                return;
            }
        }

        $tag = $this->tagService->createTag([
            'name' => $this->newTagName
        ]);

        if (!in_array($tag->name, $this->selectedTags)) {
            $this->selectedTags[] = $tag->name;
            $this->tags = $this->tagService->formatTagsToString($this->selectedTags);
        }

        $this->newTagName = '';
        $this->showNewTagInput = false;
        $this->loadPopularTags();
    }

    public function addTag($tagName)
    {
        $tagName = trim($tagName);
        if (!in_array($tagName, $this->selectedTags)) {
            $this->selectedTags[] = $tagName;
            $this->tags = $this->tagService->formatTagsToString($this->selectedTags);
        }
    }

    public function removeTag($tagName)
    {
        $this->selectedTags = array_diff($this->selectedTags, [$tagName]);
        $this->updateTagsString();
    }

    public function updateTagsFromInput()
    {
        $this->selectedTags = $this->tagService->parseTagsString($this->tags);
        $this->tags = $this->tagService->formatTagsToString($this->selectedTags);
    }

    protected function updateTagsString()
    {
        $this->selectedTags = array_values(array_filter($this->selectedTags));
        $this->tags = $this->tagService->formatTagsToString($this->selectedTags);
    }

    public function render()
    {
        return view('livewire.tag-selector');
    }
}
