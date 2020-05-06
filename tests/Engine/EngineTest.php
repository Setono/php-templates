<?php

declare(strict_types=1);

namespace Setono\PhpTemplates\Engine;

use PHPUnit\Framework\TestCase;
use Setono\PhpTemplates\Exception\ExistingNamespaceException;
use Setono\PhpTemplates\Exception\InvalidTemplateFormatException;
use Setono\PhpTemplates\Exception\NamespaceNotRegisteredException;
use Setono\PhpTemplates\Exception\RenderingException;
use Setono\PhpTemplates\Exception\TemplateNotFoundException;

/**
 * @covers \Setono\PhpTemplates\Engine\Engine
 * @covers \Setono\PhpTemplates\Exception\ExistingNamespaceException
 * @covers \Setono\PhpTemplates\Exception\InvalidTemplateFormatException
 * @covers \Setono\PhpTemplates\Exception\NamespaceNotRegisteredException
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
        $engine->addPath('Test', __DIR__ . '/Fixtures');

        $result = $engine->render('@Test/template', [
            'name' => 'John Doe',
        ]);

        $this->assertSame('<h1>Hello John Doe</h1>', $result);
    }

    /**
     * @test
     */
    public function it_renders_when_paths_are_added_in_the_constructor(): void
    {
        $engine = new Engine([
            'Test' => __DIR__ . '/Fixtures',
        ]);

        $result = $engine->render('@Test/template', [
            'name' => 'John Doe',
        ]);

        $this->assertSame('<h1>Hello John Doe</h1>', $result);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_same_namespace_is_added_twice(): void
    {
        $this->expectException(ExistingNamespaceException::class);

        $engine = new Engine();
        $engine->addPath('Test', __DIR__ . '/Fixtures');
        $engine->addPath('Test', __DIR__ . '/Fixtures');
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
    public function it_throws_exception_when_namespace_is_not_registered(): void
    {
        $this->expectException(NamespaceNotRegisteredException::class);

        $engine = new Engine();
        $engine->render('@Namespace/template');
    }

    /**
     * @test
     */
    public function it_throws_exception_when_template_does_not_exist(): void
    {
        $this->expectException(TemplateNotFoundException::class);

        $engine = new Engine(['Test' => __DIR__ . '/Fixtures']);
        $engine->render('@Test/templte');
    }

    /**
     * @test
     */
    public function it_catches_exception_in_template(): void
    {
        $this->expectException(RenderingException::class);

        $engine = new Engine(['Test' => __DIR__ . '/Fixtures']);
        $engine->render('@Test/template_throws');
    }
}
