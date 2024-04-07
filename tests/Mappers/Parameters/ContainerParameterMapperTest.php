<?php

declare(strict_types=1);

namespace TheCodingMachine\GraphQLite\Mappers\Parameters;

use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use ReflectionMethod;
use ReflectionParameter;
use TheCodingMachine\GraphQLite\AbstractQueryProvider;
use TheCodingMachine\GraphQLite\Annotations\Autowire;
use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotations;
use TheCodingMachine\GraphQLite\Parameters\ParameterInterface;

class ContainerParameterMapperTest extends AbstractQueryProvider
{
    public function testMapParameter(): void
    {
        $mapper = new ContainerParameterHandler($this->getRegistry());

        $refMethod = new ReflectionMethod(self::class, 'dummy');
        $parameter = $refMethod->getParameters()[0];

        $this->expectException(MissingAutowireTypeException::class);
        $this->expectExceptionMessage('For parameter $foo in TheCodingMachine\GraphQLite\Mappers\Parameters\ContainerParameterMapperTest::dummy, annotated with annotation @Autowire, you must either provide a type-hint or specify the container identifier with @Autowire(identifier="my_service")');
        $mapper->mapParameter(
            $parameter,
            new DocBlock(),
            null,
            $this->getAnnotationReader()->getParameterAnnotationsPerParameter([$parameter])['foo'],
            new class implements ParameterHandlerInterface {
                public function mapParameter(ReflectionParameter $parameter, DocBlock $docBlock, Type|null $paramTagType, ParameterAnnotations $parameterAnnotations): ParameterInterface
                {
                }
            },
        );
    }

    private function dummy(
        #[Autowire]
        $foo,
    ): void
    {
    }
}
