<?php

declare(strict_types=1);

namespace Domain\TechStack;

use Illuminate\Contracts\Support\Arrayable;
use App\Support\Resolvable\Contracts\Resolvable;
use App\Support\Resolvable\Concerns\CanBeResolved;
use Domain\TechStack\Collections\PrimaryTechStackCollection;
use Domain\TechStack\Collections\HaveUsedTechStackCollection;
use Domain\TechStack\Collections\LearningTechStackCollection;
use Domain\TechStack\Collections\SecondaryTechStackCollection;
use Domain\TechStack\Collections\InterestedInLearningTechStackCollection;

/**
 * @implements Arrayables<string, array>
 */
final class TechStack implements Resolvable, Arrayable
{
    use CanBeResolved;

    /**
     * Items regularly used.
     */
    public function primary(): PrimaryTechStackCollection
    {
        return PrimaryTechStackCollection::make();
    }

    /**
     * Items used from time to time.
     * Wouldn't mind using again.
     */
    public function secondary(): SecondaryTechStackCollection
    {
        return SecondaryTechStackCollection::make();
    }

    /**
     * Items used at some point.
     * Wouldn't use again or would need a compeling
     * reason to.
     */
    public function haveUsed(): HaveUsedTechStackCollection
    {
        return HaveUsedTechStackCollection::make();
    }

    /**
     * Items currently being learned (not necessarily in an active manner).
     */
    public function learning(): LearningTechStackCollection
    {
        return LearningTechStackCollection::make();
    }

    /**
     * Items that could be learned in the future.
     */
    public function interestedInLearning(): InterestedInLearningTechStackCollection
    {
        return InterestedInLearningTechStackCollection::make();
    }

    public function all(): array
    {
        return [
            'primary' => $this->primary(),
            'secondary' => $this->secondary(),
            'have_used' => $this->haveUsed(),
            'learning' => $this->learning(),
            'interested_in_learning' => $this->interestedInLearning(),
        ];
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return [
            'primary' => $this->primary()->toArray(),
            'secondary' => $this->secondary()->toArray(),
            'have_used' => $this->haveUsed()->toArray(),
            'learning' => $this->learning()->toArray(),
            'interested_in_learning' => $this->interestedInLearning()->toArray(),
        ];
    }
}
