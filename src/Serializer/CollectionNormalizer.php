<?php

declare(strict_types=1);

namespace Fschmtt\Keycloak\Serializer;

use Fschmtt\Keycloak\Collection\Collection;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CollectionNormalizer implements DenormalizerInterface
{
    public function __construct(
        private readonly DenormalizerInterface $denormalizer,
    ) {
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        /** @var Collection $collection */
        $collection = new $type();

        foreach ($data as $representation) {
            $collection->add(
                $this->denormalizer->denormalize($representation, $collection::getRepresentationClass(), $format, $context)
            );
        }

        return $collection;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return is_subclass_of($type, Collection::class);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Collection::class => true,
        ];
    }
}
