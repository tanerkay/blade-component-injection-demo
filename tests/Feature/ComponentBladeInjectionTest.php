<?php

namespace Tests\Feature;

use Illuminate\Contracts\View\View;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Component;
use Tests\TestCase;

class ComponentBladeInjectionTest extends TestCase
{
    public function testComponentWithStringRenderableDoesNotAllowBladeInjection(): void
    {
        $component = (new class extends Component {
            public function render(): string {
                return view('welcome', ['foo' => '{!! phpversion() !!}'])->render();
            }
        });

        $compiledView = BladeCompiler::renderComponent($component);

        // The view should have the input as a literal string,
        // but as it's rendered twice, we get the actual PHP version output here.
        $this->assertEquals('{!! phpversion() !!}', trim($compiledView));
    }

    public function testComponentWithViewRenderableDoesNotAllowBladeInjection(): void
    {
        $component = (new class extends Component {
            public function render(): View {
                return view('welcome', ['foo' => '{!! phpversion() !!}']);
            }
        });

        $compiledView = BladeCompiler::renderComponent($component);

        $this->assertEquals('{!! phpversion() !!}', trim($compiledView));
    }
}
