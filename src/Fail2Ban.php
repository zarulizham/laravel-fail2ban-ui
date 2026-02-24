<?php

namespace ZarulIzham\Fail2Ban;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;

class Fail2Ban
{
    /**
     * @var null|callable(Request):bool
     */
    protected static $authUsing;

    /**
     * Register the Fail2Ban UI access gate callback.
     */
    public function auth(callable $callback): void
    {
        static::$authUsing = $callback;
    }

    /**
     * Determine whether the incoming request can access Fail2Ban routes.
     */
    public function check(Request $request): bool
    {
        if (is_callable(static::$authUsing)) {
            return (bool) call_user_func(static::$authUsing, $request);
        }

        return false;
    }

    /**
     * Parse the output from `fail2ban-client status` and return active jails data.
     *
     * @return array{number_of_jail:int,jail_list:array<int,string>,raw_output:string}
     */
    public function activeJail(): array
    {
        $rawOutput = $this->runCommand($this->buildFail2BanCommand(['status']));

        if ($rawOutput === '') {
            return [
                'number_of_jail' => 0,
                'jail_list' => [],
                'raw_output' => '',
            ];
        }

        $numberOfJail = 0;
        $jailList = [];

        if (preg_match('/Number of jail:\s*(\d+)/i', $rawOutput, $numberMatch) === 1) {
            $numberOfJail = (int) ($numberMatch[1] ?? 0);
        }

        if (preg_match('/Jail list:\s*(.+)/i', $rawOutput, $jailListMatch) === 1) {
            $list = trim($jailListMatch[1] ?? '');

            if ($list !== '') {
                $jailList = array_values(array_filter(array_map(
                    static fn (string $jail): string => trim($jail),
                    explode(',', $list)
                )));
            }
        }

        if ($numberOfJail === 0 && $jailList !== []) {
            $numberOfJail = count($jailList);
        }

        return [
            'number_of_jail' => $numberOfJail,
            'jail_list' => $jailList,
            'raw_output' => $rawOutput,
        ];
    }

    /**
     * Build overview data by reading all jails and fetching detail per jail.
     *
     * @return array{number_of_jail:int,jail_list:array<int,string>,rows:array<int,array<string,mixed>>,raw_output:string}
     */
    public function overview(): array
    {
        $activeJails = $this->activeJail();

        $rows = array_map(function (string $jailName): array {
            $detail = $this->jailStatus($jailName);

            return [
                'jail' => $jailName,
                'currently_failed' => $detail['currently_failed'],
                'total_failed' => $detail['total_failed'],
                'currently_banned' => $detail['currently_banned'],
                'total_banned' => $detail['total_banned'],
                'banned_ip_list' => $detail['banned_ip_list'],
                'raw_output' => $detail['raw_output'],
            ];
        }, $activeJails['jail_list']);

        return [
            'number_of_jail' => $activeJails['number_of_jail'],
            'jail_list' => $activeJails['jail_list'],
            'rows' => array_values($rows),
            'raw_output' => $activeJails['raw_output'],
        ];
    }

    /**
     * Get detailed status for one jail from `fail2ban-client status <jailName>`.
     *
     * @return array{jail:string,currently_failed:int,total_failed:int,currently_banned:int,total_banned:int,banned_ip_list:array<int,string>,raw_output:string}
     */
    public function jailStatus(string $jailName): array
    {
        $rawOutput = $this->runCommand($this->buildFail2BanCommand(['status', $jailName]));

        $currentlyFailed = 0;
        $totalFailed = 0;
        $currentlyBanned = 0;
        $totalBanned = 0;
        $bannedIpList = [];

        if (preg_match('/Currently failed:\s*(\d+)/i', $rawOutput, $match) === 1) {
            $currentlyFailed = (int) ($match[1] ?? 0);
        }

        if (preg_match('/Total failed:\s*(\d+)/i', $rawOutput, $match) === 1) {
            $totalFailed = (int) ($match[1] ?? 0);
        }

        if (preg_match('/Currently banned:\s*(\d+)/i', $rawOutput, $match) === 1) {
            $currentlyBanned = (int) ($match[1] ?? 0);
        }

        if (preg_match('/Total banned:\s*(\d+)/i', $rawOutput, $match) === 1) {
            $totalBanned = (int) ($match[1] ?? 0);
        }

        if (preg_match('/Banned IP list:\s*(.*)/i', $rawOutput, $match) === 1) {
            $list = trim($match[1] ?? '');

            if ($list !== '') {
                $bannedIpList = array_values(array_filter(preg_split('/\s+/', $list) ?: []));
            }
        }

        return [
            'jail' => $jailName,
            'currently_failed' => $currentlyFailed,
            'total_failed' => $totalFailed,
            'currently_banned' => $currentlyBanned,
            'total_banned' => $totalBanned,
            'banned_ip_list' => $bannedIpList,
            'raw_output' => $rawOutput,
        ];
    }

    /**
     * Unban an IP address from a jail.
     *
     * @return array{ok:bool,output:string}
     */
    public function unbanIp(string $serviceName, string $ip): array
    {
        $result = Process::run($this->buildFail2BanCommand(['set', $serviceName, 'unbanip', $ip]));

        $output = trim($result->output());

        if ($output === '') {
            $output = trim($result->errorOutput());
        }

        return [
            'ok' => $result->successful(),
            'output' => $output,
        ];
    }

    /**
     * Execute a fail2ban command and return output (stdout fallback to stderr).
     */
    private function runCommand(array $command): string
    {
        $result = Process::run($command);

        $output = trim($result->output());

        if ($output === '') {
            $output = trim($result->errorOutput());
        }

        return $output;
    }

    /**
     * Build the command array to execute fail2ban-client (optionally via sudo).
     *
     * @param  array<int, string>  $arguments
     * @return array<int, string>
     */
    private function buildFail2BanCommand(array $arguments): array
    {
        $binaryPath = trim((string) config('fail2ban-ui.fail2ban_client_path', 'fail2ban-client'));

        if ($binaryPath === '') {
            $binaryPath = 'fail2ban-client';
        }

        $command = [$binaryPath, ...$arguments];

        if ((bool) config('fail2ban-ui.use_sudo', true)) {
            $sudoPath = trim((string) config('fail2ban-ui.sudo_path', 'sudo'));

            if ($sudoPath === '') {
                $sudoPath = 'sudo';
            }

            array_unshift($command, $sudoPath);
        }

        return $command;
    }
}
