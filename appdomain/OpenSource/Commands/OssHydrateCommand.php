<?php

declare(strict_types=1);

namespace Domain\OpenSource\Commands;

use Generator;
use App\Support\GitHub\GitHub;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Console\Prohibitable;
use Domain\OpenSource\Models\OssRelease;
use Domain\OpenSource\Models\OssRepository;
use Domain\OpenSource\Models\OssDocumentation;
use App\Support\GitHub\Resources\User\Repos\UserRepoData;
use Domain\OpenSource\Jobs\RenderOssDocumentationContentJob;
use App\Support\GitHub\Resources\Repo\Releases\RepoReleaseData;
use App\Support\GitHub\Resources\User\Repos\UserRepoCollection;
use App\Support\GitHub\Resources\Repo\Contents\File\RepoContentFileData;
use Domain\OpenSource\Actions\RecursivelyReadGitHubRepositoryDirectoryAction;

final class OssHydrateCommand extends Command
{
    use Prohibitable;

    /**
     * The name and signature of the console command.
     */
    protected $signature = 'oss:hydrate';

    /**
     * The console command description.
     */
    protected $description = 'Hydrate the "oss_*" DB tables with data from GitHub';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->isProhibited()) {
            return self::FAILURE;
        }

        $this->newLine();

        $repositories = $this->getRepositories();

        $bar = $this->output->createProgressBar($repositories->count());

        $bar->start();

        foreach ($repositories as $repository) {
            $ossRepository = $this->createOrUpdateOssRepository($repository);
            $latestRelease = $this->getRepositoryLatestRelease($repository);
            $ossRelease = $this->createOrUpdateOssRelease($latestRelease, $ossRepository);

            $files = $this->getReleaseDocumentationFileContents($repository, $latestRelease);

            foreach ($files as $file) {
                $ossDocumentation = $this->createOrUpdateOssDocumentation($file, $ossRelease);

                $this->resetOssDocumentationHtmlIfNecessary($ossDocumentation);
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine(2);

        $this->components->info('"oss_*" DB tables hydrated successfully!');

        return self::SUCCESS;
    }

    /* Data retrieval
    ------------------------------------------------*/

    private function getRepositories(): UserRepoCollection
    {
        return GitHub::user()->repos()->whereIsPublic()->whereEnabled()->whereNotForked()->whereNotIgnored();
    }

    /**
     * @return Collection<string, int>
     */
    private function getRepositoryLanguages(UserRepoData $repository): Collection
    {
        return GitHub::repo()->languages($repository->name);
    }

    private function getRepositoryLatestRelease(UserRepoData $repository): RepoReleaseData
    {
        return GitHub::repo()->latestRelease($repository->name);
    }

    /**
     * @return Generator<int, RepoContentFileData>
     */
    private function getReleaseDocumentationFileContents(UserRepoData $repository, RepoReleaseData $release): Generator
    {
        /**
         * TODO: Move docs to a `/.docs` sub-directory
         * then remove this.
         */
        return yield GitHub::repo()->contentsAsFile($repository->name, 'README.md', params: [
            'ref' => $release->tag_name,
        ]);

        // /**
        //  * TODO: Move docs to a `/.docs` sub-directory
        //  * then uncomment these.
        //  */
        // $files = RecursivelyReadGitHubRepositoryDirectoryAction::resolve()->execute(
        //     $repository->name,
        //     $release->tag_name,
        //     '/.docs'
        // );

        // foreach ($files as $file) {
        //     yield GitHub::repo()->contentsAsFile(
        //         $repository->name,
        //         $file->path,
        //         params: [
        //             'ref' => $release->tag_name,
        //         ],
        //     );
        // }
    }

    /* Actions
    ------------------------------------------------*/

    private function createOrUpdateOssRepository(UserRepoData $repository): OssRepository
    {
        $languages = $this->getRepositoryLanguages($repository);

        $model = OssRepository::createOrUpdateFromUserRepoData($repository);

        $model->update([
            'languages' => $languages->all(),
        ]);

        return $model;
    }

    private function createOrUpdateOssRelease(RepoReleaseData $release, OssRepository $repository): OssRelease
    {
        return OssRelease::createOrUpdateFromRepoReleaseData($release, $repository);
    }

    private function createOrUpdateOssDocumentation(RepoContentFileData $file, OssRelease $release): OssDocumentation
    {
        return OssDocumentation::createOrUpdateFromRepoContentFileData($file, $release);
    }

    private function resetOssDocumentationHtmlIfNecessary(OssDocumentation $doc): void
    {
        if (!$doc->wasRecentlyCreated && !$doc->wasChanged('content')) {
            return;
        }

        if (!$doc->content) {
            return;
        }

        RenderOssDocumentationContentJob::dispatchForModel($doc);
    }
}
