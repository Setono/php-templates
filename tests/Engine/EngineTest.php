<?php

declare(strict_types=1);

namespace Setono\PhpTemplates\Engine;

use PHPUnit\Framework\TestCase;
use Setono\PhpTemplates\Exception\InvalidPathException;
use Setono\PhpTemplates\Exception\InvalidTemplateFormatException;
use Setono\PhpTemplates\Exception\RenderingException;
use Setono\PhpTemplates\Exception\TemplateNotFoundException;

/**
 * @covers \Setono\PhpTemplates\Engine\Engine
 * @covers \Setono\PhpTemplates\Exception\InvalidPathException
 * @covers \Setono\PhpTemplates\Exception\InvalidTemplateFormatException
 * @covers \Setono\PhpTemplates\Exception\RenderingException
 * @covers \Setono\PhpTemplates\Exception\TemplateNotFoundException
 */
final class EngineTest extends TestCase
{
    /**
     * @test
     */
    public function it_renders(): void
    {
        $engine = new Engine();
        $engine->addPath(__DIR__ . '/Fixtures/app-template-dir');

        $result = $engine->render('@App/template', [
            'name' => 'John Doe',
        ]);

        $this->assertSame('<h1>Hello John Doe</h1>', $result);
    }

    /**
     * @test
     */
    public function it_renders_when_paths_are_added_in_the_constructor(): void
    {
        $engine = new Engine([__DIR__ . '/Fixtures/app-template-dir']);

        $result = $engine->render('@App/template', [
            'name' => 'John Doe',
        ]);

        $this->assertSame('<h1>Hello John Doe</h1>', $result);
    }

    /**
     * @test
     */
    public function it_renders_when_paths_are_added_in_the_constructor_with_priorities(): void
    {
        $engine = new Engine([__DIR__ . '/Fixtures/app-template-dir' => 10]);

        $result = $engine->render('@App/template', [
            'name' => 'John Doe',
        ]);

        $this->assertSame('<h1>Hello John Doe</h1>', $result);
    }

    /**
     * @test
     */
    public function it_renders_correct_template_when_a_template_is_overridden(): void
    {
        $engine = new Engine();
        $engine->addPath(__DIR__ . '/Fixtures/app-template-dir', 10);
        $engine->addPath(__DIR__ . '/Fixtures/third-party-template-dir', 0);

        $result = $engine->render('@ThirdPartyNamespace/template', [
            'name' => 'John Doe',
        ]);

        $this->assertSame('<h1>Hi John Doe! This is rendered</h1>', $result);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_incorrect_template_format_is_used(): void
    {
        $this->expectException(InvalidTemplateFormatException::class);

        $engine = new Engine();
        $engine->render('template');
    }

    /**
     * @test
     */
    public function it_throws_exception_when_template_does_not_exist(): void
    {
        $this->expectException(TemplateNotFoundException::class);

        $engine = new Engine([__DIR__ . '/Fixtures/app-template-dir']);
        $engine->render('@App/templte');
    }

    /**
     * @test
     */
    public function it_throws_exception_when_path_is_invalid(): void
    {
        $this->expectException(InvalidPathException::class);

        $engine = new Engine([__DIR__ . '/Fixtures/app-template-dri']);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_namespace_directory_does_not_exist_and_no_other_matches_are_present(): void
    {
        $this->expectException(TemplateNotFoundException::class);

        $engine = new Engine([__DIR__ . '/Fixtures/app-template-dir']);
        $engine->render('@Apps/template');
    }

    /**
     * @test
     */
    public function it_catches_exception_in_template(): void
    {
        $this->expectException(RenderingException::class);

        $engine = new Engine([__DIR__ . '/Fixtures/app-template-dir']);
        $engine->render('@App/template_throws');
    }
}
