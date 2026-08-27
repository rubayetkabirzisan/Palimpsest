<?php

namespace App\Services;

class RegexDetector
{
    /**
     * All regex patterns for sensitive data detection.
     * Each pattern returns: type, regex, reason, severity.
     */
    protected array $patterns = [
        [
            'type' => 'credit_card',
            'regex' => '/\b(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|6(?:011|5[0-9]{2})[0-9]{12}|(?:2131|1800|35\d{3})\d{11})\b/',
            'reason' => 'Credit card number detected',
            'severity' => 'high',
        ],
        [
            'type' => 'ssn',
            'regex' => '/\b\d{3}-\d{2}-\d{4}\b/',
            'reason' => 'Social Security Number pattern detected',
            'severity' => 'high',
        ],
        [
            'type' => 'email',
            'regex' => '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/',
            'reason' => 'Email address detected',
            'severity' => 'low',
        ],
        [
            'type' => 'phone',
            'regex' => '/\b(?:\+?1[-.\s]?)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}\b/',
            'reason' => 'Phone number detected',
            'severity' => 'medium',
        ],
        [
            'type' => 'api_key',
            'regex' => '/\b(?:sk|pk|api|key|token|secret|access)[_-]?(?:live|test|prod|dev)?[_-]?[A-Za-z0-9]{20,}\b/i',
            'reason' => 'API key or secret token detected',
            'severity' => 'high',
        ],
        [
            'type' => 'aws_key',
            'regex' => '/\b(?:AKIA|ASIA)[A-Z0-9]{16}\b/',
            'reason' => 'AWS Access Key ID detected',
            'severity' => 'high',
        ],
        [
            'type' => 'private_key',
            'regex' => '/-----BEGIN (?:RSA |EC |DSA )?PRIVATE KEY-----/',
            'reason' => 'Private key header detected',
            'severity' => 'high',
        ],
        [
            'type' => 'ip_address',
            'regex' => '/\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/',
            'reason' => 'IP address detected',
            'severity' => 'low',
        ],
        [
            'type' => 'password',
            'regex' => '/(?:password|passwd|pwd)\s*[:=]\s*\S+/i',
            'reason' => 'Password value detected in text',
            'severity' => 'high',
        ],
    ];

    /**
     * Scan text content and return an array of findings.
     *
     * @return array<int, array{type: string, snippet: string, reason: string, severity: string, source: string, position: int}>
     */
    public function scan(string $content): array
    {
        $findings = [];

        foreach ($this->patterns as $pattern) {
            preg_match_all($pattern['regex'], $content, $matches, PREG_OFFSET_CAPTURE);

            foreach ($matches[0] as $match) {
                $findings[] = [
                    'type' => $pattern['type'],
                    'snippet' => $match[0],
                    'reason' => $pattern['reason'],
                    'severity' => $pattern['severity'],
                    'source' => 'regex',
                    'position' => $match[1],
                ];
            }
        }

        // Sort by position in document
        usort($findings, fn ($a, $b) => $a['position'] <=> $b['position']);

        return $findings;
    }
}
