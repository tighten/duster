<?php

namespace App\Actions;

use App\Output\SummaryOutput;
use Illuminate\Console\Command;
use PhpCsFixer\Console\Report\FixReport;
use PhpCsFixer\Console\Report\FixReport\ReportSummary;
use PhpCsFixer\Error\ErrorsManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// Shadows vendor/laravel/pint/app/Actions/ElaborateSummary.php.
// Pint's copy short-circuits to a JSON `AgentReporter` whenever an AI-agent
// env var (CLAUDECODE, CURSOR_AGENT, etc.) is set, which leaks JSON into
// Duster's otherwise human-readable, multi-tool output. This version keeps
// explicit `--format` support but never auto-switches based on environment.
class ElaborateSummary
{
    public function __construct(
        protected ErrorsManager $errors,
        protected InputInterface $input,
        protected OutputInterface $output,
        protected SummaryOutput $summaryOutput,
    ) {}

    /**
     * @param  array<string, array{appliedFixers: array<int, string>, diff: string}>  $changes
     */
    public function execute(int $totalFiles, array $changes): int
    {
        $summary = new ReportSummary(
            $changes,
            $totalFiles,
            0,
            0,
            $this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE,
            $this->input->getOption('test') || $this->input->getOption('bail'),
            $this->output->isDecorated()
        );

        $format = $this->input->getOption('format');

        if ($format) {
            $this->displayUsingFormatter($summary, $format);
        } else {
            $this->summaryOutput->handle($summary, $totalFiles);
        }

        if (($file = $this->input->getOption('output-to-file')) && ($outputFormat = $this->input->getOption('output-format') ?: $format)) {
            $this->displayUsingFormatter($summary, $outputFormat, $file);
        }

        $failure = (($summary->isDryRun() || $this->input->getOption('repair')) && count($changes) > 0)
            || count($this->errors->getInvalidErrors()) > 0
            || count($this->errors->getExceptionErrors()) > 0
            || count($this->errors->getLintErrors()) > 0;

        return $failure ? Command::FAILURE : Command::SUCCESS;
    }

    protected function displayUsingFormatter(ReportSummary $summary, string $format, ?string $outputPath = null): void
    {
        $reporter = match ($format) {
            'checkstyle' => new FixReport\CheckstyleReporter,
            'gitlab' => new FixReport\GitlabReporter,
            'json' => new FixReport\JsonReporter,
            'junit' => new FixReport\JunitReporter,
            'txt' => new FixReport\TextReporter,
            'xml' => new FixReport\XmlReporter,
            default => abort(1, sprintf('Format [%s] is not supported.', $format)),
        };

        if ($outputPath) {
            file_put_contents($outputPath, $reporter->generate($summary));

            return;
        }

        $this->output->write($reporter->generate($summary));
    }
}
