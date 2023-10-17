<?php

namespace Livewire\Features\SupportQueryString;

use Livewire\Component;
use Livewire\Livewire;

class BrowserTest extends \Tests\BrowserTestCase
{
    /** @test */
    public function can_encode_url_containing_spaces_and_commas()
    {
        Livewire::visit([
            new class extends Component
            {
                #[BaseUrl]
                public $space = '';

                #[BaseUrl]
                public $comma = '';

                public function render()
                {
                    return <<<'HTML'
                    <div>
                        <input type="text" dusk="space" wire:model.live="space" />
                        <input type="text" dusk="comma" wire:model.live="comma" />
                    </div>
                    HTML;
                }
            },
        ])
            ->waitForLivewire()
            ->type('@space', 'foo bar')
            ->type('@comma', 'foo,bar')
            ->assertScript('return !! window.location.search.match(/space=foo\+bar/)')
            ->assertScript('return !! window.location.search.match(/comma=foo\,bar/)');
    }

    /** @test */
    public function can_encode_url_containing_reserved_characters()
    {
        Livewire::visit([
            new class extends Component
            {
                #[BaseUrl]
                public $exclamation = '';

                #[BaseUrl]
                public $quote = '';

                #[BaseUrl]
                public $parentheses = '';

                #[BaseUrl]
                public $asterisk = '';

                public function render()
                {
                    return <<<'HTML'
                     <div>
                         <input type="text" dusk="exclamation" wire:model.live="exclamation" />
                         <input type="text" dusk="quote" wire:model.live="quote" />
                         <input type="text" dusk="parentheses" wire:model.live="parentheses" />
                         <input type="text" dusk="asterisk" wire:model.live="asterisk" />
                     </div>
                     HTML;
                }
            },
        ])
            ->waitForLivewire()
            ->type('@exclamation', 'foo!')
            ->type('@parentheses', 'foo(bar)')
            ->type('@asterisk', 'foo*')
            ->assertScript('return !! window.location.search.match(/exclamation=foo\!/)')
            ->assertScript('return !! window.location.search.match(/parentheses=foo\(bar\)/)')
            ->assertScript('return !! window.location.search.match(/asterisk=foo\*/)');
    }
}